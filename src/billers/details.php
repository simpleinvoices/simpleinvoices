<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



#get the invoice id
$biller_id = $_GET['submit'];


$biller = getBiller($biller_id);
$biller['wording_for_enabled'] = $biller['b_enabled']==1?$wording_for_enabledField:$wording_for_disabledField;


/*drop down list code for invoice logo */

$files = getLogoList();


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