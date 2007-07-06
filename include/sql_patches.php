<?php
	$patch['0']['name'] = "Start";
	$patch['0']['patch'] = "SHOW TABLES LIKE 'test'";
	$patch['0']['date'] = "20060514";

	$patch['1']['name'] = "Create si_sql_patchmanger table";
	$patch['1']['patch'] = "CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM)";
        $patch['1']['date'] = "20060514";
	
	$patch['2']['name'] = "Update invoice no details to have a default currency sign";
        $patch['2']['patch'] = "UPDATE si_preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1";
        $patch['2']['date'] = "20060514";
	
	$patch['3']['name'] = "Add a row into the defaults table to handle the default number of line items";
        $patch['3']['patch'] = "ALTER TABLE si_defaults ADD def_number_line_items INT( 25 ) NOT NULL";
        $patch['3']['date'] = "20060514";
	
	$patch['4']['name'] = "Set the default number of line items to 5";
        $patch['4']['patch'] = "UPDATE si_defaults SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1";
        $patch['4']['date'] = "20060514";
	
	$patch['5']['name'] = "Add logo and invoice footer support to biller";
        $patch['5']['patch'] = "ALTER TABLE si_biller ADD b_co_logo VARCHAR( 50 ) ,
ADD b_co_footer TEXT";
        $patch['5']['date'] = "20060514";
	
	$patch['6']['name'] = "Add default invoice template option";
        $patch['6']['patch'] = "ALTER TABLE si_defaults ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL";
        $patch['6']['date'] = "20060514";
	
	$patch['7']['name'] = "Edit tax description field lenght to 50";
        $patch['7']['patch'] = "ALTER TABLE si_tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL";
        $patch['7']['date'] = "20060526";
	
	$patch['8']['name'] = "Edit default invoice template field lenght to 50";
        $patch['8']['patch'] = "ALTER TABLE si_defaults CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL";
        $patch['8']['date'] = "20060526";

	$patch['9']['name'] = "Add consulting style invoice";
        $patch['9']['patch'] = "INSERT INTO si_invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')";
        $patch['9']['date'] = "20060531";

        $patch['10']['name'] = "Add enabled to biller";
        $patch['10']['patch'] = "ALTER TABLE si_biller ADD b_enabled varchar(1) NOT NULL default '1'";
        $patch['10']['date'] = "20060815";

        $patch['11']['name'] = "Add enabled to customters";
        $patch['11']['patch'] = "ALTER TABLE si_customers ADD c_enabled varchar(1) NOT NULL default '1'";
        $patch['11']['date'] = "20060815";

        $patch['12']['name'] = "Add enabled to prefernces";
        $patch['12']['patch'] = "ALTER TABLE si_preferences ADD pref_enabled varchar(1) NOT NULL default '1'";
        $patch['12']['date'] = "20060815";

        $patch['13']['name'] = "Add enabled to products";
        $patch['13']['patch'] = "ALTER TABLE si_products ADD prod_enabled varchar(1) NOT NULL default '1'";
        $patch['13']['date'] = "20060815";

        $patch['14']['name'] = "Add enabled to products";
        $patch['14']['patch'] = "ALTER TABLE si_tax ADD tax_enabled varchar(1) NOT NULL default '1'";
        $patch['14']['date'] = "20060815";

        $patch['15']['name'] = "Add tax_id into invoice_items table";
        $patch['15']['patch'] = "ALTER TABLE si_invoice_items ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0'  AFTER inv_it_unit_price";
        $patch['15']['date'] = "20060815";

        $patch['16']['name'] = "Add Payments table";
        $patch['16']['patch'] = "
		CREATE TABLE `si_account_payments` (
		`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`ac_inv_id` VARCHAR( 10 ) NOT NULL ,
		`ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
		`ac_notes` TEXT NOT NULL ,
		`ac_date` DATETIME NOT NULL
		);
	";
        $patch['16']['date'] = "20060827";

        $patch['17']['name'] = "Adjust data type of quantuty field";
        $patch['17']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'";
        $patch['17']['date'] = "20060827";

        $patch['18']['name'] = "Create Payment Types table";
        $patch['18']['patch'] = "CREATE TABLE `si_payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1')";
        $patch['18']['date'] = "20060909";
	
        $patch['19']['name'] = "Add info into the Payment Type table";
        $patch['19']['patch'] = "INSERT INTO `si_payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')";
        $patch['19']['date'] = "20060909";

        $patch['20']['name'] = "Adjust accounts payments table to add a type field";
        $patch['20']['patch'] = "ALTER TABLE `si_account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'";
        $patch['20']['date'] = "20060909";
	
        $patch['21']['name'] = "Adjust the defautls table to add a payment type field";
        $patch['21']['patch'] = "ALTER TABLE `si_defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'";
        $patch['21']['date'] = "20060909";

        $patch['22']['name'] = "Add note field to customer";
        $patch['22']['patch'] = "ALTER TABLE `si_customers` ADD `c_notes` TEXT NULL AFTER `c_email`";
        $patch['22']['date'] = "20061026";

        $patch['23']['name'] = "Add note field to Biller";
        $patch['23']['patch'] = "ALTER TABLE `si_biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`";
        $patch['23']['date'] = "20061026";

        $patch['24']['name'] = "Add note field to Products";
        $patch['24']['patch'] = "ALTER TABLE `si_products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`";
        $patch['24']['date'] = "20061026";

