<?php
/*
* Script: manage.php
* 	Biller manage page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$billers = getBillers();

$pageActive = "billers";


getRicoLiveGrid("rico_biller","{ type:'number', decPlaces:0, ClassName:'alignleft'}");

$smarty -> assign("billers",$billers);
$smarty -> assign('pageActive', $pageActive);
?>
