<?php
ob_start();
// like i said, we must never forget to start the session
session_start();

print_r($_SESSION);
// is the one accessing this page logged in or not?

require_once "Auth.php";

$options = array(
   'dsn' => $db_server."://".$db_user."@".$db_host."/".$db_name."",
   'table' => TB_PREFIX.'users',
   'usernamecol' => 'user_email',
   'passwordcol' => 'user_password',
   'cryptType' => 'md5',
   'regenerateSessionId' => true,
   'db_fields' => '*'
);

$a = new Auth("MDB2", $options, "loginFunction");
	  

//if ($a->getAuth() == FALSE) {
if ( ($a->getAuth() == FALSE) AND ($_SESSION[md5($authSessionIdentifier)] != md5($authSessionIdentifier) ))  
{
//if (!isset($_SESSION['db_is_logged_in']) || $_SESSION['db_is_logged_in'] !== true) {

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
