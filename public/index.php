<?php

/*
   ============================================================================================================================
   Simple Invoices
   www.simpleinvoices.org

   MAIN FILE

   This file will default startup with the app folder
   To implement more instances, copy the app folder into another folder and change the settings.

   To startup SI with another instance then app use:  index.php?app=xxxx where xxxx is the name of the instance
   To startup SI with another default language use: index.php?lang=xxxx where xxxx is the language i.e. nl_NL

   sample of both: index.php?lang=nl_NL&app=app_mycompany

   Build 2010.09.1
   ============================================================================================================================
*/

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(dirname(__FILE__)) . '/app'));
 
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// SYS
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH)));
// Libraries
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH) . '/lib'));

require_once 'Zend/Application.php';

/**
* To start up SI with another instance just point to a new configuration ini file.
*/
$application = new Zend_Application( APPLICATION_ENV, APPLICATION_PATH . '/config/config.ini' );
$application->bootstrap();
//$application->run();

/**
* Ugly, ugly! 
* All the code should be in the application but we _MUST_
* make this work ASAP.
* 
* ToDo: Make it cleaner!
*/
include(APPLICATION_PATH .'/index.php');
?>