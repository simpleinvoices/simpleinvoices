<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/invoice/manage.php
 * 	Manage Invoices page
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-08-30
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
//echo '<script>alert("manage module loaded")</script>';
//exit ('<script>alert("manage module loaded")</script>');

global $smarty, $pdoDb, $auth_session;

// stop the direct browsing to this file - let index.php handle which files get displayed
//checkLogin();
global $cc_months, $cc_years, $pagerows;//Matt
modifyDB::invoices();
//$sql = "SELECT count(*) AS count FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
//$sth = dbQuery($sql, ':domain_id', domain_id::get());
//$number_of_invoices = $sth->fetch(PDO::FETCH_ASSOC);
$pdoDb->setSelectList(array());
$pdoDb->addSimpleWhere("domain_id", $auth_session->domain_id);
$pdoDb->addToFunctions("count(id) AS count");
$rows = $pdoDb->request("SELECT", "invoices");

$smarty->assign("number_of_invoices", $rows[0]['count']);

/*$having = "";
if (isset($_GET['having'])) {
	$having = "&having=" . $_GET['having'];
}*/
$having = isset($_GET['having']) 	? 	"&having=" . $_GET['having'] 	: 	"";//Matt
$url = 'index.php?module=invoices&view=xml' . $having;
$smarty->assign('url', $url);

$smarty->assign('pageActive', "invoice");
$smarty->assign('active_tab', '#money');
$smarty->assign ("defaults", getSystemDefaults());//Matt
$smarty->assign ("array", $pagerows);//Matt
$smarty->assign('cc_months', $cc_months);//Matt