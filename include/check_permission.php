<?php

global $auth_session;

	// Install wizard: no ACL until schema and essential data exist (guest OK).
	if ($module === 'install' && (!$install_tables_exists || !$install_data_exists)) {
		return;
	}

	$acl_view   = $_GET['view'] ?? null;
	$acl_action = $_GET['action'] ?? null;
	$role_name  = $auth_session->role_name ?? '';

	if ($acl_action === null && $acl_view !== null) {
		$checkPermission = $acl->isAllowed($role_name, $module, $acl_view) ? "allowed" : "denied";
	} elseif ($acl_action !== null) {
		$checkPermission = $acl->isAllowed($role_name, $module, $acl_action) ? "allowed" : "denied";
	} else {
		$checkPermission = "allowed";
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
