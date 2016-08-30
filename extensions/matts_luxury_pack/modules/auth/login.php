<?php
/*
* Script: add.php
* 	Customers add page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-29
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
global $zendDb, $patchCount, $smarty;

$menu = false;
if ($menu) {} // eliminate unused variable warning.

if (!defined("BROWSE")) define("BROWSE", "browse");

Zend_Session::start();

$errorMessage = '';
if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    $authAdapter = new Zend_Auth_Adapter_DbTable($zendDb);

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

    // Set the input credential values (e.g., from a login form)
    $authAdapter->setIdentity($userEmail)->setCredential($password);

    // Perform the authentication query, saving the result
    $result = $authAdapter->authenticate();
/* debug */ //error_log("moudles/auth/login.php: patchCount[$patchCount] result - " . print_r($result,true));
    if ($result->isValid()) {
        Zend_Session::start();

        // Grab user data from the database
        //patch 147 adds user_role table - need to accomodate pre and post patch 147
        // @formatter:off
        if ($patchCount < "147") {
            $result = $zendDb->fetchRow("SELECT	u.user_id AS id,
                                                u.user_email,
                                                u.user_name
                                         FROM " . TB_PREFIX . "users u
                                         WHERE user_email = ?", $userEmail);
             $result['role_name']="administrator";
        } elseif ($patchCount < "184") {
            $result = $zendDb->fetchRow("SELECT u.user_id AS id,
                                                u.user_email,
                                                u.user_name,
                                                r.name AS role_name,
                                                u.user_domain_id
                                         FROM " . TB_PREFIX . "user u
                                         LEFT JOIN ".TB_PREFIX."user_role r ON (u.user_role_id = r.id)
                                         WHERE u.user_email = ?", $userEmail);
        } elseif ($patchCount < "292") {
            $result = $zendDb->fetchRow("SELECT u.id, u.email,
                                                r.name AS role_name,
                                                u.domain_id,
                                                0 AS user_id
                                         FROM " . TB_PREFIX . "user u
                                         LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
                                         WHERE u.email = ? AND u.enabled = '" . ENABLED . "'", $userEmail);
        } else {
            $result = $zendDb->fetchRow("SELECT u.id,
                                                u.email,
                                                r.name AS role_name,
                                                u.domain_id,
                                                u.user_id
                                         FROM " . TB_PREFIX . "user u
                                         LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
                                         WHERE  u.email = ? AND u.enabled = '" . ENABLED . "'", $userEmail);
        }
        // @formatter:on

        // Chuck the user details sans password into the Zend_auth session
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        $authNamespace->setExpirationSeconds(60 * 60);
        foreach ($result as $key => $value) {
            $authNamespace->$key = $value;
        }

        if ($authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
            header('Location: index.php?module=customers&view=details&action=view&id='.$authNamespace->user_id);
        } else {
            header('Location: .');
        }
    } else {
        $errorMessage = 'Sorry, wrong user / password';
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'login' &&
   (empty($_POST['user']) || empty($_POST['pass']))) {
        $errorMessage = 'Username and password required';
}

// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage",$errorMessage);
$smarty->assign("version_name",$config->version->name);//Matt
