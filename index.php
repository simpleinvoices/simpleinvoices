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

global $cust_language;

// SYS
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH)));
// Libraries
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(APPLICATION_PATH) . '/lib'));

require_once("sys/include/init_pre.php");

$include_dir ='./';
$smarty_embed_path = getcwd() . '/';
$tpl_path = '../';

// look to see if there's another language in the parameter list
if (!isset($cust_language)) {
    $cust_language =  isset($_GET['lang'])  ? filenameEscape($_GET['lang'])    : null;
}

// look to see if there's another instance to run
if (!isset($app)) {
    $app =  isset($_GET['app'])  ? filenameEscape($_GET['app'])    : 'app';
}

$app_folder = $include_dir . $app;

$pdf_dir = '../../' . $app;

include($app .'/index.php');
