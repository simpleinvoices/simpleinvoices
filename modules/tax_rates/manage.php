<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> assign("taxes",getTaxes());

getRicoLiveGrid("rico_tax_rates","{ type:'number', decPlaces:0, ClassName:'alignleft' },
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' }");

?>
