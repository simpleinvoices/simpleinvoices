<?php
/*
 * Script: past_due_report.php collecting past due information.
 * Author: Richard Rowley
 */
global $menu, $smarty;

checkLogin();
$language = $smarty->tpl_vars['defaults']->language;

$cust_info = CustomersPastDue::getCustInfo($language);
$smarty->assign('cust_info', $cust_info);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
$smarty->assign('menu'      , $menu);
