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
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/app'));
 
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// SYS
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH)));
// Libraries
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH) . '/lib'));



include(APPLICATION_PATH .'/index.php');
?>