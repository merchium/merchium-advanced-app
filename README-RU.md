Merchium Advanced App
=====================

������ ���������� ��� ��������, ��������������� ����������� [��������](https://docs.google.com/document/d/1zlNv0s5XWCJMcpRYzOfiGyyKGP2_1XKb4LGMVI2hRyQ/edit) � [HTML-��������](https://docs.google.com/document/d/1vS82CLQ7Jf1fourYRfcynYKYndU4GJBLdlAeC7VFGWs/edit) � �������, [���-����](https://docs.google.com/document/d/1FtwxZBRCZ1EBMPVHf6PZwSH0Mvmq7UjXMrR_LiuT6uk/edit) � [���������� ���������� �������](https://docs.google.com/document/d/1Qe1_k59OekJ6SUNLgVo4gci34xJOhvWzfa0dTVt1i9Y/edit).

� ���������� �����������:

	1. �������� � �������
	1. ���� ����������� ��� ��������� �������
	1. ������� ������� �� ������ �� �������
	1. ������ ���������� �������

��� ���������� ������� �� [������� �������� ���������� �� ���������� Yii 2](https://github.com/yiisoft/yii2-app-basic).

��������� ����������
--------------------

PHP 5.4.0 � ���-������ � ��� ����������.

� ���������� �� ��������� � ������� ���������� ������������ ���������� ������ PHP, SQLite � [ngrok](https://ngrok.com/). ������ SQLite ����� ������������ MySQL ��� ������ ��.

����� ���������, ������������� �� ���� ������� �����������, ��������� `php requirements.php`.

���������
---------

1. ����������� ����������� � ��������� � ����� � �����������:

	```bash
	$ git clone https://github.com/merchium/merchium-advanced-app.git
	$ cd merchium-advanced-app
	```

1. [���������� Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

1. ���������� ������ � �����������:

	```bash
	$ composer global require "fxp/composer-asset-plugin:1.0.0"

	$ composer update
	```

��������� ��
------------

1. �������� �� �� �������:

    ```bash
    $ cp sqlite.db merchium-advanced-app.db
    ```

1. �������� ������ �� �� �������:
	
	```bash
	$ cp config/db.php.example config/db.php
	```

1. �������������� ������ �� (���� � ����� �� ������ ���� ����������):

	```php
	<?php

	return [
		'class' => 'yii\db\Connection',
		'dsn' => 'sqlite:/absolute/path/to/merchium-advanced-app.db',
		'charset' => 'utf8',
	];
	```

**�������� ��������:** ������ sqlite.db ��� �������� �������. ���� �� ����������� ������ ��, ��������� �� ������� � ������� ��������:

```bash
$ php yii migrate
```

������
------

1. ��������� ���������� ���-������ PHP:

	```bash
	php -S localhost:8000
	```

1. � ������� ngrok ������������ ���������� https-������:

	```bash
	$ ngrok http 8000
	...
	Forwarding                    https://3cd89e8a.ngrok.com -> 127.0.0.1:8000
	...
	```

���������� � ������� ��������
-----------------------------

1. ��������� �� �������� https://3cd89e8a.ngrok.com/web/index.php?r=site%2Flogin � ������� ��� ������������ *admin* � ������� *admin*.

�� ������� URL �������� �������� ���������� � URL �������� ��������� ���������� (Redirect URI).

1. �������� [������ ��������](http://marketplace.merchium.ru/partner.php) � ����� ������� � [�������� ����� ����������](https://docs.google.com/document/d/1pYS6ta0NzWd_JmxP8xbmjDI8aCppJ8Z5JaFzB5DaZTs/edit#heading=h.we5t9psgine1). ������� URL �������� �������� ���������� � URL �������� ��������� ���������� (Redirect URI), ������� �� �������� �� ���������� ����.

1. ������������� �� ������� **���������� ����������** � �������� ��������� ����������:

	- ��������, ��������, �������������� � �������� ����������� ��������� �������� (ScriptTags)
	- ��������, ��������, �������������� � �������� ����������� ����������� HTML � Smarty 3 � ����� (TemplateHooks)
	- ��������, ��������, �������������� � �������� ��������� ����������� (PaymentProcessors)
	- ��������, �������������� � �������� ���-����� (Webhooks)

1. �������� ������ �� �������:

	```bash
	$ cp config/params.php.example config/params.php
	```

1. �������� ���� `config/params.php` � �������� �������� App key � Client secret �� �������� ���������� � ������ ��������:

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
1. ������������� ������, ����� ��������� �������� � ����.

��������� � ������� ��� ����������
----------------------------------

1. [�������� ������� ��� ����������](https://docs.google.com/document/d/1pYS6ta0NzWd_JmxP8xbmjDI8aCppJ8Z5JaFzB5DaZTs/edit#heading=h.yupo48jq0g7z).

1. ��������� �� �������� ���������� � [���������� ��� � ������� ��� ����������](https://docs.google.com/document/d/1t4rvueMARuzN42YuuILPV__XPBQssSvIRaF_6UvmOIU/edit#heading=h.czhvqfkcd6o5).

��������� �����
---------------

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