<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



$sql = "select * from {$tb_prefix}payment_types ORDER BY pt_description";

$result = mysql_query($sql) or die(mysql_error());


$pts = null;

for ($i=0;$pt = mysql_fetch_array($result);$i++) {
	if ($pt['pt_enabled'] == 1) {
		$$pt['pt_enabled'] = $wording_for_enabledField;
	} else {
		$$pt['pt_enabled'] = $wording_for_disabledField;
	}
	$pts[$i]=$pt;
}
$smarty -> assign('pts',$pts);
?>
