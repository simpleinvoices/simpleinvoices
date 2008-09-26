<?php
// like i said, we must never forget to start the session
Zend_Session::start();
//session_start();

// is the one accessing this page logged in or not?
if (!isset($_SESSION['Zend_Auth']['user_id']))
{
	if  ($_GET['module'] !== "auth")  
	{

		if ($_GET['location'] == 'pdf' ) {
			// not logged in, and coming from the pdf converter move to login page
			header('Location: ../index.php?module=auth&view=login');	
			exit;
		} 
		
		else if ($_GET['location'] !== 'pdf' ) {
				// not logged in, move to login page
				header('Location: index.php?module=auth&view=login');       
				exit;
			} 
		else {};
	}

}

?>
