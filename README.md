Merchium Advanced App
================================

Based on Yii 2 Basic Application Template.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      behaviors/          contains behaviors definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      widgets/            contains widgets for view files for the Web application



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

Clone repo:

```bash
git clone git@github.com:merchium/merchium-advanced-app.git
```

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Install composer plugin using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
~~~

Install composer dependencies

~~~
php composer update
~~~

You can then access the application through the following URL:

~~~
http://localhost/merchium-advanced-app/web/
~~~


CONFIGURATION
-------------

### Check requirements

Console:
```bash
php requirements.php
```

Web:
~~~
http://localhost/merchium-advanced-app/requirements.php
~~~

### Database

Copy the file `config/db.php.example` into the `config/db.php` and edit them with real data. For example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=merchium_advanced_app',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTE:** Yii won't create the database for you, this has to be done manually before you can access it.

Use following to create database

```sql
CREATE DATABASE merchium_advanced_app CHARACTER SET utf8;
```

Use following to apply mogrations:

```bash
./yii migrate
```

### Merchium Application

Edit the file `config/params.php.example` into the `config/params.php` and edit them with real data. For example:

```php
return [
    'adminEmail' => 'admin@example.com',
    'applicationName' => 'Merchium Example App',
    'companyName' => 'My Company',

    'userPasswordResetTokenExpire' => 3600,

    /**
     * Application params - required
     */
    'appKey' => '8K7P5D0G2u6u5C4r7W0S5y4y4X5n7e9z',
    'clientSecret' => '1n3t6n6n9u2G5u3i2r8V6R5M7u5u0F2q',

];
```
