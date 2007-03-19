<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/*drop down list code for invoice logo */


$files = getLogoList();


#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

//TODO: not complet template

$display_block_logo_list = "";
foreach ($files as $file)
{
	include("./templates/default/billers/add.tpl");
	$display_block_logo_list .= $display_block_logo_line;
}


include("./templates/default/billers/add.tpl");

if($_POST['b_name'] == "" ) {
	if(isset($_POST['submit'])) {
		echo "<div class='validation_alert'><img src='./images/common/important.png'></img>You must enter a Biller name</div><hr></hr>";
	}
}
else {
	include("./src/billers/save.php");
	include("./templates/default/billers/add.tpl");
	$block = $save;
}

echo $block;

?>
