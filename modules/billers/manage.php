<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$billers = getBillers();

getRicoLiveGrid("rico_biller","{ type:'number', decPlaces:0, ClassName:'alignleft'}");

$smarty -> assign("billers",$billers);
?>
