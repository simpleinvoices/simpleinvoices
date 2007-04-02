<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



#get the invoice id
$biller_id = $_GET['submit'];


$biller = getBiller($biller_id);
$biller['wording_for_enabled'] = $biller['b_enabled']==1?$wording_for_enabledField:$wording_for_disabledField;


/*drop down list code for invoice logo */

$files = getLogoList();

include("./templates/default/billers/details2.tpl");
$display_block_logo_list = "";

foreach ($files as $file)
{
	eval ('$display_block_logo_list .= "'.$display_block_logo_line.'";');
}

/*end logo stuff */

#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

$display_block = "";
$footer = "";

eval ('$display_block_logo = "'.addslashes($display_block_logo).'";');

if ($_GET['action'] == "view") {
	eval ('$display_block_view = "'.addslashes($display_block_view).'";');
	$display_block = $display_block_view;
	$footer = $footer_view;
}
if ($_GET['action'] == "edit") {
	eval ('$display_block_edit = "'.addslashes($display_block_edit).'";');
	$display_block = $display_block_edit;
	$footer = $footer_edit;
}


eval ('$block = "'.addslashes($block).'";');
echo $block;

?>