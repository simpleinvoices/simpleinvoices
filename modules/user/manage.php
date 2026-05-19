<?php
/*
* Script: manage.php
* 	Manage users page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

checkLogin();

$sql = "SELECT count(*) as count FROM ".TB_PREFIX."user WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
$number_of_rows  = $sth->fetch(PDO::FETCH_ASSOC);

$bladeView -> assign("number_of_rows",$number_of_rows);

$userSavedOp = (string) ($_GET['user_saved'] ?? '');
if (!in_array($userSavedOp, ['insert_user', 'edit_user'], true)) {
    $userSavedOp = '';
}
$bladeView->assign('userSavedOp', $userSavedOp);

$bladeView -> assign('pageActive', 'user');
$bladeView -> assign('active_tab', '#people');
?>