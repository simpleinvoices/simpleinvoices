<?php
/*
 * Script: login.php
 * Login page
 * License:
 * GPL v3 or above
 */
if (!function_exists('loginLogo')) {
    function loginLogo($smarty, $defaults) {
        // Not a post action so set up company logo and name to display on login screen.
        //<img src="./extensions/user_security/images/{$defaults.company_logo}" alt="User Logo">
        $image = "./extensions/user_security/images/" . $defaults['company_logo'];
        if (is_readable($image)) {
            $imgWidth = 0;
            $imgHeight = 0;
            $maxWidth = 100;
            $maxHeight = 100;
            list($width, $height, $type, $attr) = getimagesize($image);
            if (($width > $maxWidth || $height > $maxHeight)) {
                $wp = $maxWidth / $width;
                $hp = $maxHeight / $height;
                $percent = ($wp > $hp ? $hp : $wp);
                $imgWidth = ($width * $percent);
                $imgHeight = ($height * $percent);
            }
            if ($imgWidth > 0 && $imgWidth > $imgHeight) {
                $w1 = "20%";
                $w2 = "78%";
            } else {
                $w1 = "18%";
                $w2 = "80%";
            }
            $comp_logo_lines = "<div style='display:inline-block;width:$w1;'>" .
                               "  <img src='$image' alt='Company Logo' " .
                                  ($imgHeight == 0 ? "" : "height='$imgHeight' ") .
                                  ($imgWidth  == 0 ? "" : "width='$imgWidth' ") . "/>" .
                               "</div>";
            $smarty->assign('comp_logo_lines', $comp_logo_lines);
            $txt_align = "left";
        } else {
            $w2 = "100%";
            $txt_align = "center";
        }
        $comp_name_lines = "<div style='display:inline-block;width:$w2;vertical-align:middle;'>" .
                           "  <h1 style='margin-left:20px;text-align:$txt_align;'>" .
                                  $defaults['company_name_item'] . "</h1>" .
                           "</div>";

        $smarty->assign('comp_name_lines', $comp_name_lines);
    }
}

global $patchCount,
       $defaults,
       $smarty,
       $zendDb;

$menu = false;

if (!defined("BROWSE")) define("BROWSE", "browse");

// The error on any authentication attempt needs to be the same for all situations.
if (!defined("STD_LOGIN_FAILED_MSG")) define("STD_LOGIN_FAILED_MSG", "Invalid User ID and/or Password!");

Zend_Session::start();
$errorMessage = '';
loginLogo($smarty, $defaults);

if ($patchCount < "293") {
    $errorMessage = "Extension \"user_security\" requires sql patch level 293 or greater.";
} else if (empty($_POST['user']) || empty($_POST['pass'])) {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
} else {
    $authAdapter = new Zend_Auth_Adapter_DbTable($zendDb);

    // @formatter:off
    $authAdapter->setTableName(TB_PREFIX . "user")
                    ->setIdentityColumn("username")
                        ->setCredentialColumn("password")
                            ->setCredentialTreatment('MD5(?)');

    $username = $_POST['user'];
    $password = $_POST['pass'];
    // @formatter:on

    // Set the input credential values (e.g., from a login form)
    $authAdapter->setIdentity($username)->setCredential($password);

    // Perform the authentication query, saving the result
    $result = $authAdapter->authenticate();
    if ($result->isValid()) {
        Zend_Session::start();

        // Chuck the user details sans password into the Zend_auth session
        $authNamespace = new Zend_Session_Namespace('Zend_Auth');
        // @formatter:off
        $timeout = 0;
        $session_timeout = $zendDb->fetchOne("SELECT value FROM ". TB_PREFIX."system_defaults
                                              WHERE name='session_timeout'");
        $timeout = intval($session_timeout);
        // @formatter:on

        if ($timeout <= 0) {
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

        if (isset($authNamespace->role_name) && $authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
            header('Location: index.php?module=customers&view=details&action=view&id=' . $authNamespace->user_id);
        } else {
            header('Location: .');
        }
    } else {
        $errorMessage = STD_LOGIN_FAILED_MSG;
    }
}
// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage", $errorMessage);
