<?php
/*
* Script: login.php
* 	Login page
*
* License:
*	 GPL v2 or above
*/

$menu = false;
// we must never forget to start the session
//so config.php works ok without using index.php define browse
define("BROWSE","browse");
//print_r($_SESSION);
/*
set_include_path(get_include_path() . PATH_SEPARATOR . "./library/");
require_once './library/Zend/Loader.php';
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Zend_Debug');
Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Session');
Zend_Loader::loadClass('Zend_Config_Ini');
*/
	Zend_Session::start();
	Zend_Session::destroy(true);
	header('Location: .');

?>
