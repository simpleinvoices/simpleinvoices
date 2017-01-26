<?php
/*
 * Script: manage.php
 * Biller manage page
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 * Last edited:
 * 2007-07-19
 * License:
 * GPL v2 or above
 * Website:
 * http://www.simpleinvoices.org
 */
global $dbh, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT count(*) AS count FROM " . TB_PREFIX . "biller WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows = $sth->fetch(PDO::FETCH_ASSOC);

$smarty->assign("number_of_rows", $number_of_rows);
$smarty->assign('pageActive', 'biller');
$smarty->assign('active_tab', '#people');
