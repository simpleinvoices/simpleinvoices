<?php
// like i said, we must never forget to start the session
session_start();

// is the one accessing this page logged in or not?
if (!isset($_SESSION['db_is_logged_in']) || $_SESSION['db_is_logged_in'] !== true) {

	if ($_GET['location'] == 'pdf' ) {
		// not logged in, and coming from the pdf converter move to login page
		header('Location: ../login.php');	
		exit;
	} 
	
	else if ($_GET['location'] !== 'pdf' ) {
	        // not logged in, move to login page
	        header('Location: login.php');       
       		exit;
        } 

	else {};

}


?>