/*Custom fields patches - start */
        $patch['25']['name'] = "Add street address 2 to customers";
        $patch['25']['patch'] = "ALTER TABLE `si_customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ";
        $patch['25']['date'] = "20061211";
	
        $patch['26']['name'] = "Add custom fields to customers";
        $patch['26']['patch'] = "
	ALTER TABLE `si_customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
		ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
		ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
		ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;
	";
        $patch['26']['date'] = "20061211";

        $patch['27']['name'] = "Add mobile phone to customers";
        $patch['27']['patch'] = "ALTER TABLE `si_customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`";
        $patch['27']['date'] = "20061211";

        $patch['28']['name'] = "Add street address 2 to billers";
        $patch['28']['patch'] = "ALTER TABLE `si_biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ";
        $patch['28']['date'] = "20061211";

        $patch['29']['name'] = "Add custom fields to billers";
        $patch['29']['patch'] = "
	ALTER TABLE `si_biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
		ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
		ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
		ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;
	";
        $patch['29']['date'] = "20061211";

        $patch['30']['name'] = "Creating the custom fields table";
        $patch['30']['patch'] = "
		CREATE TABLE `si_custom_fields` (
			`cf_id` INT NOT NULL AUTO_INCREMENT ,
			`cf_custom_field` VARCHAR( 50 ) NOT NULL ,
			`cf_custom_label` VARCHAR( 50 ) ,
			`cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
			PRIMARY KEY ( `cf_id` )
		);
	";
        $patch['30']['date'] = "20061211";

        $patch['31']['name'] = "Adding data to the custom fields table";
        $patch['31']['patch'] = "
	INSERT INTO `si_custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
		VALUES (
		'', 'biller_cf1', NULL , '0'
		), (
		'', 'biller_cf2', NULL , '0'
		), (
		'', 'biller_cf3', NULL , '0'
		), (
		'', 'biller_cf4', NULL , '0'
		), (
		'', 'customer_cf1', NULL , '0'
		), (
		'', 'customer_cf2', NULL , '0'
		), (
		'', 'customer_cf3', NULL , '0'
		), (
		'', 'customer_cf4', NULL , '0'
		), (
		'', 'product_cf1', NULL , '0'
		), (
		'', 'product_cf2', NULL , '0'
		), (
		'', 'product_cf3', NULL , '0'
		), (
		'', 'prod_custom_field4', NULL , '0'
	);
";
        $patch['31']['date'] = "20061211";

        $patch['32']['name'] = "Adding custom fields to products";
        $patch['32']['patch'] = "
	ALTER TABLE `si_products` ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price` ,
		ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1` ,
		ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2` ,
		ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3` ;
	";
        $patch['32']['date'] = "20061211";

        $patch['33']['name'] = "Alter product custom field 4";
        $patch['33']['patch'] = "
		UPDATE `si_custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `si_custom_fields`.`cf_id` =12 LIMIT 1 ;
        ";
        $patch['33']['date'] = "20061214";

        $patch['34']['name'] = "Reset invoice template to default refer Issue 70";
        $patch['34']['patch'] = "
		UPDATE `si_defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;
        ";
        $patch['34']['date'] = "20070125";

        $patch['35']['name'] = "Adding data to the custom fields table for invoices";
        $patch['35']['patch'] = "
        INSERT INTO `si_custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
                VALUES (
                '', 'invoice_cf1', NULL , '0'
                ), (
                '', 'invoice_cf2', NULL , '0'
                ), (
                '', 'invoice_cf3', NULL , '0'
                ), (
                '', 'invoice_cf4', NULL , '0'             
	        );
	";
        $patch['35']['date'] = "20070204";

        $patch['36']['name'] = "Adding custom fields to the invoices table";
        $patch['36']['patch'] = "
        ALTER TABLE `si_invoices` ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date` ,
                ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1` ,
                ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2` ,
                ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3` ;
        ";
        $patch['36']['date'] = "20070204";

        $patch['37']['name'] = "Reset invoice template to default due to new invoice template system";
        $patch['37']['patch'] = "
		UPDATE `si_defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;
        ";
        $patch['37']['date'] = "20070523";
        
        $patch['38']['name'] = "Alter custom field table - field length now 255 for field name";
        $patch['38']['patch'] = "
		ALTER TABLE `si_custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )
        ";
        $patch['38']['date'] = "20070523";
        
        $patch['39']['name'] = "Alter custom field table - field length now 255 for field label";
        $patch['39']['patch'] = "
		ALTER TABLE `si_custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )
        ";
        $patch['39']['date'] = "20070523";


        $patch['40']['name'] = "Alter field name in si_partchmanager";
        $patch['40']['patch'] = "ALTER TABLE `si_sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL";
        $patch['40']['date'] = "20070523";
	
		$patch['41']['name'] = "Alter field name in si_account_payments";
        $patch['41']['patch'] = "ALTER TABLE  `si_account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
        $patch['41']['date'] = "20070523";
                
        $patch['42']['name'] = "Alter field name b_name to name";
        $patch['42']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;";
        $patch['42']['date'] = "20070523";

        $patch['43']['name'] = "Alter field name b_id to id";
        $patch['43']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
        $patch['43']['date'] = "20070523";

	$patch['44']['name'] = "Alter field name b_street_address to street_address";
        $patch['44']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
        $patch['44']['date'] = "20070523";

	$patch['45']['name'] = "Alter field name b_street_address2 to street_address2";
        $patch['45']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['45']['date'] = "20070523";

	$patch['46']['name'] = "Alter field name b_city to city";
        $patch['46']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL";
        $patch['46']['date'] = "20070523";
	
	$patch['47']['name'] = "Alter field name b_state to state";
        $patch['47']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['47']['date'] = "20070523";

	$patch['48']['name'] = "Alter field name b_zip_code to zip_code";
        $patch['48']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['48']['date'] = "20070523";

	$patch['49']['name'] = "Alter field name b_country to country";
        $patch['49']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['49']['date'] = "20070523";

	$patch['50']['name'] = "Alter field name b_phone to phone";
        $patch['50']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['50']['date'] = "20070523";

	$patch['51']['name'] = "Alter field name b_mobile_phone to mobile_phone";
        $patch['51']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['51']['date'] = "20070523";

	$patch['52']['name'] = "Alter field name b_fax to fax";
	$patch['52']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['52']['date'] = "20070523";

	$patch['53']['name'] = "Alter field name b_email to email";
        $patch['53']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['53']['date'] = "20070523";

	$patch['54']['name'] = "Alter field name b_co_logo to logo";
        $patch['54']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['54']['date'] = "20070523";

	$patch['55']['name'] = "Alter field name b_co_footer to footer";
        $patch['55']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ";
	$patch['55']['date'] = "20070523";

	$patch['56']['name'] = "Alter field name b_notes to notes";
        $patch['56']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ";
	$patch['56']['date'] = "20070523";

	$patch['57']['name'] = "Alter field name b_enabled to enabled";
        $patch['57']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['57']['date'] = "20070523";
	
	$patch['58']['name'] = "Alter field name b_custom_field1 to custom_field1";
        $patch['58']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['58']['date'] = "20070523";

	$patch['59']['name'] = "Alter field name b_custom_field2 to custom_field2";
        $patch['59']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['59']['date'] = "20070523";

	$patch['60']['name'] = "Alter field name b_custom_field3 to custom_field3";
        $patch['60']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['60']['date'] = "20070523";

	$patch['61']['name'] = "Alter field name b_custom_field4 to custom_field4";
        $patch['61']['patch'] = "ALTER TABLE `si_biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['61']['date'] = "20070523";
	
	$patch['62']['name'] = "Introduce system_defaults table";
	$patch['62']['patch'] = "CREATE TABLE `si_system_defaults` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ";
	$patch['62']['date'] = "20070523";

	$patch['63']['name'] = "Insert date into the system_defaults table";
	$patch['63']['patch'] = "INSERT INTO `si_system_defaults` (`id`, `name`, `value`) VALUES 
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
(20, 'emailpassword', '');";
	$patch['63']['date'] = "20070523";
		
	$patch['64']['name'] = "Alter field name prod_id to id";
        $patch['64']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT";
	$patch['64']['date'] = "20070523";

	$patch['65']['name'] = "Alter field name prod_description to description";
        $patch['65']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_description` `description` TEXT NOT NULL ";
	$patch['65']['date'] = "20070523";
		
	$patch['66']['name'] = "Alter field name prod_unit_price to unit_price";
        $patch['66']['patch'] = " ALTER TABLE `si_products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL";
	$patch['66']['date'] = "20070523";

	$patch['67']['name'] = "Alter field name prod_custom_field1 to custom_field1";
        $patch['67']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['67']['date'] = "20070523";

	$patch['68']['name'] = "Alter field name prod_custom_field2 to custom_field2";
        $patch['68']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['68']['date'] = "20070523";

	$patch['69']['name'] = "Alter field name prod_custom_field3 to custom_field3";
        $patch['69']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['69']['date'] = "20070523";

	$patch['70']['name'] = "Alter field name prod_custom_field4 to custom_field4";
        $patch['70']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['70']['date'] = "20070523";
		
	$patch['71']['name'] = "Alter field name prod_notes to notes";
        $patch['71']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_notes` `notes` TEXT NOT NULL";
	$patch['71']['date'] = "20070523";

	$patch['72']['name'] = "Alter field name prod_enabled to enabled";
        $patch['72']['patch'] = "ALTER TABLE `si_products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['72']['date'] = "20070523";

	//customer fields rename sql patches
	$patch['73']['name'] = "Alter field name c_id to id";
        $patch['73']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['73']['date'] = "20070523";

	$patch['74']['name'] = "Alter field name c_attention to attention";
        $patch['74']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['74']['date'] = "20070523";

	$patch['75']['name'] = "Alter field name c_name to name";
        $patch['75']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['75']['date'] = "20070523";

	$patch['76']['name'] = "Alter field name c_street_address to street_address";
        $patch['76']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['76']['date'] = "20070523";

	$patch['77']['name'] = "Alter field name c_street_address2 to street_address2";
        $patch['77']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['77']['date'] = "20070523";

	$patch['78']['name'] = "Alter field name c_city to city";
        $patch['78']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['78']['date'] = "20070523";

	$patch['79']['name'] = "Alter field name c_state to state";
        $patch['79']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['79']['date'] = "20070523";

	$patch['80']['name'] = "Alter field name c_zip_code to zip_code";
        $patch['80']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['80']['date'] = "20070523";

	$patch['81']['name'] = "Alter field name c_country to countyr";
        $patch['81']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['81']['date'] = "20070523";

	$patch['82']['name'] = "Alter field name c_phone to phone";
        $patch['82']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['82']['date'] = "20070523";

	$patch['83']['name'] = "Alter field name c_mobile_phone to mobile_phone";
        $patch['83']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['83']['date'] = "20070523";

	$patch['84']['name'] = "Alter field name c_fax to fax";
        $patch['84']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['84']['date'] = "20070523";

	$patch['85']['name'] = "Alter field name c_email to email";
        $patch['85']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['85']['date'] = "20070523";

	$patch['86']['name'] = "Alter field name c_notes to notes";
        $patch['86']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL";
	$patch['86']['date'] = "20070523";

	$patch['87']['name'] = "Alter field name c_custom_field1 to custom_field1";
        $patch['87']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['87']['date'] = "20070523";

	$patch['88']['name'] = "Alter field name c_custom_field2 to custom_field2";
        $patch['88']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['88']['date'] = "20070523";

	$patch['89']['name'] = "Alter field name c_custom_field3 to custom_field3";
        $patch['89']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['89']['date'] = "20070523";

	$patch['90']['name'] = "Alter field name c_custom_field4 to custom_field4";
        $patch['90']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['90']['date'] = "20070523";

	$patch['91']['name'] = "Alter field name c_enabled to enabled";
        $patch['91']['patch'] = "ALTER TABLE `si_customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'";
	$patch['91']['date'] = "20070523";

	//invoices sql patches
	$patch['92']['name'] = "Alter field name inv_id to id";
        $patch['92']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['92']['date'] = "20070523";

	$patch['93']['name'] = "Alter field name inv_biller_id to biller_id";
        $patch['93']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['93']['date'] = "20070523";

	$patch['94']['name'] = "Alter field name inv_customer_id to customer_id";
	$patch['94']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['94']['date'] = "20070523";

	$patch['95']['name'] = "Alter field name inv_type type_id";
	$patch['95']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['95']['date'] = "20070523";

	$patch['96']['name'] = "Alter field name inv_preference to preference_id";
	$patch['96']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['96']['date'] = "20070523";

	$patch['97']['name'] = "Alter field name inv_date to date";
	$patch['97']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'";
	$patch['97']['date'] = "20070523";

	$patch['98']['name'] = "Alter field name invoice_custom_field1 to custom_field1";
	$patch['98']['patch'] = "ALTER TABLE `si_invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['98']['date'] = "20070523";

	$patch['99']['name'] = "Alter field name invoice_custom_field2 to custom_field2";
	$patch['99']['patch'] = "ALTER TABLE `si_invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['99']['date'] = "20070523";

	$patch['100']['name'] = "Alter field name invoice_custom_field3 to custom_field3";
	$patch['100']['patch'] = "ALTER TABLE `si_invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['100']['date'] = "20070523";

	$patch['101']['name'] = "Alter field name invoice_custom_field4 to custom_field4";
	$patch['101']['patch'] = "ALTER TABLE `si_invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL";
	$patch['101']['date'] = "20070523";

	$patch['102']['name'] = "Alter field name inv_note to note ";
	$patch['102']['patch'] = "ALTER TABLE `si_invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL";
	$patch['102']['date'] = "20070523";

	//invoice items
	$patch['103']['name'] = "Alter field name inv_it_id to id ";
	$patch['103']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
	$patch['103']['date'] = "20070523";

	$patch['104']['name'] = "Alter field name inv_it_invoice_id to invoice_id ";
	$patch['104']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'";
	$patch['104']['date'] = "20070523";

	$patch['105']['name'] = "Alter field name inv_it_quantity to quantity ";
	$patch['105']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'";
	$patch['105']['date'] = "20070523";

	$patch['106']['name'] = "Alter field name inv_it_product_id to product_id ";
	$patch['106']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'";
	$patch['106']['date'] = "20070523";

	$patch['107']['name'] = "Alter field name inv_it_unit_price to unit_price ";
	$patch['107']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['107']['date'] = "20070523";

	$patch['108']['name'] = "Alter field name inv_it_tax_id to tax_id  ";
	$patch['108']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'";
	$patch['108']['date'] = "20070523";

	$patch['109']['name'] = "Alter field name inv_it_tax to tax  ";
	$patch['109']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['109']['date'] = "20070523";

	$patch['110']['name'] = "Alter field name inv_it_tax_amount to tax_amount  ";
	$patch['110']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ";
	$patch['110']['date'] = "20070523";

	$patch['111']['name'] = "Alter field name inv_it_gross_total to gross_total ";
	$patch['111']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['111']['date'] = "20070523";

	$patch['112']['name'] = "Alter field name inv_it_description to description ";
	$patch['112']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL";
	$patch['112']['date'] = "20070523";

	$patch['113']['name'] = "Alter field name inv_it_total to total";
	$patch['113']['patch'] = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'";
	$patch['113']['date'] = "20070523";

	$patch['114']['name'] = "Add logging table";
	$patch['114']['patch'] = "CREATE TABLE `si_log` (
