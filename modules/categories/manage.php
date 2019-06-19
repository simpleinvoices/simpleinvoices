<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."categories WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id',domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

$smarty -> assign("number_of_rows",$number_of_rows);
$smarty -> assign('pageActive', 'categories_manage');
$smarty -> assign('active_tab', '#product');
?>
