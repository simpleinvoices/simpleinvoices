<?php
/**
 * SessionTimeout class
 * @author Rich
 *
 */
class SessionTimeout {
  /**
   * Static function to add the session_timeout column to the system_defaults
   * table if it is not present.
   */
  public static function addSessionTimeout() {
    $sql = "SELECT name FROM `".TB_PREFIX."system_defaults` WHERE `name`='session_timeout';";
    $sth = dbQuery($sql);
    $names = $sth->fetchAll(PDO::FETCH_COLUMN, 'name');
    if (empty($names)) {
      $domain_id = domain_id::get ();
      $ext_id = getExtensionID('session_timeout');
      $sql = "INSERT INTO `" . TB_PREFIX . "system_defaults`
                     (`domain_id`,`name`           ,`value`,`extension_id`)
              VALUES ($domain_id, 'session_timeout', 15    , $ext_id);";
      if (!($sth = dbQuery ($sql))) {
        $arr = $sth->errorInfo();
        error_log('SessionTimeout - addSessionTimeout(): Failed to insert new record. sql['.$sql.']');
      }
    }
  }
}
