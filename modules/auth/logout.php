<?php
/*
* Script: login.php
* 	Login page
*
* License:
*	 GPL v3 or above
*/

$menu = false;
// we must never forget to start the session
//so config.php works ok without using index.php define browse
define("BROWSE","browse");

	Zend_Session::start();
	Zend_Session::destroy(true);
	header('Location: .');

?>