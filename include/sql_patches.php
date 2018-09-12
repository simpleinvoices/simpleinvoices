<?php
if (!function_exists("patchmaker")) {
    function patchmaker($num, $patchlines, &$patches) {
        static $last = -1;
        if ($num == 0 && $patchlines['name'] == "Start") $last = -1;
        if (++$last != $num) {
            throw new Exception("patchmaker - Patch #$num is out of sequence.");
        }
        $patches[$num]['name' ] = $patchlines['name' ];
        $patches[$num]['patch'] = $patchlines['patch'];
        $patches[$num]['date' ] = $patchlines['date' ];
    }
}

global $config,
       $language;

//$is_mysql = $config->database->adapter != "pdo_pgsql";
$is_mysql = true;
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

$patchlines['name' ] = "Start";
$patchlines['patch'] = "SHOW TABLES LIKE 'test'";
$patchlines['date' ] = "20060514";
patchmaker('0', $patchlines, $si_patches);

$patchlines['name' ] = "Create sql_patchmanger table";
$patchlines['patch'] = "CREATE TABLE ".TB_PREFIX."sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL, sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL, sql_statement TEXT NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$patchlines['date' ] = "20060514";
patchmaker('1', $patchlines, $si_patches);

$patchlines['name' ] = "Update invoice no details to have a default currency sign";
$patchlines['patch'] = "UPDATE ".TB_PREFIX."preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1";
$patchlines['date' ] = "20060514";
patchmaker('2', $patchlines, $si_patches);

$patchlines['name' ] = "Add a row into the defaults table to handle the default number of line items";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."defaults ADD def_number_line_items INT( 25 ) NOT NULL";
$patchlines['date' ] = "20060514";
patchmaker('3', $patchlines, $si_patches);

$patchlines['name' ] = "Set the default number of line items to 5";
$patchlines['patch'] = "UPDATE ".TB_PREFIX."defaults SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1";
$patchlines['date' ] = "20060514";
patchmaker('4', $patchlines, $si_patches);

$patchlines['name' ] = "Add logo and invoice footer support to biller";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."biller ADD b_co_logo VARCHAR( 50 ), ADD b_co_footer TEXT";
$patchlines['date' ] = "20060514";
patchmaker('5', $patchlines, $si_patches);

$patchlines['name' ] = "Add default invoice template option";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."defaults ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL";
$patchlines['date' ] = "20060514";
patchmaker('6', $patchlines, $si_patches);

$patchlines['name' ] = "Edit tax description field length to 50";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL";
$patchlines['date' ] = "20060526";
patchmaker('7', $patchlines, $si_patches);

$patchlines['name' ] = "Edit default invoice template field length to 50";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."defaults CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL";
$patchlines['date' ] = "20060526";
patchmaker('8', $patchlines, $si_patches);

$patchlines['name' ] = "Add consulting style invoice";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')";
$patchlines['date' ] = "20060531";
patchmaker('9', $patchlines, $si_patches);

$patchlines['name' ] = "Add enabled to biller";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."biller ADD b_enabled varchar(1) NOT NULL default '1'";
$patchlines['date' ] = "20060815";
patchmaker('10', $patchlines, $si_patches);

$patchlines['name' ] = "Add enabled to customers";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."customers ADD c_enabled varchar(1) NOT NULL default '1'";
$patchlines['date' ] = "20060815";
patchmaker('11', $patchlines, $si_patches);

$patchlines['name' ] = "Add enabled to preferences";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."preferences ADD pref_enabled varchar(1) NOT NULL default '1'";
$patchlines['date' ] = "20060815";
patchmaker('12', $patchlines, $si_patches);

$patchlines['name' ] = "Add enabled to products";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."products ADD prod_enabled varchar(1) NOT NULL default '1'";
$patchlines['date' ] = "20060815";
patchmaker('13', $patchlines, $si_patches);

$patchlines['name' ] = "Add enabled to products";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."tax ADD tax_enabled varchar(1) NOT NULL default '1'";
$patchlines['date' ] = "20060815";
patchmaker('14', $patchlines, $si_patches);

$patchlines['name' ] = "Add tax_id into invoice_items table";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0'  AFTER inv_it_unit_price";
$patchlines['date' ] = "20060815";
patchmaker('15', $patchlines, $si_patches);

$patchlines['name' ] = "Add Payments table";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."account_payments` (
  `ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `ac_inv_id` VARCHAR( 10 ) NOT NULL ,
  `ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
  `ac_notes` TEXT NOT NULL ,
  `ac_date` DATETIME NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20060827";
patchmaker('16', $patchlines, $si_patches);

$patchlines['name' ] = "Adjust data type of quantity field";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20060827";
patchmaker('17', $patchlines, $si_patches);

$patchlines['name' ] = "Create Payment Types table";
$patchlines['patch'] = "CREATE TABLE `".TB_PREFIX."payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$patchlines['date' ] = "20060909";
patchmaker('18', $patchlines, $si_patches);

$patchlines['name' ] = "Add info into the Payment Type table";
$patchlines['patch'] = "INSERT INTO `".TB_PREFIX."payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')";
$patchlines['date' ] = "20060909";
patchmaker('19', $patchlines, $si_patches);

$patchlines['name' ] = "Adjust accounts payments table to add a type field";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'";
$patchlines['date' ] = "20060909";
patchmaker('20', $patchlines, $si_patches);

$patchlines['name' ] = "Adjust the defautls table to add a payment type field";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'";
$patchlines['date' ] = "20060909";
patchmaker('21', $patchlines, $si_patches);

$patchlines['name' ] = "Add note field to customer";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_notes` TEXT NULL AFTER `c_email`";
$patchlines['date' ] = "20061026";
patchmaker('22', $patchlines, $si_patches);

$patchlines['name' ] = "Add note field to Biller";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`";
$patchlines['date' ] = "20061026";
patchmaker('23', $patchlines, $si_patches);

$patchlines['name' ] = "Add note field to Products";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`";
$patchlines['date' ] = "20061026";
patchmaker('24', $patchlines, $si_patches);

/*Custom fields patches - start */
$patchlines['name' ] = "Add street address 2 to customers";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ";
$patchlines['date' ] = "20061211";
patchmaker('25', $patchlines, $si_patches);

$patchlines['name' ] = "Add custom fields to customers";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
  ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
  ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
  ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;";
$patchlines['date' ] = "20061211";
patchmaker('26', $patchlines, $si_patches);

$patchlines['name' ] = "Add mobile phone to customers";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`";
$patchlines['date' ] = "20061211";
patchmaker('27', $patchlines, $si_patches);

$patchlines['name' ] = "Add street address 2 to billers";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ";
$patchlines['date' ] = "20061211";
patchmaker('28', $patchlines, $si_patches);

$patchlines['name' ] = "Add custom fields to billers";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
  ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
  ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
  ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;";
$patchlines['date' ] = "20061211";
patchmaker('29', $patchlines, $si_patches);

$patchlines['name' ] = "Creating the custom fields table";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."custom_fields` (
  `cf_id` INT NOT NULL AUTO_INCREMENT ,
  `cf_custom_field` VARCHAR( 50 ) NOT NULL ,
  `cf_custom_label` VARCHAR( 50 ) ,
  `cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
  PRIMARY KEY(`cf_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20061211";
patchmaker('30', $patchlines, $si_patches);

