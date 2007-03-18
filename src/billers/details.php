<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



#get the invoice id
$biller_id = $_GET['submit'];




#biller query
$print_biller = "SELECT * FROM {$tb_prefix}biller WHERE b_id = $biller_id";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());


$biller = mysql_fetch_array($result_print_biller);
$biller['wording_for_enabled'] = $biller['b_enabled']==1?$wording_for_enabledField:$wording_for_disabledField;


/*drop down list code for invoice logo */

$dirname="images/logo";
   $ext = array("jpg", "png", "jpeg", "gif");
   $files = array();
   if($handle = opendir($dirname)) {
       while(false !== ($file = readdir($handle)))
	   for($i=0;$i<sizeof($ext);$i++)
	       if(stristr($file, ".".$ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
		   $files[] = $file;
       closedir($handle);
   }

sort($files);

$display_block_logo_list = <<<EOD
<select name="b_co_logo">
	<option selected value="$biller[b_co_logo]" style="font-weight:bold;">$biller[b_co_logo]</option>
EOD;


$display_block_logo_list = "";
foreach ($files as $file)
{
	include("./templates/default/billers/add.tpl");
	$display_block_logo_list .= $display_block_logo_line;
}

/*end logo stuff */

#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

$display_block = "";
$footer = "";


include('./templates/default/billers/details.tpl');

if ($_GET['action'] == "view") {
	$display_block = $display_block_view;
	$footer = $footer_view;
}
if ($_GET['action'] == "edit") {
	$display_block = $display_block_edit;
	$footer = $footer_edit;
}

include('./templates/default/billers/details.tpl');

echo $block;

?>