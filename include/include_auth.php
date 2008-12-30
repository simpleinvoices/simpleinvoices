<?php

// 1 = config->auth->enabled == "true"
if ($config->authentication->enabled == 1 ) {
	if (isset($_GET['location']) && $_GET['location'] == 'pdf' ) {
		include('../include/auth/auth.php');
	} 
	else {
		include('./include/auth/auth.php');
	}
}

/*If auth not on - use default domain and user id of 1*/
if ($config->authentication->enabled != 1 ) 
{
		//Zend_Session::start();

		/*
		* chuck the user details sans password into the Zend_auth session
		*/
		//$authNamespace = new Zend_Session_Namespace('Zend_Auth');
		$auth_session->id = "1";
		$auth_session->domain_id = "1";

}

?>
