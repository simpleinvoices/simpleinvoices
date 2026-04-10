<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//$products = getProducts();
$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

$defaults = getSystemDefaults();
$bladeView -> assign("defaults",$defaults);
$bladeView -> assign("number_of_rows",$number_of_rows);

$bladeView -> assign('pageActive', 'product_manage');
$bladeView -> assign('active_tab', '#product');
?>
