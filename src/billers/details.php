<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#table
include("./include/validation.php");

/*validation code*/
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();
/*end validation code*/


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

foreach ( $files as $var )
{
	$display_block_logo_list .= "<option>{$var}</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

$display_block = "";
$footer = "";


include('./src/billers/details.tpl');

if ($_GET['action'] == "view") {
	$display_block = $display_block_view;
	$footer = $footer_view;
}
else if ($_GET['action'] == "edit") {
	$display_block = $display_block_edit;
	$footer = $footer_edit;
}

include('./templates/default/billers/details.tpl');

echo $block;

?>