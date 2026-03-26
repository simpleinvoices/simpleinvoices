<?php
/*
* Script: save.php
* 	Biller save page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

require_once __DIR__ . '/../../include/auth/password.php';

# Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? preg_replace('/[^a-z_]/', '', (string) $_POST['op']) : null;

#insert biller

$saved = false;

if ($op === 'insert_user') {
	requireCSRFProtection('user_save');
	$passwordHash = auth_hash_password((string) ($_POST['password_field'] ?? ''));
	$sql = "INSERT INTO " . TB_PREFIX . "user (email, password, role_id, domain_id, enabled, user_id)
	        VALUES (:email, :password, :role, :domain_id, :enabled, :user_id)";
	$sth = dbQuery($sql,
		':email', $_POST['email'] ?? '',
		':password', $passwordHash,
		':role', (int) ($_POST['role'] ?? 0),
		':domain_id', $auth_session->domain_id,
		':enabled', (int) ($_POST['enabled'] ?? 1),
		':user_id', (int) ($_POST['user_id'] ?? 0)
	);
	if ($sth) {
		$saved = true;
	}
}

if ($op === 'edit_user') {
	requireCSRFProtection('user_save');
	$passwordField = trim((string) ($_POST['password_field'] ?? ''));
	if ($passwordField !== '') {
		$passwordHash = auth_hash_password($passwordField);
		$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, password = :password, role_id = :role, enabled = :enabled, user_id = :user_id WHERE id = :id";
		$sth = dbQuery($sql,
			':email', $_POST['email'] ?? '',
			':password', $passwordHash,
			':role', (int) ($_POST['role'] ?? 0),
			':enabled', (int) ($_POST['enabled'] ?? 1),
			':user_id', (int) ($_POST['user_id'] ?? 0),
			':id', (int) ($_POST['id'] ?? 0)
		);
	} else {
		$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, role_id = :role, enabled = :enabled, user_id = :user_id WHERE id = :id";
		$sth = dbQuery($sql,
			':email', $_POST['email'] ?? '',
			':role', (int) ($_POST['role'] ?? 0),
			':enabled', (int) ($_POST['enabled'] ?? 1),
			':user_id', (int) ($_POST['user_id'] ?? 0),
			':id', (int) ($_POST['id'] ?? 0)
		);
	}
	if ($sth) {
		$saved = true;
	}
}


$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'user');
$smarty -> assign('active_tab', '#people');
?>
