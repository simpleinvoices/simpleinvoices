<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$products = getProducts();
$smarty -> assign("products",$products);

getRicoLiveGrid("rico_product","{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' }");

?>
