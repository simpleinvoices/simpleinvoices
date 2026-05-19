<?php


global $auth_session;

//if user logged into Simple Invoices with auth off then auth turned on - id via fake_auth and kill session
if ($config->authentication->enabled == 1 && $auth_session->fake_auth === '1') {
    session_regenerate_id(true);
    $auth_session->destroy();
    header('Location: .');
    exit;
}

// 1 = config->auth->enabled == "true"
if ($config->authentication->enabled == 1) {
    if ($auth_session->domain_id == null) {
        $auth_session->domain_id = "1";
    }

    include('./include/auth/auth.php');
}

/*If auth not on - use default domain and user id of 1*/
if ($config->authentication->enabled != 1 ) 
{
	
		/*
			* chuck the user details sans password into the legacy session
			*/
	
		$auth_session->id = "1";
		$auth_session->domain_id = "1";
		$auth_session->email = "demo@simpleinvoices.org";
		//fake_auth is identifier to say that user logged in with auth off
		$auth_session->fake_auth = "1";
		//No Customer login as logins disabled
		$auth_session->user_id = "0";

}
