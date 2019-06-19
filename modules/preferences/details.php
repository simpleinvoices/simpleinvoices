<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$files = getLogoList();

#get the invoice id
$preference_id = $_GET['id'];

$preference = getPreference($preference_id);
$index_group = getPreference($preference['index_group']);

$preferences = getActivePreferences();
$defaults = getSystemDefaults();
$status = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));
$localelist = Zend_Locale::getLocaleList();

if ($_GET["module"] == "preferences") {
	
	$default = "template";
	/*drop down list code for invoice template - only show the folder names in src/invoices/templates*/

	$handle=opendir("./templates/invoices/");
	while ($template = readdir($handle)) {
		if ($template != ".." && $template != "." && $template !="logos" && $template !=".svn" && $template !="template.php" && $template !="template.php~" ) {
			$templateslist[] = $template;
		}
	}
	closedir($handle);
	sort($templateslist);

	$escaped = htmlsafe($preference['template']);
	$display_block_templates_list = <<<EOD
	<select name="value">
EOD;

	$display_block_templates_list .= <<<EOD
	<option selected value='$escaped' style="font-weight: bold" >$escaped</option>
EOD;

	foreach ( $templateslist as $var )
	{
		$var = htmlsafe($var);
		$display_block_templates_list .= "<option value='$var' >";
		$display_block_templates_list .= $var;
		$display_block_templates_list .= "</option>";
	}


	$display_block_templates_list .= "</select>";

	/*end drop down list section */	
	
	$valuetemp = $display_block_templates_list;
	//error_log($value);

}

$smarty->assign('valuetemp',$valuetemp);

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}

$smarty->assign('files',$files);
$smarty->assign('preference',$preference);
$smarty->assign('defaults',$defaults);
$smarty->assign('index_group',$index_group);
$smarty->assign('preferences',$preferences);
$smarty->assign('status',$status);
$smarty->assign('localelist',$localelist);

$smarty -> assign('pageActive', 'preference');
$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
?>
