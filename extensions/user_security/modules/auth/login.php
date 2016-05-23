<?php
/*
 * Script: login.php
 * Login page
 * License:
 * GPL v3 or above
 */
$menu = false;

if (!defined("BROWSE")) define("BROWSE", "browse");

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");

Zend_Session::start();

$errorMessage = '';
if ($patchCount >= "294" && !empty($_POST['user']) && !empty($_POST['pass'])) {
    $authAdapter = new Zend_Auth_Adapter_DbTable($zendDb);

    // @formatter:off
    $user_table    = "user";
    $user_username = "username";
    $user_password = "password";

    $authAdapter->setTableName(TB_PREFIX.$user_table)
                    ->setIdentityColumn($user_username)
                        ->setCredentialColumn($user_password)
                            ->setCredentialTreatment('MD5(?)');

    $username = $_POST['user'];
    $password  = $_POST['pass'];
    // @formatter:on

    // Set the input credential values (e.g., from a login form)
    $authAdapter->setIdentity($username)
        ->setCredential($password);

    // Perform the authentication query, saving the result
    $result = $authAdapter->authenticate();

    if ($result->isValid()) {
        Zend_Session::start();

        // Chuck the user details sans password into the Zend_auth session
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        // @formatter:off
        $session_timeout = $zendDb->fetchRow("SELECT value FROM ". TB_PREFIX."system_defaults
                                              WHERE name='session_timeout'");
        // @formatter:on
        $timeout = intval($session_timeout['value']);
        if ($timeout <= 0) {
            error_log("Extension - system Defaults - invalid timeout value[$timeout]");
            $timeout = 60;
        }
        $authNamespace->setExpirationSeconds($timeout * 60);

        // Customer / Biller User ID available on and after Patch 292
        // @formatter:off
        $result = $zendDb->fetchRow("SELECT
                                         u.id,
                                         u.email,
                                         r.name AS role_name,
                                         u.domain_id,
                                         u.user_id,
                                         u.username
                                     FROM ".TB_PREFIX."user u
                                     LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
                                     WHERE u.username = ? AND u.enabled = '".ENABLED."'", $username
                                   );
        // @formatter:on

        foreach ($result as $key => $value) {
            $authNamespace->$key = $value;
        }

        if ($authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
            header('Location: index.php?module=customers&view=details&action=view&id=' . $authNamespace->user_id);
        } else {
            header('Location: .');
        }
    } else {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
}

if ($patchCount < '294') {
    $errorMessage = "Extension \"user_security\" requires sql patch level 294 or greater.";
} else if (isset($_POST['action']) && $_POST['action'] == 'login' && (empty($_POST['user']) or empty($_POST['pass']))) {
    $errorMessage = STD_LOGIN_FAILED_MSG;
}
// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage", $errorMessage);
