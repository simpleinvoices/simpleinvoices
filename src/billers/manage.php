<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$sql = "SELECT * FROM {$tb_prefix}biller ORDER BY b_name";

$result = mysql_query($sql, $conn) or die(mysql_error());

$billers = null;

if (mysql_num_rows($result) != 0) {
	$count = 0;
	//$display_block_lines = "";
	while ($biller = mysql_fetch_array($result)) {
	
        if ($biller['b_enabled'] == 1) {
                $biller['b_enabled'] = $wording_for_enabledField;
        } else {
        	$biller['b_enabled'] = $wording_for_disabledField;
        }
		$billers[$count] = $biller;
		$count++;
	}
}

getRicoLiveGrid("rico_biller","{ type:'number', decPlaces:0, ClassName:'alignleft'}");

$smarty -> assign("billers",$billers);
?>
