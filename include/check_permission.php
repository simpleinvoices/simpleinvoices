<?php

$auth_session = new Zend_Session_Namespace('Zend_Auth');

//print_r($_SESSION);

$acl_view   = (isset($_GET['view']) ? $_GET['view'] : null);
$acl_action = (isset($_GET['action']) ? $_GET['action'] : null);
if (empty($acl_action)) {
	// no action is given
	if (!empty($acl_view)) {
		// view is available with no action
		$checkPermission = $acl->isAllowed($auth_session->role_name, $module, $acl_view) ?  "allowed" : "denied"; // allowed
	}
} else {
	// action available
	$checkPermission = $acl->isAllowed($auth_session->role_name, $module, $acl_action) ?  "allowed" : "denied"; // allowed
}

//basic customer page check 
if( ($auth_session->role_name =='customer') AND ($module == 'customers') AND ($_GET['id'] != $auth_session->user_id) )
{
	$checkPermission = "denied";
}

//customer invoice page add/edit check since no acl for invoices
if( ($auth_session->role_name =='customer') 
		&& ($module == 'invoices') 
		 ) {
	if (   $acl_view == 'itemised' 
		|| $acl_view == 'total' 
		|| $acl_view == 'consulting' 
		|| $acl_action == 'view'
		|| ($acl_action != '' && isset($_GET['id']) && $_GET['id'] != $auth_session->user_id) ) {

		$checkPermission = "denied";

	}
}

//echo $module." :: ".$_GET['action'];
$checkPermission == "denied" ? exit($LANG['denied_page']) :"" ;
