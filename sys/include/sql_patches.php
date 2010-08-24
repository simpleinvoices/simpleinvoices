<?php

	$patch['0']['name'] = "Start";
	$patch['0']['patch'] = "SHOW TABLES LIKE 'test'";
	$patch['0']['date'] = "20060514";

	$patch['1']['name'] = "Create sql_patchmanger table";
	$patch['1']['patch'] = "CREATE TABLE ".TB_PREFIX."sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL, sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL, sql_statement TEXT NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	$patch['1']['date'] = "20060514";
	
	$patch['2']['name'] = "Update invoice no details to have a default currency sign";
	$patch['2']['patch'] = "UPDATE ".TB_PREFIX."preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1";
	$patch['2']['date'] = "20060514";
	
	$patch['3']['name'] = "Add a row into the defaults table to handle the default number of line items";
	$patch['3']['patch'] = "ALTER TABLE ".TB_PREFIX."defaults ADD def_number_line_items INT( 25 ) NOT NULL";
	$patch['3']['date'] = "20060514";
	
	$patch['4']['name'] = "Set the default number of line items to 5";
	$patch['4']['patch'] = "UPDATE ".TB_PREFIX."defaults SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1";
	$patch['4']['date'] = "20060514";
	
	$patch['5']['name'] = "Add logo and invoice footer support to biller";
	$patch['5']['patch'] = "ALTER TABLE ".TB_PREFIX."biller ADD b_co_logo VARCHAR( 50 ), ADD b_co_footer TEXT";
	$patch['5']['date'] = "20060514";
	
	$patch['6']['name'] = "Add default invoice template option";
	$patch['6']['patch'] = "ALTER TABLE ".TB_PREFIX."defaults ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL";
	$patch['6']['date'] = "20060514";
	
	$patch['7']['name'] = "Edit tax description field lenght to 50";
	$patch['7']['patch'] = "ALTER TABLE ".TB_PREFIX."tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL";
	$patch['7']['date'] = "20060526";
	
	$patch['8']['name'] = "Edit default invoice template field lenght to 50";
	$patch['8']['patch'] = "ALTER TABLE ".TB_PREFIX."defaults CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL";
	$patch['8']['date'] = "20060526";

	$patch['9']['name'] = "Add consulting style invoice";
	$patch['9']['patch'] = "INSERT INTO ".TB_PREFIX."invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')";
	$patch['9']['date'] = "20060531";

	$patch['10']['name'] = "Add enabled to biller";
	$patch['10']['patch'] = "ALTER TABLE ".TB_PREFIX."biller ADD b_enabled varchar(1) NOT NULL default '1'";
	$patch['10']['date'] = "20060815";

	$patch['11']['name'] = "Add enabled to customers";
	$patch['11']['patch'] = "ALTER TABLE ".TB_PREFIX."customers ADD c_enabled varchar(1) NOT NULL default '1'";
	$patch['11']['date'] = "20060815";

	$patch['12']['name'] = "Add enabled to preferences";
	$patch['12']['patch'] = "ALTER TABLE ".TB_PREFIX."preferences ADD pref_enabled varchar(1) NOT NULL default '1'";
	$patch['12']['date'] = "20060815";

	$patch['13']['name'] = "Add enabled to products";
	$patch['13']['patch'] = "ALTER TABLE ".TB_PREFIX."products ADD prod_enabled varchar(1) NOT NULL default '1'";
	$patch['13']['date'] = "20060815";

	$patch['14']['name'] = "Add enabled to products";
	$patch['14']['patch'] = "ALTER TABLE ".TB_PREFIX."tax ADD tax_enabled varchar(1) NOT NULL default '1'";
	$patch['14']['date'] = "20060815";

	$patch['15']['name'] = "Add tax_id into invoice_items table";
	$patch['15']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0'  AFTER inv_it_unit_price";
	$patch['15']['date'] = "20060815";

	$patch['16']['name'] = "Add Payments table";
	$patch['16']['patch'] = "CREATE TABLE `".TB_PREFIX."account_payments` (
`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ac_inv_id` VARCHAR( 10 ) NOT NULL ,
`ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
`ac_notes` TEXT NOT NULL ,
`ac_date` DATETIME NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
	$patch['16']['date'] = "20060827";

	$patch['17']['name'] = "Adjust data type of quantity field";
	$patch['17']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'";
	$patch['17']['date'] = "20060827";

	$patch['18']['name'] = "Create Payment Types table";
	$patch['18']['patch'] = "CREATE TABLE `".TB_PREFIX."payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	$patch['18']['date'] = "20060909";
	
	$patch['19']['name'] = "Add info into the Payment Type table";
	$patch['19']['patch'] = "INSERT INTO `".TB_PREFIX."payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')";
	$patch['19']['date'] = "20060909";

	$patch['20']['name'] = "Adjust accounts payments table to add a type field";
	$patch['20']['patch'] = "ALTER TABLE `".TB_PREFIX."account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'";
	$patch['20']['date'] = "20060909";
	
	$patch['21']['name'] = "Adjust the defautls table to add a payment type field";
	$patch['21']['patch'] = "ALTER TABLE `".TB_PREFIX."defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'";
	$patch['21']['date'] = "20060909";

	$patch['22']['name'] = "Add note field to customer";
	$patch['22']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_notes` TEXT NULL AFTER `c_email`";
	$patch['22']['date'] = "20061026";

	$patch['23']['name'] = "Add note field to Biller";
	$patch['23']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`";
	$patch['23']['date'] = "20061026";

	$patch['24']['name'] = "Add note field to Products";
	$patch['24']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`";
	$patch['24']['date'] = "20061026";

/*Custom fields patches - start */
	$patch['25']['name'] = "Add street address 2 to customers";
	$patch['25']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ";
	$patch['25']['date'] = "20061211";
	
	$patch['26']['name'] = "Add custom fields to customers";
	$patch['26']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;
";
	$patch['26']['date'] = "20061211";

	$patch['27']['name'] = "Add mobile phone to customers";
	$patch['27']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`";
	$patch['27']['date'] = "20061211";

	$patch['28']['name'] = "Add street address 2 to billers";
	$patch['28']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ";
	$patch['28']['date'] = "20061211";

	$patch['29']['name'] = "Add custom fields to billers";
	$patch['29']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;
";
	$patch['29']['date'] = "20061211";

	$patch['30']['name'] = "Creating the custom fields table";
	$patch['30']['patch'] = "CREATE TABLE `".TB_PREFIX."custom_fields` (
`cf_id` INT NOT NULL AUTO_INCREMENT ,
`cf_custom_field` VARCHAR( 50 ) NOT NULL ,
`cf_custom_label` VARCHAR( 50 ) ,
`cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
PRIMARY KEY ( `cf_id` )	) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
	$patch['30']['date'] = "20061211";

	$patch['31']['name'] = "Adding data to the custom fields table";
	$patch['31']['patch'] = "INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` ) VALUES 
(NULL, 'biller_cf1', NULL , '0'),
(NULL, 'biller_cf2', NULL , '0'),
(NULL, 'biller_cf3', NULL , '0'),
(NULL, 'biller_cf4', NULL , '0'),
(NULL, 'customer_cf1', NULL , '0'),
(NULL, 'customer_cf2', NULL , '0'),
(NULL, 'customer_cf3', NULL , '0'),
(NULL, 'customer_cf4', NULL , '0'),
(NULL, 'product_cf1', NULL , '0'),
(NULL, 'product_cf2', NULL , '0'),
(NULL, 'product_cf3', NULL , '0'),
(NULL, 'product_cf4', NULL , '0');
";
	$patch['31']['date'] = "20061211";

	$patch['32']['name'] = "Adding custom fields to products";
	$patch['32']['patch'] = "ALTER TABLE `".TB_PREFIX."products` 
ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price` ,
ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1` ,
ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2` ,
ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3` ;
";
	$patch['32']['date'] = "20061211";

	$patch['33']['name'] = "Alter product custom field 4";
	$patch['33']['patch'] = "UPDATE `".TB_PREFIX."custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `".TB_PREFIX."custom_fields`.`cf_id` =12 LIMIT 1 ;";
	$patch['33']['date'] = "20061214";

	$patch['34']['name'] = "Reset invoice template to default refer Issue 70";
	$patch['34']['patch'] = "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;";
	$patch['34']['date'] = "20070125";

	$patch['35']['name'] = "Adding data to the custom fields table for invoices";
	$patch['35']['patch'] = "INSERT INTO `".TB_PREFIX."custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` ) VALUES 
