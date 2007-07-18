<?php


/*
* Script: logout.php
* 	Unset the session - ie log the user out
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/

// i will keep yelling this
// DON'T FORGET TO START THE SESSION !!!

session_start();

// if the user is logged in, unset the session
if (isset($_SESSION['db_is_logged_in'])) {
   unset($_SESSION['db_is_logged_in']);
}

// now that the user is logged out,
// go to login page
header('Location: login.php');
?> 