$patchlines['name' ] = "Adding data to the custom fields table";
$patchlines['patch'] =
"INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
 VALUES (NULL,'biller_cf1'  ,NULL,'0'),(NULL,'biller_cf2'  ,NULL,'0'),(NULL,'biller_cf3'  ,NULL,'0'),(NULL,'biller_cf4'  ,NULL,'0'),
        (NULL,'customer_cf1',NULL,'0'),(NULL,'customer_cf2',NULL,'0'),(NULL,'customer_cf3',NULL,'0'),(NULL,'customer_cf4',NULL,'0'),
        (NULL,'product_cf1' ,NULL,'0'),(NULL,'product_cf2' ,NULL,'0'),(NULL,'product_cf3' ,NULL,'0'),(NULL,'product_cf4' ,NULL,'0');";
$patchlines['date' ] = "20061211";
patchmaker('31', $patchlines, $si_patches);

$patchlines['name' ] = "Adding custom fields to products";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."products`
  ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price`,
  ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1`,
  ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2`,
  ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3`;";
$patchlines['date' ] = "20061211";
patchmaker('32', $patchlines, $si_patches);

$patchlines['name' ] = "Alter product custom field 4";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `".TB_PREFIX."custom_fields`.`cf_id` =12 LIMIT 1 ;";
$patchlines['date' ] = "20061214";
patchmaker('33', $patchlines, $si_patches);

$patchlines['name' ] = "Reset invoice template to default refer Issue 70";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;";
$patchlines['date' ] = "20070125";
patchmaker('34', $patchlines, $si_patches);

$patchlines['name' ] = "Adding data to the custom fields table for invoices";
$patchlines['patch'] =
"INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
 VALUES (NULL,'invoice_cf1',NULL,'0'),(NULL,'invoice_cf2',NULL,'0'),(NULL,'invoice_cf3',NULL,'0'),(NULL,'invoice_cf4',NULL,'0');";
$patchlines['date' ] = "20070204";
patchmaker('35', $patchlines, $si_patches);

$patchlines['name' ] = "Adding custom fields to the invoices table";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."invoices`
  ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date` ,
  ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1` ,
  ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2` ,
  ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3` ;";
$patchlines['date' ] = "20070204";
patchmaker('36', $patchlines, $si_patches);

$patchlines['name' ] = "Reset invoice template to default due to new invoice template system";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;";
$patchlines['date' ] = "20070523";
patchmaker('37', $patchlines, $si_patches);

$patchlines['name' ] = "Alter custom field table - field length now 255 for field name";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )";
$patchlines['date' ] = "20070523";
patchmaker('38', $patchlines, $si_patches);

