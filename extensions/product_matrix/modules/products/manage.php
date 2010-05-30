<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//$products = getProducts();
$sql = "SELECT count(*) as count FROM ".TB_PREFIX."products";
$sth = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

$smarty -> assign("number_of_rows",$number_of_rows);

$pageActive = "products";
$smarty->assign('pageActive', $pageActive);


?>
