<?php

	namespace Grav\Plugin;

	//	use Grav\Common\GPM\GPM;
	use Grav\Common\GPM\GPM;
	use Grav\Common\Plugin;
	use RocketTheme\Toolbox\Event\Event;

	/**
	 * Class IsimoGravPlugin
	 * @package Grav\Plugin
	 */
	class IsimoGravPlugin extends Plugin
	{
		/**
		 * @return array
		 *
		 * The getSubscribedEvents() gives the core a list of events
		 *     that the plugin wants to listen to. The key of each
		 *     array section is the event that the plugin listens to
		 *     and the value (in the form of an array) contains the
		 *     callable (or function) as well as the priority. The
		 *     higher the number the higher the priority.
		 */
		public static function getSubscribedEvents()
		{
			return [
				'onPluginsInitialized' => ['onPluginsInitialized', 0]
			];
		}

		/**
		 * Initialize the plugin
		 */
		public function onPluginsInitialized()
		{
			// Don't proceed if we are in the admin plugin
			if($this->isAdmin())
			{
				return;
			}

			$uri = $this->grav['uri'];
			$route = $this->config->get('plugins.isimo-grav.route');
			if(!$route || substr($uri->path(), 0, strlen($route)) !== $route)
			{
				return;
			}

			// Enable the main event we are interested in
			$this->enable(
				[
					'onPageInitialized' => ['onPageInitialized', 0]
				]
			);
		}

		/**
		 * Do some work for this event, full details of events can be found
		 * on the learn site: http://learn.getgrav.org/plugins/event-hooks
		 *
		 * @param Event $e
		 */
		public function onPageInitialized(Event $e)
		{
			$full_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$route = $this->config->get('plugins.isimo-grav.route');
			$url_token = trim(substr(strstr($full_path, $route), strlen($route)), '/');
			$config_token = $this->config->get('plugins.isimo-grav.token');

			if($url_token !== $config_token)
			{
				header('HTTP/1.1 403 Wrong token');
				die(json_encode(['error' => 'bad token'], JSON_PRETTY_PRINT));
			}

			$data = (object) [
				'software' => 'Grav',
				'version' => GRAV_VERSION,
				'client' => 'isimo grav v1.0.0',
				'time' => time(),
			];

			$data->composer_lock = file_get_contents(GRAV_ROOT . "/composer.lock");

			$data->composer_outdated = NULL;
			$data->composer_diagnose = NULL;

			$bins = [
				'/usr/bin/composer',
				'/usr/local/bin/composer',
			];
			foreach($bins as $bin)
			{
				if(file_exists($bin))
				{
					$cmd_parts = [];
					$cmd_parts[] = 'COMPOSER_HOME=' . escapeshellarg(sys_get_temp_dir());
					$cmd_parts[] = escapeshellcmd($bin);
					$cmd_parts[] = '--working-dir=' . escapeshellarg(GRAV_ROOT);

					$cmd_parts['composer_command'] = escapeshellarg('outdated');
					$cmd_parts[] = ' 2>&1';
					$data->composer_outdated = shell_exec(implode(' ', $cmd_parts));

					$cmd_parts['composer_command'] = escapeshellarg('diagnose');
					$data->composer_diagnose = shell_exec(implode(' ', $cmd_parts));

					break;
				}
			}

			ob_start();
			phpinfo();
			$data->phpinfo = ob_get_clean();

			$gpm = new GPM();
			$installed = $gpm->getInstalled();
			$data->report = [
				'installed' => [
					'themes' => $installed['themes']->toArray(),
					'plugins' => $installed['plugins']->toArray(),
				],
				'updatable' => [
					'core' => $gpm->grav->isUpdatable() ? $gpm->grav->getVersion() : null,
					'themes' => $gpm->getUpdatableThemes(),
					'plugins' => $gpm->getUpdatablePlugins(),
				]
			];

			header('Content-type: application/json');
			echo json_encode($data, JSON_PRETTY_PRINT);
			die();
		}
	}
