<?php

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT count(*) AS count FROM " . TB_PREFIX . "products WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows = $sth->fetch(PDO::FETCH_ASSOC);

$defaults = getSystemDefaults();

$cflgs = getCustomFlagsQualified('E', domain_id::get());
$smarty->assign("cflgs", $cflgs);
$smarty->assign("defaults", $defaults);
$smarty->assign("number_of_rows", $number_of_rows);

$smarty->assign('pageActive', 'product_manage');
$smarty->assign('active_tab', '#product');
