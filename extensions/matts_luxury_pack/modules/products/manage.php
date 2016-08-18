<?php

//print_r($defaults);

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//$products = getProducts();
$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);
/**/
$sql = "SELECT * FROM ".TB_PREFIX."custom_fields WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', domain_id::get()) or die(end($dbh->errorInfo()));
$cfs = array();
for($i=0; $cf = $sth->fetch(); $i++) {
	$cfs[$cf['cf_custom_field']] = $cf['cf_custom_label'];
}
$smarty -> assign("cfs", $cfs);
/**/
$smarty->assign ("defaults", getSystemDefaults());
$smarty -> assign("number_of_rows",$number_of_rows);

$smarty -> assign('pageActive', 'product_manage');
$smarty -> assign('active_tab', '#product');
?>
