<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$sql = "SELECT * FROM {$tb_prefix}biller ORDER BY b_name";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);

include("./templates/default/billers/manage.tpl");
if (mysql_num_rows($result) == 0) {
	$block = $display_block_vide;
}
else {

	
	$display_block_lines = "";
	while ($biller = mysql_fetch_array($result)) {
	
        if ($biller['b_enabled'] == 1) {
                $wording_for_enabled = $wording_for_enabledField;
        } else {
                $wording_for_enabled = $wording_for_disabledField;
        }

		include("./templates/default/billers/manage.tpl");
		$display_block_lines .= $display_block_line;           
	
	}
	
	include("./templates/default/billers/manage.tpl");

	$block = $display_block;

}

require("./src/include/js/lgplus/php/chklang.php");
require("./src/include/js/lgplus/php/settings.php");

getRicoLiveGrid("rico_biller","{ type:'number', decPlaces:0, ClassName:'alignleft'}");
	
echo $block; 
?>