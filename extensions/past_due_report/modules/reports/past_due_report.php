<?php
/*
 * Script: past_due_report.php collecting past due information.
 * Author: Richard Rowley
 */
checkLogin();
$defaults = $smarty->_tpl_vars['defaults'];
$language = $defaults['language'];

$cust_info = CustomersPastDue::getCustInfo($language);
$smarty->assign('cust_info', $cust_info);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu', $menu);
