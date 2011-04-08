<?php

$auth_session = new Zend_Session_Namespace('Zend_Auth');

//print_r($_SESSION);

$checkPermission = $acl->isAllowed($auth_session->role_name, $module, $view) ?  "allowed" : "denied"; // allowed

//sbasic customer page check - exmaple
/*
if( ($auth_session->role_name =='customer') AND ($module == 'customers') AND ($_GET['id'] != $auth_session->user_id) )
{
	$checkPermission = "denied";
}
*/

/*
 * Check customer
 */
if( ($auth_session->role_name =='customer') AND ($module == 'invoices') AND ($_GET['id']) )
{

   // Check invoice ID belongs to customer
   $invoice = invoice::select($_GET['id']);
   if($invoice['customer_id'] != $auth_session->role_link_id)
   {
        $checkPermission = "denied";
   }

}

$checkPermission == "denied" ? exit($LANG['denied_page']) :"" ;
