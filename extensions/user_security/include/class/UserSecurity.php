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
    public static function addSystemDefaultFields() {
        global $LANG, $dbh;

        $fld = "character_maximum_length";
        $sql = "SELECT `$fld` FROM `information_schema`.`columns`
                WHERE `table_name`='" . TB_PREFIX . "system_defaults' AND `column_name`='value';";
        if (($sth = $dbh->query($sql)) === false) {
            // Non-critical error so continue with next action.
            error_log("UserSecurity - addSystemDefaultFields(): Unable to perform request: $sql");
        } else {
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if (intval($row[$fld]) < 60) {
                $sql = "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY COLUMN `value` varchar(60);";
                if (($sth = $dbh->query($sql)) === false) {
                    // Non-critical error so continue with next action.
                    error_log("UserSecurity - addSystemDefaultFields() - Unable to perform request: $sql");
                }
            }
        }

        if (empty($LANG['company_name_item'])) $LANG['company_name_item'] = 'Company Name';
        // @formatters:off
        $flds = array('company_logo',
                      'company_name_item',
                      'password_min_length',
                      'password_lower',
                      'password_number',
                      'password_special',
                      'password_upper',
                      'session_timeout');
        $sql = "SELECT `name` FROM `" . TB_PREFIX . "system_defaults`
                WHERE `name` IN ('" . implode("', '", $flds) . "');";
        // @formatter:on
        if (($sth = $dbh->query($sql)) === false) {
            error_log("UserSecurity - addSystemdefaultFields(): Unable to perform request: $sql");
            return false;
        }

        $ok = true;
        $names = $sth->fetchAll(PDO::FETCH_COLUMN, 'name');
        if (count($names) != count($flds)) {
            $domain_id = domain_id::get();
            $ext_id = getExtensionID('user_security');
            $conam = $LANG['company_name'];
            $cologo = 'simple_invoices_logo.png';
            $dbh->beginTransaction();
            foreach ($flds as $fld) {
                if (array_search($fld,$names) === false) {
                    // @formatter:off
                    $sql = "INSERT INTO `" . TB_PREFIX . "system_defaults`
                                             (`domain_id` , `name`                , `value`  ,`extension_id`)";
                    switch ($fld) {
                        case 'company_logo':
                            $sql .= " VALUES ('$domain_id', 'company_logo'        , '$cologo', $ext_id);";
                            break;

                        case 'company_name_item':
                            $sql .= " VALUES ('$domain_id', 'company_name_item'   , '$conam' , $ext_id);";
                            break;

                        case 'password_min_length':
                            $sql .= " VALUES ('$domain_id', 'password_min_length' , 8        , $ext_id);";
                            break;

                        case 'password_lower':
                            $sql .= " VALUES ('$domain_id', 'password_lower'      , 1        , $ext_id);";
                            break;

                        case 'password_number':
                            $sql .= " VALUES ('$domain_id', 'password_number'     , 1        , $ext_id);";
                            break;

                        case 'password_special':
                            $sql .= " VALUES ('$domain_id', 'password_special'    , 1        , $ext_id);";
                            break;

                        case 'password_upper':
                            $sql .= " VALUES ('$domain_id', 'password_upper'      , 1        , $ext_id);";
                            break;

                        case 'session_timeout':
                            $sql .= " VALUES ('$domain_id', 'session_timeout'     , 15       , $ext_id);";
                            break;

                        default:
                            $ok = false;
                            error_log("UserSecurity - addSystemDefaultFields(): Invalid field name, $fld.");
                            break;
                    }
                    // @formatter:on
                    if ($ok) {
                        if (($dbh->exec($sql)) === false) {
                            $ok = false;
                            error_log("UserSecurity - addSystemDefaultFields(): Failed to insert new record. sql[$sql]");
                            break;
                        }
                    }
                }
            }

            // @formatter:off
            if ($ok) $dbh->commit();
            else     $dbh->rollback();
            // @formatter:on
        }

        if ($ok) {
            $sql = "SELECT `value` FROM `" . TB_PREFIX . "system_defaults` WHERE `name`='company_name_item';";
            if ($sth = $dbh->query($sql)) {
                $row = $sth->fetch(PDO::FETCH_ASSOC);
                $LANG['company_name'] = $row['value'];
            } else {
                $ok = false;
                error_log("UserSecurity - addSessionTimeout(): Failed to retrieve company name. sql[$sql]");
            }
        }
        return $ok;
    }

    /**
     * Static function to add the username column to the user table if it is not present.
     * @return <b>true</b> if table changes successfully made; otherwise <b>false</b>.
     */
    public static function addUserName() {
        global $dbh;

        if (checkFieldExists(TB_PREFIX . "user", "username")) return true;
        $sqls = array();
        $sqls[] = "ALTER TABLE `" . TB_PREFIX . "user` ADD `username` VARCHAR(255) DEFAULT '' AFTER `id`;";
        $sqls[] = "ALTER TABLE `" . TB_PREFIX . "user` DROP INDEX `UnqEMailPwd`;";
        $dbh->beginTransaction();
        $ok = true;
        foreach($sqls as $sql) {
            if ($dbh->exec($sql) === false) {
                $ok = false;
                error_log("UserSecurity - addUserName(): Unable to perform request: sql[$sql]");
                break;
            }
        }

        if ($ok) {
            $sql = "SELECT * FROM `" . TB_PREFIX . "user`;";
            if (($sth = $dbh->query($sql)) === false) {
                $ok = false;
                error_log('UserSecurity - addUserName(): Unable to read users for update');
            } else {
                $unames = array();
                while ($user = $sth->fetch(PDO::FETCH_ASSOC)) {
                    if (array_search($user['email'], $unames)) {
                        $ok = false;
                        error_log("UserSecurity - addUserName(): Non-unique email, " . $user['email'] . ".");
                        break;
                    }
                    $unames[] = $user['email'];

                    $sql = "UPDATE `" . TB_PREFIX . "user` SET `username`='" . $user['email'] . "' WHERE `id`=" . $user['id'] . ";";
                    if ($dbh->exec($sql) === false) {
                        $ok = false;
                        error_log("UserSecurity - addUserName(): Unable to update username for " . $user['email'] . ". sql[$sql]");
                        break;
                    }
                }
                if ($ok) {
                    $sql = "ALTER TABLE `" . TB_PREFIX . "user` ADD UNIQUE INDEX `uname` (`username`);";
                    if ($dbh->exec($sql) === false) {
                        $ok = false;
                        error_log("UserSecurity - addUserName(): Unable to add unique index. sql[$sql]");
                    }
                }
            }
        }

        if ($ok) {
            $dbh->commit();
            return true;
        }

        $dbh->rollback();
        return false;
    }

    /**
     * Build the pattern for the specified password constrants.
     * @return string Password pattern.
     */
    public static function buildPwdPattern() {
        global $smarty;
        $defaults = $smarty->_tpl_vars['defaults'];
        //(?=^.{8,}$)(?=^[a-zA-Z])(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*\W)(?![.\n]).*$
        $pwd_pattern = "(?=^.{" . $defaults['password_min_length'] . ",}$)(?=^[a-zA-Z])";

        if ($defaults['password_upper'] == 1) {
            $pwd_pattern .= "(?=.*[A-Z])";
        }

        if ($defaults['password_lower'] > 0) {
            $pwd_pattern .= "(?=.*[a-z])";
        }

        if ($defaults['password_number'] > 0) {
            $pwd_pattern .= "(?=.*\d)";
        }

        if ($defaults['password_special'] > 0) {
            $pwd_pattern .= "(?=.*\W)";
        }
        $pwd_pattern .= "(?![.\\n]).*$";

        return $pwd_pattern;
    }

}
