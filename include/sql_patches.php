<?php
if (!function_exists("patchmaker")) {
    function patchmaker($num, $patchlines, &$patches) {
        static $last = -1;
        if ($num == 0 && $patchlines['name'] == "Start") {
            $last = -1;
        }
        $last++;
        if ($last != $num) {
            throw new Exception("patchmaker - Patch #$num is out of sequence.");
        }
        $patches[$num] = array(
            'name' => $patchlines['name' ],
            'patch' => $patchlines['patch'],
            'date' => $patchlines['date' ]
        );
    }
}

global $config,
       $language;

$defaults = null;
$numpatchesdone = getNumberOfDonePatches();
if ($numpatchesdone < 124) {
    // System defaults conversion patch. Defaults query and DEFAULT NUMBER OF LINE ITEMS
    $sql_defaults = "SELECT * FROM ".TB_PREFIX."defaults";
    $sth = dbQuery($sql_defaults);
    $defaults = $sth->fetch();
}

$si_patches = array();
$patchlines = array();

$patchlines = array(
    'name'  => "Start",
    'patch' => "SHOW TABLES LIKE 'test'",
    'date'  => "20060514"
);
patchmaker('0', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create sql_patchmanager table",
    'patch' => "CREATE TABLE ".TB_PREFIX."sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                            sql_patch_ref VARCHAR( 50 ) NOT NULL,
                                                            sql_patch VARCHAR( 255 ) NOT NULL ,
                                                            sql_release VARCHAR( 25 ) NOT NULL,
                                                            sql_statement TEXT NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
    'date'  => "20060514"
);
patchmaker('1', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Update invoice no details to have a default currency sign",
    'patch' => "UPDATE ".TB_PREFIX."preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1",
    'date'  => "20060514"
);
patchmaker('2', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add a row into the defaults table to handle the default number of line items",
    'patch' => "ALTER TABLE ".TB_PREFIX."defaults ADD def_number_line_items INT( 25 ) NOT NULL",
    'date'  => "20060514"
);
patchmaker('3', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set the default number of line items to 5",
    'patch' => "UPDATE ".TB_PREFIX."defaults SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1",
    'date'  => "20060514"
);
patchmaker('4', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add logo and invoice footer support to biller",
    'patch' => "ALTER TABLE ".TB_PREFIX."biller ADD b_co_logo VARCHAR( 50 ), ADD b_co_footer TEXT",
    'date'  => "20060514"
);
patchmaker('5', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add default invoice template option",
    'patch' => "ALTER TABLE ".TB_PREFIX."defaults ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL",
    'date'  => "20060514"
);
patchmaker('6', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Edit tax description field length to 50",
    'patch' => "ALTER TABLE ".TB_PREFIX."tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL",
    'date'  => "20060526"
);
patchmaker('7', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Edit default invoice template field length to 50",
    'patch' => "ALTER TABLE ".TB_PREFIX."defaults CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL",
    'date'  => "20060526"
);
patchmaker('8', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add consulting style invoice",
    'patch' => "INSERT INTO ".TB_PREFIX."invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')",
    'date'  => "20060531"
);
patchmaker('9', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add enabled to biller",
    'patch' => "ALTER TABLE ".TB_PREFIX."biller ADD b_enabled varchar(1) NOT NULL default '1'",
    'date'  => "20060815"
);
patchmaker('10', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add enabled to customers",
    'patch' => "ALTER TABLE ".TB_PREFIX."customers ADD c_enabled varchar(1) NOT NULL default '1'",
    'date'  => "20060815"
);
patchmaker('11', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add enabled to preferences",
    'patch' => "ALTER TABLE ".TB_PREFIX."preferences ADD pref_enabled varchar(1) NOT NULL default '1'",
    'date'  => "20060815"
);
patchmaker('12', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add enabled to products",
    'patch' => "ALTER TABLE ".TB_PREFIX."products ADD prod_enabled varchar(1) NOT NULL default '1'",
    'date'  => "20060815"
);
patchmaker('13', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add enabled to products",
    'patch' => "ALTER TABLE ".TB_PREFIX."tax ADD tax_enabled varchar(1) NOT NULL default '1'",
    'date'  => "20060815"
);
patchmaker('14', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add tax_id into invoice_items table",
    'patch' => "ALTER TABLE ".TB_PREFIX."invoice_items ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0'  AFTER inv_it_unit_price",
    'date'  => "20060815"
);
patchmaker('15', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add Payments table",
    'patch' => "CREATE TABLE `".TB_PREFIX."account_payments` (`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                              `ac_inv_id` VARCHAR( 10 ) NOT NULL ,
                                                              `ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
                                                              `ac_notes` TEXT NOT NULL ,
                                                              `ac_date` DATETIME NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20060827"
);
patchmaker('16', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adjust data type of quantity field",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'",
    'date'  => "20060827"
);
patchmaker('17', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create Payment Types table",
    'patch' => "CREATE TABLE `".TB_PREFIX."payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
    'date'  => "20060909"
);
patchmaker('18', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add info into the Payment Type table",
    'patch' => "INSERT INTO `".TB_PREFIX."payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')",
    'date'  => "20060909"
);
patchmaker('19', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adjust accounts payments table to add a type field",
    'patch' => "ALTER TABLE `".TB_PREFIX."account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'",
    'date'  => "20060909"
);
patchmaker('20', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adjust the defautls table to add a payment type field",
    'patch' => "ALTER TABLE `".TB_PREFIX."defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'",
    'date'  => "20060909"
);
patchmaker('21', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add note field to customer",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` ADD `c_notes` TEXT NULL AFTER `c_email`",
    'date'  => "20061026"
);
patchmaker('22', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add note field to Biller",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`",
    'date'  => "20061026"
);
patchmaker('23', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add note field to Products",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`",
    'date'  => "20061026"
);
patchmaker('24', $patchlines, $si_patches);

