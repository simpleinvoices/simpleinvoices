<?php
/*
* Script: logout.php
* 	Logout page
*
* License:
*	 GPL v3 or above
*/
$menu = false;
if ($menu) {} // eliminates unused warning
// we must never forget to start the session
//so config.php works ok without using index.php define browse
if (!defined("BROWSE")) define("BROWSE", "browse");

Zend_Session::start();
Zend_Session::destroy(true);
header('Location: .');