$patchlines['name' ] = "Alter custom field table - field length now 255 for field label";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )";
$patchlines['date' ] = "20070523";
patchmaker('39', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name in sql_patchmanager";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL";
$patchlines['date' ] = "20070523";
patchmaker('40', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name in ".TB_PREFIX."account_payments";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('41', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_name to name";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;";
$patchlines['date' ] = "20070523";
patchmaker('42', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_id to id";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('43', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_street_address to street_address";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('44', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_street_address2 to street_address2";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('45', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_city to city";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('46', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_state to state";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('47', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_zip_code to zip_code";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('48', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_country to country";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('49', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_phone to phone";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('50', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_mobile_phone to mobile_phone";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('51', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_fax to fax";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('52', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_email to email";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('53', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_co_logo to logo";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('54', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_co_footer to footer";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ";
$patchlines['date' ] = "20070523";
patchmaker('55', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_notes to notes";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ";
$patchlines['date' ] = "20070523";
patchmaker('56', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_enabled to enabled";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
$patchlines['date' ] = "20070523";
patchmaker('57', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_custom_field1 to custom_field1";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('58', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_custom_field2 to custom_field2";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('59', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_custom_field3 to custom_field3";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('60', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name b_custom_field4 to custom_field4";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('61', $patchlines, $si_patches);

$patchlines['name' ] = "Introduce system_defaults table";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."system_defaults` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$patchlines['date' ] = "20070523";
patchmaker('62', $patchlines, $si_patches);

$patchlines['name' ] = "Insert date into the system_defaults table";
$patchlines['patch'] =
"INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`)
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
  (20, 'emailpassword'  , '');";
$patchlines['date' ] = "20070523";
patchmaker('63', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_id to id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('64', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_description to description";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_description` `description` TEXT NOT NULL ";
$patchlines['date' ] = "20070523";
patchmaker('65', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_unit_price to unit_price";
$patchlines['patch'] = " ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('66', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_custom_field1 to custom_field1";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('67', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_custom_field2 to custom_field2";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('68', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_custom_field3 to custom_field3";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('69', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_custom_field4 to custom_field4";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('70', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_notes to notes";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_notes` `notes` TEXT NOT NULL";
$patchlines['date' ] = "20070523";
patchmaker('71', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name prod_enabled to enabled";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
$patchlines['date' ] = "20070523";
patchmaker('72', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_id to id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('73', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_attention to attention";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('74', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_name to name";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('75', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_street_address to street_address";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('76', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_street_address2 to street_address2";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('77', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_city to city";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('78', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_state to state";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('79', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_zip_code to zip_code";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('80', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_country to country";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('81', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_phone to phone";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('82', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_mobile_phone to mobile_phone";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('83', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_fax to fax";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('84', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_email to email";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('85', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_notes to notes";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('86', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_custom_field1 to custom_field1";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('87', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_custom_field2 to custom_field2";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('88', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_custom_field3 to custom_field3";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('89', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_custom_field4 to custom_field4";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('90', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name c_enabled to enabled";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
$patchlines['date' ] = "20070523";
patchmaker('91', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_id to id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('92', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_biller_id to biller_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('93', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_customer_id to customer_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('94', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_type type_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('95', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_preference to preference_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('96', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_date to date";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
$patchlines['date' ] = "20070523";
patchmaker('97', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name invoice_custom_field1 to custom_field1";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('98', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name invoice_custom_field2 to custom_field2";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('99', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name invoice_custom_field3 to custom_field3";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('100', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name invoice_custom_field4 to custom_field4";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('101', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_note to note ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('102', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_id to id ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
$patchlines['date' ] = "20070523";
patchmaker('103', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_invoice_id to invoice_id ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('104', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_quantity to quantity ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('105', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_product_id to product_id ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('106', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_unit_price to unit_price ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
$patchlines['date' ] = "20070523";
patchmaker('107', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_tax_id to tax_id  ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "20070523";
patchmaker('108', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_tax to tax  ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
$patchlines['date' ] = "20070523";
patchmaker('109', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_tax_amount to tax_amount  ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ";
$patchlines['date' ] = "20070523";
patchmaker('110', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_gross_total to gross_total ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
$patchlines['date' ] = "20070523";
patchmaker('111', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_description to description ";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL";
$patchlines['date' ] = "20070523";
patchmaker('112', $patchlines, $si_patches);

$patchlines['name' ] = "Alter field name inv_it_total to total";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
$patchlines['date' ] = "20070523";
patchmaker('113', $patchlines, $si_patches);

$patchlines['name' ] = "Add logging table";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."log` (
  `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `userid` INT NOT NULL ,
  `sqlquerie` TEXT NOT NULL
  ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20070523";
patchmaker('114', $patchlines, $si_patches);

$patchlines['name' ] = "Add logging system preference";
$patchlines['patch'] = "INSERT INTO `".TB_PREFIX."system_defaults` ( `id` , `name` , `value` ) VALUES (NULL , 'logging', '0');";
$patchlines['date' ] = "20070523";
patchmaker('115', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default biller";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_biller] where name = 'biller'";
$patchlines['date' ] = "20070523";
patchmaker('116', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default customer";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_customer] where name = 'customer'";
$patchlines['date' ] = "20070523";
patchmaker('117', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default tax";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_tax] where name = 'tax'";
$patchlines['date' ] = "20070523";
patchmaker('118', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default invoice reference";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_inv_preference] where name = 'preference'";
$patchlines['date' ] = "20070523";
patchmaker('119', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default number of line items";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_number_line_items] where name = 'line_items'";
$patchlines['date' ] = "20070523";
patchmaker('120', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default invoice template";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = '$defaults[def_inv_template]' where name = 'template'";
$patchlines['date' ] = "20070523";
patchmaker('121', $patchlines, $si_patches);

$patchlines['name' ] = "System defaults conversion patch - set default paymemt type";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_payment_type] where name = 'payment_type'";
$patchlines['date' ] = "20070523";
patchmaker('122', $patchlines, $si_patches);

$patchlines['name' ] = "Add option to delete invoices into the system_defaults table";
$patchlines['patch'] = "INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`) VALUES (NULL, 'delete', 'N');";
$patchlines['date' ] = "200709";
patchmaker('123', $patchlines, $si_patches);

$patchlines['name' ] = "Set default language in new lang system";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = 'en-gb' where name ='language';";
$patchlines['date' ] = "200709";
patchmaker('124', $patchlines, $si_patches);

$patchlines['name' ] = "Change log table that usernames are also possible as id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'";
$patchlines['date' ] = "200709";
patchmaker('125', $patchlines, $si_patches);

$patchlines['name' ] = "Add visible attribute to the products table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';";
$patchlines['date' ] = "200709";
patchmaker('126', $patchlines, $si_patches);

$patchlines['name' ] = "Add last_id to logging table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."log` ADD  `last_id` INT NULL ;";
$patchlines['date' ] = "200709";
patchmaker('127', $patchlines, $si_patches);

$u = (checkTableExists(TB_PREFIX.'users'));
$ud = (checkFieldExists(TB_PREFIX.'users','user_domain'));
$patchlines['name' ] = "Add user table";
// @formatter:off
$patchlines['patch'] = ($u ? ($ud ? "SELECT * FROM ".TB_PREFIX."users;" :
                                        "ALTER TABLE `".TB_PREFIX."users` ADD `user_domain` VARCHAR( 255 ) NOT NULL AFTER `user_group`;") :
                                    "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."users` (`user_id` int(11) NOT NULL auto_increment,
                                                                                      `user_email` varchar(255) NOT NULL,
                                                                                      `user_name` varchar(255) NOT NULL,
                                                                                      `user_group` varchar(255) NOT NULL,
                                                                                      `user_domain` varchar(255) NOT NULL,
                                                                                      `user_password` varchar(255) NOT NULL,
                                                                                      PRIMARY KEY(`user_id`))
                                                                                      ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
// @formatter:on
$patchlines['date' ] = "200709";
patchmaker('128', $patchlines, $si_patches);

$patchlines['name' ] = "Fill user table with default values";
$patchlines['patch'] = "INSERT INTO `".TB_PREFIX."users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`)
                                                  VALUES (NULL, 'demo@simpleinvoices.group', 'demo', '1', '1', MD5('demo'))";
$patchlines['date' ] = "200709";
patchmaker('129', $patchlines, $si_patches);

$ac = (checkTableExists(TB_PREFIX.'auth_challenges'));
$patchlines['name' ] = "Create auth_challenges table";
$patchlines['patch'] =
($ac ? "SELECT * FROM " . TB_PREFIX . "auth_challenges" :
 "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."auth_challenges` (
    `challenges_key` int(11) NOT NULL,
    `challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);");
$patchlines['date' ] = "200709";
patchmaker('130', $patchlines, $si_patches);

$patchlines['name' ] = "Make tax field 3 decimal places";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL";
$patchlines['date' ] = "200709";
patchmaker('131', $patchlines, $si_patches);

$patchlines['name' ] = "Correct Foreign Key Tax ID Field Type in Invoice Items Table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT '0' NOT NULL ;";
$patchlines['date' ] = "20071126";
patchmaker('132', $patchlines, $si_patches);

$patchlines['name' ] = "Correct Foreign Key Invoice ID Field Type in Ac Payments Table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;";
$patchlines['date' ] = "20071126";
patchmaker('133', $patchlines, $si_patches);

$patchlines['name' ] = "Drop non-int compatible default from si_sql_patchmanager";
$patchlines['patch'] =
($is_mysql ? "SELECT 1+1;" :
             "ALTER TABLE ".TB_PREFIX."sql_patchmanager ALTER COLUMN sql_patch_ref DROP DEFAULT;");
$patchlines['date' ] = "20071218";
patchmaker('134', $patchlines, $si_patches);

$patchlines['name' ] = "Change sql_patch_ref type in sql_patchmanager to int";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;" :
             "ALTER TABLE  " . TB_PREFIX . "sql_patchmanager ALTER COLUMN sql_patch_ref TYPE int USING to_number(sql_patch_ref, '999');");
$patchlines['date' ] = "20071218";
patchmaker('135', $patchlines, $si_patches);

$patchlines['name' ] = "Create domain mapping table";
$patchlines['patch'] =
($is_mysql ?
 "CREATE TABLE " . TB_PREFIX . "user_domain
    (`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
     `name` varchar(255) UNIQUE NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci" :
 "CREATE TABLE " . TB_PREFIX . "user_domain (id serial PRIMARY KEY, name text UNIQUE NOT NULL;");
$patchlines['date' ] = "200712";
patchmaker('136', $patchlines, $si_patches);

$patchlines['name' ] = "Insert default domain";
$patchlines['patch'] = "INSERT INTO " . TB_PREFIX . "user_domain (name) VALUES ('default');";
$patchlines['date' ] = "200712";
patchmaker('137', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to payment_types table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `" . TB_PREFIX . "payment_types` ADD `domain_id` INT  NOT NULL AFTER `pt_id` ;" :
             "ALTER TABLE  " . TB_PREFIX . "payment_types  ADD COLUMN domain_id int NOT NULL REFERENCES " . TB_PREFIX . "domain(id);");
$patchlines['date' ] = "200712";
patchmaker('138', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to preferences table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `domain_id` INT  NOT NULL AFTER `pref_id`;" :
             "ALTER TABLE  " . TB_PREFIX . "preferences  ADD COLUMN domain_id int NOT NULL REFERENCES " . TB_PREFIX . "domain(id);");
$patchlines['date' ] = "200712";
patchmaker('139', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to products table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."products` ADD `domain_id` INT  NOT NULL AFTER `id` ;" :
             "ALTER TABLE ".TB_PREFIX."products ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);");
$patchlines['date' ] = "200712";
patchmaker('140', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to billers table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."biller` ADD `domain_id` INT  NOT NULL AFTER `id` ;" :
             "ALTER TABLE ".TB_PREFIX."biller ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);");
$patchlines['date' ] = "200712";
patchmaker('141', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to invoices table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoices` ADD `domain_id` INT NOT NULL AFTER `id` ;" :
             "ALTER TABLE ".TB_PREFIX."invoices ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);");
$patchlines['date' ] = "200712";
patchmaker('142', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to customers table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."customers` ADD `domain_id` INT NOT NULL AFTER `id` ;" :
             "ALTER TABLE ".TB_PREFIX."customers ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);");
$patchlines['date' ] = "200712";
patchmaker('143', $patchlines, $si_patches);

$patchlines['name' ] = "Change group field to user_role_id in users table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."users` CHANGE `user_group` `user_role_id` INT  DEFAULT '1' NOT NULL;" :
             "ALTER TABLE ".TB_PREFIX."users RENAME COLUMN user_group TO user_role_id;");
$patchlines['date' ] = "20080102";
patchmaker('144', $patchlines, $si_patches);

$patchlines['name' ] = "Change domain field to user_domain_id in users table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT '1' NOT NULL;" :
             "ALTER TABLE " . TB_PREFIX . "users RENAME COLUMN user_domain TO user_domain_id;");
$patchlines['date' ] = "20080102";
patchmaker('145', $patchlines, $si_patches);

$patchlines['name' ] = "Drop old auth_challenges table";
$patchlines['patch'] =
($is_mysql ? "DROP TABLE IF EXISTS `".TB_PREFIX."auth_challenges`;" :
             "SELECT 1+1"); // Removed from postgres schema before this patch
$patchlines['date' ] = "20080102";
patchmaker('146', $patchlines, $si_patches);

$patchlines['name' ] = "Create user_role table";
$patchlines['patch'] =
($is_mysql ?
"CREATE TABLE ".TB_PREFIX."user_role (
   `id` int(11) NOT NULL auto_increment  PRIMARY KEY,
   `name` varchar(255) UNIQUE NOT NULL) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" :
"CREATE TABLE ".TB_PREFIX."user_role (id serial PRIMARY KEY,name text UNIQUE NOT NULL);");
$patchlines['date' ] = "20080102";
patchmaker('147', $patchlines, $si_patches);

$patchlines['name' ] = "Insert default user group";
$patchlines['patch'] =
($is_mysql ? "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('administrator');" :
             "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('administrator');");
$patchlines['date' ] = "20080102";
patchmaker('148', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Account_payments Field = ac_amount : change field type and length to decimal" :
             "Widen ac_amount field of account_payments");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;" :
             "ALTER TABLE ".TB_PREFIX."account_payments ALTER COLUMN ac_amount TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('149', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = quantity : change field type and length to decimal" :
             "Widen quantity field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT '0' " :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN quantity TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('150', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = unit_price : change field type and length to decimal" :
             "Widen unit_price field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' " :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN unit_price TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('151', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = tax : change field type and length to decimal" :
             "Widen tax field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' " :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN tax TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('152', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = tax_amount : change field type and length to decimal" :
             "Widen tax_amount field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'" :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN tax_amount TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('153', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = gross_total : change field type and length to decimal" :
             "Widen gross_total field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'" :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN gross_total TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('154', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Invoice_items Field = total : change field type and length to decimal" :
             "Widen total field of invoice_items");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' " :
             "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN total TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('155', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Products Field = unit_price : change field type and length to decimal" :
             "Widen unit_price field of products");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'" :
             "ALTER TABLE ".TB_PREFIX."products ALTER COLUMN unit_price TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('156', $patchlines, $si_patches);

$patchlines['name' ] =
($is_mysql ? "Table = Tax Field = quantity : change field type and length to decimal" :
             "Widen tax_percentage field of tax");
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'" :
             "ALTER TABLE ".TB_PREFIX."tax ALTER COLUMN tax_percentage TYPE numeric(25, 6)");
$patchlines['date' ] = "20080128";
patchmaker('157', $patchlines, $si_patches);

$patchlines['name' ] = "Rename table si_account_payments to si_payment";
$patchlines['patch'] =
($is_mysql ? "RENAME TABLE `".TB_PREFIX."account_payments` TO  `".TB_PREFIX."payment`;" :
             "RENAME TABLE `".TB_PREFIX."account_payments` TO  `".TB_PREFIX."payment`");
$patchlines['date' ] = "20081201";
patchmaker('158', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to payments table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE  `".TB_PREFIX."payment` ADD  `domain_id` INT NOT NULL ;" :
             "ALTER TABLE  `".TB_PREFIX."payment` ADD  `domain_id` INT NOT NULL ");
$patchlines['date' ] = "20081201";
patchmaker('159', $patchlines, $si_patches);

$patchlines['name' ] = "Add domain_id to tax table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE  `".TB_PREFIX."tax` ADD  `domain_id` INT NOT NULL ;" :
             "ALTER TABLE  `".TB_PREFIX."tax` ADD  `domain_id` INT NOT NULL ");
$patchlines['date' ] = "20081201";
patchmaker('160', $patchlines, $si_patches);

$patchlines['name' ] = "Change user table from si_users to si_user";
$patchlines['patch'] =
($is_mysql ? "RENAME TABLE `".TB_PREFIX."users` TO  `".TB_PREFIX."user`;" :
             "RENAME TABLE `".TB_PREFIX."users` TO  `".TB_PREFIX."user`");
$patchlines['date' ] = "20081201";
patchmaker('161', $patchlines, $si_patches);

$patchlines['name' ] = "Add new invoice items tax table";
$patchlines['patch'] =
($is_mysql ?
"CREATE TABLE `".TB_PREFIX."invoice_item_tax` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `invoice_item_id` INT( 11 ) NOT NULL ,
   `tax_id` INT( 11 ) NOT NULL ,
   `tax_type` VARCHAR( 1 ) NOT NULL ,
   `tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
   `tax_amount` DECIMAL( 25, 6 ) NOT NULL
   ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" :
"CREATE TABLE `".TB_PREFIX."invoice_item_tax` (
   `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `invoice_item_id` INT( 11 ) NOT NULL ,
   `tax_id` INT( 11 ) NOT NULL ,
   `tax_type` VARCHAR( 1 ) NOT NULL ,
   `tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
   `tax_amount` DECIMAL( 25, 6 ) NOT NULL
   ) ENGINE = MYISAM ;");
$patchlines['date' ] = "20081212";
patchmaker('162', $patchlines, $si_patches);

    //do conversion
$patchlines['name' ] = "Convert tax info in si_invoice_items to si_invoice_item_tax";
$patchlines['patch'] =
($is_mysql ?
 "INSERT INTO `" . TB_PREFIX . "invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount)
                SELECT id, tax_id, '%', tax, tax_amount FROM `" . TB_PREFIX . "invoice_items`;" :
 "INSERT INTO `" . TB_PREFIX . "invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount)
                SELECT id, tax_id, '%', tax, tax_amount FROM `" . TB_PREFIX . "invoice_items;");
$patchlines['date' ] = "20081212";
patchmaker('163', $patchlines, $si_patches);


$patchlines['name' ] = "Add default tax id into products table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;" :
             "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;");
$patchlines['date' ] = "20081212";
patchmaker('164', $patchlines, $si_patches);

$patchlines['name' ] = "Add default tax id 2 into products table";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;" :
             "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;");
$patchlines['date' ] = "20081212";
patchmaker('165', $patchlines, $si_patches);

$patchlines['name' ] = "Add default tax into product items";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."products` SET default_tax_id = (SELECT value FROM `".TB_PREFIX."system_defaults` WHERE name ='tax');" :
             "UPDATE `".TB_PREFIX."products` SET default_tax_id = (SELECT value FROM `".TB_PREFIX."system_defaults` WHERE name ='tax');");
$patchlines['date' ] = "20081212";
patchmaker('166', $patchlines, $si_patches);

$patchlines['name' ] = "Add default number of taxes per line item into system_defaults";
$patchlines['patch'] =
($is_mysql ? "INSERT INTO `".TB_PREFIX."system_defaults` VALUES ('','tax_per_line_item','1')" :
             "INSERT INTO `".TB_PREFIX."system_defaults` VALUES ('','tax_per_line_item','1')");
$patchlines['date' ] = "20081212";
patchmaker('167', $patchlines, $si_patches);

$patchlines['name' ] = "Add tax type";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;" :
             "ALTER TABLE `".TB_PREFIX."tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;");
$patchlines['date' ] = "20081212";
patchmaker('168', $patchlines, $si_patches);

$patchlines['name' ] = "Set tax type on current taxes to %";
$patchlines['patch'] =
($is_mysql ? "SELECT 1+1;" :
             "UPDATE `".TB_PREFIX."tax` SET `type` = '%';");
$patchlines['date' ] = "20081212";
patchmaker('169', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on tax table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."tax` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."tax` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('170', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on payment table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."payment` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."payment` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('171', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on payment_types table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."payment_types` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."payment_types` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('172', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on preference table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."preferences` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."preferences` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('173', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on products table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."products` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."products` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('174', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on biller table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."biller` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."biller` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('175', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on invoices table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."invoices` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."invoices` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('176', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on customers table to 1";
$patchlines['patch'] =
($is_mysql ? "UPDATE `".TB_PREFIX."customers` SET `domain_id` = '1' ;" :
             "UPDATE `".TB_PREFIX."customers` SET `domain_id` = '1';");
$patchlines['date' ] = "20081229";
patchmaker('177', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_id to si_user.id";
$patchlines['patch'] =
($is_mysql ? "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_id` `id` int(11) ;" :
             "UPDATE `".TB_PREFIX."user` CHANGE `user_id` `id` int(11);");
$patchlines['date' ] = "20081229";
patchmaker('178', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_email to si_user.email";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_email` `email` VARCHAR( 255 );";
$patchlines['date' ] = "20081229";
patchmaker('179', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_name to si_user.name";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_name` `name` VARCHAR( 255 );";
$patchlines['date' ] = "20081229";
patchmaker('180', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_role_id to si_user.role_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_role_id` `role_id` int(11);";
$patchlines['date' ] = "20081229";
patchmaker('181', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_domain_id to si_user.domain_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_domain_id` `domain_id` int(11) ;";
$patchlines['date' ] = "20081229";
patchmaker('182', $patchlines, $si_patches);

$patchlines['name' ] = "Rename si_user.user_password to si_user.password";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_password` `password` VARCHAR( 255 ) ;";
$patchlines['date' ] = "20081229";
patchmaker('183', $patchlines, $si_patches);

$patchlines['name' ] = "Drop name column from si_user table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` DROP `name`  ;";
$patchlines['date' ] = "20081230";
patchmaker('184', $patchlines, $si_patches);

$patchlines['name' ] = "Drop old defaults table";
$patchlines['patch'] = "DROP TABLE `".TB_PREFIX."defaults` ;";
$patchlines['date' ] = "20081230";
patchmaker('185', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on customers table to 1";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."custom_fields` ADD  `domain_id` INT NOT NULL ;";
$patchlines['date' ] = "20081230";
patchmaker('186', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on custom_feilds table to 1";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."custom_fields` SET `domain_id` = '1' ;";
$patchlines['date' ] = "20081230";
patchmaker('187', $patchlines, $si_patches);

$patchlines['name' ] = "Drop tax_id column from si_invoice_items table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax_id`  ;";
$patchlines['date' ] = "20090118";
patchmaker('188', $patchlines, $si_patches);

$patchlines['name' ] = "Drop tax column from si_invoice_items table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax`  ;";
$patchlines['date' ] = "20090118";
patchmaker('189', $patchlines, $si_patches);

$patchlines['name' ] = "Insert user role - user";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('user');";
$patchlines['date' ] = "20090215";
patchmaker('190', $patchlines, $si_patches);

$patchlines['name' ] = "Insert user role - viewer";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('viewer');";
$patchlines['date' ] = "20090215";
patchmaker('191', $patchlines, $si_patches);

$patchlines['name' ] = "Insert user role - customer";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('customer');";
$patchlines['date' ] = "20090215";
patchmaker('192', $patchlines, $si_patches);

$patchlines['name' ] = "Insert user role - biller";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('biller');";
$patchlines['date' ] = "20090215";
patchmaker('193', $patchlines, $si_patches);

$patchlines['name' ] = "User table - auto increment";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;";
$patchlines['date' ] = "20090215";
patchmaker('194', $patchlines, $si_patches);

$patchlines['name' ] = "User table - add enabled field";
$patchlines['patch'] = "ALTER TABLE ".TB_PREFIX."user ADD enabled INT( 1 ) NOT NULL ;";
$patchlines['date' ] = "20090215";
patchmaker('195', $patchlines, $si_patches);

$patchlines['name' ] = "User table - make all existing users enabled";
$patchlines['patch'] = "UPDATE ".TB_PREFIX."user SET enabled = 1 ;";
$patchlines['date' ] = "20090217";
patchmaker('196', $patchlines, $si_patches);

$patchlines['name' ] = "Defaults table - add domain_id and extension_id field";
$patchlines['patch'] =
"ALTER TABLE ".TB_PREFIX."system_defaults
   ADD `domain_id` INT( 5 ) NOT NULL DEFAULT '1',
   ADD `extension_id` INT( 5 ) NOT NULL DEFAULT '1',
   DROP INDEX `name`,
   ADD INDEX `name` ( `name` );";
$patchlines['date' ] = "20090321";
patchmaker('197', $patchlines, $si_patches);

$patchlines['name' ] = "Extension table - create table to hold extension status";
$patchlines['patch'] =
"CREATE TABLE ".TB_PREFIX."extensions (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `domain_id` INT( 11 ) NOT NULL ,
  `name` VARCHAR( 255 ) NOT NULL ,
  `description` VARCHAR( 255 ) NOT NULL ,
  `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '0') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20090322";
patchmaker('198', $patchlines, $si_patches);

$patchlines['name' ] = "Update extensions table";
$patchlines['patch'] =
"INSERT INTO ".TB_PREFIX."extensions (`id`,`domain_id`,`name`,`description`,`enabled`)
                VALUES ('1','1','core','Core part of SimpleInvoices - always enabled','1');";
$patchlines['date' ] = "20090529";
patchmaker('199', $patchlines, $si_patches);

$patchlines['name' ] = "Update extensions table";
$patchlines['patch'] = "UPDATE ".TB_PREFIX."extensions SET `id` = '1' WHERE `name` = 'core' LIMIT 1;";
$patchlines['date' ] = "20090529";
patchmaker('200', $patchlines, $si_patches);

$patchlines['name' ] = "Set domain_id on system defaults table to 1";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET `domain_id` = '1' ;";
$patchlines['date' ] = "20090622";
patchmaker('201', $patchlines, $si_patches);

$patchlines['name' ] = "Set extension_id on system defaults table to 1";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET `extension_id` = '1' ;";
$patchlines['date' ] = "20090622";
patchmaker('202', $patchlines, $si_patches);

$patchlines['name' ] = "Move all old consulting style invoices to itemised";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."invoices` SET `type_id` = '2' where `type_id`=3 ;";
$patchlines['date' ] = "20090704";
patchmaker('203', $patchlines, $si_patches);

$patchlines['name' ] = "Create index table to handle new invoice numbering system";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."index` (
   `id` INT( 11 ) NOT NULL ,
   `node` VARCHAR( 255 ) NOT NULL ,
   `sub_node` VARCHAR( 255 ) NULL ,
   `domain_id` INT( 11 ) NOT NULL
   ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20090818";
patchmaker('204', $patchlines, $si_patches);

$patchlines['name' ] = "Add index_id to invoice table - new invoice numbering";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD `index_id` INT( 11 ) NOT NULL AFTER `id`;";
$patchlines['date' ] = "20090818";
patchmaker('205', $patchlines, $si_patches);

$patchlines['name' ] = "Add status and locale to preferences";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."preferences` ADD `status` INT( 1 ) NOT NULL ,
   ADD `locale` VARCHAR( 255 ) NULL ,
   ADD `language` VARCHAR( 255 ) NULL ;";
$patchlines['date' ] = "20090826";
patchmaker('206', $patchlines, $si_patches);

$patchlines['name' ] = "Populate the status, locale, and language fields in preferences table";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."preferences` SET status = '1', locale = '".$config->local->locale."', language = '$language' ;";
$patchlines['date' ] = "20090826";
patchmaker('207', $patchlines, $si_patches);

$patchlines['name' ] = "Populate the status, locale, and language fields in preferences table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `index_group` INT( 11 ) NOT NULL ;";
$patchlines['date' ] = "20090826";
patchmaker('208', $patchlines, $si_patches);

$defaults = getSystemDefaults();
$patchlines['name' ] = "Populate the status, locale, and language fields in preferences table";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."preferences` SET index_group = '".$defaults['preference']."' ;";
$patchlines['date' ] = "20090826";
patchmaker('209', $patchlines, $si_patches);

$patchlines['name' ] = "Create composite primary key for invoice table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`,`id` );";
$patchlines['date' ] = "20090826";
patchmaker('210', $patchlines, $si_patches);

$patchlines['name' ] = "Reset auto-increment for invoice table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` AUTO_INCREMENT = 1;";
$patchlines['date' ] = "20090826";
patchmaker('211', $patchlines, $si_patches);

$patchlines['name' ] = "Copy invoice.id into invoice.index_id";
$patchlines['patch'] = "update `".TB_PREFIX."invoices` set index_id = id;";
$patchlines['date' ] = "20090902";
patchmaker('212', $patchlines, $si_patches);

$max_invoice = Invoice::maxIndexId();
$patchlines['name' ] = "Update the index table with max invoice id - if required";
$patchlines['patch'] =
($max_invoice > "0" ?
 "INSERT INTO `" . TB_PREFIX . "index` (id, node, sub_node, domain_id)
       VALUES (".$max_invoice.", 'invoice', '".$defaults['preference']."','1');" :
 "SELECT 1+1;");
$patchlines['date' ] = "20090902";
patchmaker('213', $patchlines, $si_patches);
unset($defaults);
unset($max_invoice);

$patchlines['name' ] = "Add sub_node_2 to si_index table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."index` ADD  `sub_node_2` VARCHAR( 255 ) NULL AFTER  `sub_node`";
$patchlines['date' ] = "20090912";
patchmaker('214', $patchlines, $si_patches);

$patchlines['name' ] = "si_invoices - add composite primary key - patch removed";
            //$si_patches['215']['patch'] = "ALTER TABLE  `".TB_PREFIX."index` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            //$si_patches['215']['patch'] = "ALTER TABLE  `".TB_PREFIX."index` ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['patch'] = "SELECT 1+1;";
$patchlines['date' ] = "20090912";
patchmaker('215', $patchlines, $si_patches);

$patchlines['name' ] = "si_payment - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['date' ] = "20090912";
patchmaker('216', $patchlines, $si_patches);

$patchlines['name' ] = "si_payment_types - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."payment_types` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pt_id`)";
$patchlines['date' ] = "20090912";
patchmaker('217', $patchlines, $si_patches);

$patchlines['name' ] = "si_preferences - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pref_id`)";
$patchlines['date' ] = "20090912";
patchmaker('218', $patchlines, $si_patches);

$patchlines['name' ] = "si_products - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."products` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['date' ] = "20090912";
patchmaker('219', $patchlines, $si_patches);

$patchlines['name' ] = "si_tax - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."tax` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `tax_id`)";
$patchlines['date' ] = "20090912";
patchmaker('220', $patchlines, $si_patches);

$patchlines['name' ] = "si_user - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."user` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['date' ] = "20090912";
patchmaker('221', $patchlines, $si_patches);

$patchlines['name' ] = "si_biller - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['date' ] = "20100209";
patchmaker('222', $patchlines, $si_patches);

$patchlines['name' ] = "si_customers - add composite primary key";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
$patchlines['date' ] = "20100209";
patchmaker('223', $patchlines, $si_patches);

$patchlines['name' ] = "Add paypal business name";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_business_name` VARCHAR( 255 ) NULL AFTER  `footer`";
$patchlines['date' ] = "20100209";
patchmaker('224', $patchlines, $si_patches);

$patchlines['name' ] = "Add paypal notify url";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_notify_url` VARCHAR( 255 ) NULL AFTER  `paypal_business_name`";
$patchlines['date' ] = "20100209";
patchmaker('225', $patchlines, $si_patches);

$patchlines['name' ] = "Define currency in preferences";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_code` VARCHAR( 25 ) NULL ;";
$patchlines['date' ] = "20100209";
patchmaker('226', $patchlines, $si_patches);

$patchlines['name' ] = "Create cron table to handle recurrence";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."cron` (
    `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
    `domain_id` INT( 11 ) NOT NULL ,
    `invoice_id` INT( 11 ) NOT NULL ,
    `start_date` DATE NOT NULL ,
    `end_date` VARCHAR( 10 ) NULL ,
    `recurrence` INT( 11 ) NOT NULL ,
    `recurrence_type` VARCHAR( 11 ) NOT NULL ,
    `email_biller` INT( 1 ) NULL ,
    `email_customer` INT( 1 ) NULL ,
    PRIMARY KEY (`domain_id` ,`id`)
   ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20100215";
patchmaker('227', $patchlines, $si_patches);

$patchlines['name' ] = "Create cron_log table to handle record when cron was run";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."cron_log` (
    `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
    `domain_id` INT( 11 ) NOT NULL ,
    `run_date` DATE NOT NULL ,
    PRIMARY KEY (  `domain_id` ,  `id` )
    ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20100216";
patchmaker('228', $patchlines, $si_patches);

$patchlines['name' ] = "preferences - add online payment type";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `include_online_payment` VARCHAR( 255 ) NULL ;";
$patchlines['date' ] = "20100209";
patchmaker('229', $patchlines, $si_patches);

$patchlines['name' ] = "Add paypal notify url";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_return_url` VARCHAR( 255 ) NULL AFTER  `paypal_notify_url`";
$patchlines['date' ] = "20100223";
patchmaker('230', $patchlines, $si_patches);

$patchlines['name' ] = "Add paypal payment id into payment table";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` ADD `online_payment_id` VARCHAR( 255 ) NULL AFTER  `domain_id`";
$patchlines['date' ] = "20100226";
patchmaker('231', $patchlines, $si_patches);

$patchlines['name' ] = "Define currency display in preferences";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_position` VARCHAR( 25 ) NULL ;";
$patchlines['date' ] = "20100227";
patchmaker('232', $patchlines, $si_patches);

$patchlines['name' ] = "Add system default to control invoice number by biller -- dummy patch -- this sql was removed";
$patchlines['patch'] = "SELECT 1+1;";
$patchlines['date' ] = "20100302";
patchmaker('233', $patchlines, $si_patches);

$patchlines['name' ] = "Add eway customer ID";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `eway_customer_id` VARCHAR( 255 ) NULL AFTER `paypal_return_url`;";
$patchlines['date' ] = "20100315";
patchmaker('234', $patchlines, $si_patches);

$patchlines['name' ] = "Add eway card holder name";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_holder_name` VARCHAR( 255 ) NULL AFTER `email`;";
$patchlines['date' ] = "20100315";
patchmaker('235', $patchlines, $si_patches);

$patchlines['name' ] = "Add eway card number";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_number` VARCHAR( 255 ) NULL AFTER `credit_card_holder_name`;";
$patchlines['date' ] = "20100315";
patchmaker('236', $patchlines, $si_patches);

$patchlines['name' ] = "Add eway card expiry month";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_month` VARCHAR( 02 ) NULL AFTER `credit_card_number`;";
$patchlines['date' ] = "20100315";
patchmaker('237', $patchlines, $si_patches);

$patchlines['name' ] = "Add eway card expirt year";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_year` VARCHAR( 04 ) NULL AFTER `credit_card_expiry_month` ;";
$patchlines['date' ] = "20100315";
patchmaker('238', $patchlines, $si_patches);

$patchlines['name' ] = "cronlog - add invoice id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."cron_log` ADD `cron_id` VARCHAR( 25 ) NULL AFTER `domain_id` ;";
$patchlines['date' ] = "20100321";
patchmaker('239', $patchlines, $si_patches);

$patchlines['name' ] = "si_system_defaults - add composite primary key";
$patchlines['patch'] = "
  ALTER TABLE  `".TB_PREFIX."system_defaults` ADD `new_id` INT( 11 ) NOT NULL FIRST;
  UPDATE `".TB_PREFIX."system_defaults` SET new_id = id;
  ALTER TABLE  `".TB_PREFIX."system_defaults` DROP  `id` ;
  ALTER TABLE  `".TB_PREFIX."system_defaults` DROP INDEX `name` ;
  ALTER TABLE  `".TB_PREFIX."system_defaults` CHANGE  `new_id`  `id` INT( 11 ) NOT NULL;
  ALTER TABLE  `".TB_PREFIX."system_defaults` ADD PRIMARY KEY(`domain_id`,`id` );";
$patchlines['date' ] = "20100305";
patchmaker('240', $patchlines, $si_patches);

$patchlines['name' ] = "si_system_defaults - add composite primary key";
$patchlines['patch'] = "INSERT INTO `".TB_PREFIX."system_defaults` VALUES ('','inventory','0','1','1');";
$patchlines['date' ] = "20100409";
patchmaker('241', $patchlines, $si_patches);

$patchlines['name' ] = "Add cost to products table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `cost` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' AFTER `default_tax_id_2`;";
$patchlines['date' ] = "20100409";
patchmaker('242', $patchlines, $si_patches);

$patchlines['name' ] = "Add reorder_level to products table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `reorder_level` INT( 11 ) NULL AFTER `cost` ;";
$patchlines['date' ] = "20100409";
patchmaker('243', $patchlines, $si_patches);

$patchlines['name' ] = "Create inventory table";
$patchlines['patch'] =
"CREATE TABLE  `".TB_PREFIX."inventory` (
    `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
    `domain_id` INT( 11 ) NOT NULL ,
    `product_id` INT( 11 ) NOT NULL ,
    `quantity` DECIMAL( 25, 6 ) NOT NULL ,
    `cost` DECIMAL( 25, 6 ) NULL ,
    `date` DATE NOT NULL ,
    `note` TEXT NULL ,
    PRIMARY KEY ( `domain_id`, `id` )
   ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20100409";
patchmaker('244', $patchlines, $si_patches);

$patchlines['name' ] = "Preferences - make locale null field";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `locale`  `locale` VARCHAR( 255 ) NULL ;";
$patchlines['date' ] = "20100419";
patchmaker('245', $patchlines, $si_patches);

$patchlines['name' ] = "Preferences - make language a null field";
$patchlines['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `language`  `language` VARCHAR( 255 ) NULL;";
$patchlines['date' ] = "20100419";
patchmaker('246', $patchlines, $si_patches);

$patchlines['name' ] = "Custom fields - make sure domain_id is 1";
$patchlines['patch'] = "update ".TB_PREFIX."custom_fields set domain_id = '1';";
$patchlines['date' ] = "20100419";
patchmaker('247', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`domain_id`);";
$patchlines['date' ] = "20100419";
patchmaker('248', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`biller_id`) ;";
$patchlines['date' ] = "20100419";
patchmaker('249', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`customer_id`);";
$patchlines['date' ] = "20100419";
patchmaker('250', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`domain_id`);";
$patchlines['date' ] = "20100419";
patchmaker('251', $patchlines, $si_patches);

$patchlines['name' ] = "Language - reset to en_US - due to folder renaming";
$patchlines['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value ='en_US' where name='language';";
$patchlines['date' ] = "20100419";
patchmaker('252', $patchlines, $si_patches);

$patchlines['name' ] = "Add PaymentsGateway API ID field";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD  `paymentsgateway_api_id` VARCHAR( 255 ) NULL AFTER `eway_customer_id`;";
$patchlines['date' ] = "20110918";
patchmaker('253', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;";
$patchlines['date' ] = "20130313";
patchmaker('254', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."products_attributes` (
    `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `name` VARCHAR( 255 ) NOT NULL,
    `type_id` VARCHAR( 255 ) NOT NULL
    ) ENGINE = MYISAM ;";
$patchlines['date' ] = "20130313";
patchmaker('255', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "INSERT INTO `". TB_PREFIX ."products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, 'Size','1'), (NULL,'Colour','1');";
$patchlines['date' ] = "20130313";
patchmaker('256', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] =
"CREATE TABLE `". TB_PREFIX ."products_values` (
    `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `attribute_id` INT( 11 ) NOT NULL ,
    `value` VARCHAR( 255 ) NOT NULL
   ) ENGINE = MYISAM ;";
$patchlines['date' ] = "20130313";
patchmaker('257', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "INSERT INTO `". TB_PREFIX ."products_values` (`id`, `attribute_id`,`value`) VALUES (NULL,'1', 'S'),  (NULL,'1', 'M'), (NULL,'1', 'L'),  (NULL,'2', 'Red'),  (NULL,'2', 'White');";
$patchlines['date' ] = "20130313";
patchmaker('258', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "SELECT 1+1;";  //remove matrix code
$patchlines['date' ] = "20130313";
patchmaker('259', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "SELECT 1+1;"; //remove matrix code
$patchlines['date' ] = "20130313";
patchmaker('260', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "SELECT 1+1;"; //remove matrix code
$patchlines['date' ] = "20130313";
patchmaker('261', $patchlines, $si_patches);

$patchlines['name' ] = "Add product attributes system preference";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'product_attributes', '0', '1', '1');";
$patchlines['date' ] = "20130313";
patchmaker('262', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - update line items table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `attribute` VARCHAR( 255 ) NULL ;";
$patchlines['date' ] = "20130313";
patchmaker('263', $patchlines, $si_patches);

$patchlines['name' ] = "Product - use notes as default line item description";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;";
$patchlines['date' ] = "20130314";
patchmaker('264', $patchlines, $si_patches);

$patchlines['name' ] = "Product - expand/show line item description";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `show_description` VARCHAR( 1 ) NULL ;";
$patchlines['date' ] = "20130314";
patchmaker('265', $patchlines, $si_patches);

$patchlines['name' ] = "Product - expand/show line item description";
$patchlines['patch'] =
"CREATE TABLE `".TB_PREFIX."products_attribute_type` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$patchlines['date' ] = "20130322";
patchmaker('266', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - insert attribute types";
$patchlines['patch'] = "INSERT INTO `". TB_PREFIX ."products_attribute_type` (`id`, `name`) VALUES (NULL,'list'),  (NULL,'decimal'), (NULL,'free');";
$patchlines['date' ] = "20130325";
patchmaker('267', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - insert attribute types";
$patchlines['patch'] =
"ALTER TABLE  `". TB_PREFIX ."products_attributes`
   ADD `enabled` VARCHAR( 1 ) NULL DEFAULT  '1',
   ADD `visible` VARCHAR( 1 ) NULL DEFAULT  '1';";
$patchlines['date' ] = "20130327";
patchmaker('268', $patchlines, $si_patches);

$patchlines['name' ] = "Product Matrix - insert attribute types";
$patchlines['patch'] = "ALTER TABLE  `". TB_PREFIX ."products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  '1';";
$patchlines['date' ] = "20130327";
patchmaker('269', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`ac_inv_id`);";
$patchlines['date' ] = "20100419";
patchmaker('270', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`ac_amount`);";
$patchlines['date' ] = "20100419";
patchmaker('271', $patchlines, $si_patches);

$patchlines['name' ] = "Add product attributes system preference";
$patchlines['patch'] = "INSERT INTO ".TB_PREFIX."system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'large_dataset', '0', '1', '1');";
$patchlines['date' ] = "20130313";
patchmaker('272', $patchlines, $si_patches);

$patchlines['name' ] = "Make SimpleInvoices faster - add index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` ADD INDEX(`invoice_id`);";
$patchlines['date' ] = "20130927";
patchmaker('273', $patchlines, $si_patches);

$patchlines['name' ] = "Only One Default Variable name per domain allowed - add unique index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."system_defaults` ADD UNIQUE INDEX `UnqNameInDomain` (`domain_id`, `name`);";
$patchlines['date' ] = "20131007";
patchmaker('274', $patchlines, $si_patches);

$patchlines['name' ] = "Make EMail / Password pair unique per domain - add unique index";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `password` `password` VARCHAR(64) NULL, ADD UNIQUE INDEX `UnqEMailPwd` (`email`, `password`);";
$patchlines['date' ] = "20131007";
patchmaker('275', $patchlines, $si_patches);

$patchlines['name' ] = "Each invoice Item must belong to a specific Invoice with a specific domain_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `invoice_id`;";
$patchlines['date' ] = "20131008";
patchmaker('276', $patchlines, $si_patches);

$patchlines['name' ] = "Add Index for Quick Invoice Item Search for a domain_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` ADD INDEX `DomainInv` (`invoice_id`, `domain_id`);";
$patchlines['date' ] = "20131008";
patchmaker('277', $patchlines, $si_patches);

$patchlines['name' ] = "Each Invoice Item can have only one instance of each tax";
//    Patch disabled for old installs with inadequate database integrity
//    $si_patches['278']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_item_tax` ADD UNIQUE INDEX `UnqInvTax` (`invoice_item_id`, `tax_id`);";
$patchlines['patch'] = "SELECT 1+1;";
$patchlines['date' ] = "20131008";
patchmaker('278', $patchlines, $si_patches);

$patchlines['name' ] = "Drop unused superceeded table si_product_matrix if present";
$patchlines['patch'] = "DROP TABLE IF EXISTS `".TB_PREFIX."products_matrix`;";
$patchlines['date' ] = "20131009";
patchmaker('279', $patchlines, $si_patches);

$patchlines['name' ] = "Each domain has their own extension instances";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."extensions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);";
$patchlines['date' ] = "20131011";
patchmaker('280', $patchlines, $si_patches);

$patchlines['name' ] = "Each domain has their own custom_field id sets";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."custom_fields` DROP PRIMARY KEY, ADD PRIMARY KEY (`cf_id`, `domain_id`);";
$patchlines['date' ] = "20131011";
patchmaker('281', $patchlines, $si_patches);

$patchlines['name' ] = "Each domain has their own logs";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."log` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `id`, DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);";
$patchlines['date' ] = "20131011";
patchmaker('282', $patchlines, $si_patches);

$patchlines['name' ] = "Match field type with foreign key field si_user.id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."log` CHANGE `userid` `userid` INT NOT NULL DEFAULT '1';";
$patchlines['date' ] = "20131012";
patchmaker('283', $patchlines, $si_patches);

$patchlines['name' ] = "Make si_index sub_node and sub_node_2 fields as integer";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."index` CHANGE `node` `node` VARCHAR(64) NOT NULL, CHANGE `sub_node` `sub_node` INT NOT NULL, CHANGE `sub_node_2` `sub_node_2` INT NOT NULL;";
$patchlines['date' ] = "20131016";
patchmaker('284', $patchlines, $si_patches);

$patchlines['name' ] = "Fix compound Primary Key for si_index table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."index` ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);";
$patchlines['date' ] = "20131016";
patchmaker('285', $patchlines, $si_patches);

$patchlines['name' ] = "Speedup lookups from si_index table with indices in si_invoices table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD UNIQUE INDEX `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`), ADD INDEX `IdxDI` (`index_id`, `preference_id`, `domain_id`);";
$patchlines['date' ] = "20131016";
patchmaker('286', $patchlines, $si_patches);

$patchlines['name' ] = "Populate additional user roles like domain_administrator";
$patchlines['patch'] = "INSERT IGNORE INTO `".TB_PREFIX."user_role` (`name`) VALUES ('domain_administrator'), ('customer'), ('biller');";
$patchlines['date' ] = "20131017";
patchmaker('287', $patchlines, $si_patches);

$patchlines['name' ] = "Fully relational now - do away with the si_index table";
// Omitted for now till all users check their relation data integrity
//    $si_patches['288']['patch'] = "DROP TABLE IF EXISTS `".TB_PREFIX."index`;";
$patchlines['patch'] = "SELECT 1+1;";
$patchlines['date' ] = "20131017";
patchmaker('288', $patchlines, $si_patches);

$patchlines['name' ] = "Each cron_id can run a maximum of only once a day for each domain_id";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."cron_log` ADD UNIQUE INDEX `CronIdUnq` (`domain_id`, `cron_id`, `run_date`);";
$patchlines['date' ] = "20131108";
patchmaker('289', $patchlines, $si_patches);

$patchlines['name' ] = "Set all Flag fields to tinyint(1) and other 1 byte fields to char";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."biller` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."customers` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."extensions` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 0 NOT NULL;
 ALTER TABLE `".TB_PREFIX."payment_types` CHANGE `pt_enabled` `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."preferences`
    CHANGE `pref_enabled` `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
    CHANGE `status` `status` TINYINT(1) NOT NULL;
 ALTER TABLE `".TB_PREFIX."products` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
    CHANGE `notes_as_description` `notes_as_description` CHAR(1) NULL,
    CHANGE `show_description` `show_description` CHAR(1) NULL;
 ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_enabled` `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."cron`
    CHANGE `email_biller` `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,
    CHANGE `email_customer` `email_customer` TINYINT(1) DEFAULT 0 NOT NULL;
 ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_display` `cf_display` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."invoice_item_tax` CHANGE `tax_type` `tax_type` CHAR(1) DEFAULT '%' NOT NULL;
 ALTER TABLE `".TB_PREFIX."tax` CHANGE `type` `type` CHAR(1) DEFAULT '%' NOT NULL;
 ALTER TABLE `".TB_PREFIX."products_attributes`
    CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
    CHANGE `visible` `visible` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."products_VALUES` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
 ALTER TABLE `".TB_PREFIX."user` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;";
$patchlines['date' ] = "20131109";
patchmaker('290', $patchlines, $si_patches);

$patchlines['name' ] = "Clipped size of zip_code and credit_card_number fields to realistic values";
$patchlines['patch'] =
"ALTER TABLE `".TB_PREFIX."customers`
    CHANGE `zip_code` `zip_code` VARCHAR(20) NULL,
    CHANGE `credit_card_number` `credit_card_number` VARCHAR(20) NULL;
 ALTER TABLE `".TB_PREFIX."biller` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL;";
$patchlines['date' ] = "20131111";
patchmaker('291', $patchlines, $si_patches);

$patchlines['name' ] = "Added Customer/Biller User ID column to user table";
$patchlines['patch'] = "ALTER TABLE `".TB_PREFIX."user` ADD COLUMN `user_id` INT  DEFAULT 0 NOT NULL AFTER `enabled`;";
$patchlines['date' ] = "20140103";
patchmaker('292', $patchlines, $si_patches);

