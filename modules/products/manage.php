<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$products = getProducts();

$pageActive = "products";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign("products",$products);

?>
