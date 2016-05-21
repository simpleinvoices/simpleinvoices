<?php
/**
 * UserSecurity class
 * @author Rich
 *
 */
class UserSecurity {

    /**
     * Static function to add the session_timeout column to the system_defaults
     * table if it is not present.
     */
    public static function addSessionTimeout() {
        $sql = "SELECT name FROM `" . TB_PREFIX . "system_defaults` WHERE `name`='session_timeout';";
        $sth = dbQuery($sql);
        $names = $sth->fetchAll(PDO::FETCH_COLUMN, 'name');
        if (empty($names)) {
            $domain_id = domain_id::get();
            $ext_id = getExtensionID('user_security');
            $sql = "INSERT INTO `" . TB_PREFIX . "system_defaults`
                     (`domain_id`,`name`           ,`value`,`extension_id`)
              VALUES ($domain_id, 'user_security', 15    , $ext_id);";
            if (!($sth = dbQuery($sql))) {
                $arr = $sth->errorInfo();
                error_log('UserSecurity - addSessionTimeout(): Failed to insert new record. sql[' . $sql . ']');
            }
        }
    }

    /**
     * Static function to add the username column to the user table if it is not present.
     */
    public static function addUsername() {
        if (checkFieldExists(TB_PREFIX . "user", "username")) return;
        $sql = "ALTER TABLE `" . TB_PREFIX . "user` ADD username VARCHAR(255) DEFAULT ''";
        if (!($sth = dbQuery($sql))) {
            $arr = $sth->errorInfo();
            error_log('UserSecurity - addUsername(): Failed to add username field. sql[' . $sql . ']');
        } else {
            $sql = "SELECT * FROM `" . TB_PREFIX . "user`;";
            $sth = dbQuery($sql);
            while ($user = $sth->fetch(PDO::FETCH_ASSOC)) {
                $sql = "UPDATE `" . TB_PREFIX . "user` SET `username`='" . $user['email'] . "' WHERE `id`=" . $user['id'] . ";";
                if (!($tth = dbQuery($sql))) {
                    $arr = $tth->errorInfo();
                    error_log('UserSecurity - addUsername(): Unable to update username for ' . $user['email']);
                    break;
                }
            }
        }
    }
}