`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`userid` INT NOT NULL ,
`sqlquerie` TEXT NOT NULL
) ENGINE = MYISAM ;
";
	$patch['114']['date'] = "20070523";

	$patch['115']['name'] = "Add logging systempreference";
	$patch['115']['patch'] = "INSERT INTO `si_system_defaults` ( `id` , `name` , `value` ) 
VALUES (
NULL , 'logging', '0'
);";
	$patch['115']['date'] = "20070523";

	//systemd efaults conversion patch
	#defaults query and DEFAULT NUMBER OF LINE ITEMS
	$sql_defaults = "SELECT * FROM ".TB_PREFIX."defaults";
	$result_defaults = mysqlQuery($sql_defaults);
	$defaults = mysql_fetch_array($result_defaults);

	$patch['116']['name'] = "System defaults conversion patch - set default biller";
	$patch['116']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_biller] where name = 'biller'";
	$patch['116']['date'] = "20070523";

	$patch['117']['name'] = "System defaults conversion patch - set default customer";
	$patch['117']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_customer] where name = 'customer'";
	$patch['117']['date'] = "20070523";

	$patch['118']['name'] = "System defaults conversion patch - set default tax";
	$patch['118']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_tax] where name = 'tax'";
	$patch['118']['date'] = "20070523";

	$patch['119']['name'] = "System defaults conversion patch - set default invoice preference";
	$patch['119']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_inv_preference] where name = 'preference'";
	$patch['119']['date'] = "20070523";

	$patch['120']['name'] = "System defaults conversion patch - set default number of line items";
	$patch['120']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_number_line_items] where name = 'line_items'";
	$patch['120']['date'] = "20070523";

	$patch['121']['name'] = "System defaults conversion patch - set default invoice template";
	$patch['121']['patch'] = "UPDATE `si_system_defaults` SET value = '$defaults[def_inv_template]' where name = 'template'";
	$patch['121']['date'] = "20070523";

	$patch['122']['name'] = "System defaults conversion patch - set default paymemt type";
	$patch['122']['patch'] = "UPDATE `si_system_defaults` SET value = $defaults[def_payment_type] where name = 'payment_type'";
	$patch['122']['date'] = "20070523";
	
	$patch['123']['name'] = "Create menu table";
	$patch['123']['patch'] = "CREATE TABLE `si_menu` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`parentid` INT NOT NULL ,
`order` INT NOT NULL ,
`name` text NOT NULL ,
`link` VARCHAR( 100 ) NOT NULL ,
 `enabled` tinyint(1) NOT NULL default '1'
) ENGINE = MYISAM ;";
	$patch['123']['date'] = "20070523";
	
	$patch['124']['name'] = "Insert menu table";
	$patch['124']['patch'] = 'INSERT INTO `si_menu` (`id`, `parentid`, `order`, `name`, `link`, `enabled`) VALUES 
(1, 0, 1, "{$LANG[\"home\"]}", "index.php", 1),
(2, 0, 2, "{$LANG[\"invoices\"]} +", "index.php?module=invoices&view=manage", 1),
(3, 2, 1, "{$LANG[\"manage_invoices\"]}", "index.php?module=invoices&view=manage", 1),
(4, 2, 2, "{$LANG[\"new_invoice_total\"]}", "index.php?module=invoices&view=total", 1),
(5, 2, 3, "{$LANG[\"new_invoice_itemised\"]}", "index.php?module=invoices&view=itemised", 1),
(6, 2, 4, "{$LANG[\"new_invoice_consulting\"]}", "index.php?module=invoices&view=consulting", 1),
(7, 2, 7, "Search Invoices", "index.php?module=invoices&view=search", 1),
(8, 0, 5, "{$LANG[\"customer\"]} +", "index.php?module=customers&view=manage", 1),
(9, 8, 1, "{$LANG[\"manage_customers\"]}", "index.php?module=customers&view=manage", 1),
(10, 8, 2, "{$LANG[\"add_customer\"]}", "index.php?module=customers&view=add", 1),
(11, 8, 9, "Search customer", "index.php?module=customers&view=search", 1),
(12, 0, 15, "{$LANG[\"products\"]} +", "index.php?module=products&view=manage", 1),
(13, 12, 1, "{$LANG[\"manage_products\"]}", "index.php?module=products&view=manage", 1),
(14, 12, 5, "{$LANG[\"add_product\"]}", "index.php?module=products&view=add", 1),
(15, 0, 20, "{$LANG[\"billers\"]} +", "index.php?module=billers&view=manage", 1),
(16, 15, 1, "{$LANG[\"manage_billers\"]}", "index.php?module=billers&view=manage", 1),
(17, 15, 5, "{$LANG[\"add_biller\"]}", "index.php?module=billers&view=add", 1),
(18, 0, 25, "{$LANG[\"payments\"]} +", "index.php?module=payments&view=manage", 1),
(19, 18, 5, "{$LANG[\"manage_payments\"]}", "index.php?module=payments&view=manage", 1),
(67, 18, 10, "{$LANG[\"process_payment\"]}", "index.php?module=payments&view=process", 1),
(20, 0, 30, "{$LANG[\"reports\"]} +", "#", 1),
(21, 20, 5, "{$LANG[\"sales\"]} +", "index.php?module=reports&view=report_sales_total", 1),
(22, 21, 5, "{$LANG[\"total_sales\"]}", "index.php?module=reports&view=report_sales_total", 1),
(23, 20, 10, "{$LANG[\"sales_by_customers\"]} +", "index.php?module=reports&view=report_sales_customers_total", 1),
(24, 23, 5, "{$LANG[\"total_sales_by_customer\"]}", "index.php?module=reports&view=report_sales_customers_total", 1),
(25, 20, 15, "{$LANG[\"tax\"]} +", "index.php?module=reports&view=report_tax_total", 1),
(26, 25, 10, "{$LANG[\"total_taxes\"]}", "index.php?module=reports&view=report_tax_total", 1),
(27, 20, 20, "{$LANG[\"product_sales\"]} +", "index.php?module=reports&view=report_products_sold_total", 1),
(28, 27, 10, "{$LANG[\"products_sold_total\"]}", "index.php?module=reports&view=report_products_sold_total", 1),
(29, 20, 25, "{$LANG[\"products_by_customer\"]} +", "index.php?module=reports&view=report_products_sold_by_customer", 1),
(30, 29, 10, "{$LANG[\"products_by_customer\"]}", "index.php?module=reports&view=report_products_sold_by_customer", 1),
(31, 20, 30, "{$LANG[\"biller_sales\"]} +", "index.php?module=reports&view=report_biller_total", 1),
(32, 31, 5, "{$LANG[\"biller_sales_total\"]}", "index.php?module=reports&view=report_biller_total", 1),
(33, 31, 35, "{$LANG[\"biller_sales_by_customer_totals\"]}", "index.php?module=reports&view=report_biller_by_customer", 1),
(34, 20, 10, "{$LANG[\"debtors\"]} +", "index.php?module=reports&view=report_debtors_by_amount", 1),
(35, 34, 10, "{$LANG[\"debtors_by_amount_owed\"]}", "index.php?module=reports&view=report_debtors_by_amount", 1),
(36, 34, 10, "{$LANG[\"debtors_by_aging_periods\"]}", "index.php?module=reports&view=report_debtors_by_aging", 1),
(37, 34, 10, "{$LANG[\"total_owed_per_customer\"]}", "index.php?module=reports&view=report_debtors_owing_by_customer", 1),
(38, 34, 10, "{$LANG[\"total_by_aging_periods\"]}", "index.php?module=reports&view=report_debtors_aging_total", 1),
(39, 20, 40, "Database Log", "index.php?module=reports&view=database_log", 1),
(40, 0, 35, "{$LANG[\"options\"]} +", "#", 1),
(41, 40, 5, "{$LANG[\"system_defaults\"]}", "index.php?module=system_defaults&view=manage", 1),
(42, 40, 10, "{$LANG[\"custom_fields_upper\"]}", "index.php?module=custom_fields&view=manage", 1),
(43, 40, 15, "{$LANG[\"tax_rates\"]} +", "index.php?module=tax_rates&view=manage", 1),
(44, 43, 5, "{$LANG[\"manage_tax_rates\"]}", "index.php?module=tax_rates&view=manage", 1),
(45, 43, 10, "{$LANG[\"add_tax_rate\"]}", "index.php?module=tax_rates&view=add", 1),
(46, 40, 20, "{$LANG[\"invoice_preferences\"]} +", "index.php?module=preferences&view=manage", 1),
(47, 46, 5, "{$LANG[\"manage_invoice_preferences\"]}", "index.php?module=preferences&view=manage", 1),
(48, 46, 10, "{$LANG[\"add_invoice_preference\"]}", "index.php?module=preferences&view=add", 1),
(49, 40, 25, "{$LANG[\"payment_types\"]} +", "index.php?module=payment_types&view=manage", 1),
(50, 49, 5, "{$LANG[\"manage_payment_types\"]}", "index.php?module=payment_types&view=manage", 1),
(51, 49, 10, "{$LANG[\"add_payment_type\"]}", "index.php?module=payment_types&view=add", 1),
(52, 40, 30, "{$LANG[\"database_upgrade_manager\"]}", "index.php?module=options&view=manage_sqlpatches", 1),
(53, 40, 35, "{$LANG[\"backup_database\"]}", "index.php?module=options&view=backup_database", 1),
(54, 40, 40, "{$LANG[\"help\"]} +", "docs.php?p=ReadMe", 1),
(55, 54, 10, "{$LANG[\"installation\"]}", "docs.php?p=ReadMe#installation", 1),
(56, 54, 15, "{$LANG[\"upgrading_simple_invoices\"]}", "docs.php?p=ReadMe#upgrading", 1),
(57, 54, 20, "{$LANG[\"prepare_simple_invoices\"]}", "docs.php?p=ReadMe#prepare", 1),
(58, 54, 25, "{$LANG[\"using_simple_invoices\"]}", "docs.php?p=ReadMe#use", 1),
(59, 54, 30, "{$LANG[\"faqs\"]}", "docs.php?p=ReadMe#faqs", 1),
(60, 54, 35, "{$LANG[\"get_help\"]}", "index.php?module=options&view=help", 1),
(61, 40, 45, "{$LANG[\"about\"]} +", "docs.php?p=ReadMe#faqs", 1),
(62, 61, 5, "{$LANG[\"about\"]}", "docs.php?p=ReadMe#faqs", 1),
(63, 61, 10, "{$LANG[\"change_log\"]}", "docs.php?p=ReadMe#faqs", 1),
(64, 61, 15, "{$LANG[\"credits\"]}", "docs.php?p=ReadMe#faqs", 1),
(65, 61, 20, "{$LANG[\"license\"]}", "docs.php?p=ReadMe#faqs", 1),
(66, 0, 100, "{$LANG[\"login\"]}", "login.php", 1),
(68, 40, 14, "Manage Custom Fields 2", "index.php?module=customFields&view=manageCustomFields", 1);';
	$patch['124']['date'] = "20070523";


	$patch['125']['name'] = "Change log table that usernames are also possible as id";
	$patch['125']['patch'] = "ALTER TABLE `si_log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'";
	$patch['125']['date'] = "20070525";
	
	$patch['126']['name'] = "Add visible attribute to the products table";
	$patch['126']['patch'] = "ALTER TABLE  `si_products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';";
	$patch['126']['date'] = "20070528";

	$patch['127']['name'] = "Add last_id to logging table";
	$patch['127']['patch'] = "ALTER TABLE  `si_log` ADD  `last_id` INT NULL ;";
	$patch['127']['date'] = "20070623";
	
	$patch['128']['name'] = "Add si_user table";
	$patch['128']['patch'] = "CREATE TABLE `si_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_email` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_group` varchar(10) NOT NULL,
  `user_domain` varchar(10) NOT NULL,
  `user_password` char(32) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ;";
	$patch['128']['date'] = "200700623";
	
	$patch['129']['name'] = "Fill si_user table with default values";
	$patch['129']['patch'] = "INSERT INTO `si_users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`) VALUES 
