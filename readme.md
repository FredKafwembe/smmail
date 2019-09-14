# Smmail - A Php SMS and Email library

## INSTALLATION
### Using Composer

To install the library you will need to add the following into your composer.json file.

This will specify that smmail is a requirment for your project.
```
"require": {
	"tgwob/smmail": "dev-master"
}
```
This will give you access the repository that smmail is hosted in.
```
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/FredKafwembe/smmail"
	}
]
```
Save your composer.json and run the following command to install smmail.

composer update

## Configuring Smmail

Smmail has some configurations that need to be setup depending on what you want to use. To setup the configurations add the following defines to a php file and set them to suit your needs and environment.

Note: if you are using composer and you add all the configurations into a file of their own you will need to add that file to the autoload section in your composer.json. For example if you add a file called config.php.
```
"autoload": {
	"files": ["path-to-config-file/config.php"]
}
```
After updating your composer.json run
```
composer dump-autoload
```
### Database settings
Smmail has the ability to store all the information that it sends into a database. To enable this add the following defines.
```php
define("ENABLE_DATABASE_STORAGE", true);
define("DATABASE_TYPE", "mysql");
define("DATABASE_HOST", "localhost");
define("DATABASE_NAME", "Smmail");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "");
```
The required tables will be created in the database that you specifiy automatically once you attempt to use any function that requires data to be stored into the database. The table names are prefixed with "smmail" to avoid any table name collisions, this makes it easy to use your applications database with smmail.

### SendGrid settings
Smmail uses SendGrid to send emails, to use the email functionality you will need to create a SendGrid account and add the following defines. You can create a SendGrid account at [SendGrid](https://sendgrid.com).
```php
define("SENDGRID_API_KEY", "Your SendGrid API key");
```
### Nexmo settings
To send SMSs smmail uses Nexmo, in order to use SMS functionality add the following define. You will as need to create a Nexmo account to obtain your own key and secret. You can create a Nexmo account at [Nexmo](https://www.nexmo.com).
```php
define("NEXMO_API_KEY", array("key" => "Your Nexmo key", "secret" => "Your Nexmo secret"));
```
