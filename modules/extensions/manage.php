<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
function getExtensions() {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM si_extensions WHERE domain_id = 0 OR domain_id = :domain_id ORDER BY name";
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	
	$exts = null;
	
	for($i=0; $ext = $sth->fetch(); $i++) {
		$exts[$i] = $ext;
	}
	
	return $exts;
}

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
	setStatusExtension($extension_id) or die(htmlsafe("Something went wrong with the status change!"));
}

$smarty -> assign("exts",getExtensions());

$smarty -> assign('pageActive', 'setting');
$smarty -> assign('active_tab', '#setting');
$smarty -> assign('subPageActive', 'setting_extensions');
?>
