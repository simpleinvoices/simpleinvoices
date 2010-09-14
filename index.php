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

global $cust_language;

set_include_path(get_include_path() . PATH_SEPARATOR . ".");
set_include_path(get_include_path() . PATH_SEPARATOR . "./sys/include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./lib/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./lib/pdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "./sys/include/");

require_once("sys/include/init_pre.php");    

$include_dir ='./';
$smarty_include_dir = '../../../';
$smarty_embed_path = '../../../';

// look to see if there's another language in the parameter list
if (!isset($cust_language)) {
    $cust_language =  isset($_GET['lang'])  ? filenameEscape($_GET['lang'])    : null;                                                                               
}

// look to see if there's another instance to run
if (!isset($app)) {
    $app =  isset($_GET['app'])  ? filenameEscape($_GET['app'])    : 'app';                                                                               
}

$app_folder = $include_dir . $app;

include($app .'/index.php');

?>
