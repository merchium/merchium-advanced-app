Merchium Advanced App
=====================

Пример приложения для Мерчиума, демонстрирующий подключение [скриптов](https://docs.google.com/document/d/1zlNv0s5XWCJMcpRYzOfiGyyKGP2_1XKb4LGMVI2hRyQ/edit) и [HTML-контента](https://docs.google.com/document/d/1vS82CLQ7Jf1fourYRfcynYKYndU4GJBLdlAeC7VFGWs/edit) к витрине, [веб-хуки](https://docs.google.com/document/d/1FtwxZBRCZ1EBMPVHf6PZwSH0Mvmq7UjXMrR_LiuT6uk/edit) и [интеграцию платежного сервиса](https://docs.google.com/document/d/1Qe1_k59OekJ6SUNLgVo4gci34xJOhvWzfa0dTVt1i9Y/edit).

В приложении реализованы:

	1. Снегопад в витрине
	1. Окно приветствия при посещении витрины
	1. Счетчик нажатий на кнопку «В корзину»
	1. Пример платежного сервиса

Код приложения основан на [шаблоне базового приложения на фреймворке Yii 2](https://github.com/yiisoft/yii2-app-basic).

Системные требования
--------------------

PHP 5.4.0 и веб-сервер с его поддержкой.

В инструкции по установке и запуску приложения используется встроенный сервер PHP, SQLite и [ngrok](https://ngrok.com/). Вместо SQLite можно использовать MySQL или другую БД.

Чтобы проверить, соответствует ли ваша система требованиям, запустите `php requirements.php`.

Установка
---------

1. Склонируйте репозиторий и перейдите в папку с приложением:

	```bash
	$ git clone https://github.com/merchium/merchium-advanced-app.git
	$ cd merchium-advanced-app
	```

1. [Установите Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

1. Установите плагин и зависимости:

	```bash
	$ composer global require "fxp/composer-asset-plugin:1.0.0"
	$ composer update
	```

1. Установите права доступа на папки:

	```bash
	$ chmod -R 777 runtime
	$ chmod -R 777 web/assets
	```

Настройка БД
------------

1. Создайте БД из шаблона:

    ```bash
    $ cp sqlite.db merchium-advanced-app.db
    ```

1. Создайте конфиг БД из шаблона:
	
	```bash
	$ cp config/db.php.example config/db.php
	```

1. Отредактируйте конфиг БД (путь к файлу БД должен быть абсолютным):

	```php
	<?php

	return [
		'class' => 'yii\db\Connection',
		'dsn' => 'sqlite:/absolute/path/to/merchium-advanced-app.db',
		'charset' => 'utf8',
	];
	```

**Обратите внимание:** Шаблон sqlite.db уже заполнен данными. Если вы используете другую БД, заполните ее данными с помощью миграций:

```bash
$ php yii migrate
```

Запуск
------

1. Запустите встроенный веб-сервер PHP:

	```bash
	php -S localhost:8000
	```

1. С помощью ngrok сгенерируйте глобальную https-ссылку:

	```bash
	$ ngrok http 8000
	...
	Forwarding                    https://3cd89e8a.ngrok.com -> 127.0.0.1:8000
	...
	```

Приложение в Маркете Мерчиума
-----------------------------

1. Перейдите на страницу https://3cd89e8a.ngrok.com/web/index.php?r=site%2Flogin и войдите как пользователь *admin* с паролем *admin*.

Вы увидите URL страницы настроек приложения и URL страницы установки приложения (Redirect URI).

1. Откройте [панель партнера](http://marketplace.merchium.ru/partner.php) в новой вкладке и [создайте новое приложение](https://docs.google.com/document/d/1pYS6ta0NzWd_JmxP8xbmjDI8aCppJ8Z5JaFzB5DaZTs/edit#heading=h.we5t9psgine1). Введите URL страницы настроек приложения и URL страницы установки приложения (Redirect URI), которые вы получили на предыдущем шаге.

1. Переключитесь во вкладку **Привилегии приложения** и выберите следующие привилегии:

	- Просмотр, создание, редактирование и удаление подключений сторонних скриптов (ScriptTags)
	- Просмотр, создание, редактирование и удаление подключений содержимого HTML и Smarty 3 к хукам (TemplateHooks)
	- Просмотр, создание, редактирование и удаление платежных процессоров (PaymentProcessors)
	- Создание, редактирование и удаление веб-хуков (Webhooks)

1. Создайте конфиг из шаблона:

	```bash
	$ cp config/params.php.example config/params.php
	```

1. Откройте файл `config/params.php` и вставьте значения App key и Client secret со страницы приложения в панели партнера:

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
1. Перезапустите сервер, чтобы изменения вступили в силу.

Установка в магазин для разработки
----------------------------------

1. [Создайте магазин для разработки](https://docs.google.com/document/d/1pYS6ta0NzWd_JmxP8xbmjDI8aCppJ8Z5JaFzB5DaZTs/edit#heading=h.yupo48jq0g7z).

1. Перейдите на страницу приложения и [установите его в магазин для разработки](https://docs.google.com/document/d/1t4rvueMARuzN42YuuILPV__XPBQssSvIRaF_6UvmOIU/edit#heading=h.czhvqfkcd6o5).

Структура папок
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
