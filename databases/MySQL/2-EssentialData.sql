-- phpMyAdmin SQL Dump
-- version 3.1.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2009 at 08:12 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `simple_invoices`
--

--
-- Dumping data for table `si_biller`
--


--
-- Dumping data for table `si_customers`
--


--
-- Dumping data for table `si_custom_fields`
--

INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES
(1, 'biller_cf1', NULL, '0', 1),
(2, 'biller_cf2', 'Tax ID', '0', 1),
(3, 'biller_cf3', NULL, '0', 1),
(4, 'biller_cf4', NULL, '0', 1),
(5, 'customer_cf1', NULL, '0', 1),
(6, 'customer_cf2', NULL, '0', 1),
(7, 'customer_cf3', NULL, '0', 1),
(8, 'customer_cf4', NULL, '0', 1),
(9, 'product_cf1', NULL, '0', 1),
(10, 'product_cf2', NULL, '0', 1),
(11, 'product_cf3', NULL, '0', 1),
(12, 'product_cf4', NULL, '0', 1),
(13, 'invoice_cf1', NULL, '0', 1),
(14, 'invoice_cf2', NULL, '0', 1),
(15, 'invoice_cf3', NULL, '0', 1),
(16, 'invoice_cf4', NULL, '0', 1);

--
-- Dumping data for table `si_extensions`
--

INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`) VALUES
(0, 0, 'core', 'Core part of Simple Invoices - always enabled', '1'),
(2, 0, 'core', 'Core part of Simple Invoices - always enabled', '1');

--
-- Dumping data for table `si_invoices`
--


--
-- Dumping data for table `si_invoice_items`
--


--
-- Dumping data for table `si_invoice_item_tax`
--


--
-- Dumping data for table `si_invoice_type`
--

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES
(1, 'Total'),
(2, 'Itemised'),
(3, 'Consulting');

--
-- Dumping data for table `si_log`
--

INSERT INTO `si_log` (`id`, `timestamp`, `userid`, `sqlquerie`, `last_id`) VALUES
(1, '2009-05-29 16:48:56', '1', 'SHOW TABLES LIKE ''si_sql_patchmanager''', NULL),
(2, '2009-05-29 16:48:56', '1', 'INSERT INTO si_user_role (name) VALUES (''user'');', NULL),
(3, '2009-05-29 16:48:56', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(4, '2009-05-29 16:48:56', '1', 'INSERT INTO si_user_role (name) VALUES (''viewer'');', NULL),
(5, '2009-05-29 16:48:56', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(6, '2009-05-29 16:48:56', '1', 'INSERT INTO si_user_role (name) VALUES (''customer'');', NULL),
(7, '2009-05-29 16:48:56', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(8, '2009-05-29 16:48:56', '1', 'INSERT INTO si_user_role (name) VALUES (''biller'');', NULL),
(9, '2009-05-29 16:48:56', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(10, '2009-05-29 16:48:56', '1', 'ALTER TABLE si_user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;', NULL),
(11, '2009-05-29 16:48:56', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(12, '2009-05-29 16:48:57', '1', 'ALTER TABLE si_user ADD enabled INT( 1 ) NOT NULL ;', NULL),
(13, '2009-05-29 16:48:57', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(14, '2009-05-29 16:48:57', '1', 'UPDATE si_user SET enabled = 1 ;', NULL),
(15, '2009-05-29 16:48:57', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(16, '2009-05-29 16:48:57', '1', 'ALTER TABLE si_system_defaults \n             ADD `domain_id` INT( 5 ) NOT NULL DEFAULT ''0'',\n              ADD `extension_id` INT( 5 ) NOT NULL DEFAULT ''0'',\n               DROP INDEX `name`,\n                ADD INDEX `name` ( `name` );', NULL),
(17, '2009-05-29 16:48:57', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(18, '2009-05-29 16:48:57', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(19, '2009-05-29 20:10:21', '1', 'SHOW TABLES LIKE ''si_sql_patchmanager''', NULL),
(20, '2009-05-29 20:10:21', '1', 'INSERT INTO si_extensions (\n         `id`,`domain_id`,`name`,`description`,`enabled`) \n         VALUES (''0'',''0'',''core'',''Core part of Simple Invoices - always enabled'',''1'');', NULL),
(21, '2009-05-29 20:10:21', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL),
(22, '2009-05-29 20:10:21', '1', 'UPDATE si_extensions SET `id` = ''0'' WHERE `name` = ''core'' LIMIT 1;', NULL),
(23, '2009-05-29 20:10:21', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)', NULL);

--
-- Dumping data for table `si_payment`
--


--
-- Dumping data for table `si_payment_types`
--

INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`) VALUES
(1, 1, 'Cash', '1'),
(2, 1, 'Credit Card', '1');

