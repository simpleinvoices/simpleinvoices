<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$customers = getCustomers();

$smarty -> assign("customers",$customers);

getRicoLiveGrid("rico_customer","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' },{ type:'number', decPlaces:2, ClassName:'alignleft' }");

?>
