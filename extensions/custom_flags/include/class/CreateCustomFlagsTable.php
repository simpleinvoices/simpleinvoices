<?php
class CreateCustomFlagsTable {

    public static function createTable() {
        if (checkTableExists(TB_PREFIX . 'custom_flags') == false) {
            $domain_id = domain_id::get();
            // @formatter:off
            $sql = "CREATE TABLE `" .
                        TB_PREFIX . "custom_flags` (
                              `domain_id`        int(11)             NOT NULL,
                              `associated_table` char(10)            NOT NULL COMMENT 'Table flag is associated with. Only defined for products for now.',
                              `flg_id`           tinyint(3) unsigned NOT NULL COMMENT 'Flag number ranging from 1 to 10',
                              `field_label`      varchar(20)         NOT NULL COMMENT 'Label to use on screen where option is set.',
                              `enabled`          tinyint(1)          NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
                              `field_help`       varchar(255)        NOT NULL COMMENT 'Help information to display for this field.',
                    PRIMARY KEY `uid`       (`domain_id`, `associated_table`, `flg_id`),
                            KEY `domain_id` (`domain_id`),
                            KEY `dtable`    (`domain_id`, `associated_table`)
              ) ENGINE=InnoDB COMMENT='Specifies an allowed setting for a flag field';";
            // @formatter:on
            if (!($sth = dbQuery($sql))) {
                $arr = $sth->errorInfo();
                error_log('CreateCustomFlagsTable - createTable(): Failed to create table. sql[' . $sql . ']');
                error_log('                                      : Error array[' . print_r($arr, true));
            } else {
                // @formatter:off
                $sql = "ALTER TABLE " .
                            TB_PREFIX . "products 
                        ADD `custom_flags` CHAR( 10 ) NOT NULL COMMENT 'User defined flags'";
                // @formatter:on
                if (!($sth = dbQuery($sql))) {
                    $arr = $sth->errorInfo();
                    // @formatter:off
                    error_log('CreateCustomFlagsTable - createTable(): Failed to add field to products table. sql[' . $sql . ']');
                    error_log('                                      : Error array[' . print_r($arr, true));
                    // @formatter:on
                } else {
                    for ($i = 1; $i <= 10; $i++) {
                        // @formatter:off
                        $sql = "INSERT INTO " .
                                    TB_PREFIX . "custom_flags 
                                       ( domain_id, associated_table, flg_id, enabled)
                                VALUES (:domain_id,'products',        $i,     0);";
                        // @formatter:off
                        if (!($sth = dbQuery($sql, ':domain_id', $domain_id))) {
                            $arr = $sth->errorInfo();
                            // @formatter:off
                            error_log('CreateCustomFlagsTable - createTable(): Failed to add flag ' . $i . '. sql[' . $sql . ']');
                            error_log('                                      : Error array[' . print_r($arr, true) . ']');
                            // @formatter:off
                            break;
                        }
                    }
                }
            }
        }
    }
}
