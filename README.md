Merchium Advanced App
=====================

Example app for Merchium that demonstrates the use of [external JavaScript](https://docs.google.com/document/d/1xxKaQ2J-oHGnL2TZGGRbKGwxg94ZDeOcAARL6JWHEl8/edit), [external HTML/Smarty content](https://docs.google.com/document/d/13XUTMq7AxbRMK26PCzGHQHULeeF4yOYEa17d0MPHj3U/edit), [webhooks](https://docs.google.com/document/d/13XUTMq7AxbRMK26PCzGHQHULeeF4yOYEa17d0MPHj3U/edit), and [payment service integration](https://docs.google.com/document/d/1tDJWZgzEjtUHmp4RfsTY36Vr-ObhL_53H6dPhhKXNXE/edit).

The app implements the following features:

	1. Snowfall for the storefront
	1. Welcome popup
	1. The "Add to cart" counter
	1. Example payment

The app is based on the [Yii 2 Basic Application Template](https://github.com/yiisoft/yii2-app-basic).

Requirements
------------

PHP 5.4.0 and a web server supporting it.

In the example below we'll use PHP's built-in server, SQLite, and [ngrok](https://ngrok.com/). You can use MySQL or any other database.

Check the requirements with `php requirements.php`.

Install
-------

1. Clone repo and switch to the app directory:

	```bash
	$ git clone https://github.com/merchium/merchium-advanced-app.git
	$ cd merchium-advanced-app
	```

1. [Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

1. Install the plugin and dependencies by running the following command:

	```bash
	$ composer global require "fxp/composer-asset-plugin:1.0.0"

	$ composer update
	```

Configure DB
------------

1. Create a DB config from the provided example:
	
	```bash
	$ cp config/db.php.example config/db.php
	```

1. Edit the DB config (note that the DB path must be absolute):

	```php
	<?php

	return [
		'class' => 'yii\db\Connection',
		'dsn' => 'sqlite:/absolute/path/to/sqlite.db',
		'charset' => 'utf8',
	];
	```

**Note:** The DB template sqlite.db already has some data. If you're starting from scratch or using a different database (e.g. MySQL), apply the migrations:

```bash
$ php yii migrate
```

Launch
------

1. Run PHP's built-in dev server:

	```bash
	php -S localhost:8000
	```

1. Use ngrok to get an https URL pointing to your server:

	```bash
	$ ngrok http 8000
	...
	Forwarding                    https://3cd89e8a.ngrok.com -> 127.0.0.1:8000
	...
	```

1. Open your browser and go to https://3cd89e8a.ngrok.com/web.

Create a Merchium Marketplace App
---------------------------------

1. Go to https://3cd89e8a.ngrok.com/web/index.php?r=site%2Flogin and login to the dashboard with username *admin* and password *admin*.

You'll see the app's admin panel and install page URLs.

1. Open your [Merchium partner page](http://marketplace.merchium.com/partner.php) on a new tab and [create an app](https://docs.google.com/document/d/1mU7cJTNlXuaiGIQ645gxu8XonV0xm7sGnKsjdJESxxs/edit#heading=h.92nl0c1q6xrh). Use the admin panel and install page URLs from the dashboard.

1. Switch to **App permissions** and check the following permissions:

	- View, create, edit, and delete custom script connections (ScriptTags)
	- View, create, edit, and delete custom HTML and Smarty 3 content connections to hooks (TemplateHooks)
	- View, create, edit, and delete payment processors (PaymentProcessors)
	- Create, edit, and delete webhooks (Webhooks)

On the app page in your Merchium partner page, you'll see the App key and Client secret values.

1. Create a config from the provided example:

	```bash
	$ cp config/params.php.example config/params.php
	```

1. Open `config/params.php` and paste the App key and Client secret values from the Merchium partner page into the respective params:

	```php
	<?php

	return [

		'adminEmail' => 'admin@example.com',
		'applicationName' => 'Merchium Example App',
		'companyName' => 'My Company',

		'userPasswordResetTokenExpire' => 3600,

		/**
		 * Application params. Required for installation.
		 */
		'appKey' => 'APPKEY12345',
		'clientSecret' => 'CLIENTSECRET12345',

	];
	```
1. Restart the server to apply the new params.

Install the App in a Dev Store
------------------------------

1. [Create a dev store](https://docs.google.com/document/d/1mU7cJTNlXuaiGIQ645gxu8XonV0xm7sGnKsjdJESxxs/edit#heading=h.qp62dajl6jj5) in your Merchium partner panel.

1. Go to the app page and [install it to the dev store](https://docs.google.com/document/d/1DrNs_ae2YlyY-I0argpUHmcVEFr3Ta3-Mrl47cWeYp0/edit#heading=h.4si4ojwgrtgl).

Directories
-----------

	assets/             assets definition
	behaviors/          behaviors definition
	commands/           console commands (controllers)
	config/             application configurations
	controllers/        Web controller classes
	mail/               view files for e-mails
	models/             model classes
	runtime/            files generated during runtime
	tests/              various tests for the basic application
	vendor/             dependent 3rd-party packages
	views/              view files for the Web application
	web/                the entry script and Web resources
	widgets/            widgets for view files for the Web application