(NULL, 'invoice_cf1', NULL , '0'),
(NULL, 'invoice_cf2', NULL , '0'),
(NULL, 'invoice_cf3', NULL , '0'),
(NULL, 'invoice_cf4', NULL , '0');
";
	$patch['35']['date'] = "20070204";

	$patch['36']['name'] = "Adding custom fields to the invoices table";
	$patch['36']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` 
ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date` ,
ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1` ,
ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2` ,
ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3` ;
";
	$patch['36']['date'] = "20070204";

	$patch['37']['name'] = "Reset invoice template to default due to new invoice template system";
	$patch['37']['patch'] = "UPDATE `".TB_PREFIX."defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;";
	$patch['37']['date'] = "20070523";
        
	$patch['38']['name'] = "Alter custom field table - field length now 255 for field name";
	$patch['38']['patch'] = "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )";
	$patch['38']['date'] = "20070523";
        
	$patch['39']['name'] = "Alter custom field table - field length now 255 for field label";
	$patch['39']['patch'] = "ALTER TABLE `".TB_PREFIX."custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )";
	$patch['39']['date'] = "20070523";


	$patch['40']['name'] = "Alter field name in sql_patchmanager";
	$patch['40']['patch'] = "ALTER TABLE `".TB_PREFIX."sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL";
	$patch['40']['date'] = "20070523";
	
	$patch['41']['name'] = "Alter field name in ".TB_PREFIX."account_payments";
	$patch['41']['patch'] = "ALTER TABLE  `".TB_PREFIX."account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['41']['date'] = "20070523";
                
	$patch['42']['name'] = "Alter field name b_name to name";
	$patch['42']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;";
	$patch['42']['date'] = "20070523";

	$patch['43']['name'] = "Alter field name b_id to id";
	$patch['43']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['43']['date'] = "20070523";

	$patch['44']['name'] = "Alter field name b_street_address to street_address";
	$patch['44']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['44']['date'] = "20070523";

	$patch['45']['name'] = "Alter field name b_street_address2 to street_address2";
	$patch['45']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['45']['date'] = "20070523";

	$patch['46']['name'] = "Alter field name b_city to city";
	$patch['46']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['46']['date'] = "20070523";
	
	$patch['47']['name'] = "Alter field name b_state to state";
	$patch['47']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['47']['date'] = "20070523";

	$patch['48']['name'] = "Alter field name b_zip_code to zip_code";
	$patch['48']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['48']['date'] = "20070523";

	$patch['49']['name'] = "Alter field name b_country to country";
	$patch['49']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['49']['date'] = "20070523";

	$patch['50']['name'] = "Alter field name b_phone to phone";
	$patch['50']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['50']['date'] = "20070523";

	$patch['51']['name'] = "Alter field name b_mobile_phone to mobile_phone";
	$patch['51']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['51']['date'] = "20070523";

	$patch['52']['name'] = "Alter field name b_fax to fax";
	$patch['52']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['52']['date'] = "20070523";

	$patch['53']['name'] = "Alter field name b_email to email";
	$patch['53']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['53']['date'] = "20070523";

	$patch['54']['name'] = "Alter field name b_co_logo to logo";
	$patch['54']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['54']['date'] = "20070523";

	$patch['55']['name'] = "Alter field name b_co_footer to footer";
	$patch['55']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ";
	$patch['55']['date'] = "20070523";

	$patch['56']['name'] = "Alter field name b_notes to notes";
	$patch['56']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ";
	$patch['56']['date'] = "20070523";

	$patch['57']['name'] = "Alter field name b_enabled to enabled";
	$patch['57']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['57']['date'] = "20070523";
	
	$patch['58']['name'] = "Alter field name b_custom_field1 to custom_field1";
	$patch['58']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['58']['date'] = "20070523";

	$patch['59']['name'] = "Alter field name b_custom_field2 to custom_field2";
	$patch['59']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['59']['date'] = "20070523";

	$patch['60']['name'] = "Alter field name b_custom_field3 to custom_field3";
	$patch['60']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['60']['date'] = "20070523";

	$patch['61']['name'] = "Alter field name b_custom_field4 to custom_field4";
	$patch['61']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['61']['date'] = "20070523";
	
	$patch['62']['name'] = "Introduce system_defaults table";
	$patch['62']['patch'] = "CREATE TABLE `".TB_PREFIX."system_defaults` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(30) NOT NULL,
