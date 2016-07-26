<?php
global $patchCount, $help_image_path, $zendDb;

if ($patchCount >= "293") {
    include_once './extensions/user_security/include/sql_queries.php';
    include_once './extensions/user_security/include/class/UserSecurity.php';
    UserSecurity::addUserName();
    UserSecurity::addSystemDefaultFields();

    // @formatter:off
    $session_timeout = $zendDb->fetchRow("SELECT value
                                          FROM ". TB_PREFIX."system_defaults
                                          WHERE name='session_timeout'");
    // @formatter:on
    $timeout = intval($session_timeout['value']);
    if ($timeout <= 0) {
      $timeout = 60;
    }

    // Chuck the user details sans password into the Zend_auth session
    $authNamespace = new Zend_Session_Namespace('Zend_Auth');
    $authNamespace->setExpirationSeconds($timeout * 60);

    $help_image_path = "./extensions/user_security/images/common/";
    if ($help_image_path) {} // Remove unused variable warning.
}
