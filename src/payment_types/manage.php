<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "select * from {$tb_prefix}payment_types ORDER BY pt_description";

$result = mysql_query($sql) or die(mysql_error());


$pts = null;

for ($i=0;$pt = mysql_fetch_array($result);$i++) {
	if ($pt['pt_enabled'] == 1) {
		$$pt['pt_enabled'] = $LANG['enabled'];
	} else {
		$$pt['pt_enabled'] = $LANG['disabled'];
	}
	$pts[$i]=$pt;
}
$smarty -> assign('pts',$pts);

getRicoLiveGrid("rico_payment_types","{ type:'number', decPlaces:0, ClassName:'alignleft' }");
?>