`value` varchar(30) NOT NULL,
PRIMARY KEY  (`id`),
UNIQUE KEY `name` (`name`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
";
	$patch['62']['date'] = "20070523";

	$patch['63']['name'] = "Insert date into the system_defaults table";
	$patch['63']['patch'] = "
INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`) VALUES 
(1, 'biller', '4'),
(2, 'customer', '3'),
(3, 'tax', '1'),
(4, 'preference', '1'),
(5, 'line_items', '5'),
(6, 'template', 'default'),
(7, 'payment_type', '1'),
(8, 'language', 'en'),
(9, 'dateformat', 'Y-m-d'),
(10, 'spreadsheet', 'xls'),
(11, 'wordprocessor', 'doc'),
(12, 'pdfscreensize', '800'),
(13, 'pdfpapersize', 'A4'),
(14, 'pdfleftmargin', '15'),
(15, 'pdfrightmargin', '15'),
(16, 'pdftopmargin', '15'),
(17, 'pdfbottommargin', '15'),
(18, 'emailhost', 'localhost'),
(19, 'emailusername', ''),
(20, 'emailpassword', '');
";
	$patch['63']['date'] = "20070523";
		
	$patch['64']['name'] = "Alter field name prod_id to id";
	$patch['64']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT";
	$patch['64']['date'] = "20070523";

	$patch['65']['name'] = "Alter field name prod_description to description";
	$patch['65']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_description` `description` TEXT NOT NULL ";
	$patch['65']['date'] = "20070523";
		
	$patch['66']['name'] = "Alter field name prod_unit_price to unit_price";
	$patch['66']['patch'] = " ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL";
	$patch['66']['date'] = "20070523";

	$patch['67']['name'] = "Alter field name prod_custom_field1 to custom_field1";
	$patch['67']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['67']['date'] = "20070523";

	$patch['68']['name'] = "Alter field name prod_custom_field2 to custom_field2";
	$patch['68']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['68']['date'] = "20070523";

	$patch['69']['name'] = "Alter field name prod_custom_field3 to custom_field3";
	$patch['69']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['69']['date'] = "20070523";

	$patch['70']['name'] = "Alter field name prod_custom_field4 to custom_field4";
	$patch['70']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['70']['date'] = "20070523";
		
	$patch['71']['name'] = "Alter field name prod_notes to notes";
	$patch['71']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_notes` `notes` TEXT NOT NULL";
	$patch['71']['date'] = "20070523";

	$patch['72']['name'] = "Alter field name prod_enabled to enabled";
	$patch['72']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['72']['date'] = "20070523";

	//customer fields rename sql patches
	$patch['73']['name'] = "Alter field name c_id to id";
	$patch['73']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['73']['date'] = "20070523";

	$patch['74']['name'] = "Alter field name c_attention to attention";
	$patch['74']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['74']['date'] = "20070523";

	$patch['75']['name'] = "Alter field name c_name to name";
	$patch['75']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['75']['date'] = "20070523";

	$patch['76']['name'] = "Alter field name c_street_address to street_address";
	$patch['76']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['76']['date'] = "20070523";

	$patch['77']['name'] = "Alter field name c_street_address2 to street_address2";
	$patch['77']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['77']['date'] = "20070523";

	$patch['78']['name'] = "Alter field name c_city to city";
	$patch['78']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['78']['date'] = "20070523";

	$patch['79']['name'] = "Alter field name c_state to state";
	$patch['79']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['79']['date'] = "20070523";

	$patch['80']['name'] = "Alter field name c_zip_code to zip_code";
	$patch['80']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['80']['date'] = "20070523";

	$patch['81']['name'] = "Alter field name c_country to country";
	$patch['81']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['81']['date'] = "20070523";

	$patch['82']['name'] = "Alter field name c_phone to phone";
	$patch['82']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['82']['date'] = "20070523";

	$patch['83']['name'] = "Alter field name c_mobile_phone to mobile_phone";
	$patch['83']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['83']['date'] = "20070523";

	$patch['84']['name'] = "Alter field name c_fax to fax";
	$patch['84']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['84']['date'] = "20070523";

	$patch['85']['name'] = "Alter field name c_email to email";
	$patch['85']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['85']['date'] = "20070523";

	$patch['86']['name'] = "Alter field name c_notes to notes";
	$patch['86']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL";
	$patch['86']['date'] = "20070523";

	$patch['87']['name'] = "Alter field name c_custom_field1 to custom_field1";
	$patch['87']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['87']['date'] = "20070523";

	$patch['88']['name'] = "Alter field name c_custom_field2 to custom_field2";
	$patch['88']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['88']['date'] = "20070523";

	$patch['89']['name'] = "Alter field name c_custom_field3 to custom_field3";
	$patch['89']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['89']['date'] = "20070523";

	$patch['90']['name'] = "Alter field name c_custom_field4 to custom_field4";
	$patch['90']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['90']['date'] = "20070523";

	$patch['91']['name'] = "Alter field name c_enabled to enabled";
	$patch['91']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['91']['date'] = "20070523";

	//invoices sql patches
	$patch['92']['name'] = "Alter field name inv_id to id";
	$patch['92']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['92']['date'] = "20070523";

	$patch['93']['name'] = "Alter field name inv_biller_id to biller_id";
	$patch['93']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['93']['date'] = "20070523";

	$patch['94']['name'] = "Alter field name inv_customer_id to customer_id";
	$patch['94']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['94']['date'] = "20070523";

	$patch['95']['name'] = "Alter field name inv_type type_id";
	$patch['95']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['95']['date'] = "20070523";

	$patch['96']['name'] = "Alter field name inv_preference to preference_id";
	$patch['96']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['96']['date'] = "20070523";

	$patch['97']['name'] = "Alter field name inv_date to date";
	$patch['97']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
	$patch['97']['date'] = "20070523";

	$patch['98']['name'] = "Alter field name invoice_custom_field1 to custom_field1";
	$patch['98']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['98']['date'] = "20070523";

	$patch['99']['name'] = "Alter field name invoice_custom_field2 to custom_field2";
	$patch['99']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['99']['date'] = "20070523";

	$patch['100']['name'] = "Alter field name invoice_custom_field3 to custom_field3";
	$patch['100']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['100']['date'] = "20070523";

	$patch['101']['name'] = "Alter field name invoice_custom_field4 to custom_field4";
	$patch['101']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['101']['date'] = "20070523";

	$patch['102']['name'] = "Alter field name inv_note to note ";
	$patch['102']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL";
	$patch['102']['date'] = "20070523";

	//invoice items
	$patch['103']['name'] = "Alter field name inv_it_id to id ";
	$patch['103']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['103']['date'] = "20070523";

	$patch['104']['name'] = "Alter field name inv_it_invoice_id to invoice_id ";
	$patch['104']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['104']['date'] = "20070523";

	$patch['105']['name'] = "Alter field name inv_it_quantity to quantity ";
	$patch['105']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'";
	$patch['105']['date'] = "20070523";

	$patch['106']['name'] = "Alter field name inv_it_product_id to product_id ";
	$patch['106']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'";
	$patch['106']['date'] = "20070523";

	$patch['107']['name'] = "Alter field name inv_it_unit_price to unit_price ";
	$patch['107']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['107']['date'] = "20070523";

	$patch['108']['name'] = "Alter field name inv_it_tax_id to tax_id  ";
	$patch['108']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'";
	$patch['108']['date'] = "20070523";

	$patch['109']['name'] = "Alter field name inv_it_tax to tax  ";
	$patch['109']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['109']['date'] = "20070523";

	$patch['110']['name'] = "Alter field name inv_it_tax_amount to tax_amount  ";
	$patch['110']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ";
	$patch['110']['date'] = "20070523";

	$patch['111']['name'] = "Alter field name inv_it_gross_total to gross_total ";
	$patch['111']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['111']['date'] = "20070523";

	$patch['112']['name'] = "Alter field name inv_it_description to description ";
	$patch['112']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL";
	$patch['112']['date'] = "20070523";

	$patch['113']['name'] = "Alter field name inv_it_total to total";
	$patch['113']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['113']['date'] = "20070523";

	$patch['114']['name'] = "Add logging table";
	$patch['114']['patch'] = "CREATE TABLE `".TB_PREFIX."log` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`userid` INT NOT NULL ,
`sqlquerie` TEXT NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
	$patch['114']['date'] = "20070523";

	$patch['115']['name'] = "Add logging system preference";
	$patch['115']['patch'] = "INSERT INTO `".TB_PREFIX."system_defaults` ( `id` , `name` , `value` ) VALUES (NULL , 'logging', '0');";
	$patch['115']['date'] = "20070523";

	$numpatchesdone = getNumberOfDonePatches();
	$defaults = null;
	
	if ($numpatchesdone < 124) {
		// system defaults conversion patch
		// defaults query and DEFAULT NUMBER OF LINE ITEMS
		$sql_defaults = "SELECT * FROM ".TB_PREFIX."defaults";
		$sth = dbQuery($sql_defaults);
		$defaults = $sth->fetch();
	}

	$patch['116']['name'] = "System defaults conversion patch - set default biller";
	$patch['116']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_biller] where name = 'biller'";
	$patch['116']['date'] = "20070523";

	$patch['117']['name'] = "System defaults conversion patch - set default customer";
	$patch['117']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_customer] where name = 'customer'";
	$patch['117']['date'] = "20070523";

	$patch['118']['name'] = "System defaults conversion patch - set default tax";
	$patch['118']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_tax] where name = 'tax'";
	$patch['118']['date'] = "20070523";

	$patch['119']['name'] = "System defaults conversion patch - set default invoice reference";
	$patch['119']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_inv_preference] where name = 'preference'";
	$patch['119']['date'] = "20070523";

	$patch['120']['name'] = "System defaults conversion patch - set default number of line items";
	$patch['120']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_number_line_items] where name = 'line_items'";
	$patch['120']['date'] = "20070523";

	$patch['121']['name'] = "System defaults conversion patch - set default invoice template";
	$patch['121']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = '$defaults[def_inv_template]' where name = 'template'";
	$patch['121']['date'] = "20070523";

	$patch['122']['name'] = "System defaults conversion patch - set default paymemt type";
	$patch['122']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = $defaults[def_payment_type] where name = 'payment_type'";
	$patch['122']['date'] = "20070523";

	//sept release 

	$patch['123']['name'] = "Add option to delete invoices into the system_defaults table";
	$patch['123']['patch'] = "INSERT INTO `".TB_PREFIX."system_defaults` (`id`, `name`, `value`) VALUES (NULL, 'delete', 'N');";
	$patch['123']['date'] = "200709";
	
	$patch['124']['name'] = "Set default language in new lang system";
	$patch['124']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value = 'en-gb' where name ='language';";
	$patch['124']['date'] = "200709";

	$patch['125']['name'] = "Change log table that usernames are also possible as id";
	$patch['125']['patch'] = "ALTER TABLE `".TB_PREFIX."log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'";
	$patch['125']['date'] = "200709";
	
	$patch['126']['name'] = "Add visible attribute to the products table";
	$patch['126']['patch'] = "ALTER TABLE  `".TB_PREFIX."products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';";
	$patch['126']['date'] = "200709";

	$patch['127']['name'] = "Add last_id to logging table";
	$patch['127']['patch'] = "ALTER TABLE  `".TB_PREFIX."log` ADD  `last_id` INT NULL ;";
	$patch['127']['date'] = "200709";
	
	$patch['128']['name'] = "Add user table";
		if(checkTableExists(TB_PREFIX.'users') == true) 
		{
			if(checkFieldExists(TB_PREFIX.'users','user_domain') == true) 
			{	
				//dummy patch - if table and domain field exists do nothing
				$patch['128']['patch'] = "select * from ".TB_PREFIX."users ;";
			}
			if(checkFieldExists(TB_PREFIX.'users','user_domain') == false) 
			{	
				//alter existing table to add domain
				$patch['128']['patch'] = "ALTER TABLE `".TB_PREFIX."users` ADD `user_domain` VARCHAR( 255 ) NOT NULL AFTER `user_group` ;";				
			}	
		}
		if(checkTableExists(TB_PREFIX.'users') == false) 
		{
			$patch['128']['patch'] = "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."users` (
`user_id` int(11) NOT NULL auto_increment,
`user_email` varchar(255) NOT NULL,
`user_name` varchar(255) NOT NULL,
`user_group` varchar(255) NOT NULL,
`user_domain` varchar(255) NOT NULL,
`user_password` varchar(255) NOT NULL,
PRIMARY KEY  (`user_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
";
		}
	$patch['128']['date'] = "200709";
	
	
	$patch['129']['name'] = "Fill user table with default values";
	$patch['129']['patch'] = "INSERT INTO `".TB_PREFIX."users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`) VALUES (NULL, 'demo@simpleinvoices.org', 'demo', '1', '1', MD5('demo'))";
	$patch['129']['date'] = "200709";
	
	$patch['130']['name'] = "Create auth_challenges table";
	
			if(checkTableExists(TB_PREFIX.'auth_challenges') == true)
			{
				//a do nothing patch cause the table already exists
			$patch['130']['patch'] = "select * from ".TB_PREFIX."auth_challenges";
			}
			if(checkTableExists(TB_PREFIX.'auth_challenges') == false)
			{
				$patch['130']['patch'] = "CREATE TABLE IF NOT EXISTS `".TB_PREFIX."auth_challenges` (
`challenges_key` int(11) NOT NULL,
`challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);
";
			}  
	$patch['130']['date'] = "200709";
	
	$patch['131']['name'] = "Make tax field 3 decimal places";
	$patch['131']['patch'] = "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL";
	$patch['131']['date'] = "200709";

//2008 09 26 - Beta release patches start

 
    $patch['132']['name'] = "Correct Foreign Key Tax ID Field Type in Invoice Items Table";
    $patch['132']['patch'] = "ALTER TABLE  `".TB_PREFIX."invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT '0' NOT NULL ;";
    $patch['132']['date'] = "20071126";
	
    $patch['133']['name'] = "Correct Foreign Key Invoice ID Field Type in Ac Payments Table";
    $patch['133']['patch'] = "ALTER TABLE  `".TB_PREFIX."account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;";
    $patch['133']['date'] = "20071126";

    $patch['134']['name'] = "Drop non-int compatible default from si_sql_patchmanager";
    switch ($config->database->adapter)
    {
		case "pdo_pgsql" :
        	$patch['134']['patch'] = "ALTER TABLE ".TB_PREFIX."sql_patchmanager ALTER COLUMN sql_patch_ref DROP DEFAULT;";
        	break;
		case "pdo_mysql" :
		default :
			$patch['134']['patch'] = "SELECT 1+1;";
    } 
    $patch['134']['date'] = "20071218";

    $patch['135']['name'] = "Change sql_patch_ref type in sql_patchmanager to int";
    switch ($config->database->adapter)
    {
		case "pdo_pgsql" :
        	$patch['135']['patch'] = "ALTER TABLE  ".TB_PREFIX."sql_patchmanager ALTER COLUMN sql_patch_ref TYPE int USING to_number(sql_patch_ref, '999');";
        	break;
		case "pdo_mysql" :
		default :
   			$patch['135']['patch'] = "ALTER TABLE  `".TB_PREFIX."sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;";
    } 
    $patch['135']['date'] = "20071218";
	
    $patch['136']['name'] = "Create domain mapping table";
    switch ($config->database->adapter)
    {
		case "pdo_pgsql" :
			$patch['136']['patch'] = "CREATE TABLE ".TB_PREFIX."user_domain (
	            id serial PRIMARY KEY,
            	name text UNIQUE NOT NULL;";
         	break;
		case "pdo_mysql" :
		default :
        	$patch['136']['patch'] = "CREATE TABLE ".TB_PREFIX."user_domain (
	    		`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
            	`name` varchar(255) UNIQUE NOT NULL
            	) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    }
    $patch['136']['date'] = "200712";
    

    $patch['137']['name'] = "Insert default domain";
    switch ($config->database->adapter)
    {
		case "pdo_pgsql" :
			$patch['137']['patch'] = "INSERT INTO ".TB_PREFIX."user_domain (name) VALUES ('default');";
			break;
		case "pdo_mysql" :
		default:
			$patch['137']['patch'] = "INSERT INTO ".TB_PREFIX."user_domain (name) VALUES ('default');";
    }
    $patch['137']['date'] = "200712";
    //TODO postgres patch 

    $patch['138']['name'] = "Add domain_id to payment_types table";
    switch ($config->database->adapter)
    {
		case "pdo_pgsql" :
			$patch['138']['patch'] = "ALTER TABLE ".TB_PREFIX."payment_types ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
			break;
		case "pdo_mysql" :
		default:
   			$patch['138']['patch'] = "ALTER TABLE `".TB_PREFIX."payment_types` ADD `domain_id` INT  NOT NULL AFTER `pt_id` ;";     
    }
    $patch['138']['date'] = "200712";

    $patch['139']['name'] = "Add domain_id to preferences table";
    $patch['139']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `domain_id` INT  NOT NULL AFTER `pref_id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['139']['patch'] = "ALTER TABLE ".TB_PREFIX."preferences ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
    }
    $patch['139']['date'] = "200712";

    $patch['140']['name'] = "Add domain_id to products table";
    $patch['140']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `domain_id` INT  NOT NULL AFTER `id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['140']['patch'] = "ALTER TABLE ".TB_PREFIX."products ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
    }
    $patch['140']['date'] = "200712"; 
    
    $patch['141']['name'] = "Add domain_id to billers table";
    $patch['141']['patch'] = "ALTER TABLE `".TB_PREFIX."biller` ADD `domain_id` INT  NOT NULL AFTER `id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['141']['patch'] = "ALTER TABLE ".TB_PREFIX."biller ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
    }
    $patch['141']['date'] = "200712";

    $patch['142']['name'] = "Add domain_id to invoices table";
    $patch['142']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD `domain_id` INT NOT NULL AFTER `id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['142']['patch'] = "ALTER TABLE ".TB_PREFIX."invoices ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
    }
    $patch['142']['date'] = "200712";

    $patch['143']['name'] = "Add domain_id to customers table";
    $patch['143']['patch'] = "ALTER TABLE `".TB_PREFIX."customers` ADD `domain_id` INT NOT NULL AFTER `id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['143']['patch'] = "ALTER TABLE ".TB_PREFIX."customers ADD COLUMN domain_id int NOT NULL REFERENCES ".TB_PREFIX."domain(id);";
    }
    $patch['143']['date'] = "200712";


    $patch['144']['name'] = "Change group field to user_role_id in users table";
    $patch['144']['patch'] = "ALTER TABLE `".TB_PREFIX."users` CHANGE `user_group` `user_role_id` INT  DEFAULT '1' NOT NULL;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['144']['patch'] = "ALTER TABLE ".TB_PREFIX."users RENAME COLUMN user_group TO user_role_id;";
    }
    $patch['144']['date'] = "20080102";
    
    $patch['145']['name'] = "Change domain field to user_domain_id in users table";
    $patch['145']['patch'] = "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT '1' NOT NULL;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['145']['patch'] = "ALTER TABLE " . TB_PREFIX . "users RENAME COLUMN user_domain TO user_domain_id;";
    }
    $patch['145']['date'] = "20080102";

    $patch['146']['name'] = "Drop old auth_challenges table";
    $patch['146']['patch'] = "DROP TABLE IF EXISTS `".TB_PREFIX."auth_challenges`;";
    if ($config->database->adapter == "pdo_pgsql") {
        /* SC: auth_challenges creation was already removed from the postgres
         *     schema before this patch
         */
        $patch['146']['patch'] = "SELECT 1+1";
    }
    $patch['146']['date'] = "20080102";
    
    $patch['147']['name'] = "Create user_role table";
    $patch['147']['patch'] = "CREATE TABLE ".TB_PREFIX."user_role (
	    `id` int(11) NOT NULL auto_increment  PRIMARY KEY,
            `name` varchar(255) UNIQUE NOT NULL
            ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['147']['patch'] = "CREATE TABLE ".TB_PREFIX."user_role (
            id serial PRIMARY KEY,
            name text UNIQUE NOT NULL
            );";
    }
    $patch['147']['date'] = "20080102";
    
    $patch['148']['name'] = "Insert default user group";
    $patch['148']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('administrator');";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['148']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('administrator');";
    }
    $patch['148']['date'] = "20080102";


    $patch['149']['name'] = "Table = Account_payments Field = ac_amount : change field type and length to decimal";
    $patch['149']['patch'] = "ALTER TABLE `".TB_PREFIX."account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['149']['name'] = "Widen ac_amount field of account_payments";
        $patch['149']['patch'] = "ALTER TABLE ".TB_PREFIX."account_payments ALTER COLUMN ac_amount TYPE numeric(25, 6)";
    }
    $patch['149']['date'] = "20080128";

    $patch['150']['name'] = "Table = Invoice_items Field = quantity : change field type and length to decimal";
    $patch['150']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT '0' ";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['150']['name'] = "Widen quantity field of invoice_items";
        $patch['150']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN quantity TYPE numeric(25, 6)";
    }
    $patch['150']['date'] = "20080128";

    $patch['151']['name'] = "Table = Invoice_items Field = unit_price : change field type and length to decimal";
    $patch['151']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['151']['name'] = "Widen unit_price field of invoice_items";
        $patch['151']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN unit_price TYPE numeric(25, 6)";
    }
    $patch['151']['date'] = "20080128";

    $patch['152']['name'] = "Table = Invoice_items Field = tax : change field type and length to decimal";
    $patch['152']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['152']['name'] = "Widen tax field of invoice_items";
        $patch['152']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN tax TYPE numeric(25, 6)";
    }
    $patch['152']['date'] = "20080128";

    $patch['153']['name'] = "Table = Invoice_items Field = tax_amount : change field type and length to decimal";
    $patch['153']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['153']['name'] = "Widen tax_amount field of invoice_items";
        $patch['153']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN tax_amount TYPE numeric(25, 6)";
    }
    $patch['153']['date'] = "20080128";

    $patch['154']['name'] = "Table = Invoice_items Field = gross_total : change field type and length to decimal";
    $patch['154']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['154']['name'] = "Widen gross_total field of invoice_items";
        $patch['154']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN gross_total TYPE numeric(25, 6)";
    }
    $patch['154']['date'] = "20080128";

    $patch['155']['name'] = "Table = Invoice_items Field = total : change field type and length to decimal";
    $patch['155']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['155']['name'] = "Widen total field of invoice_items";
        $patch['155']['patch'] = "ALTER TABLE ".TB_PREFIX."invoice_items ALTER COLUMN total TYPE numeric(25, 6)";
    }
    $patch['155']['date'] = "20080128";

    $patch['156']['name'] = "Table = Products Field = unit_price : change field type and length to decimal";
    $patch['156']['patch'] = "ALTER TABLE `".TB_PREFIX."products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['156']['name'] = "Widen unit_price field of products";
        $patch['156']['patch'] = "ALTER TABLE ".TB_PREFIX."products ALTER COLUMN unit_price TYPE numeric(25, 6)";
    }
    $patch['156']['date'] = "20080128";

    $patch['157']['name'] = "Table = Tax Field = quantity : change field type and length to decimal";
    $patch['157']['patch'] = "ALTER TABLE `".TB_PREFIX."tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['157']['name'] = "Widen tax_percentage field of tax";
        $patch['157']['patch'] = "ALTER TABLE ".TB_PREFIX."tax ALTER COLUMN tax_percentage TYPE numeric(25, 6)";
    }
    $patch['157']['date'] = "20080128";
   
    $patch['158']['name'] = "Rename table si_account_payments to si_payment";
    $patch['158']['patch'] = "RENAME TABLE `".TB_PREFIX."account_payments` TO  `".TB_PREFIX."payment`;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['158']['patch'] = "RENAME TABLE `".TB_PREFIX."account_payments` TO  `".TB_PREFIX."payment`";
    }
    $patch['158']['date'] = "20081201";
    //TODO: postgres and sqlite patch
    
    $patch['159']['name'] = "Add domain_id to payments table";
    $patch['159']['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` ADD  `domain_id` INT NOT NULL ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['159']['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` ADD  `domain_id` INT NOT NULL ";
    }
    $patch['159']['date'] = "20081201";
    //TODO: postgres and sqlite patch  

    $patch['160']['name'] = "Add domain_id to tax table";
    $patch['160']['patch'] = "ALTER TABLE  `".TB_PREFIX."tax` ADD  `domain_id` INT NOT NULL ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['160']['patch'] = "ALTER TABLE  `".TB_PREFIX."tax` ADD  `domain_id` INT NOT NULL ";
    }
    $patch['160']['date'] = "20081201";
    //TODO: postgres and sqlite patch  
    
    $patch['161']['name'] = "Change user table from si_users to si_user";
    $patch['161']['patch'] = "RENAME TABLE `".TB_PREFIX."users` TO  `".TB_PREFIX."user` ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['161']['patch'] = "RENAME TABLE `".TB_PREFIX."users` TO  `".TB_PREFIX."user`";
    }
    $patch['161']['date'] = "20081201";

    $patch['162']['name'] = "Add new invoice items tax table";
    $patch['162']['patch'] = "CREATE TABLE `".TB_PREFIX."invoice_item_tax` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`invoice_item_id` INT( 11 ) NOT NULL ,
		`tax_id` INT( 11 ) NOT NULL ,
		`tax_type` VARCHAR( 1 ) NOT NULL ,
		`tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
		`tax_amount` DECIMAL( 25, 6 ) NOT NULL
		) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['162']['patch'] = "CREATE TABLE `".TB_PREFIX."invoice_item_tax` (
			`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`invoice_item_id` INT( 11 ) NOT NULL ,
			`tax_id` INT( 11 ) NOT NULL ,
			`tax_type` VARCHAR( 1 ) NOT NULL ,
			`tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
			`tax_amount` DECIMAL( 25, 6 ) NOT NULL
			) ENGINE = MYISAM ;"; //TODO - psql version
    }
    $patch['162']['date'] = "20081212";

	//do conversion
    $patch['163']['name'] = "Convert tax info in si_invoice_items to si_invoice_item_tax";
    $patch['163']['patch'] = "insert into `".TB_PREFIX."invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount) select id, tax_id, '%', tax, tax_amount from `".TB_PREFIX."invoice_items`;";
    if ($config->database->adapter == "pdo_pgsql") {
    	$patch['163']['patch'] = "insert into `".TB_PREFIX."invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount) select id, tax_id, '%', tax, tax_amount from `".TB_PREFIX."invoice_items;";
    }
    $patch['163']['date'] = "20081212";


    $patch['164']['name'] = "Add default tax id into products table";
    $patch['164']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;";
    if ($config->database->adapter == "pdo_pgsql") {
    	$patch['164']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;";
    }
    $patch['164']['date'] = "20081212";

    $patch['165']['name'] = "Add default tax id 2 into products table";
    $patch['165']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;";
    if ($config->database->adapter == "pdo_pgsql") {
    	$patch['165']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;";
    }
    $patch['165']['date'] = "20081212";

    $patch['166']['name'] = "Add default tax into product items";
    $patch['166']['patch'] = "update `".TB_PREFIX."products` set default_tax_id = (select value from `".TB_PREFIX."system_defaults` where name ='tax');";
    if ($config->database->adapter == "pdo_pgsql") {
    	$patch['166']['patch'] = "update `".TB_PREFIX."products` set default_tax_id = (select value from `".TB_PREFIX."system_defaults` where name ='tax');";
    }
    $patch['166']['date'] = "20081212";

    $patch['167']['name'] = "Add default number of taxes per line item into system_defaults";
    $patch['167']['patch'] = "insert into `".TB_PREFIX."system_defaults` values ('','tax_per_line_item','1')";
    if ($config->database->adapter == "pdo_pgsql") {
    	$patch['167']['patch'] = "insert into `".TB_PREFIX."system_defaults` values ('','tax_per_line_item','1')";
    }
    $patch['167']['date'] = "20081212";

    $patch['168']['name'] = "Add tax type";
    $patch['168']['patch'] = "ALTER TABLE `".TB_PREFIX."tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['168']['patch'] = "ALTER TABLE `".TB_PREFIX."tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;";
    }
    $patch['168']['date'] = "20081212";

    $patch['169']['name'] = "Set tax type on current taxes to %";
    $patch['169']['patch'] = "UPDATE `".TB_PREFIX."tax` SET `type` = '%' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['169']['patch'] = "UPDATE `".TB_PREFIX."tax` SET `type` = '%';"; 
    }
    $patch['169']['date'] = "20081212";
	//delete the old fields in si_invoice_items

    //TODO: postgres and sqlite patch      

    
    $patch['170']['name'] = "Set domain_id on tax table to 1";
    $patch['170']['patch'] = "UPDATE `".TB_PREFIX."tax` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['170']['patch'] = "UPDATE `".TB_PREFIX."tax` SET `domain_id` = '1';"; 
    }
    $patch['170']['date'] = "20081229";

    $patch['171']['name'] = "Set domain_id on payment table to 1";
    $patch['171']['patch'] = "UPDATE `".TB_PREFIX."payment` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['171']['patch'] = "UPDATE `".TB_PREFIX."payment` SET `domain_id` = '1';"; 
    }
    $patch['171']['date'] = "20081229";
  
    $patch['172']['name'] = "Set domain_id on payment_types table to 1";
    $patch['172']['patch'] = "UPDATE `".TB_PREFIX."payment_types` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['172']['patch'] = "UPDATE `".TB_PREFIX."payment_types` SET `domain_id` = '1';"; 
    }
    $patch['172']['date'] = "20081229";    
  
    $patch['173']['name'] = "Set domain_id on preference table to 1";
    $patch['173']['patch'] = "UPDATE `".TB_PREFIX."preferences` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['173']['patch'] = "UPDATE `".TB_PREFIX."preferences` SET `domain_id` = '1';"; 
    }
    $patch['173']['date'] = "20081229";        
    
    $patch['174']['name'] = "Set domain_id on products table to 1";
    $patch['174']['patch'] = "UPDATE `".TB_PREFIX."products` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['174']['patch'] = "UPDATE `".TB_PREFIX."products` SET `domain_id` = '1';"; 
    }
    $patch['174']['date'] = "20081229";        

    $patch['175']['name'] = "Set domain_id on biller table to 1";
    $patch['175']['patch'] = "UPDATE `".TB_PREFIX."biller` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['175']['patch'] = "UPDATE `".TB_PREFIX."biller` SET `domain_id` = '1';"; 
    }
    $patch['175']['date'] = "20081229";  
          
    $patch['176']['name'] = "Set domain_id on invoices table to 1";
    $patch['176']['patch'] = "UPDATE `".TB_PREFIX."invoices` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['176']['patch'] = "UPDATE `".TB_PREFIX."invoices` SET `domain_id` = '1';"; 
    }
    $patch['176']['date'] = "20081229";        

    $patch['177']['name'] = "Set domain_id on customers table to 1";
    $patch['177']['patch'] = "UPDATE `".TB_PREFIX."customers` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['177']['patch'] = "UPDATE `".TB_PREFIX."customers` SET `domain_id` = '1';"; 
    }
    $patch['177']['date'] = "20081229";        
        
    $patch['178']['name'] = "Rename si_user.user_id to si_user.id";
    $patch['178']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_id` `id` int(11) ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['178']['patch'] = "UPDATE `".TB_PREFIX."user` CHANGE `user_id` `id` int(11);"; 
    }
    $patch['178']['date'] = "20081229";        
     //TODO: postgres and sqlite patch  

    $patch['179']['name'] = "Rename si_user.user_email to si_user.email";
    $patch['179']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_email` `email` VARCHAR( 255 );";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['179']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_email` `email` VARCHAR( 255 );"; 
    }
    $patch['179']['date'] = "20081229";        
     //TODO: postgres and sqlite patch  
     
    $patch['180']['name'] = "Rename si_user.user_name to si_user.name";
    $patch['180']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_name` `name` VARCHAR( 255 );";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['180']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_name` `name` VARCHAR( 255 );"; 
    }
    $patch['180']['date'] = "20081229";        
     //TODO: postgres and sqlite patch      
     
    $patch['181']['name'] = "Rename si_user.user_role_id to si_user.role_id";
    $patch['181']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_role_id` `role_id` int(11);";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['181']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_role_id` `role_id` int(11);"; 
    }
    $patch['181']['date'] = "20081229";        
     //TODO: postgres and sqlite patch      
     
    $patch['182']['name'] = "Rename si_user.user_domain_id to si_user.domain_id";
    $patch['182']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_domain_id` `domain_id` int(11) ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['182']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_domain_id` `domain_id` int(11) ;"; 
    }
    $patch['182']['date'] = "20081229";        
     //TODO: postgres and sqlite patch        
     
    $patch['183']['name'] = "Rename si_user.user_password to si_user.password";
    $patch['183']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_password` `password` VARCHAR( 255 )  ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['183']['patch'] = "ALTER TABLE `".TB_PREFIX."user` CHANGE `user_password` `password` VARCHAR( 255 ) ;"; 
    }
    $patch['183']['date'] = "20081229";        
     //TODO: postgres and sqlite patch     
        
    $patch['184']['name'] = "Drop name column from si_user table";
    $patch['184']['patch'] = "ALTER TABLE `".TB_PREFIX."user` DROP `name`  ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['184']['patch'] = "ALTER TABLE `".TB_PREFIX."user` DROP `name`  ;"; 
    }
    $patch['184']['date'] = "20081230";        
    //TODO: postgres and sqlite patch        

    $patch['185']['name'] = "Drop old defaults table";
    $patch['185']['patch'] = "DROP TABLE `".TB_PREFIX."defaults` ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['185']['patch'] = "DROP TABLE `".TB_PREFIX."defaults`  ;"; 
    }
    $patch['185']['date'] = "20081230";        
    //TODO: postgres and sqlite patch  
     
    $patch['186']['name'] = "Set domain_id on customers table to 1";
    $patch['186']['patch'] = "ALTER TABLE  `".TB_PREFIX."custom_fields` ADD  `domain_id` INT NOT NULL ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['186']['patch'] = "ALTER TABLE  `".TB_PREFIX."custom_fields` ADD  `domain_id` INT NOT NULL ;"; 
    }
    $patch['186']['date'] = "20081230";        
    //TODO: postgres and sqlite patch 
              
    $patch['187']['name'] = "Set domain_id on custom_feilds table to 1";
    $patch['187']['patch'] = "UPDATE `".TB_PREFIX."custom_fields` SET `domain_id` = '1' ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['187']['patch'] = "UPDATE `".TB_PREFIX."custom_fields` SET `domain_id` = '1';"; 
    }
    $patch['187']['date'] = "20081230"; 
    //TODO: postgres and sqlite patch     
    
    $patch['188']['name'] = "Drop tax_id column from si_invoice_items table";
    $patch['188']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax_id`  ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['188']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax_id`  ;"; 
    }
    $patch['188']['date'] = "20090118";        

    //TODO: postgres and sqlite patch        
    $patch['189']['name'] = "Drop tax column from si_invoice_items table";
    $patch['189']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax`  ;";
    if ($config->database->adapter == "pdo_pgsql") {
	    $patch['189']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_items` DROP `tax`  ;"; 
    }
    $patch['189']['date'] = "20090118";        

    $patch['190']['name'] = "Insert user role - user";
    $patch['190']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('user');";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['190']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('user');";
    }
    $patch['190']['date'] = "20090215";

    $patch['191']['name'] = "Insert user role - viewer";
	$patch['191']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('viewer');";
	if ($config->database->adapter == "pdo_pgsql") {
		$patch['191']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('viewer');";
	}
	$patch['191']['date'] = "20090215";

    $patch['192']['name'] = "Insert user role - customer";
    $patch['192']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('customer');";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['192']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('customer');";
    }
    $patch['192']['date'] = "20090215";

    $patch['193']['name'] = "Insert user role - biller";
    $patch['193']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('biller');";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['193']['patch'] = "INSERT INTO ".TB_PREFIX."user_role (name) VALUES ('biller');";
    }
    $patch['193']['date'] = "20090215";

    $patch['194']['name'] = "User table - auto increment";
    $patch['194']['patch'] = "ALTER TABLE ".TB_PREFIX."user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['194']['patch'] = "ALTER TABLE ".TB_PREFIX."user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT";
    }
    $patch['194']['date'] = "20090215";

    $patch['195']['name'] = "User table - add enabled field";
    $patch['195']['patch'] = "ALTER TABLE ".TB_PREFIX."user ADD enabled INT( 1 ) NOT NULL ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['195']['patch'] = "ALTER TABLE ".TB_PREFIX."user ADD enabled INT( 1 ) NOT NULL ;";
    }
    $patch['195']['date'] = "20090215";

    $patch['196']['name'] = "User table - make all existing users enabled";
    $patch['196']['patch'] = "UPDATE ".TB_PREFIX."user SET enabled = 1 ;";
    if ($config->database->adapter == "pdo_pgsql") {
        $patch['196']['patch'] = "UPDATE ".TB_PREFIX."user SET enabled = 1;";
    }
    $patch['196']['date'] = "20090217";
    
    $patch['197']['name'] = "Defaults table - add domain_id and extension_id field";
    $patch['197']['patch'] = "ALTER TABLE ".TB_PREFIX."system_defaults 
				ADD `domain_id` INT( 5 ) NOT NULL DEFAULT '1',
				ADD `extension_id` INT( 5 ) NOT NULL DEFAULT '1',
				DROP INDEX `name`,
				ADD INDEX `name` ( `name` );";
    $patch['197']['date'] = "20090321";
    	
    $patch['198']['name'] = "Extension table - create table to hold extension status";
    $patch['198']['patch'] = "CREATE TABLE ".TB_PREFIX."extensions ( 
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`domain_id` INT( 11 ) NOT NULL ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`description` VARCHAR( 255 ) NOT NULL ,
		`enabled` VARCHAR( 1 ) NOT NULL DEFAULT '0') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $patch['198']['date'] = "20090322";	
 
    $patch['199']['name'] = "Update extensions table";
    $patch['199']['patch'] = "INSERT INTO ".TB_PREFIX."extensions (
			`id`,`domain_id`,`name`,`description`,`enabled`) 
			VALUES ('1','1','core','Core part of Simple Invoices - always enabled','1');";
    $patch['199']['date'] = "20090529";

    $patch['200']['name'] = "Update extensions table";
    $patch['200']['patch'] = "UPDATE ".TB_PREFIX."extensions SET `id` = '1' WHERE `name` = 'core' LIMIT 1;";
    $patch['200']['date'] = "20090529";

    $patch['201']['name'] = "Set domain_id on system defaults table to 1";
    $patch['201']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET `domain_id` = '1' ;";
    $patch['201']['date'] = "20090622";    

    $patch['202']['name'] = "Set extension_id on system defaults table to 1";
    $patch['202']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET `extension_id` = '1' ;";
    $patch['202']['date'] = "20090622";    

    $patch['203']['name'] = "Move all old consulting style invoices to itemised";
    $patch['203']['patch'] = "UPDATE `".TB_PREFIX."invoices` SET `type_id` = '2' where `type_id`=3 ;";
    $patch['203']['date'] = "20090704";    

    $patch['204']['name'] = "Create index table to handle new invoice numbering system";
    $patch['204']['patch'] = "CREATE TABLE `".TB_PREFIX."index` (
`id` INT( 11 ) NOT NULL ,
`node` VARCHAR( 255 ) NOT NULL ,
`sub_node` VARCHAR( 255 ) NULL ,
`domain_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    	$patch['204']['date'] = "20090818";    

    $patch['205']['name'] = "Add index_id to invoice table - new invoice numbering";
    $patch['205']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD `index_id` INT( 11 ) NOT NULL AFTER `id`;";
    $patch['205']['date'] = "20090818";    

    $patch['206']['name'] = "Add status and locale to preferences";
    $patch['206']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `status` INT( 1 ) NOT NULL ,
ADD `locale` VARCHAR( 255 ) NULL ,
ADD `language` VARCHAR( 255 ) NULL ;";
    $patch['206']['date'] = "20090826";    

    $patch['207']['name'] = "Populate the status, locale, and language fields in preferences table";
    $patch['207']['patch'] = "UPDATE `".TB_PREFIX."preferences` SET status = '1', locale = '".$config->local->locale."', language = '".$language."' ;";
    $patch['207']['date'] = "20090826";    

    $patch['208']['name'] = "Populate the status, locale, and language fields in preferences table";
    $patch['208']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `index_group` INT( 11 ) NOT NULL ;";
    $patch['208']['date'] = "20090826";    

    $defaults = getSystemDefaults();
    $patch['209']['name'] = "Populate the status, locale, and language fields in preferences table";
    $patch['209']['patch'] = "UPDATE `".TB_PREFIX."preferences` SET index_group = '".$defaults['preference']."' ;";
    $patch['209']['date'] = "20090826";    

    $patch['210']['name'] = "Create composite primary key for invoice table";
    $patch['210']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`,`id` );";
    $patch['210']['date'] = "20090826";    

    $patch['211']['name'] = "Reset auto-increment for invoice table";
    $patch['211']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` AUTO_INCREMENT = 1;";
    $patch['211']['date'] = "20090826";    

    $patch['212']['name'] = "Copy invoice.id into invoice.index_id";
    $patch['212']['patch'] = "update `".TB_PREFIX."invoices` set index_id = id;";
    $patch['212']['date'] = "20090902";    
    
    $max_invoice = invoice::max();
    $patch['213']['name'] = "Update the index table with max invoice id - if required";
    if($max_invoice > "0")
    {
        $patch['213']['patch'] = "insert into `".TB_PREFIX."index` (id, node, sub_node, domain_id)  VALUES (".$max_invoice.", 'invoice', '".$defaults['preference']."','1');";
    } else {
        $patch['213']['patch'] = "select 1 from `".TB_PREFIX."index`;";
    }
    $patch['213']['date'] = "20090902";
    
            unset($defaults);
            unset($max_invoice);

            $patch['214']['name'] = "Add sub_node_2 to si_index table";
            $patch['214']['patch'] = "ALTER TABLE  `".TB_PREFIX."index` ADD  `sub_node_2` VARCHAR( 255 ) NULL AFTER  `sub_node`";
            $patch['214']['date'] = "20090912";    


            $patch['215']['name'] = "si_invoices - add composite primary key - patch removed";
            #$patch['215']['patch'] = "ALTER TABLE  `".TB_PREFIX."index` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            #$patch['215']['patch'] = "ALTER TABLE  `".TB_PREFIX."index` ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['215']['patch'] = "select 1 from `".TB_PREFIX."index`;";
            $patch['215']['date'] = "20090912";    

            $patch['216']['name'] = "si_payment - add composite primary key";
            $patch['216']['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['216']['date'] = "20090912";    

            $patch['217']['name'] = "si_payment_types - add composite primary key";
            $patch['217']['patch'] = "ALTER TABLE  `".TB_PREFIX."payment_types` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pt_id`)";
            $patch['217']['date'] = "20090912";    

            $patch['218']['name'] = "si_preferences - add composite primary key";
            $patch['218']['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pref_id`)";
            $patch['218']['date'] = "20090912";    

            $patch['219']['name'] = "si_products - add composite primary key";
            $patch['219']['patch'] = "ALTER TABLE  `".TB_PREFIX."products` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['219']['date'] = "20090912";    
            
            $patch['220']['name'] = "si_tax - add composite primary key";
            $patch['220']['patch'] = "ALTER TABLE  `".TB_PREFIX."tax` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `tax_id`)";
            $patch['220']['date'] = "20090912";    

            $patch['221']['name'] = "si_user - add composite primary key";
            $patch['221']['patch'] = "ALTER TABLE  `".TB_PREFIX."user` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['221']['date'] = "20090912";    

            $patch['222']['name'] = "si_biller - add composite primary key";
            $patch['222']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['222']['date'] = "20100209";    

            $patch['223']['name'] = "si_customers - add composite primary key";
            $patch['223']['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
            $patch['223']['date'] = "20100209";    

            $patch['224']['name'] = "Add paypal business name";
            $patch['224']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_business_name` VARCHAR( 255 ) NULL AFTER  `footer`";
            $patch['224']['date'] = "20100209";    

            $patch['225']['name'] = "Add paypal notify url";
            $patch['225']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_notify_url` VARCHAR( 255 ) NULL AFTER  `paypal_business_name`";
            $patch['225']['date'] = "20100209";    

            $patch['226']['name'] = "Define currency in preferences";
            $patch['226']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_code` VARCHAR( 25 ) NULL ;";
            $patch['226']['date'] = "20100209";    

            $patch['227']['name'] = "Create cron table to handle recurrence";
            $patch['227']['patch'] = "CREATE TABLE `".TB_PREFIX."cron` (
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
        $patch['227']['date'] = "20100215";    

        $patch['228']['name'] = "Create cron_log table to handle record when cron was run";
        $patch['228']['patch'] = "CREATE TABLE `".TB_PREFIX."cron_log` (
            `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
            `domain_id` INT( 11 ) NOT NULL ,
            `run_date` DATE NOT NULL ,
            PRIMARY KEY (  `domain_id` ,  `id` )
            ) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
        $patch['228']['date'] = "20100216";    

        $patch['229']['name'] = "preferences - add online payment type";
        $patch['229']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `include_online_payment` VARCHAR( 255 ) NULL ;";
        $patch['229']['date'] = "20100209";    

        $patch['230']['name'] = "Add paypal notify url";
        $patch['230']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `paypal_return_url` VARCHAR( 255 ) NULL AFTER  `paypal_notify_url`";
        $patch['230']['date'] = "20100223";    

        $patch['231']['name'] = "Add paypal payment id into payment table";
        $patch['231']['patch'] = "ALTER TABLE  `".TB_PREFIX."payment` ADD `online_payment_id` VARCHAR( 255 ) NULL AFTER  `domain_id`";
        $patch['231']['date'] = "20100226";    

        $patch['232']['name'] = "Define currency display in preferences";
        $patch['232']['patch'] = "ALTER TABLE `".TB_PREFIX."preferences` ADD `currency_position` VARCHAR( 25 ) NULL ;";
        $patch['232']['date'] = "20100227";    

        $patch['233']['name'] = "Add system default to control invoice number by biller -- dummy patch -- this sql was removed";
    $patch['233']['patch'] = "select 1+1;";
    $patch['233']['date'] = "20100302";    

    $patch['234']['name'] = "Add eway customer ID";
    $patch['234']['patch'] = "ALTER TABLE  `".TB_PREFIX."biller` ADD `eway_customer_id` VARCHAR( 255 ) NULL AFTER `paypal_return_url`;";
    $patch['234']['date'] = "20100315";    

    $patch['235']['name'] = "Add eway card holder name";
    $patch['235']['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_holder_name` VARCHAR( 255 ) NULL AFTER `email`;";
    $patch['235']['date'] = "20100315";    

    $patch['236']['name'] = "Add eway card number";
    $patch['236']['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_number` VARCHAR( 255 ) NULL AFTER `credit_card_holder_name`;";
    $patch['236']['date'] = "20100315";    

    $patch['237']['name'] = "Add eway card expiry month";
    $patch['237']['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_month` VARCHAR( 02 ) NULL AFTER `credit_card_number`;";
    $patch['237']['date'] = "20100315";  

    $patch['238']['name'] = "Add eway card expirt year";
    $patch['238']['patch'] = "ALTER TABLE  `".TB_PREFIX."customers` ADD `credit_card_expiry_year` VARCHAR( 04 ) NULL AFTER `credit_card_expiry_month` ;";
    $patch['238']['date'] = "20100315";  

    $patch['239']['name'] = "cronlog - add invoice id";
    $patch['239']['patch'] = "ALTER TABLE `".TB_PREFIX."cron_log` ADD `cron_id` VARCHAR( 25 ) NULL AFTER `domain_id` ;";
    $patch['239']['date'] = "20100321";    

    $patch['240']['name'] = "si_system_defaults - add composite primary key";
    #$patch['240']['patch'] = "ALTER TABLE  `".TB_PREFIX."system_defaults` DROP INDEX `name`, DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)";
    $patch['240']['patch'] = "select +1 from `".TB_PREFIX."system_defaults`";
    $patch['240']['date'] = "20100305";    

    $patch['241']['name'] = "si_system_defaults - add composite primary key";
    $patch['241']['patch'] = "insert into `".TB_PREFIX."system_defaults` values ('','inventory','0','1','1');";
    $patch['241']['date'] = "20100409";    

    $patch['242']['name'] = "Add cost to products table";
    $patch['242']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `cost` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' AFTER `default_tax_id_2`;";
    $patch['242']['date'] = "20100409";    
    
    $patch['243']['name'] = "Add reorder_level to products table";
    $patch['243']['patch'] = "ALTER TABLE `".TB_PREFIX."products` ADD `reorder_level` INT( 11 ) NULL AFTER `cost` ;";
    $patch['243']['date'] = "20100409";    

    $patch['244']['name'] = "Create inventory table";
    $patch['244']['patch'] = "CREATE TABLE  `".TB_PREFIX."inventory` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`domain_id` INT( 11 ) NOT NULL ,
`product_id` INT( 11 ) NOT NULL ,
`quantity` DECIMAL( 25, 6 ) NOT NULL ,
`cost` DECIMAL( 25, 6 ) NULL ,
`date` DATE NOT NULL ,
`note` TEXT NULL ,
PRIMARY KEY ( `domain_id`, `id` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $patch['244']['date'] = "20100409";    

    $patch['245']['name'] = "Preferences - make locale null field";
    $patch['245']['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `locale`  `locale` VARCHAR( 255 ) NULL ;";
    $patch['245']['date'] = "20100419";    

    $patch['246']['name'] = "Preferences - make language a null field";
    $patch['246']['patch'] = "ALTER TABLE  `".TB_PREFIX."preferences` CHANGE  `language`  `language` VARCHAR( 255 ) NULL;";
    $patch['246']['date'] = "20100419";    

    $patch['247']['name'] = "Custom fields - make sure domain_id is 1";
    $patch['247']['patch'] = "update ".TB_PREFIX."custom_fields set domain_id = '1';";
    $patch['247']['date'] = "20100419";    

    $patch['248']['name'] = "Make Simple Invoices faster - add index";
    $patch['248']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`domain_id`);";
    $patch['248']['date'] = "20100419";    

    $patch['249']['name'] = "Make Simple Invoices faster - add index";
    $patch['249']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`biller_id`) ;";
    $patch['249']['date'] = "20100419";    

    $patch['250']['name'] = "Make Simple Invoices faster - add index";
    $patch['250']['patch'] = "ALTER TABLE `".TB_PREFIX."invoices` ADD INDEX(`customer_id`);";
    $patch['250']['date'] = "20100419";    

    $patch['251']['name'] = "Make Simple Invoices faster - add index";
    $patch['251']['patch'] = "ALTER TABLE `".TB_PREFIX."payment` ADD INDEX(`domain_id`);";
    $patch['251']['date'] = "20100419";    

    $patch['252']['name'] = "Language - reset to en_GB - due to folder renaming";
    $patch['252']['patch'] = "UPDATE `".TB_PREFIX."system_defaults` SET value ='en_GB' where name='language';";
    $patch['252']['date'] = "20100419";    
/*
ALTER TABLE  `si_system_defaults` ADD  `new_id` INT( 11 ) NOT NULL FIRST; UPDATE `si_system_defaults` SET new_id = id; ALTER TABLE  `si_system_defaults` DROP  `id` ; ALTER TABLE  `si_system_defaults` DROP INDEX `name` ; ALTER TABLE  `si_system_defaults` CHANGE  `new_id`  `id` INT( 11 ) NOT NULL; ALTER TABLE  `si_system_defaults` ADD PRIMARY KEY(`domain_id`,`id` );

*/
