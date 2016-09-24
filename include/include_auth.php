<?php
global $config, $auth_session;

// if user logged into SimpleInvoices with authentication set to false,
// then use the fake authentication, killing the session that was started.
if (($config->authentication->enabled == 1) && ($auth_session->fake_auth == "1")) {
    Zend_Session::start();
    Zend_Session::destroy(true);
    header('Location: .');
}

// 1 = config->auth->enabled == "true"
if ($config->authentication->enabled == 1) {
    // TODO - this needs to be fixed !!
    if ($auth_session->domain_id == null) {
        $auth_session->domain_id = "1";
    }
    global $ext_names;
    $done = false;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/include/auth/auth.php")) {
            require_once ("./extensions/$ext_name/include/auth/auth.php");
            $done = true;
            break;
        }
    }
    if (!$done)     include ('./include/auth/auth.php');
} else {
    // If auth not on - use default domain and user id of 1
    // Chuck the user details sans password into the Zend_auth session
    $auth_session->id = "1";
    $auth_session->domain_id = "1";
    $auth_session->email = "demo@simpleinvoices.org";
    // fake_auth is identifier to say that user logged in with auth off
    $auth_session->fake_auth = "1";
    // No Customer login as logins disabled
    $auth_session->user_id = "0";
}
