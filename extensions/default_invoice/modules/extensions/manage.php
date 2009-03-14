<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
function getExtensions() {
	global $LANG;
	global $dbh;
	global $auth_session;
	
	$sql = "SELECT * FROM si_dev_extensions WHERE domain_id =  :domain_id ORDER BY name";
	$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
	
	$exts = null;
	
	for($i=0;$ext = $sth->fetch();$i++) {
		if ($ext['enabled'] == 1) {
			$ext['enabled'] = $LANG['enabled'];
		} else {
			$ext['enabled'] = $LANG['disabled'];
		}

		$exts[$i] = $ext;
	}
	
	return $exts;
}

$extension_id = $_GET['id'];
$action = $_GET['action'];

if ($action == 'toggle') {
	statusExtension($extension_id) or die(htmlspecialchars("Something went wrong with the status change!"));
}

$smarty -> assign("exts",getExtensions());

$smarty -> assign('pageActive', 'extensions');
$smarty -> assign('active_tab', '#settings');
