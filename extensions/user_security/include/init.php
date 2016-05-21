<?php
if ($patchCount >= "294") {
    include_once './extensions/user_security/include/class/UserSecurity.php';
    UserSecurity::addUsername();
    UserSecurity::addSessionTimeout();

    // @formatter:off
    $session_timeout = $zendDb->fetchRow("SELECT value
                                          FROM ". TB_PREFIX."system_defaults
                                          WHERE name='session_timeout'");
    // @formatter:on
    $timeout = intval($session_timeout['value']);
    if ($timeout <= 0) {
      error_log("Extension - session_timeout - invalid timeout value[$timeout]");
      $timeout = 60;
    }

    // Chuck the user details sans password into the Zend_auth session
    $authNamespace = new Zend_Session_Namespace('Zend_Auth');
    $authNamespace->setExpirationSeconds($timeout * 60);
}
