<?php
	

	$patch['1']['name'] = "Create si_sql_patchmanger table";
	$patch['1']['patch'] = "CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM)";
        $patch['1']['date'] = "20060514";;
	
	$patch['2']['name'] = "Update invoice no details to have a default currency sign";
        $patch['2']['patch'] = "UPDATE si_preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1";
        $patch['2']['date'] = "20060514";;
	
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
        $patch['37']['date'] = "20070325";
        
        $patch['38']['name'] = "Alter custom field table - field length now 255 for field name";
        $patch['38']['patch'] = "
		ALTER TABLE `si_custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )
        ";
        $patch['38']['date'] = "20070325";
        
        $patch['39']['name'] = "Alter custom field table - field length now 255 for field label";
        $patch['39']['patch'] = "
		ALTER TABLE `si_custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )
        ";
        $patch['39']['date'] = "20070325";
               
        $patch['40']['name'] = "Alter field name in si_account_payments";
        $patch['40']['patch'] = "ALTER TABLE  `si_account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
        $patch['40']['date'] = "20070424";
                
        $patch['41']['name'] = "Alter field name b_name to name";
        $patch['41']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_name`  `name` VARCHAR( 50 ) NULL DEFAULT NULL;";
        $patch['41']['date'] = "20070424";

        $patch['42']['name'] = "Alter field name b_id to id";
        $patch['42']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT";
        $patch['42']['date'] = "20070430";

	$patch['43']['name'] = "Alter field name b_street_address to street_address";
        $patch['43']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL";
        $patch['43']['date'] = "20070430";

	$patch['44']['name'] = "Alter field name b_street_address2 to street_address2";
        $patch['44']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['44']['date'] = "20070430";

	$patch['45']['name'] = "Alter field name b_city to city";
        $patch['45']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL";
        $patch['45']['date'] = "20070430";
	
	$patch['46']['name'] = "Alter field name b_state to state";
        $patch['46']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['46']['date'] = "20070430";

	$patch['47']['name'] = "Alter field name b_zip_code to zip_code";
        $patch['47']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['47']['date'] = "20070430";

	$patch['48']['name'] = "Alter field name b_country to country";
        $patch['48']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['48']['date'] = "20070430";

	$patch['49']['name'] = "Alter field name b_phone to phone";
        $patch['49']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['49']['date'] = "20070430";

	$patch['50']['name'] = "Alter field name b_mobile_phone to mobile_phone";
        $patch['50']['patch'] = "ALTER TABLE  `si_biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL";
	$patch['50']['date'] = "20070430";
	//b_co_logo -> logo & b_co_footer -> footer
?>

