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


if ($config->authentication->enabled != 1 ) 
{

		Zend_Session::start();

		/*
		* grab user data  from the datbase
		*/
		/*
		$sql ="
			SELECT 
				u.user_id, u.user_email, u.user_name, r.name as role_name, u.user_domain_id
			FROM 
				si_user u,  si_user_role r 
			WHERE 
				u.user_id = :user_id 
				AND 
				u.user_domain_id = :user_domain_id
		";
		
		$sth = dbQuery($sql, ':user_id', '1', ':user_domain_id','1') or die(htmlspecialchars(end($dbh->errorInfo())));
		$result = $sth->fetchRow();
		
*/
		
		/*
		* chuck the user details sans password into the Zend_auth session
		*/
		$authNamespace = new Zend_Session_Namespace('Zend_Auth');
		$authNamespace->user_id = "1";
		$authNamespace->domain_id = "1";

	
}
?>