(1, 'demo@simpleinvoices.org', 'guest', '1', '1', MD5('demo'))";
	$patch['129']['date'] = "20070623";
	
	$patch['130']['name'] = "Create si_auth_challenges table";
	$patch['130']['patch'] = "CREATE TABLE `si_auth_challenges` (
  `challenges_key` int(11) NOT NULL,
  `challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);";
	$patch['130']['date'] = "20070623";
	
	
	$patch['131']['name'] = "Create si_customFieldCategories table";
	$patch['131']['patch'] = "CREATE TABLE `si_customFieldCategories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY  (`id`) );";
	$patch['131']['date'] = "20070629";
	
	
	$patch['132']['name'] = "Insert si_customFieldCategories default values";
	$patch['132']['patch'] = "INSERT INTO `si_customFieldCategories` (`id`, `name`) VALUES 
(1, 'biller'),
(2, 'customer'),
(3, 'product'),
(4, 'invoice');";
	$patch['132']['date'] = "20070629";
	
	$patch['133']['name'] = "Create si_customFieldValues table";
	$patch['133']['patch'] = "CREATE TABLE `si_customFieldValues` (
  `id` int(11) NOT NULL auto_increment,
  `customFieldId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL COMMENT 'could be invocie-id,customer-id etc.',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`));";
	$patch['133']['date'] = "20070629";
	
	$patch['134']['name'] = "Create si_customFields table";
	$patch['134']['patch'] = "CREATE TABLE `si_customFields` (
  `id` int(11) NOT NULL auto_increment,
  `pluginId` int(11) NOT NULL,
  `categorieId` int(11) NOT NULL,
  `name` varchar(30) character set latin1 NOT NULL,
  `description` varchar(50) collate utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);";
	$patch['134']['date'] = "20070629";


