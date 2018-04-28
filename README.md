# Isimo Grav Plugin

The **Isimo Grav** Plugin is for [Grav CMS](http://github.com/getgrav/grav). 
A Isimo client for grav.

## Installation

Installing the Isimo Grav plugin can be done in one of two ways. 
The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).
From the root of your Grav install type:

    bin/gpm install isimo-grav

This will install the Isimo Grav plugin into your `/user/plugins` directory within Grav.
Its files can be found under `/your/site/grav/user/plugins/isimo-grav`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`.
Then, rename the folder to `isimo-grav`. You can find these files on [GitHub](https://github.com/SpiroAB/isimo_grav) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/isimo-grav
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/isimo-grav/isimo-grav.yaml` to `user/config/plugins/isimo-grav.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:


```yaml
enabled: true
route: /isimo/status/
token: wG3RFfFYCFhANTh8rByY8j0U0A5TF02U
```

## Usage

Enter an access-token in to the isimo-grav.yaml and enter the same token in to the isimo-servers config.
