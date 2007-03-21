<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/*drop down list code for invoice logo */



$files = getLogoList();

$smarty -> assign("files",$files);


#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");

//TODO: not complet template

//$display_block_logo_list = "";
/*foreach ($files as $file)
{
	//include("./templates/default/billers/add2.tpl");
	$display_block_logo_list .= $display_block_logo_line;
}*/


//include("./templates/default/billers/add2.tpl");

if($_POST['b_name'] == "" ) {
	if(isset($_POST['submit'])) {
		echo "<div class='validation_alert'><img src='./images/common/important.png'></img>You must enter a Biller name</div><hr></hr>";
	}
}
else {
	
	//include("./templates/default/billers/add2.tpl");
	$save = true;
}

include("./src/billers/save.php");

$smarty -> assign('save',$save);

//echo $block;

?>
