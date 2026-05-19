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
require_once __DIR__ . '/../../include/user_ui_language.php';

# Deal with op and add some basic sanity checking
$op = !empty($_POST['op']) ? preg_replace('/[^a-z_]/', '', (string) $_POST['op']) : null;

#insert biller

$saved     = false;
$saveError = null;

if ($op === 'insert_user') {
	requireCSRFProtection('user_save');
	$plain = (string) ($_POST['password_field'] ?? '');
	if (strlen($plain) > 0 && strlen($plain) < 4) {
		$saveError = 'Password must be at least 4 characters.';
	} else {
		$passwordHash = auth_hash_password($plain);
		$roleId       = (int) ($_POST['role'] ?? 0);
		$domainId     = (int) $auth_session->domain_id;
		list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role(
			$roleId,
			$domainId,
			(string) ($_POST['email'] ?? '')
		);
		$prefCol = checkFieldExists(TB_PREFIX . 'user', 'preferred_language');
		$prefVal = $prefCol ? si_user_preferred_language_from_post() : null;
		if ($prefCol) {
			$sql = "INSERT INTO " . TB_PREFIX . "user (email, name, password, role_id, domain_id, enabled, user_id, auth_staff_email, auth_customer_key, preferred_language)
			        VALUES (:email, :name, :password, :role, :domain_id, :enabled, :user_id, :auth_staff_email, :auth_customer_key, :pref_lang)";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':password', $passwordHash,
				':role', $roleId,
				':domain_id', $domainId,
				':enabled', (int) ($_POST['enabled'] ?? 1),
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey,
				':pref_lang', $prefVal
			);
		} else {
			$sql = "INSERT INTO " . TB_PREFIX . "user (email, name, password, role_id, domain_id, enabled, user_id, auth_staff_email, auth_customer_key)
			        VALUES (:email, :name, :password, :role, :domain_id, :enabled, :user_id, :auth_staff_email, :auth_customer_key)";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':password', $passwordHash,
				':role', $roleId,
				':domain_id', $domainId,
				':enabled', (int) ($_POST['enabled'] ?? 1),
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey
			);
		}
		if ($sth) {
			$saved = true;
		}
	}
}

if ($op === 'edit_user') {
	requireCSRFProtection('user_save');

	// Prevent the currently logged-in user from disabling themselves
	$editingId = (int) ($_POST['id'] ?? 0);
	$enabledValue = (int) ($_POST['enabled'] ?? 1);
	if ($editingId === (int) $auth_session->id && $enabledValue === 0) {
		$enabledValue = 1;
	}

	$passwordField = trim((string) ($_POST['password_field'] ?? ''));
	$sth           = null;
	$prefCol       = checkFieldExists(TB_PREFIX . 'user', 'preferred_language');
	$prefVal       = $prefCol ? si_user_preferred_language_from_post() : null;
	if ($passwordField !== '' && strlen($passwordField) < 4) {
		$saveError = 'Password must be at least 4 characters.';
	} elseif ($passwordField !== '') {
		$passwordHash = auth_hash_password($passwordField);
		$roleId       = (int) ($_POST['role'] ?? 0);
		$domainId     = (int) $auth_session->domain_id;
		list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role(
			$roleId,
			$domainId,
			(string) ($_POST['email'] ?? '')
		);
		if ($prefCol) {
			$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, name = :name, password = :password, role_id = :role, enabled = :enabled, user_id = :user_id, auth_staff_email = :auth_staff_email, auth_customer_key = :auth_customer_key, preferred_language = :pref_lang WHERE id = :id AND domain_id = :domain_id";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':password', $passwordHash,
				':role', $roleId,
				':enabled', $enabledValue,
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey,
				':pref_lang', $prefVal,
				':id', $editingId,
				':domain_id', $domainId
			);
		} else {
			$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, name = :name, password = :password, role_id = :role, enabled = :enabled, user_id = :user_id, auth_staff_email = :auth_staff_email, auth_customer_key = :auth_customer_key WHERE id = :id AND domain_id = :domain_id";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':password', $passwordHash,
				':role', $roleId,
				':enabled', $enabledValue,
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey,
				':id', $editingId,
				':domain_id', $domainId
			);
		}
	} else {
		$roleId   = (int) ($_POST['role'] ?? 0);
		$domainId = (int) $auth_session->domain_id;
		list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role(
			$roleId,
			$domainId,
			(string) ($_POST['email'] ?? '')
		);
		if ($prefCol) {
			$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, name = :name, role_id = :role, enabled = :enabled, user_id = :user_id, auth_staff_email = :auth_staff_email, auth_customer_key = :auth_customer_key, preferred_language = :pref_lang WHERE id = :id AND domain_id = :domain_id";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':role', $roleId,
				':enabled', $enabledValue,
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey,
				':pref_lang', $prefVal,
				':id', $editingId,
				':domain_id', $domainId
			);
		} else {
			$sql = "UPDATE " . TB_PREFIX . "user SET email = :email, name = :name, role_id = :role, enabled = :enabled, user_id = :user_id, auth_staff_email = :auth_staff_email, auth_customer_key = :auth_customer_key WHERE id = :id AND domain_id = :domain_id";
			$sth = dbQuery($sql,
				':email', $_POST['email'] ?? '',
				':name', $_POST['name'] ?? '',
				':role', $roleId,
				':enabled', $enabledValue,
				':user_id', (int) ($_POST['user_id'] ?? 0),
				':auth_staff_email', $authStaffEmail,
				':auth_customer_key', $authCustomerKey,
				':id', $editingId,
				':domain_id', $domainId
			);
		}
	}
	if ($saveError === null && $sth) {
		$saved = true;
		if ($prefCol && $editingId === (int) $auth_session->id) {
			$auth_session->ui_language = $prefVal === null ? '' : $prefVal;
		}
	}
}


if ($saved && ($op === 'insert_user' || $op === 'edit_user')) {
    $returnModule = preg_replace('/[^a-z0-9_]/', '', (string) ($_POST['return_module'] ?? ''));
    $returnView   = preg_replace('/[^a-z0-9_]/', '', (string) ($_POST['return_view'] ?? ''));
    $q            = 'user_saved=' . rawurlencode((string) $op);
    if ($returnModule === 'admin' && $returnView === 'domain_admin_users') {
        header('Location: index.php?module=admin&view=domain_admin_users&' . $q);
    } elseif ($returnModule === 'domain_admin' && $returnView === 'all_users') {
        header('Location: index.php?module=domain_admin&view=all_users&' . $q);
    } else {
        header('Location: index.php?module=user&view=manage&' . $q);
    }
    exit();
}

$bladeView->assign('saved', $saved);
$bladeView->assign('saveError', $saveError);

$bladeView -> assign('pageActive', 'user');
$bladeView -> assign('active_tab', '#people');
?>
