<?php
	$sql_patch_name_1 = "Create si_sql_patchmanger table";
        $sql_patch_1 = "CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM)";
        $sql_patch_update_1 = "INSERT INTO si_sql_patchmanager
 ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement )
VALUES ('','1','$sql_patch_name_1','20060514','')";

	$sql_patch_name_2 = "Update invoice no details to have a default currency sign";
        $sql_patch_2 = "UPDATE si_preferences SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1";
        $sql_patch_update_2 = "INSERT INTO si_sql_patchmanager
 ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement )
VALUES ('','2','$sql_patch_name_2','20060514','')";

	$sql_patch_name_3 = "Add a row into the defaults table to handle the default number of line items";
        $sql_patch_3 = "ALTER TABLE si_defaults ADD def_number_line_items INT( 25 ) NOT NULL";
        $sql_patch_update_3 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',3,'$sql_patch_name_3',20060514,'')";

	$sql_patch_name_4 = "Set the default number of line items to 5";
        $sql_patch_4 = "UPDATE si_defaults SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1";
        $sql_patch_update_4 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',4,'$sql_patch_name_4',20060514,'')";

	$sql_patch_name_5 = "Add logo and invoice footer support to biller";
        $sql_patch_5 = "ALTER TABLE si_biller ADD b_co_logo VARCHAR( 50 ) ,
ADD b_co_footer TEXT";
        $sql_patch_update_5 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',5,'$sql_patch_name_5',20060514,'')";

	$sql_patch_name_6 = "Add default invoice template option";
        $sql_patch_6 = "ALTER TABLE si_defaults ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL";
        $sql_patch_update_6 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',6,'$sql_patch_name_6',20060514,'')";

	$sql_patch_name_7 = "Edit tax description field lenght to 50";
        $sql_patch_7 = "ALTER TABLE si_tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL";
        $sql_patch_update_7 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',7,'$sql_patch_name_7',20060526,'')";

	$sql_patch_name_8 = "Edit default invoice template field lenght to 50";
        $sql_patch_8 = "ALTER TABLE si_defaults CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL";
        $sql_patch_update_8 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',8,'$sql_patch_name_8',20060526,'')";

	$sql_patch_name_9 = "Add consulting style invoice";
        $sql_patch_9 = "INSERT INTO si_invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')";
        $sql_patch_update_9 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',9,'$sql_patch_name_9',20060531,'')";

        $sql_patch_name_10 = "Add enabled to biller";
        $sql_patch_10 = "ALTER TABLE si_biller ADD b_enabled varchar(1) NOT NULL default '1'";
        $sql_patch_update_10 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',10,'$sql_patch_name_10',20060815,'')";

        $sql_patch_name_11 = "Add enabled to customters";
        $sql_patch_11 = "ALTER TABLE si_customers ADD c_enabled varchar(1) NOT NULL default '1'";
        $sql_patch_update_11 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',11,'$sql_patch_name_11',20060815,'')";

        $sql_patch_name_12 = "Add enabled to prefernces";
        $sql_patch_12 = "ALTER TABLE si_preferences ADD pref_enabled varchar(1) NOT NULL default '1'";
        $sql_patch_update_12 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',12,'$sql_patch_name_12',20060815,'')";

        $sql_patch_name_13 = "Add enabled to products";
        $sql_patch_13 = "ALTER TABLE si_products ADD prod_enabled varchar(1) NOT NULL default '1'";
        $sql_patch_update_13 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',13,'$sql_patch_name_13',20060815,'')";

        $sql_patch_name_14 = "Add enabled to products";
        $sql_patch_14 = "ALTER TABLE si_tax ADD tax_enabled varchar(1) NOT NULL default '1'";
        $sql_patch_update_14 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',14,'$sql_patch_name_14',20060815,'')";

        $sql_patch_name_15 = "Add tax_id into invoice_items table";
        $sql_patch_15 = "ALTER TABLE si_invoice_items ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0'  AFTER inv_it_unit_price";
        $sql_patch_update_15 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',15,'$sql_patch_name_15',20060815,'')";


        $sql_patch_name_16 = "Add Payments table";
        $sql_patch_16 = "
		CREATE TABLE `si_account_payments` (
		`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`ac_inv_id` VARCHAR( 10 ) NOT NULL ,
		`ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
		`ac_notes` TEXT NOT NULL ,
		`ac_date` DATETIME NOT NULL
		);
	";
        $sql_patch_update_16 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',16,'$sql_patch_name_16',20060827,'')";


        $sql_patch_name_17 = "Adjust data type of quantuty field";
        $sql_patch_17 = "ALTER TABLE `si_invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'";
        $sql_patch_update_17 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',17,'$sql_patch_name_17',20060827,'')";


        $sql_patch_name_18 = "Create Payment Types table";
        $sql_patch_18 = "CREATE TABLE `si_payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1')";
        $sql_patch_update_18 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',18,'$sql_patch_name_18',20060909,'')";


        $sql_patch_name_19 = "Add info into the Payment Type table";
        $sql_patch_19 = "INSERT INTO `si_payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')";
        $sql_patch_update_19 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',19,'$sql_patch_name_19',20060909,'')";


        $sql_patch_name_20 = "Adjust accounts payments table to add a type field";
        $sql_patch_20 = "ALTER TABLE `si_account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'";
        $sql_patch_update_20 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',20,'$sql_patch_name_20',20060909,'')";


        $sql_patch_name_21 = "Adjust the defautls table to add a payment type field";
        $sql_patch_21 = "ALTER TABLE `si_defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'";
        $sql_patch_update_21 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',21,'$sql_patch_name_21',20060909,'')";


        $sql_patch_name_22 = "Add note field to customer";
        $sql_patch_22 = "ALTER TABLE `si_customers` ADD `c_notes` TEXT NULL AFTER `c_email`";
        $sql_patch_update_22 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',22,'$sql_patch_name_22',20061026,'')";


        $sql_patch_name_23 = "Add note field to Biller";
        $sql_patch_23 = "ALTER TABLE `si_biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`";
        $sql_patch_update_23 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',23,'$sql_patch_name_23',20061026,'')";


        $sql_patch_name_24 = "Add note field to Products";
        $sql_patch_24 = "ALTER TABLE `si_products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`";
        $sql_patch_update_24 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',24,'$sql_patch_name_24',20061026,'')";


        $sql_patch_name_25 = "";
        $sql_patch_25 = "";
        $sql_patch_update_25 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',25,'$sql_patch_name_25',20061111,'')";
	

        $sql_patch_name_25 = "";
        $sql_patch_25 = "";
        $sql_patch_update_25 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',25,'$sql_patch_name_25',20061111,'')";


ALTER TABLE `si_customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ;

ALTER TABLE `si_customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;

ALTER TABLE `si_customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone` ;

ALTER TABLE `si_biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ;

ALTER TABLE `si_biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;

CREATE TABLE `si_custom_fields` (
`cf_id` INT NOT NULL AUTO_INCREMENT ,
`cf_custom_field` VARCHAR( 50 ) NOT NULL ,
`cf_custom_label` VARCHAR( 50 ) ,
`cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
PRIMARY KEY ( `cf_id` )
);

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
'', 'product_cf4', NULL , '0'
);


/*
CREATE TABLE `si_accounts` (
`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ac_inv_id` VARCHAR( 10 ) NOT NULL ,
`ac_amount` VARCHAR( 10 ) NOT NULL ,
`ac_notes` TEXT NOT NULL ,
`ac_date` DATETIME NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `si_invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0';
*/
?>

