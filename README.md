This is tisseurs-event-scheduler, an WordPress plugin to handle events in the association Tisseurs de Chimeres 🕸️.

## File structure

This repository is organized as follow :

 - `src` contains source of the plugin:
   - `tisseurs-event-scheduler.php` is the main file of the plugin,
   - `Db.php` is the class to handle Db,
   - `views` contains views of the plugin;
 - `tests` contains tests of the plugin:
   - `php` contains phpunit config file; 
 - `build.bash` is a build bash script to create an zip archive of the source files.
 - `composer.json` contains the configuration of `composer`;
 - `composer.lock` contains the versions of installed composer packages.

## To work on the project

To work on the project, you will need [composer](https://getcomposer.org/) and the composer packages : 

- phpunit/phpunit,
- wp-phpunit/wp-phpunit,
- yoast/phpunit-polyfills,
- brain/monkey

### Install the package 🚚

You will need to run the command `composer install`.

```bash
composer install
```

After that you have to configure PHP autoload with the command `composer -d . dump-autoload`.

```bash
composer -d . dump-autoload
```

### Build the plugin 🛠️

In order to build the plugin as a zip archive installable in WordPress, you have to run the command `composer build`.

```bash
composer build
```

### Unit test the plugin 🧪

In order to test with PHPUnit the plugin, you have to run the command `composer test`.

```bash
composer test
```