/*Custom fields patches - start */
$patchlines = array(
    'name'  => "Add street address 2 to customers",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ",
    'date'  => "20061211"
);
patchmaker('25', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add custom fields to customers",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
                                                     ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
                                                     ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
                                                     ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;",
    'date'  => "20061211"
);
patchmaker('26', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add mobile phone to customers",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`",
    'date'  => "20061211"
);
patchmaker('27', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add street address 2 to billers",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ",
    'date'  => "20061211"
);
patchmaker('28', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add custom fields to billers",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
                                                  ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
                                                  ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
                                                  ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;",
    'date'  => "20061211"
);
patchmaker('29', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Creating the custom fields table",
    'patch' =>"CREATE TABLE `".TB_PREFIX."custom_fields` (`cf_id` INT NOT NULL AUTO_INCREMENT ,
                                                                   `cf_custom_field` VARCHAR( 50 ) NOT NULL ,
                                                                   `cf_custom_label` VARCHAR( 50 ) ,
                                                                   `cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
                        PRIMARY KEY(`cf_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20061211"
);
patchmaker('30', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adding data to the custom fields table",
    'patch' => "INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
                VALUES (NULL,'biller_cf1'  ,NULL,'0'),(NULL,'biller_cf2'  ,NULL,'0'),(NULL,'biller_cf3'  ,NULL,'0'),(NULL,'biller_cf4'  ,NULL,'0'),
                       (NULL,'customer_cf1',NULL,'0'),(NULL,'customer_cf2',NULL,'0'),(NULL,'customer_cf3',NULL,'0'),(NULL,'customer_cf4',NULL,'0'),
                       (NULL,'product_cf1' ,NULL,'0'),(NULL,'product_cf2' ,NULL,'0'),(NULL,'product_cf3' ,NULL,'0'),(NULL,'product_cf4' ,NULL,'0');",
    'date'  => "20061211"
);
patchmaker('31', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adding custom fields to products",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price`,
                                                    ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1`,
                                                    ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2`,
                                                    ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3`;",
    'date'  => "20061211"
);
patchmaker('32', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter product custom field 4",
    'patch' => "UPDATE `".TB_PREFIX."custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `".TB_PREFIX."custom_fields`.`cf_id` =12 LIMIT 1 ;",
    'date'  => "20061214"
);
patchmaker('33', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Reset invoice template to default refer Issue 70",
    'patch' => "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;",
    'date'  => "20070125"
);
patchmaker('34', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adding data to the custom fields table for invoices",
    'patch' => "INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
                VALUES (NULL,'invoice_cf1',NULL,'0'),(NULL,'invoice_cf2',NULL,'0'),(NULL,'invoice_cf3',NULL,'0'),(NULL,'invoice_cf4',NULL,'0');",
    'date'  => "20070204"
);
patchmaker('35', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Adding custom fields to the invoices table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date` ,
                                                    ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1` ,
                                                    ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2` ,
                                                    ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3` ;",
    'date'  => "20070204"
);
patchmaker('36', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Reset invoice template to default due to new invoice template system",
    'patch' => "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;",
    'date'  => "20070523"
);
patchmaker('37', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter custom field table - field length now 255 for field name",
    'patch' => "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )",
    'date'  => "20070523"
);
patchmaker('38', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter custom field table - field length now 255 for field label",
    'patch' => "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )",
    'date'  => "20070523"
);
patchmaker('39', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name in sql_patchmanager",
    'patch' => "ALTER TABLE `".TB_PREFIX."sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL",
    'date'  => "20070523"
);
patchmaker('40', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name in ".TB_PREFIX."account_payments",
    'patch' => "ALTER TABLE `".TB_PREFIX."account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('41', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_name to name",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;",
    'date'  => "20070523"
);
patchmaker('42', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_id to id",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('43', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_street_address to street_address",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('44', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_street_address2 to street_address2",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('45', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_city to city",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('46', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_state to state",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('47', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_zip_code to zip_code",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('48', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_country to country",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('49', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_phone to phone",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('50', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_mobile_phone to mobile_phone",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('51', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_fax to fax",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('52', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_email to email",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('53', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_co_logo to logo",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('54', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_co_footer to footer",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ",
    'date'  => "20070523"
);
patchmaker('55', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_notes to notes",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ",
    'date'  => "20070523"
);
patchmaker('56', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_enabled to enabled",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
    'date'  => "20070523"
);
patchmaker('57', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_custom_field1 to custom_field1",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('58', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_custom_field2 to custom_field2",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('59', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_custom_field3 to custom_field3",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('60', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name b_custom_field4 to custom_field4",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('61', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Introduce system_defaults table",
    'patch' => "CREATE TABLE `".TB_PREFIX."system_defaults` (`id` int(11) NOT NULL auto_increment,
                                                             `name` varchar(30) NOT NULL,
                                                             `value` varchar(30) NOT NULL,
                                                             PRIMARY KEY  (`id`),
                                                             UNIQUE KEY `name` (`name`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
    'date'  => "20070523"
);
patchmaker('62', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Inserts data into the system_defaults table",
    'patch' => "INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`)
                VALUES
                  ( 1, 'biller'         , '4'),
                  ( 2, 'customer'       , '3'),
                  ( 3, 'tax'            , '1'),
                  ( 4, 'preference'     , '1'),
                  ( 5, 'line_items'     , '5'),
                  ( 6, 'template'       , 'default'),
                  ( 7, 'payment_type'   , '1'),
                  ( 8, 'language'       , 'en'),
                  ( 9, 'dateformat'     , 'Y-m-d'),
                  (10, 'spreadsheet'    , 'xls'),
                  (11, 'wordprocessor'  , 'doc'),
                  (12, 'pdfscreensize'  , '800'),
                  (13, 'pdfpapersize'   , 'A4'),
                  (14, 'pdfleftmargin'  , '15'),
                  (15, 'pdfrightmargin' , '15'),
                  (16, 'pdftopmargin'   , '15'),
                  (17, 'pdfbottommargin', '15'),
                  (18, 'emailhost'      , 'localhost'),
                  (19, 'emailusername'  , ''),
                  (20, 'emailpassword'  , '');",
    'date'  => "20070523"
);
patchmaker('63', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_id to id",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('64', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_description to description",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_description` `description` TEXT NOT NULL ",
    'date'  => "20070523"
);
patchmaker('65', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_unit_price to unit_price",
    'patch' => " ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('66', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_custom_field1 to custom_field1",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('67', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_custom_field2 to custom_field2",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('68', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_custom_field3 to custom_field3",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('69', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_custom_field4 to custom_field4",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('70', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_notes to notes",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_notes` `notes` TEXT NOT NULL",
    'date'  => "20070523"
);
patchmaker('71', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name prod_enabled to enabled",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
    'date'  => "20070523"
);
patchmaker('72', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_id to id",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('73', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_attention to attention",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('74', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_name to name",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('75', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_street_address to street_address",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('76', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_street_address2 to street_address2",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('77', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_city to city",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('78', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_state to state",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('79', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_zip_code to zip_code",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('80', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_country to country",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('81', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_phone to phone",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('82', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_mobile_phone to mobile_phone",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('83', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_fax to fax",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('84', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_email to email",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('85', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_notes to notes",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('86', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_custom_field1 to custom_field1",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('87', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_custom_field2 to custom_field2",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('88', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_custom_field3 to custom_field3",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('89', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_custom_field4 to custom_field4",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('90', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name c_enabled to enabled",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
    'date'  => "20070523"
);
patchmaker('91', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_id to id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('92', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_biller_id to biller_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('93', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_customer_id to customer_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('94', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_type type_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('95', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_preference to preference_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('96', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_date to date",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
    'date'  => "20070523"
);
patchmaker('97', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name invoice_custom_field1 to custom_field1",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('98', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name invoice_custom_field2 to custom_field2",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('99', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name invoice_custom_field3 to custom_field3",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('100', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name invoice_custom_field4 to custom_field4",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('101', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_note to note ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('102', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_id to id ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
    'date'  => "20070523"
);
patchmaker('103', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_invoice_id to invoice_id ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('104', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_quantity to quantity ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('105', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_product_id to product_id ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('106', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_unit_price to unit_price ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
    'date'  => "20070523"
);
patchmaker('107', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_tax_id to tax_id  ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'",
    'date'  => "20070523"
);
patchmaker('108', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_tax to tax  ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
    'date'  => "20070523"
);
patchmaker('109', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_tax_amount to tax_amount  ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ",
    'date'  => "20070523"
);
patchmaker('110', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_gross_total to gross_total ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
    'date'  => "20070523"
);
patchmaker('111', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_description to description ",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL",
    'date'  => "20070523"
);
patchmaker('112', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Alter field name inv_it_total to total",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
    'date'  => "20070523"
);
patchmaker('113', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add logging table",
    'patch' => "CREATE TABLE `".TB_PREFIX."log` (`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                 `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                                                 `userid` INT NOT NULL ,
                                                 `sqlquerie` TEXT NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20070523"
);
patchmaker('114', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add logging system preference",
    'patch' => "INSERT INTO `".TB_PREFIX."system_defaults` ( `id` , `name` , `value` ) VALUES (NULL , 'logging', '0');",
    'date'  => "20070523"
);
patchmaker('115', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default biller",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_biller] where name = 'biller'",
    'date'  => "20070523"
);
patchmaker('116', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default customer",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_customer] where name = 'customer'",
    'date'  => "20070523"
);
patchmaker('117', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default tax",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_tax] where name = 'tax'",
    'date'  => "20070523"
);
patchmaker('118', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default invoice reference",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_inv_preference] where name = 'preference'",
    'date'  => "20070523"
);
patchmaker('119', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default number of line items",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_number_line_items] where name = 'line_items'",
    'date'  => "20070523"
);
patchmaker('120', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default invoice template",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = '$defaults[def_inv_template]' where name = 'template'",
    'date'  => "20070523"
);
patchmaker('121', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "System defaults conversion patch - set default paymemt type",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_payment_type] where name = 'payment_type'",
    'date'  => "20070523"
);
patchmaker('122', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add option to delete invoices into the system_defaults table",
    'patch' => "INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`) VALUES (NULL, 'delete', 'N');",
    'date'  => "200709"
);
patchmaker('123', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set default language in new lang system",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value = 'en-gb' where name ='language';",
    'date'  => "200709"
);
patchmaker('124', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Change log table that usernames are also possible as id",
    'patch' => "ALTER TABLE `".TB_PREFIX."log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'",
    'date'  => "200709"
);
patchmaker('125', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add visible attribute to the products table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';",
    'date'  => "200709"
);
patchmaker('126', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add last_id to logging table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."log` ADD  `last_id` INT NULL ;",
    'date'  => "200709"
);
patchmaker('127', $patchlines, $si_patches);

$u = (checkTableExists(TB_PREFIX.'users'));
$ud = (checkFieldExists(TB_PREFIX.'users','user_domain'));
$patchlines = array(
    'name'  => "Add user table",
    'patch' => ($u ? ($ud ? "SELECT * FROM ".TB_PREFIX."users;" :
                            "ALTER TABLE `".TB_PREFIX."users` ADD `user_domain` VARCHAR( 255 ) NOT NULL AFTER `user_group`;") :
                            "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."users` (`user_id` int(11) NOT NULL auto_increment,
                                                                              `user_email` varchar(255) NOT NULL,
                                                                              `user_name` varchar(255) NOT NULL,
                                                                              `user_group` varchar(255) NOT NULL,
                                                                              `user_domain` varchar(255) NOT NULL,
                                                                              `user_password` varchar(255) NOT NULL,
                            PRIMARY KEY(`user_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"),
    'date'  => "200709"
);
patchmaker('128', $patchlines, $si_patches);
unset($u);
unset($ud);

$patchlines = array(
    'name'  => "Fill user table with default values",
    'patch' => "INSERT INTO `".TB_PREFIX."users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`)
                VALUES (NULL, 'demo@simpleinvoices.group', 'demo', '1', '1', MD5('demo'))",
    'date'  => "200709"
);
patchmaker('129', $patchlines, $si_patches);

$ac = (checkTableExists(TB_PREFIX.'auth_challenges'));
$patchlines = array(
    'name'  => "Create auth_challenges table",
    'patch' => ($ac ? "SELECT * FROM " . TB_PREFIX . "auth_challenges" :
                      "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."auth_challenges` (`challenges_key` int(11) NOT NULL,
                                                                                  `challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);"),
    'date'  => "200709"
);
patchmaker('130', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make tax field 3 decimal places",
    'patch' => "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL",
    'date'  => "200709"
);
patchmaker('131', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Correct Foreign Key Tax ID Field Type in Invoice Items Table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT '0' NOT NULL ;",
    'date'  => "20071126"
);
patchmaker('132', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Correct Foreign Key Invoice ID Field Type in Ac Payments Table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;",
    'date'  => "20071126"
);
patchmaker('133', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop non-int compatible default from si_sql_patchmanager",
    'patch' => "SELECT 1+1;",
    'date'  => "20071218"
);
patchmaker('134', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Change sql_patch_ref type in sql_patchmanager to int",
    'patch' => "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;",
    'date'  => "20071218"
);
patchmaker('135', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create domain mapping table",
    'patch' => "CREATE TABLE " . TB_PREFIX . "user_domain (`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
                                                           `name` varchar(255) UNIQUE NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
    'date'  => "200712"
);
patchmaker('136', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert default domain",
    'patch' => "INSERT INTO " . TB_PREFIX . "user_domain (name) VALUES ('default');",
    'date'  => "200712"
);
patchmaker('137', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to payment_types table",
    'patch' => "ALTER TABLE `" . TB_PREFIX . "payment_types` ADD `domain_id` INT  NOT NULL AFTER `pt_id` ;",
    'date'  => "200712"
);
patchmaker('138', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to preferences table",
    'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `domain_id` INT  NOT NULL AFTER `pref_id`;",
    'date'  => "200712"
);
patchmaker('139', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to products table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
    'date'  => "200712"
);
patchmaker('140', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to billers table",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
    'date'  => "200712"
);
patchmaker('141', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to invoices table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD `domain_id` INT NOT NULL AFTER `id` ;",
    'date'  => "200712"
);
patchmaker('142', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to customers table",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` ADD `domain_id` INT NOT NULL AFTER `id` ;",
    'date'  => "200712"
);
patchmaker('143', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Change group field to user_role_id in users table",
    'patch' => "ALTER TABLE `".TB_PREFIX."users` CHANGE `user_group` `user_role_id` INT  DEFAULT '1' NOT NULL;",
    'date'  => "20080102"
);
patchmaker('144', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Change domain field to user_domain_id in users table",
    'patch' => "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT '1' NOT NULL;",
    'date'  => "20080102"
);
patchmaker('145', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop old auth_challenges table",
    'patch' => "DROP TABLE IF EXISTS `".TB_PREFIX."auth_challenges`;",
    'date'  => "20080102"
);
patchmaker('146', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create user_role table",
    'patch' => "CREATE TABLE ".TB_PREFIX."user_role (`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
                                                     `name` varchar(255) UNIQUE NOT NULL) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20080102"
);
patchmaker('147', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert default user group",
    'patch' => "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('administrator');",
    'date'  => "20080102"
);
patchmaker('148', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Account_payments Field = ac_amount : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;",
    'date'  => "20080128"
);
patchmaker('149', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = quantity : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT '0' ",
    'date'  => "20080128"
);
patchmaker('150', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = unit_price : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
    'date'  => "20080128"
);
patchmaker('151', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = tax : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
    'date'  => "20080128"
);
patchmaker('152', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = tax_amount : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
    'date'  => "20080128"
);
patchmaker('153', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = gross_total : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
    'date'  => "20080128"
);
patchmaker('154', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Invoice_items Field = total : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
    'date'  => "20080128"
);
patchmaker('155', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Products Field = unit_price : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
    'date'  => "20080128"
);
patchmaker('156', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Table = Tax Field = quantity : change field type and length to decimal",
    'patch' => "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
    'date'  => "20080128"
);
patchmaker('157', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename table si_account_payments to si_payment",
    'patch' => "RENAME TABLE `".TB_PREFIX."account_payments` TO  `".TB_PREFIX."payment`;",
    'date'  => "20081201"
);
patchmaker('158', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to payments table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."payment` ADD  `domain_id` INT NOT NULL ;",
    'date'  => "20081201"
);
patchmaker('159', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add domain_id to tax table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."tax` ADD  `domain_id` INT NOT NULL ;",
    'date'  => "20081201"
);
patchmaker('160', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Change user table from si_users to si_user",
    'patch' => "RENAME TABLE `".TB_PREFIX."users` TO  `".TB_PREFIX."user`;",
    'date'  => "20081201"
);
patchmaker('161', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add new invoice items tax table",
    'patch' => "CREATE TABLE `".TB_PREFIX."invoice_item_tax` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                              `invoice_item_id` INT( 11 ) NOT NULL ,
                                                              `tax_id` INT( 11 ) NOT NULL ,
                                                              `tax_type` VARCHAR( 1 ) NOT NULL ,
                                                              `tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
                                                              `tax_amount` DECIMAL( 25, 6 ) NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20081212"
);
patchmaker('162', $patchlines, $si_patches);

    //do conversion
$patchlines = array(
    'name'  => "Convert tax info in si_invoice_items to si_invoice_item_tax",
    'patch' => "INSERT INTO `" . TB_PREFIX . "invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount)
                SELECT id, tax_id, '%', tax, tax_amount FROM `" . TB_PREFIX . "invoice_items`;",
    'date'  => "20081212"
);
patchmaker('163', $patchlines, $si_patches);


$patchlines = array(
    'name'  => "Add default tax id into products table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;",
    'date'  => "20081212"
);
patchmaker('164', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add default tax id 2 into products table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;",
    'date'  => "20081212"
);
patchmaker('165', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add default tax into product items",
    'patch' => "UPDATE `".TB_PREFIX."products` SET default_tax_id = (SELECT value FROM `".TB_PREFIX."system_defaults` WHERE name ='tax');",
    'date'  => "20081212"
);
patchmaker('166', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add default number of taxes per line item into system_defaults",
    'patch' => "INSERT INTO `".TB_PREFIX."system_defaults` VALUES ('','tax_per_line_item','1')",
    'date'  => "20081212"
);
patchmaker('167', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add tax type",
    'patch' => "ALTER TABLE `".TB_PREFIX."tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;",
    'date'  => "20081212"
);
patchmaker('168', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set tax type on current taxes to %",
    'patch' => "SELECT 1+1;",
    'date'  => "20081212"
);
patchmaker('169', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on tax table to 1",
    'patch' => "UPDATE `".TB_PREFIX."tax` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('170', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on payment table to 1",
    'patch' => "UPDATE `".TB_PREFIX."payment` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('171', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on payment_types table to 1",
    'patch' => "UPDATE `".TB_PREFIX."payment_types` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('172', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on preference table to 1",
    'patch' => "UPDATE `".TB_PREFIX."preferences` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('173', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on products table to 1",
    'patch' => "UPDATE `".TB_PREFIX."products` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('174', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on biller table to 1",
    'patch' => "UPDATE `".TB_PREFIX."biller` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('175', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on invoices table to 1",
    'patch' => "UPDATE `".TB_PREFIX."invoices` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('176', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on customers table to 1",
    'patch' => "UPDATE `".TB_PREFIX."customers` SET `domain_id` = '1' ;",
    'date'  => "20081229"
);
patchmaker('177', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_id to si_user.id",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_id` `id` int(11) ;",
    'date'  => "20081229"
);
patchmaker('178', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_email to si_user.email",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_email` `email` VARCHAR( 255 );",
    'date'  => "20081229"
);
patchmaker('179', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_name to si_user.name",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_name` `name` VARCHAR( 255 );",
    'date'  => "20081229"
);
patchmaker('180', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_role_id to si_user.role_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_role_id` `role_id` int(11);",
    'date'  => "20081229"
);
patchmaker('181', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_domain_id to si_user.domain_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_domain_id` `domain_id` int(11) ;",
    'date'  => "20081229"
);
patchmaker('182', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Rename si_user.user_password to si_user.password",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_password` `password` VARCHAR( 255 ) ;",
    'date'  => "20081229"
);
patchmaker('183', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop name column from si_user table",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` DROP `name`  ;",
    'date'  => "20081230"
);
patchmaker('184', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop old defaults table",
    'patch' => "DROP TABLE `".TB_PREFIX."defaults` ;",
    'date'  => "20081230"
);
patchmaker('185', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on customers table to 1",
    'patch' => "ALTER TABLE  `".TB_PREFIX."custom_fields` ADD  `domain_id` INT NOT NULL ;",
    'date'  => "20081230"
);
patchmaker('186', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on custom_feilds table to 1",
    'patch' => "UPDATE `".TB_PREFIX."custom_fields` SET `domain_id` = '1' ;",
    'date'  => "20081230"
);
patchmaker('187', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop tax_id column from si_invoice_items table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax_id`  ;",
    'date'  => "20090118"
);
patchmaker('188', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop tax column from si_invoice_items table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax`  ;",
    'date'  => "20090118"
);
patchmaker('189', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert user role - user",
    'patch' => "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('user');",
    'date'  => "20090215"
);
patchmaker('190', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert user role - viewer",
    'patch' => "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('viewer');",
    'date'  => "20090215"
);
patchmaker('191', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert user role - customer",
    'patch' => "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('customer');",
    'date'  => "20090215"
);
patchmaker('192', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Insert user role - biller",
    'patch' => "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('biller');",
    'date'  => "20090215"
);
patchmaker('193', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "User table - auto increment",
    'patch' => "ALTER TABLE ".TB_PREFIX."user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;",
    'date'  => "20090215"
);
patchmaker('194', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "User table - add enabled field",
    'patch' => "ALTER TABLE ".TB_PREFIX."user ADD enabled INT( 1 ) NOT NULL ;",
    'date'  => "20090215"
);
patchmaker('195', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "User table - make all existing users enabled",
    'patch' => "UPDATE ".TB_PREFIX."user SET enabled = 1 ;",
    'date'  => "20090217"
);
patchmaker('196', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Defaults table - add domain_id and extension_id field",
    'patch' => "ALTER TABLE ".TB_PREFIX."system_defaults ADD `domain_id` INT( 5 ) NOT NULL DEFAULT '1',
                                                         ADD `extension_id` INT( 5 ) NOT NULL DEFAULT '1',
                                                         DROP INDEX `name`,
                                                         ADD INDEX `name` ( `name` );",
    'date'  => "20090321"
);
patchmaker('197', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Extension table - create table to hold extension status",
    'patch' => "CREATE TABLE ".TB_PREFIX."extensions (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                      `domain_id` INT( 11 ) NOT NULL ,
                                                      `name` VARCHAR( 255 ) NOT NULL ,
                                                      `description` VARCHAR( 255 ) NOT NULL ,
                                                      `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '0') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20090322"
);
patchmaker('198', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Update extensions table",
    'patch' => "INSERT INTO ".TB_PREFIX."extensions (`id`,`domain_id`,`name`,`description`,`enabled`)
                VALUES ('1','1','core','Core part of SimpleInvoices - always enabled','1');",
    'date'  => "20090529"
);
patchmaker('199', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Update extensions table",
    'patch' => "UPDATE ".TB_PREFIX."extensions SET `id` = '1' WHERE `name` = 'core' LIMIT 1;",
    'date'  => "20090529"
);
patchmaker('200', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set domain_id on system defaults table to 1",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET `domain_id` = '1' ;",
    'date'  => "20090622"
);
patchmaker('201', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set extension_id on system defaults table to 1",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET `extension_id` = '1' ;",
    'date'  => "20090622"
);
patchmaker('202', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Move all old consulting style invoices to itemised",
    'patch' => "UPDATE `".TB_PREFIX."invoices` SET `type_id` = '2' where `type_id`=3 ;",
    'date'  => "20090704"
);
patchmaker('203', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Creates index table to handle new invoice numbering system",
    'patch' => "CREATE TABLE `".TB_PREFIX."index` (`id` INT( 11 ) NOT NULL ,
                                                   `node` VARCHAR( 255 ) NOT NULL ,
                                                   `sub_node` VARCHAR( 255 ) NULL ,
                                                   `domain_id` INT( 11 ) NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20090818"
);
patchmaker('204', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add index_id to invoice table - new invoice numbering",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD `index_id` INT( 11 ) NOT NULL AFTER `id`;",
    'date'  => "20090818"
);
patchmaker('205', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add status and locale to preferences",
    'patch' => "ALTER TABLE `".TB_PREFIX."preferences` ADD `status` INT( 1 ) NOT NULL ,
                                                       ADD `locale` VARCHAR( 255 ) NULL ,
                                                       ADD `language` VARCHAR( 255 ) NULL ;",
    'date'  => "20090826"
);
patchmaker('206', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Populate the status, locale, and language fields in preferences table",
    'patch' => "UPDATE `".TB_PREFIX."preferences` SET status = '1', locale = '".$config->local->locale."', language = '$language' ;",
    'date'  => "20090826"
);
patchmaker('207', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Populate the status, locale, and language fields in preferences table",
    'patch' => "ALTER TABLE `".TB_PREFIX."preferences` ADD `index_group` INT( 11 ) NOT NULL ;",
    'date'  => "20090826"
);
patchmaker('208', $patchlines, $si_patches);

$defaults = getSystemDefaults();
$patchlines = array(
    'name'  => "Populate the status, locale, and language fields in preferences table",
    'patch' => "UPDATE `".TB_PREFIX."preferences` SET index_group = '".$defaults['preference']."' ;",
    'date'  => "20090826"
);
patchmaker('209', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create composite primary key for invoice table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`,`id` );",
    'date'  => "20090826"
);
patchmaker('210', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Reset auto-increment for invoice table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` AUTO_INCREMENT = 1;",
    'date'  => "20090826"
);
patchmaker('211', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Copy invoice.id into invoice.index_id",
    'patch' => "update `".TB_PREFIX."invoices` set index_id = id;",
    'date'  => "20090902"
);
patchmaker('212', $patchlines, $si_patches);

$max_invoice = Invoice::maxIndexId();
$patchlines = array(
    'name'  => "Update the index table with max invoice id - if required",
    'patch' =>($max_invoice > "0" ? "INSERT INTO `" . TB_PREFIX . "index` (id, node, sub_node, domain_id)
                                     VALUES (".$max_invoice.", 'invoice', '".$defaults['preference']."','1');" :
                                     "SELECT 1+1;"),
    'date'  => "20090902"
);
patchmaker('213', $patchlines, $si_patches);
unset($defaults);
unset($max_invoice);

$patchlines = array(
    'name'  => "Add sub_node_2 to si_index table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."index` ADD  `sub_node_2` VARCHAR( 255 ) NULL AFTER  `sub_node`",
    'date'  => "20090912"
);
patchmaker('214', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_invoices - add composite primary key - patch removed",
    'patch' => "SELECT 1+1;",
    'date'  => "20090912"
);
patchmaker('215', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_payment - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."payment` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
    'date'  => "20090912"
);
patchmaker('216', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_payment_types - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."payment_types` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pt_id`)",
    'date'  => "20090912"
);
patchmaker('217', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_preferences - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."preferences` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pref_id`)",
    'date'  => "20090912"
);
patchmaker('218', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_products - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."products` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
    'date'  => "20090912"
);
patchmaker('219', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_tax - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."tax` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `tax_id`)",
    'date'  => "20090912"
);
patchmaker('220', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_user - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."user` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
    'date'  => "20090912"
);
patchmaker('221', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_biller - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
    'date'  => "20100209"
);
patchmaker('222', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_customers - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."customers` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
    'date'  => "20100209"
);
patchmaker('223', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add paypal business name",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_business_name` VARCHAR( 255 ) NULL AFTER  `footer`",
    'date'  => "20100209"
);
patchmaker('224', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add paypal notify url",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_notify_url` VARCHAR( 255 ) NULL AFTER  `paypal_business_name`",
    'date'  => "20100209"
);
patchmaker('225', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Define currency in preferences",
    'patch' => "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_code` VARCHAR( 25 ) NULL ;",
    'date'  => "20100209"
);
patchmaker('226', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create cron table to handle recurrence",
    'patch' => "CREATE TABLE `".TB_PREFIX."cron` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                  `domain_id` INT( 11 ) NOT NULL ,
                                                  `invoice_id` INT( 11 ) NOT NULL ,
                                                  `start_date` DATE NOT NULL ,
                                                  `end_date` VARCHAR( 10 ) NULL ,
                                                  `recurrence` INT( 11 ) NOT NULL ,
                                                  `recurrence_type` VARCHAR( 11 ) NOT NULL ,
                                                  `email_biller` INT( 1 ) NULL ,
                                                  `email_customer` INT( 1 ) NULL ,
                                                  PRIMARY KEY (`domain_id` ,`id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20100215"
);
patchmaker('227', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create cron_log table to handle record when cron was run",
    'patch' => "CREATE TABLE `".TB_PREFIX."cron_log` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                      `domain_id` INT( 11 ) NOT NULL ,
                                                      `run_date` DATE NOT NULL ,
                                                      PRIMARY KEY (  `domain_id` , `id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20100216"
);
patchmaker('228', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "preferences - add online payment type",
    'patch' => "ALTER TABLE `".TB_PREFIX."preferences` ADD `include_online_payment` VARCHAR( 255 ) NULL ;",
    'date'  => "20100209"
);
patchmaker('229', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add paypal notify url",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_return_url` VARCHAR( 255 ) NULL AFTER  `paypal_notify_url`",
    'date'  => "20100223"
);
patchmaker('230', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add paypal payment id into payment table",
    'patch' => "ALTER TABLE  `".TB_PREFIX."payment` ADD `online_payment_id` VARCHAR( 255 ) NULL AFTER  `domain_id`",
    'date'  => "20100226"
);
patchmaker('231', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Define currency display in preferences",
    'patch' => "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_position` VARCHAR( 25 ) NULL ;",
    'date'  => "20100227"
);
patchmaker('232', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add system default to control invoice number by biller -- dummy patch -- this sql was removed",
    'patch' => "SELECT 1+1;",
    'date'  => "20100302"
);
patchmaker('233', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add eway customer ID",
    'patch' => "ALTER TABLE  `".TB_PREFIX."biller` ADD `eway_customer_id` VARCHAR( 255 ) NULL AFTER `paypal_return_url`;",
    'date'  => "20100315"
);
patchmaker('234', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add eway card holder name",
    'patch' => "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_holder_name` VARCHAR( 255 ) NULL AFTER `email`;",
    'date'  => "20100315"
);
patchmaker('235', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add eway card number",
    'patch' => "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_number` VARCHAR( 255 ) NULL AFTER `credit_card_holder_name`;",
    'date'  => "20100315"
);
patchmaker('236', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add eway card expiry month",
    'patch' => "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_month` VARCHAR( 02 ) NULL AFTER `credit_card_number`;",
    'date'  => "20100315"
);
patchmaker('237', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add eway card expirt year",
    'patch' => "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_year` VARCHAR( 04 ) NULL AFTER `credit_card_expiry_month` ;",
    'date'  => "20100315"
);
patchmaker('238', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "cronlog - add invoice id",
    'patch' => "ALTER TABLE `".TB_PREFIX."cron_log` ADD `cron_id` VARCHAR( 25 ) NULL AFTER `domain_id` ;",
    'date'  => "20100321"
);
patchmaker('239', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_system_defaults - add composite primary key",
    'patch' => "ALTER TABLE  `".TB_PREFIX."system_defaults` ADD `new_id` INT( 11 ) NOT NULL FIRST;
                UPDATE `".TB_PREFIX."system_defaults` SET new_id = id;
                ALTER TABLE  `".TB_PREFIX."system_defaults` DROP  `id` ;
                ALTER TABLE  `".TB_PREFIX."system_defaults` DROP INDEX `name` ;
                ALTER TABLE  `".TB_PREFIX."system_defaults` CHANGE  `new_id`  `id` INT( 11 ) NOT NULL;
                ALTER TABLE  `".TB_PREFIX."system_defaults` ADD PRIMARY KEY(`domain_id`,`id` );",
    'date'  => "20100305"
);
patchmaker('240', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "si_system_defaults - add composite primary key",
    'patch' => "INSERT INTO `".TB_PREFIX."system_defaults` VALUES ('','inventory','0','1','1');",
    'date'  => "20100409"
);
patchmaker('241', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add cost to products table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `cost` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' AFTER `default_tax_id_2`;",
    'date'  => "20100409"
);
patchmaker('242', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add reorder_level to products table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `reorder_level` INT( 11 ) NULL AFTER `cost` ;",
    'date'  => "20100409"
);
patchmaker('243', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Create inventory table",
    'patch' => "CREATE TABLE  `".TB_PREFIX."inventory` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                        `domain_id` INT( 11 ) NOT NULL ,
                                                        `product_id` INT( 11 ) NOT NULL ,
                                                        `quantity` DECIMAL( 25, 6 ) NOT NULL ,
                                                        `cost` DECIMAL( 25, 6 ) NULL ,
                                                        `date` DATE NOT NULL ,
                                                        `note` TEXT NULL ,
                                                        PRIMARY KEY (`domain_id`, `id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20100409"
);
patchmaker('244', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Preferences - make locale null field",
    'patch' => "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `locale`  `locale` VARCHAR( 255 ) NULL ;",
    'date'  => "20100419"
);
patchmaker('245', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Preferences - make language a null field",
    'patch' => "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `language`  `language` VARCHAR( 255 ) NULL;",
    'date'  => "20100419"
);
patchmaker('246', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Custom fields - make sure domain_id is 1",
    'patch' => "update ".TB_PREFIX."custom_fields set domain_id = '1';",
    'date'  => "20100419"
);
patchmaker('247', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`domain_id`);",
    'date'  => "20100419"
);
patchmaker('248', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`biller_id`) ;",
    'date'  => "20100419"
);
patchmaker('249', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`customer_id`);",
    'date'  => "20100419"
);
patchmaker('250', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`domain_id`);",
    'date'  => "20100419"
);
patchmaker('251', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Language - reset to en_US - due to folder renaming",
    'patch' => "UPDATE `".TB_PREFIX."system_defaults` SET value ='en_US' where name='language';",
    'date'  => "20100419"
);
patchmaker('252', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add PaymentsGateway API ID field",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` ADD  `paymentsgateway_api_id` VARCHAR( 255 ) NULL AFTER `eway_customer_id`;",
    'date'  => "20110918"
);
patchmaker('253', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;",
    'date'  => "20130313"
);
patchmaker('254', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "CREATE TABLE `".TB_PREFIX."products_attributes` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                 `name` VARCHAR( 255 ) NOT NULL,
                                                                 `type_id` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
    'date'  => "20130313"
);
patchmaker('255', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "INSERT INTO `". TB_PREFIX ."products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, 'Size','1'), (NULL,'Colour','1');",
    'date'  => "20130313"
);
patchmaker('256', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "CREATE TABLE `". TB_PREFIX ."products_values` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                               `attribute_id` INT( 11 ) NOT NULL ,
                                                               `value` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
    'date'  => "20130313"
);
patchmaker('257', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "INSERT INTO `". TB_PREFIX ."products_values` (`id`, `attribute_id`,`value`)
                VALUES (NULL,'1', 'S'),  (NULL,'1', 'M'), (NULL,'1', 'L'),  (NULL,'2', 'Red'),  (NULL,'2', 'White');",
    'date'  => "20130313"
);
patchmaker('258', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "SELECT 1+1;",  //remove matrix code
    'date'  => "20130313"
);
patchmaker('259', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "SELECT 1+1;", //remove matrix code
    'date'  => "20130313"
);
patchmaker('260', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "SELECT 1+1;", //remove matrix code
    'date'  => "20130313"
);
patchmaker('261', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add product attributes system preference",
    'patch' => "INSERT INTO ".TB_PREFIX."system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'product_attributes', '0', '1', '1');",
    'date'  => "20130313"
);
patchmaker('262', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - update line items table",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `attribute` VARCHAR( 255 ) NULL ;",
    'date'  => "20130313"
);
patchmaker('263', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product - use notes as default line item description",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;",
    'date'  => "20130314"
);
patchmaker('264', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product - expand/show line item description",
    'patch' => "ALTER TABLE `".TB_PREFIX."products` ADD `show_description` VARCHAR( 1 ) NULL ;",
    'date'  => "20130314"
);
patchmaker('265', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product - expand/show line item description",
    'patch' => "CREATE TABLE `".TB_PREFIX."products_attribute_type` (`id` int(11) NOT NULL AUTO_INCREMENT,
                                                                     `name` varchar(255) NOT NULL,
                                                                     PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    'date'  => "20130322"
);
patchmaker('266', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - insert attribute types",
    'patch' => "INSERT INTO `". TB_PREFIX ."products_attribute_type` (`id`, `name`) VALUES (NULL,'list'),  (NULL,'decimal'), (NULL,'free');",
    'date'  => "20130325"
);
patchmaker('267', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - insert attribute types",
    'patch' =>"ALTER TABLE  `". TB_PREFIX ."products_attributes` ADD `enabled` VARCHAR( 1 ) NULL DEFAULT  '1',
                                                                ADD `visible` VARCHAR( 1 ) NULL DEFAULT  '1';",
    'date'  => "20130327"
);
patchmaker('268', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Product Matrix - insert attribute types",
    'patch' => "ALTER TABLE  `". TB_PREFIX ."products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  '1';",
    'date'  => "20130327"
);
patchmaker('269', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`ac_inv_id`);",
    'date'  => "20100419"
);
patchmaker('270', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`ac_amount`);",
    'date'  => "20100419"
);
patchmaker('271', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add product attributes system preference",
    'patch' => "INSERT INTO ".TB_PREFIX."system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'large_dataset', '0', '1', '1');",
    'date'  => "20130313"
);
patchmaker('272', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make SimpleInvoices faster - add index",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` ADD INDEX(`invoice_id`);",
    'date'  => "20130927"
);
patchmaker('273', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Only One Default Variable name per domain allowed - add unique index",
    'patch' => "ALTER TABLE `".TB_PREFIX."system_defaults` ADD UNIQUE INDEX `UnqNameInDomain` (`domain_id`, `name`);",
    'date'  => "20131007"
);
patchmaker('274', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make EMail / Password pair unique per domain - add unique index",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` CHANGE `password` `password` VARCHAR(64) NULL, ADD UNIQUE INDEX `UnqEMailPwd` (`email`, `password`);",
    'date'  => "20131007"
);
patchmaker('275', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each invoice Item must belong to a specific Invoice with a specific domain_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `invoice_id`;",
    'date'  => "20131008"
);
patchmaker('276', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Add Index for Quick Invoice Item Search for a domain_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoice_items` ADD INDEX `DomainInv` (`invoice_id`, `domain_id`);",
    'date'  => "20131008"
);
patchmaker('277', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each Invoice Item can have only one instance of each tax",
//    Patch disabled for old installs with inadequate database integrity
//    $si_patches['278']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_item_tax` ADD UNIQUE INDEX `UnqInvTax` (`invoice_item_id`, `tax_id`);";
    'patch' => "SELECT 1+1;",
    'date'  => "20131008"
);
patchmaker('278', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Drop unused superceeded table si_product_matrix if present",
    'patch' => "DROP TABLE IF EXISTS `".TB_PREFIX."products_matrix`;",
    'date'  => "20131009"
);
patchmaker('279', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each domain has their own extension instances",
    'patch' => "ALTER TABLE `".TB_PREFIX."extensions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);",
    'date'  => "20131011"
);
patchmaker('280', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each domain has their own custom_field id sets",
    'patch' => "ALTER TABLE `".TB_PREFIX."custom_fields` DROP PRIMARY KEY, ADD PRIMARY KEY (`cf_id`, `domain_id`);",
    'date'  => "20131011"
);
patchmaker('281', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each domain has their own logs",
    'patch' => "ALTER TABLE `".TB_PREFIX."log` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `id`, DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);",
    'date'  => "20131011"
);
patchmaker('282', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Match field type with foreign key field si_user.id",
    'patch' => "ALTER TABLE `".TB_PREFIX."log` CHANGE `userid` `userid` INT NOT NULL DEFAULT '1';",
    'date'  => "20131012"
);
patchmaker('283', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Make si_index sub_node and sub_node_2 fields as integer",
    'patch' => "ALTER TABLE `".TB_PREFIX."index` CHANGE `node` `node` VARCHAR(64) NOT NULL, CHANGE `sub_node` `sub_node` INT NOT NULL, CHANGE `sub_node_2` `sub_node_2` INT NOT NULL;",
    'date'  => "20131016"
);
patchmaker('284', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Fix compound Primary Key for si_index table",
    'patch' => "ALTER TABLE `".TB_PREFIX."index` ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);",
    'date'  => "20131016"
);
patchmaker('285', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Speedup lookups from si_index table with indices in si_invoices table",
    'patch' => "ALTER TABLE `".TB_PREFIX."invoices` ADD UNIQUE INDEX `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`), ADD INDEX `IdxDI` (`index_id`, `preference_id`, `domain_id`);",
    'date'  => "20131016"
);
patchmaker('286', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Populate additional user roles like domain_administrator",
    'patch' => "INSERT IGNORE INTO `".TB_PREFIX."user_role` (`name`) VALUES ('domain_administrator'), ('customer'), ('biller');",
    'date'  => "20131017"
);
patchmaker('287', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Fully relational now - do away with the si_index table",
    'patch' => "SELECT 1+1;",
    'date'  => "20131017"
);
patchmaker('288', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Each cron_id can run a maximum of only once a day for each domain_id",
    'patch' => "ALTER TABLE `".TB_PREFIX."cron_log` ADD UNIQUE INDEX `CronIdUnq` (`domain_id`, `cron_id`, `run_date`);",
    'date'  => "20131108"
);
patchmaker('289', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Set all Flag fields to tinyint(1) and other 1 byte fields to char",
    'patch' => "ALTER TABLE `".TB_PREFIX."biller` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."customers` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."extensions` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 0 NOT NULL;
                ALTER TABLE `".TB_PREFIX."payment_types` CHANGE `pt_enabled` `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."preferences` CHANGE `pref_enabled` `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                       CHANGE `status` `status` TINYINT(1) NOT NULL;
                ALTER TABLE `".TB_PREFIX."products` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                    CHANGE `notes_as_description` `notes_as_description` CHAR(1) NULL,
                                                    CHANGE `show_description` `show_description` CHAR(1) NULL;
                ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_enabled` `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."cron` CHANGE `email_biller` `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,
                                                CHANGE `email_customer` `email_customer` TINYINT(1) DEFAULT 0 NOT NULL;
                ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_display` `cf_display` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."invoice_item_tax` CHANGE `tax_type` `tax_type` CHAR(1) DEFAULT '%' NOT NULL;
                ALTER TABLE `".TB_PREFIX."tax` CHANGE `type` `type` CHAR(1) DEFAULT '%' NOT NULL;
                ALTER TABLE `".TB_PREFIX."products_attributes` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                               CHANGE `visible` `visible` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."products_VALUES` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                ALTER TABLE `".TB_PREFIX."user` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;",
    'date'  => "20131109"
);
patchmaker('290', $patchlines, $si_patches);

$patchlines = array(
    'name'  => "Clipped size of zip_code and credit_card_number fields to realistic values",
    'patch' => "ALTER TABLE `".TB_PREFIX."customers` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL,
                                                     CHANGE `credit_card_number` `credit_card_number` VARCHAR(20) NULL;
                ALTER TABLE `".TB_PREFIX."biller` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL;",
    'date'  => "20131111"
);
patchmaker('291', $patchlines, $si_patches);

$patchlines = array(
    'name' => "Added Customer/Biller User ID column to user table",
    'patch' => "ALTER TABLE `".TB_PREFIX."user` ADD COLUMN `user_id` INT  DEFAULT 0 NOT NULL AFTER `enabled`;",
    'date' => "20140103"
);
patchmaker('292', $patchlines, $si_patches);

$patchlines = array(
    'name' => 'Add Signature field to the billers table.',
    'patch' => "ALTER TABLE " . TB_PREFIX . "biller ADD `signature` varchar(255) DEFAULT NULL",
    'date' => "20180921"
);
patchmaker('293', $patchlines, $si_patches);


