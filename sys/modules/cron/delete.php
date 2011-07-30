<?php
/*
* Script: delete.php
* 	Do the deletion of a recurrence page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Seth Lauzon
*
* Last edited:
* 	 2011-04-14
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
*/

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the cron id
$cron_id = $_GET['id'];

/*If delete is disabled - dont allow people to view this page*/
if ( $defaults['delete'] == 'N' ) {
	die('Deletion has been disabled, you are not supposed to be here');
}

#get cron info
$get_cron = new cron();
$get_cron->id = $cron_id;
$cron = $get_cron->select();

if ( ($_GET['stage'] == 2 ) AND ($_POST['doDelete'] == 'y') ) {
	global $dbh;

	$dbh->beginTransaction();
	$error = false;

	//delete the info from the cron table
	if (! delete('cron','id',$cron_id)) {
		$error = true;
	}

	if ($error) {
		$dbh->rollBack();
	} else {
		$dbh->commit();
	}
	echo "<meta http-equiv='refresh' content='2;URL=index.php?module=cron&view=manage' />";

}

$smarty -> assign('cron',$cron);
$smarty -> assign('pageActive', 'cron');
$smarty -> assign('subPageActive', 'cron_edit');
$smarty -> assign('active_tab', '#money');
