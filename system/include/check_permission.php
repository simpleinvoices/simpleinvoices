<?php

$auth_session = new Zend_Session_Namespace('Zend_Auth');

//print_r($_SESSION);

$checkPermission = $acl->isAllowed($auth_session->role_name, $module, $_GET['action']) ?  "allowed" : "denied"; // allowed

//sbasic customer page check 
if( ($auth_session->role_name =='customer') AND ($module == 'customers') AND ($_GET['id'] != $auth_session->user_id) )
{
	$checkPermission = "denied";
}

//echo $module." :: ".$_GET['action'];
$checkPermission == "denied" ? exit($LANG['denied_page']) :"" ;
