<?php
/*
* Script: logout.php
* 	Logout page
*
* License:
*	 GPL v3 or above
*/

$menu = false;

// define browse
if (!defined("BROWSE")) define("BROWSE", "browse");

// we must never forget to start the session
// so config.php works ok without using index.php
Zend_Session::start();
// Regenerate ID before destroy to invalidate session cookie (prevents reuse)
Zend_Session::regenerateId();
Zend_Session::destroy(true);
header('Location: .');

?>