/*
INSERT INTO  `si_menu` (  `id` ,  `parentid` ,  `order` ,  `name` ,  `link` ,  `enabled` ) 
VALUES (
NULL ,  '61',  '100',  'Custom Fields New', 'index.php?module=customFields&amp;view=manageCustomFields',  '1'
);




/*
 * Not sure about these patches		
		$patch['64']['name'] = "Removes autoincrement from sql_id";
        $patch['64']['patch'] = "ALTER TABLE  `si_sql_patchmanager` CHANGE  `sql_id`  `sql_id` INT( 11 ) NOT NULL";
		$patch['64']['date'] = "20070506";
		
		$patch['65']['name'] = "Remove Primary Key frem sql_patchmanager";
        $patch['65']['patch'] = "ALTER TABLE `si_sql_patchmanager` DROP PRIMARY KEY";
		$patch['65']['date'] = "20070506";
				
		$patch['66']['name'] = "Makes sql_id Unique";
        $patch['66']['patch'] = "ALTER TABLE  `si_sql_patchmanager` ADD UNIQUE (`sql_id`)";
		$patch['66']['date'] = "20070506";
		
		$patch['67']['name'] = "Removes sql_patch_ref";
        $patch['67']['patch'] = "ALTER TABLE `si_sql_patchmanager` DROP `sql_patch_ref`";
		$patch['67']['date'] = "20070506";
 */
# write conversion srcipt for this release and will drop si_defaults in the following release just incase something bad happens
# thinking.. is si_system_defaults the write name or should it be si_options etc..
	
#	$patch['64']['name'] = "Drops old default table. Attention: Old defaults get lost...";
#        $patch['64']['patch'] = "DROP TABLE `si_defaults`";
#	$patch['64']['date'] = "20070503";

?>
