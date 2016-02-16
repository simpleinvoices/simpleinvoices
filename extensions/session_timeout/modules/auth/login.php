<?php
/*
 * Script: login.php
 * Login page
 *
 * License:
 * GPL v3 or above
 */
$menu = false;

// we must never forget to start the session

// so config.php works ok without using index.php define browse
if (!defined("BROWSE")) define("BROWSE", "browse");

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");

Zend_Session::start();

$errorMessage = '';
if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    // configure the instance with setter methods
    $authAdapter = new Zend_Auth_Adapter_DbTable($zendDb);
    
    // Set feature based on patch level.
    // @formatter:off
    $user_table    = ($patchCount < "161") ? "users"         : "user";
    $user_email    = ($patchCount < "184") ? "user_email"    : "email";
    $user_password = ($patchCount < "184") ? "user_password" : "password";
    
    $authAdapter->setTableName(TB_PREFIX.$user_table)
                    ->setIdentityColumn($user_email)
                        ->setCredentialColumn($user_password)
                            ->setCredentialTreatment('MD5(?)');
    
    $userEmail = $_POST['user'];
    $password  = $_POST['pass'];
    // @formatter:on
    
    // Set the input credential values (e.g., from a login form)
    $authAdapter->setIdentity($userEmail)
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
        
        // patch 147 adds user_role table - need to accomodate pre and post patch 147
        if ($patchCount < "147") {
            // @formatter:off
            $result = $zendDb->fetchRow("SELECT
                        				     u.user_id AS id,
                                             u.user_email,
                                             u.user_name
                        				 FROM ".TB_PREFIX."users u
                        				 WHERE user_email = ?", $userEmail
                                       );
            // @formatter:on
            $result['role_name'] = "administrator";
        } elseif ($patchCount < "184") {
            // @formatter:off
            $result = $zendDb->fetchRow("SELECT
                                             u.user_id AS id,
                                             u.user_email,
                                             u.user_name,
                                             r.name AS role_name,
                                             u.user_domain_id
                                         FROM ".TB_PREFIX."user u
    				                     LEFT JOIN ".TB_PREFIX."user_role r ON (u.user_role_id = r.id)
                                         WHERE u.user_email = ?", $userEmail
                                       );
            // @formatter:on
        } elseif ($patchCount < "292") {
            // @formatter:off
            $result = $zendDb->fetchRow("SELECT
                                             u.id,
                                             u.email,
                                             r.name AS role_name,
                                             u.domain_id,
                                             0 AS user_id
                                         FROM ".TB_PREFIX."user u
                                         LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
                                         WHERE u.email = ? AND u.enabled = '".ENABLED."'", $userEmail
                                       );
            // @formatter:on
        } else {
            // Customer / Biller User ID available on and after Patch 292
            // @formatter:off
            $result = $zendDb->fetchRow("SELECT
                                             u.id,
                                             u.email,
                                             r.name AS role_name,
                                             u.domain_id,
                                             u.user_id
                                         FROM ".TB_PREFIX."user u
                                         LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
                                         WHERE u.email = ? AND u.enabled = '".ENABLED."'", $userEmail
                                       );
            // @formatter:on
        }
        
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

if (isset($_POST['action']) && $_POST['action'] == 'login' && (empty($_POST['user']) or empty($_POST['pass']))) {
    $errorMessage = STD_LOGIN_FAILED_MSG;
}

// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage", $errorMessage);
