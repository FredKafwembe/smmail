<?php

/* Database settings */
define("ENABLE_DATABASE_STORAGE", true);
define("DATABASE_TYPE", "mysql");
define("DATABASE_HOST", "localhost");
define("DATABASE_NAME", "Smmail");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "");

/* Sendgrid settings - Used to send emails */
define("SENDGRID_API_KEY", "***REMOVED***");

/* Nexmo settings - Used to send text messages */
define("NEXMO_API_KEY", array("key" => "***REMOVED***", "secret" => "***REMOVED***"));