--
-- Dumping data for table `si_preferences`
--

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`) VALUES
(1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name:', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1'),
(2, 1, 'Receipt', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1'),
(3, 1, 'Estimate', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1'),
(4, 1, 'Quote', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1');

--
-- Dumping data for table `si_products`
--


--
-- Dumping data for table `si_sql_patchmanager`
--

INSERT INTO `si_sql_patchmanager` (`sql_id`, `sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`) VALUES
(1, 1, 'Create sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM '),
(2, 2, 'Update invoice no details to have a default currency sign', '20060514', ''),
(3, 3, 'Add a row into the defaults table to handle the default number of line items', '20060514', ''),
(4, 4, 'Set the default number of line items to 5', '20060514', ''),
(5, 5, 'Add logo and invoice footer support to biller', '20060514', ''),
(6, 6, 'Add default invoice template option', '20060514', ''),
(7, 7, 'Edit tax description field length to 50', '20060526', ''),
(8, 8, 'Edit default invoice template field length to 50', '20060526', ''),
(9, 9, 'Add consulting style invoice', '20060531', ''),
(10, 10, 'Add enabled to biller', '20060815', ''),
(11, 11, 'Add enabled to customers', '20060815', ''),
(12, 12, 'Add enabled to preferences', '20060815', ''),
(13, 13, 'Add enabled to products', '20060815', ''),
(14, 14, 'Add enabled to products', '20060815', ''),
(15, 15, 'Add tax_id into invoice_items table', '20060815', ''),
(16, 16, 'Add Payments table', '20060827', ''),
(17, 17, 'Adjust data type of quantity field', '20060827', ''),
(18, 18, 'Create Payment Types table', '20060909', ''),
(19, 19, 'Add info into the Payment Type table', '20060909', ''),
(20, 20, 'Adjust accounts payments table to add a type field', '20060909', ''),
(21, 21, 'Adjust the defaults table to add a payment type field', '20060909', ''),
(22, 22, 'Add note field to customer', '20061026', ''),
(23, 23, 'Add note field to Biller', '20061026', ''),
(24, 24, 'Add note field to Products', '20061026', ''),
(25, 25, 'Add street address 2 to customers', '20061211', ''),
(26, 26, 'Add custom fields to customers', '20061211', ''),
(27, 27, 'Add mobile phone to customers', '20061211', ''),
(28, 28, 'Add street address 2 to billers', '20061211', ''),
(29, 29, 'Add custom fields to billers', '20061211', ''),
(30, 30, 'Creating the custom fields table', '20061211', ''),
(31, 31, 'Adding data to the custom fields table', '20061211', ''),
(32, 32, 'Adding custom fields to products', '20061211', ''),
(33, 33, 'Alter product custom field 4', '20061214', ''),
(34, 34, 'Reset invoice template to default refer Issue 70', '20070125', ''),
(35, 35, 'Adding data to the custom fields table for invoices', '20070204', ''),
(36, 36, 'Adding custom fields to the invoices table', '20070204', ''),
(37, 0, 'Start', '20060514', ''),
(38, 37, 'Reset invoice template to default due to new invoice template system', '20070325', ''),
(39, 38, 'Alter custom field table - field length now 255 for field name', '20070325', ''),
(40, 39, 'Alter custom field table - field length now 255 for field name', '20070325', ''),
(41, 40, 'Alter field name in sql_patchmanager', '20070424', ''),
(42, 41, 'Alter field name in account_payments', '20070424', ''),
(43, 42, 'Alter field name b_name to name', '20070424', ''),
(44, 43, 'Alter field name b_id to id', '20070430', ''),
(45, 44, 'Alter field name b_street_address to street_address', '20070430', ''),
(46, 45, 'Alter field name b_street_address2 to street_address2', '20070430', ''),
(47, 46, 'Alter field name b_city to city', '20070430', ''),
(48, 47, 'Alter field name b_state to state', '20070430', ''),
(49, 48, 'Alter field name b_zip_code to zip_code', '20070430', ''),
(50, 49, 'Alter field name b_country to country', '20070430', ''),
(51, 50, 'Alter field name b_phone to phone', '20070430', ''),
(52, 51, 'Alter field name b_mobile_phone to mobile_phone', '20070430', ''),
(53, 52, 'Alter field name b_fax to fax', '20070430', ''),
(54, 53, 'Alter field name b_email to email', '20070430', ''),
(55, 54, 'Alter field name b_co_logo to logo', '20070430', ''),
(56, 55, 'Alter field name b_co_footer to footer', '20070430', ''),
(57, 56, 'Alter field name b_notes to notes', '20070430', ''),
(58, 57, 'Alter field name b_enabled to enabled', '20070430', ''),
(59, 58, 'Alter field name b_custom_field1 to custom_field1', '20070430', ''),
(60, 59, 'Alter field name b_custom_field2 to custom_field2', '20070430', ''),
(61, 60, 'Alter field name b_custom_field3 to custom_field3', '20070430', ''),
(62, 61, 'Alter field name b_custom_field4 to custom_field4', '20070430', ''),
(63, 62, 'Introduce system_defaults table', '20070503', ''),
(64, 63, 'Insert date into the system_defaults table', '20070503', ''),
(65, 64, 'Alter field name prod_id to id', '20070507', ''),
(66, 65, 'Alter field name prod_description to description', '20070507', ''),
(67, 66, 'Alter field name prod_unit_price to unit_price', '20070507', ''),
(68, 67, 'Alter field name prod_custom_field1 to custom_field1', '20070507', ''),
(69, 68, 'Alter field name prod_custom_field2 to custom_field2', '20070507', ''),
(70, 69, 'Alter field name prod_custom_field3 to custom_field3', '20070507', ''),
(71, 70, 'Alter field name prod_custom_field4 to custom_field4', '20070507', ''),
(72, 71, 'Alter field name prod_notes to notes', '20070507', ''),
(73, 72, 'Alter field name prod_enabled to enabled', '20070507', ''),
(74, 73, 'Alter field name c_id to id', '20070507', ''),
(75, 74, 'Alter field name c_attention to attention', '20070507', ''),
(76, 75, 'Alter field name c_name to name', '20070507', ''),
(77, 76, 'Alter field name c_street_address to street_address', '20070507', ''),
(78, 77, 'Alter field name c_street_address2 to street_address2', '20070507', ''),
(79, 78, 'Alter field name c_city to city', '20070507', ''),
(80, 79, 'Alter field name c_state to state', '20070507', ''),
(81, 80, 'Alter field name c_zip_code to zip_code', '20070507', ''),
(82, 81, 'Alter field name c_country to country', '20070507', ''),
(83, 82, 'Alter field name c_phone to phone', '20070507', ''),
(84, 83, 'Alter field name c_mobile_phone to mobile_phone', '20070507', ''),
(85, 84, 'Alter field name c_fax to fax', '20070507', ''),
(86, 85, 'Alter field name c_email to email', '20070507', ''),
(87, 86, 'Alter field name c_notes to notes', '20070507', ''),
(88, 87, 'Alter field name c_custom_field1 to custom_field1', '20070507', ''),
(89, 88, 'Alter field name c_custom_field2 to custom_field2', '20070507', ''),
(90, 89, 'Alter field name c_custom_field3 to custom_field3', '20070507', ''),
(91, 90, 'Alter field name c_custom_field4 to custom_field4', '20070507', ''),
(92, 91, 'Alter field name c_enabled to enabled', '20070507', ''),
(93, 92, 'Alter field name inv_id to id', '20070507', ''),
(94, 93, 'Alter field name inv_biller_id to biller_id', '20070507', ''),
(95, 94, 'Alter field name inv_customer_id to customer_id', '20070507', ''),
(96, 95, 'Alter field name inv_type type_id', '20070507', ''),
(97, 96, 'Alter field name inv_preference to preference_id', '20070507', ''),
(98, 97, 'Alter field name inv_date to date', '20070507', ''),
(99, 98, 'Alter field name invoice_custom_field1 to custom_field1', '20070507', ''),
(100, 99, 'Alter field name invoice_custom_field2 to custom_field2', '20070507', ''),
(101, 100, 'Alter field name invoice_custom_field3 to custom_field3', '20070507', ''),
(102, 101, 'Alter field name invoice_custom_field4 to custom_field4', '20070507', ''),
(103, 102, 'Alter field name inv_note to note ', '20070507', ''),
(104, 103, 'Alter field name inv_it_id to id ', '20070507', ''),
(105, 104, 'Alter field name inv_it_invoice_id to invoice_id ', '20070507', ''),
(106, 105, 'Alter field name inv_it_quantity to quantity ', '20070507', ''),
(107, 106, 'Alter field name inv_it_product_id to product_id ', '20070507', ''),
(108, 107, 'Alter field name inv_it_unit_price to unit_price ', '20070507', ''),
(109, 108, 'Alter field name inv_it_tax_id to tax_id  ', '20070507', ''),
(110, 109, 'Alter field name inv_it_tax to tax  ', '20070507', ''),
(111, 110, 'Alter field name inv_it_tax_amount to tax_amount  ', '20070507', ''),
(112, 111, 'Alter field name inv_it_gross_total to gross_total ', '20070507', ''),
(113, 112, 'Alter field name inv_it_description to description ', '20070507', ''),
(114, 113, 'Alter field name inv_it_total to total', '20070507', ''),
(115, 114, 'Add logging table', '20070514', ''),
(116, 115, 'Add logging system preference', '20070514', ''),
(117, 116, 'System defaults conversion patch - set default biller', '20070507', ''),
(118, 117, 'System defaults conversion patch - set default customer', '20070507', ''),
(119, 118, 'System defaults conversion patch - set default tax', '20070507', ''),
(120, 119, 'System defaults conversion patch - set default invoice preference', '20070507', ''),
(121, 120, 'System defaults conversion patch - set default number of line items', '20070507', ''),
(122, 121, 'System defaults conversion patch - set default invoice template', '20070507', ''),
(123, 122, 'System defaults conversion patch - set default payment type', '20070507', ''),
(124, 123, 'Add option to delete invoices into the system_defaults table', '200709', 'INSERT INTO `si_system_defaults` (`id`, `name`, `value`) VALUES \n('''', ''delete'', ''N'');'),
(125, 124, 'Set default language in new lang system', '200709', 'UPDATE `si_system_defaults` SET value = ''en-gb'' where name =''language'';'),
(126, 125, 'Change log table that usernames are also possible as id', '200709', 'ALTER TABLE `si_log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT ''0'''),
(127, 126, 'Add visible attribute to the products table', '200709', 'ALTER TABLE  `si_products` ADD  `visible` BOOL NOT NULL DEFAULT  ''1'';'),
(128, 127, 'Add last_id to logging table', '200709', 'ALTER TABLE  `si_log` ADD  `last_id` INT NULL ;'),
(129, 128, 'Add user table', '200709', 'CREATE TABLE IF NOT EXISTS `si_users` (\n           `user_id` int(11) NOT NULL auto_increment,\n            `user_email` varchar(255) NOT NULL,\n           `user_name` varchar(255) NOT NULL,\n            `user_group` varchar(255) NOT NULL,\n           `user_domain` varchar(255) NOT NULL,\n          `user_password` varchar(255) NOT NULL,\n            PRIMARY KEY  (`user_id`)\n          ) ;'),
(130, 129, 'Fill user table with default values', '200709', 'INSERT INTO `si_users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`) VALUES \n('''', ''demo@simpleinvoices.org'', ''demo'', ''1'', ''1'', MD5(''demo''))'),
(131, 130, 'Create auth_challenges table', '200709', 'CREATE TABLE IF NOT EXISTS `si_auth_challenges` (\n               `challenges_key` int(11) NOT NULL,\n                `challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);'),
(132, 131, 'Make tax field 3 decimal places', '200709', 'ALTER TABLE `si_tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL'),
(133, 132, 'Correct Foreign Key Tax ID Field Type in Invoice Items Table', '20071126', 'ALTER TABLE  `si_invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT ''0'' NOT NULL ;'),
(134, 133, 'Correct Foreign Key Invoice ID Field Type in Ac Payments Table', '20071126', 'ALTER TABLE  `si_account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;'),
(135, 134, 'Drop non-int compatible default from si_sql_patchmanager', '20071218', 'SELECT 1+1;'),
(136, 135, 'Change sql_patch_ref type in sql_patchmanager to int', '20071218', 'ALTER TABLE  `si_sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;'),
(137, 136, 'Create domain mapping table', '200712', 'CREATE TABLE si_user_domain (\n        `id` int(11) NOT NULL auto_increment  PRIMARY KEY,\n            `name` varchar(255) UNIQUE NOT NULL\n            ) ENGINE=InnoDB;'),
(138, 137, 'Insert default domain', '200712', 'INSERT INTO si_user_domain (name)\n        VALUES (''default'');'),
(139, 138, 'Add domain_id to payment_types table', '200712', 'ALTER TABLE `si_payment_types` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `pt_id` ;'),
(140, 139, 'Add domain_id to preferences table', '200712', 'ALTER TABLE `si_preferences` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `pref_id` ;'),
(141, 140, 'Add domain_id to products table', '200712', 'ALTER TABLE `si_products` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `id` ;'),
(142, 141, 'Add domain_id to billers table', '200712', 'ALTER TABLE `si_biller` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `id` ;'),
(143, 142, 'Add domain_id to invoices table', '200712', 'ALTER TABLE `si_invoices` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `id` ;'),
(144, 143, 'Add domain_id to customers table', '200712', 'ALTER TABLE `si_customers` ADD `domain_id` INT DEFAULT ''1'' NOT NULL AFTER `id` ;'),
(145, 144, 'Change group field to user_role_id in users table', '20080102', 'ALTER TABLE `si_users` CHANGE `user_group` `user_role_id` INT  DEFAULT ''1'' NOT NULL;'),
(146, 145, 'Change domain field to user_domain_id in users table', '20080102', 'ALTER TABLE `si_users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT ''1'' NOT NULL;'),
(147, 146, 'Drop old auth_challenges table', '20080102', 'DROP TABLE IF EXISTS `si_auth_challenges`;'),
(148, 147, 'Create user_role table', '20080102', 'CREATE TABLE si_user_role (\n     `id` int(11) NOT NULL auto_increment  PRIMARY KEY,\n            `name` varchar(255) UNIQUE NOT NULL\n            ) ENGINE=InnoDB;'),
(149, 148, 'Insert default user group', '20080102', 'INSERT INTO si_user_role (name) VALUES (''administrator'');'),
(150, 149, 'Table = Account_payments Field = ac_amount : change field type and length to decimal', '20080128', 'ALTER TABLE `si_account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;'),
(151, 150, 'Table = Invoice_items Field = quantity : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT ''0'' '),
(152, 151, 'Table = Invoice_items Field = unit_price : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' '),
(153, 152, 'Table = Invoice_items Field = tax : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' '),
(154, 153, 'Table = Invoice_items Field = tax_amount : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'''),
(155, 154, 'Table = Invoice_items Field = gross_total : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'''),
(156, 155, 'Table = Invoice_items Field = total : change field type and length to decimal', '20080128', 'ALTER TABLE `si_invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' '),
(157, 156, 'Table = Products Field = unit_price : change field type and length to decimal', '20080128', 'ALTER TABLE `si_products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'''),
(158, 157, 'Table = Tax Field = quantity : change field type and length to decimal', '20080128', 'ALTER TABLE `si_tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'''),
(159, 158, 'Rename table si_account_payments to si_payment', '20081201', 'RENAME TABLE `si_account_payments` TO  `si_payment`;'),
(160, 159, 'Add domain_id to payments table', '20081201', 'ALTER TABLE  `si_payment` ADD  `domain_id` INT NOT NULL ;'),
(161, 160, 'Add domain_id to tax table', '20081201', 'ALTER TABLE  `si_tax` ADD  `domain_id` INT NOT NULL ;'),
(162, 161, 'Change user table from si_users to si_user', '20081201', 'RENAME TABLE `si_users` TO  `si_user` ;'),
(163, 162, 'Add new invoice items tax table', '20081212', 'CREATE TABLE `si_invoice_item_tax` (\n       `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n      `invoice_item_id` INT( 11 ) NOT NULL ,\n        `tax_id` INT( 11 ) NOT NULL ,\n     `tax_type` VARCHAR( 1 ) NOT NULL ,\n        `tax_rate` DECIMAL( 25, 6 ) NOT NULL ,\n        `tax_amount` DECIMAL( 25, 6 ) NOT NULL\n        ) ENGINE = MYISAM ;'),
(164, 163, 'Concert tax info in si_invoice_items to si_invoice_item_tax', '20081212', 'insert into `si_invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount) select invoice_id, tax_id, ''%'', tax, tax_amount from `si_invoice_items`;'),
(165, 164, 'Add default tax id into products table', '20081212', 'ALTER TABLE `si_products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;'),
(166, 165, 'Add default tax id 2 into products table', '20081212', 'ALTER TABLE `si_products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;'),
(167, 166, 'Add default tax into product items', '20081212', 'update `si_products` set default_tax_id = (select value from `si_system_defaults` where name =''tax'');'),
(168, 167, 'Add default number of taxes per line item into system_defaults', '20081212', 'insert into `si_system_defaults` values ('''',''tax_per_line_item'',''1'')'),
(169, 168, 'Add tax type', '20081212', 'ALTER TABLE `si_tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;'),
(170, 169, 'Set tax type on current taxes to %', '20081212', 'UPDATE `si_tax` SET `type` = ''%'' ;'),
(171, 170, 'Set domain_id on tax table to 1', '20081229', 'UPDATE `si_tax` SET `domain_id` = ''1'' ;'),
(172, 171, 'Set domain_id on payment table to 1', '20081229', 'UPDATE `si_payment` SET `domain_id` = ''1'' ;'),
(173, 172, 'Set domain_id on payment_types table to 1', '20081229', 'UPDATE `si_payment_types` SET `domain_id` = ''1'' ;'),
(174, 173, 'Set domain_id on preference table to 1', '20081229', 'UPDATE `si_preferences` SET `domain_id` = ''1'' ;'),
(175, 174, 'Set domain_id on products table to 1', '20081229', 'UPDATE `si_products` SET `domain_id` = ''1'' ;'),
(176, 175, 'Set domain_id on biller table to 1', '20081229', 'UPDATE `si_biller` SET `domain_id` = ''1'' ;'),
(177, 176, 'Set domain_id on invoices table to 1', '20081229', 'UPDATE `si_invoices` SET `domain_id` = ''1'' ;'),
(178, 177, 'Set domain_id on customers table to 1', '20081229', 'UPDATE `si_customers` SET `domain_id` = ''1'' ;'),
(179, 178, 'Rename si_user.user_id to si_user.id', '20081229', 'ALTER TABLE `si_user` CHANGE `user_id` `id` int(11) ;'),
(180, 179, 'Rename si_user.user_email to si_user.email', '20081229', 'ALTER TABLE `si_user` CHANGE `user_email` `email` VARCHAR( 255 );'),
(181, 180, 'Rename si_user.user_name to si_user.name', '20081229', 'ALTER TABLE `si_user` CHANGE `user_name` `name` VARCHAR( 255 );'),
(182, 181, 'Rename si_user.user_role_id to si_user.role_id', '20081229', 'ALTER TABLE `si_user` CHANGE `user_role_id` `role_id` int(11);'),
(183, 182, 'Rename si_user.user_domain_id to si_user.domain_id', '20081229', 'ALTER TABLE `si_user` CHANGE `user_domain_id` `domain_id` int(11) ;'),
(184, 183, 'Rename si_user.user_password to si_user.password', '20081229', 'ALTER TABLE `si_user` CHANGE `user_password` `password` VARCHAR( 255 )  ;'),
(185, 184, 'Drop name column from si_user table', '20081230', 'ALTER TABLE `si_user` DROP `name`  ;'),
(186, 185, 'Drop old defaults table', '20081230', 'DROP TABLE `si_defaults` ;'),
(187, 186, 'Set domain_id on customers table to 1', '20081230', 'ALTER TABLE  `si_custom_fields` ADD  `domain_id` INT NOT NULL ;'),
(188, 187, 'Set domain_id on custom_feilds table to 1', '20081230', 'UPDATE `si_custom_fields` SET `domain_id` = ''1'' ;'),
(189, 188, 'Drop tax_id column from si_invoice_items table', '20090118', 'ALTER TABLE `si_invoice_items` DROP `tax_id`  ;'),
(190, 189, 'Drop tax column from si_invoice_items table', '20090118', 'ALTER TABLE `si_invoice_items` DROP `tax`  ;'),
(191, 190, 'Insert user role - user', '20090215', 'INSERT INTO si_user_role (name) VALUES (''user'');'),
(192, 191, 'Insert user role - viewer', '20090215', 'INSERT INTO si_user_role (name) VALUES (''viewer'');'),
(193, 192, 'Insert user role - customer', '20090215', 'INSERT INTO si_user_role (name) VALUES (''customer'');'),
(194, 193, 'Insert user role - biller', '20090215', 'INSERT INTO si_user_role (name) VALUES (''biller'');'),
(195, 194, 'User table - auto increment', '20090215', 'ALTER TABLE si_user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;'),
(196, 195, 'User table - add enabled field', '20090215', 'ALTER TABLE si_user ADD enabled INT( 1 ) NOT NULL ;'),
(197, 196, 'User table - make all existing users enabled', '20090217', 'UPDATE si_user SET enabled = 1 ;'),
(198, 197, 'Defaults table - add domain_id and extension_id field', '20090321', 'ALTER TABLE si_system_defaults \n              ADD `domain_id` INT( 5 ) NOT NULL DEFAULT ''0'',\n              ADD `extension_id` INT( 5 ) NOT NULL DEFAULT ''0'',\n               DROP INDEX `name`,\n                ADD INDEX `name` ( `name` );'),
(199, 198, 'Extension table - create table to hold extension status', '20090322', 'CREATE TABLE si_extensions ( \n      `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n      `domain_id` INT( 11 ) NOT NULL ,\n      `name` VARCHAR( 255 ) NOT NULL ,\n      `description` VARCHAR( 255 ) NOT NULL ,\n       `enabled` VARCHAR( 1 ) NOT NULL DEFAULT ''0'');\n            INSERT INTO si_extensions (\n          `id`,`domain_id`,`name`,`description`,`enabled`) \n         VALUES (''0'',''0'',''core'',''Core part of Simple Invoices - always enabled'',''1'');\n         UPDATE si_extensions SET `id` = ''0'' WHERE `name` = ''core'' LIMIT 1;'),
(200, 199, 'Update extensions table', '20090529', 'INSERT INTO si_extensions (\n            `id`,`domain_id`,`name`,`description`,`enabled`) \n         VALUES (''0'',''0'',''core'',''Core part of Simple Invoices - always enabled'',''1'');'),
(201, 200, 'Update extensions table', '20090529', 'UPDATE si_extensions SET `id` = ''0'' WHERE `name` = ''core'' LIMIT 1;');

--
-- Dumping data for table `si_system_defaults`
--

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES
(1, 'biller', '4', 0, 0),
(2, 'customer', '3', 0, 0),
(3, 'tax', '1', 0, 0),
(4, 'preference', '1', 0, 0),
(5, 'line_items', '5', 0, 0),
(6, 'template', 'default', 0, 0),
(7, 'payment_type', '1', 0, 0),
(8, 'language', 'en-gb', 0, 0),
(9, 'dateformat', 'Y-m-d', 0, 0),
(10, 'spreadsheet', 'xls', 0, 0),
(11, 'wordprocessor', 'doc', 0, 0),
(12, 'pdfscreensize', '800', 0, 0),
(13, 'pdfpapersize', 'A4', 0, 0),
(14, 'pdfleftmargin', '15', 0, 0),
(15, 'pdfrightmargin', '15', 0, 0),
(16, 'pdftopmargin', '15', 0, 0),
(17, 'pdfbottommargin', '15', 0, 0),
(18, 'emailhost', 'localhost', 0, 0),
(19, 'emailusername', '', 0, 0),
(20, 'emailpassword', '', 0, 0),
(21, 'logging', '0', 0, 0),
(22, 'delete', 'N', 0, 0),
(23, 'tax_per_line_item', '1', 0, 0);

--
-- Dumping data for table `si_tax`
--

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES
(1, 'GST', '10.000000', '%', '1', 1),
(2, 'VAT', '10.000000', '%', '1', 1),
(3, 'Sales Tax', '10.000000', '%', '1', 1),
(4, 'No Tax', '0.000000', '%', '1', 1);

--
-- Dumping data for table `si_user`
--

INSERT INTO `si_user` (`id`, `email`, `role_id`, `domain_id`, `password`, `enabled`) VALUES
(1, 'demo@simpleinvoices.org', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1);

--
-- Dumping data for table `si_user_domain`
--

INSERT INTO `si_user_domain` (`id`, `name`) VALUES
(1, 'default');

--
-- Dumping data for table `si_user_role`
--

INSERT INTO `si_user_role` (`id`, `name`) VALUES
(1, 'administrator'),
(2, 'user'),
(3, 'viewer'),
(4, 'customer'),
(5, 'biller');

