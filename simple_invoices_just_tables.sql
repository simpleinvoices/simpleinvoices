-- phpMyAdmin SQL Dump
-- version 2.8.0.3-Debian-1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 25, 2006 at 12:16 PM
-- Server version: 5.0.22
-- PHP Version: 5.1.2
-- 
-- Database: `simple_invoices`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `si_account_payments`
-- 

CREATE TABLE `si_account_payments` (
  `ac_id` int(10) NOT NULL auto_increment,
  `ac_inv_id` varchar(10) collate utf8_unicode_ci NOT NULL,
  `ac_amount` double(25,2) NOT NULL,
  `ac_notes` text collate utf8_unicode_ci NOT NULL,
  `ac_date` datetime NOT NULL,
  PRIMARY KEY  (`ac_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `si_account_payments`
-- 

INSERT INTO `si_account_payments` (`ac_id`, `ac_inv_id`, `ac_amount`, `ac_notes`, `ac_date`) VALUES (1, '1', 410.00, 'payment - cheque 14526', '2006-08-25 12:09:14'),
(2, '4', 255.75, '', '2006-08-25 12:13:53');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_biller`
-- 

CREATE TABLE `si_biller` (
  `b_id` int(10) NOT NULL auto_increment,
  `b_name` varchar(50) default NULL,
  `b_street_address` varchar(50) default NULL,
  `b_city` varchar(50) default NULL,
  `b_state` varchar(50) default NULL,
  `b_zip_code` varchar(50) default NULL,
  `b_country` varchar(100) default NULL,
  `b_phone` varchar(50) default NULL,
  `b_mobile_phone` varchar(50) default NULL,
  `b_fax` varchar(50) default NULL,
  `b_email` varchar(50) default NULL,
  `b_co_logo` varchar(50) default NULL,
  `b_co_footer` text,
  `b_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`b_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `si_biller`
-- 

INSERT INTO `si_biller` (`b_id`, `b_name`, `b_street_address`, `b_city`, `b_state`, `b_zip_code`, `b_country`, `b_phone`, `b_mobile_phone`, `b_fax`, `b_email`, `b_co_logo`, `b_co_footer`, `b_enabled`) VALUES (1, 'Mr Plough', '43 Evergreen Terace', 'Springfield', 'New York', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '1'),
(2, 'Homer Simpson', '43 Evergreen Terace', 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, '1'),
(3, 'The Beer Baron', '43 Evergreen Terace', 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, '1'),
(4, 'Fawlty Towers', '13 Seaside Drive', 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_customers`
-- 

CREATE TABLE `si_customers` (
  `c_id` int(10) NOT NULL auto_increment,
  `c_attention` varchar(50) default NULL,
  `c_name` varchar(50) default NULL,
  `c_street_address` varchar(50) default NULL,
  `c_city` varchar(50) default NULL,
  `c_state` varchar(50) default NULL,
  `c_zip_code` varchar(50) default NULL,
  `c_country` varchar(100) default NULL,
  `c_phone` varchar(50) default NULL,
  `c_fax` varchar(50) default NULL,
  `c_email` varchar(50) default NULL,
  `c_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `si_customers`
-- 

INSERT INTO `si_customers` (`c_id`, `c_attention`, `c_name`, `c_street_address`, `c_city`, `c_state`, `c_zip_code`, `c_country`, `c_phone`, `c_fax`, `c_email`, `c_enabled`) VALUES (1, 'Moe Sivloski', 'Moes Tarvern', '45 Main Road', 'Springfield', 'New York', '65891', '', '04 1234 5698', '04 5689 4566', 'moe@moestavern.com', '0'),
(2, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', 'Springfield', 'New York', '90210', NULL, '04 1235 5698', '04 5678 7899', 'mr.burn@spp.com', '0'),
(3, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', '03 9658 7457', 'kath@kathandkim.com.au', '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_defaults`
-- 

CREATE TABLE `si_defaults` (
  `def_id` int(10) NOT NULL auto_increment,
  `def_biller` int(25) default NULL,
  `def_customer` int(25) default NULL,
  `def_tax` int(25) default NULL,
  `def_inv_preference` int(25) default NULL,
  `def_number_line_items` int(25) NOT NULL default '0',
  `def_inv_template` varchar(50) NOT NULL default 'print_preview.php',
  PRIMARY KEY  (`def_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `si_defaults`
-- 

INSERT INTO `si_defaults` (`def_id`, `def_biller`, `def_customer`, `def_tax`, `def_inv_preference`, `def_number_line_items`, `def_inv_template`) VALUES (1, 4, 3, 1, 1, 5, 'print_preview.php');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_invoice_items`
-- 

CREATE TABLE `si_invoice_items` (
  `inv_it_id` int(10) NOT NULL auto_increment,
  `inv_it_invoice_id` int(10) NOT NULL default '0',
  `inv_it_quantity` float NOT NULL default '0',
  `inv_it_product_id` int(10) default '0',
  `inv_it_unit_price` double(25,2) default '0.00',
  `inv_it_tax_id` varchar(25) NOT NULL default '0',
  `inv_it_tax` double(25,2) default '0.00',
  `inv_it_tax_amount` double(25,2) default NULL,
  `inv_it_gross_total` double(25,2) default '0.00',
  `inv_it_description` text,
  `inv_it_total` double(25,2) default '0.00',
  PRIMARY KEY  (`inv_it_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `si_invoice_type`
-- 

CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL auto_increment,
  `inv_ty_description` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`inv_ty_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `si_invoice_type`
-- 

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES (1, 'Total'),
(2, 'Itemised'),
(3, 'Consulting');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_invoices`
-- 

CREATE TABLE `si_invoices` (
  `inv_id` int(10) NOT NULL auto_increment,
  `inv_biller_id` int(10) NOT NULL default '0',
  `inv_customer_id` int(10) NOT NULL default '0',
  `inv_type` int(10) NOT NULL default '0',
  `inv_preference` int(10) NOT NULL default '0',
  `inv_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `inv_note` text,
  PRIMARY KEY  (`inv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `si_preferences`
-- 

CREATE TABLE `si_preferences` (
  `pref_id` int(11) NOT NULL auto_increment,
  `pref_description` varchar(50) default NULL,
  `pref_currency_sign` varchar(50) default NULL,
  `pref_inv_heading` varchar(50) default NULL,
  `pref_inv_wording` varchar(50) default NULL,
  `pref_inv_detail_heading` varchar(50) default NULL,
  `pref_inv_detail_line` text,
  `pref_inv_payment_method` varchar(50) default NULL,
  `pref_inv_payment_line1_name` varchar(50) default NULL,
  `pref_inv_payment_line1_value` varchar(50) default NULL,
  `pref_inv_payment_line2_name` varchar(50) default NULL,
  `pref_inv_payment_line2_value` varchar(50) default NULL,
  `pref_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`pref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `si_preferences`
-- 

INSERT INTO `si_preferences` (`pref_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`) VALUES (1, 'Invoice - default', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name:', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1'),
(2, 'Invoice - no payment details', '$', 'Invoice', 'Invoice', NULL, '', '', '', '', '', '', '1'),
(3, 'Receipt - default', '$', 'Receipt', 'Receipt', 'Details', '<br>This transaction has been paid in full, please keep this receipt as proof of purchase.<br> Thank you', '', '', '', '', '', '1'),
(4, 'Estimate - default', '$', 'Estimate', 'Estimate', 'Details', '<br>This is an estimate of the final value of services rendered.<br>Thank you', '', '', '', '', '', '1'),
(5, 'Quote - default', '$', 'Quote', 'Quote', 'Details', '<br>This is a quote of the final value of services rendered.<br>Thank you', '', '', '', '', '', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_products`
-- 

CREATE TABLE `si_products` (
  `prod_id` int(11) NOT NULL auto_increment,
  `prod_description` text NOT NULL,
  `prod_unit_price` decimal(25,2) default NULL,
  `prod_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`prod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `si_products`
-- 

INSERT INTO `si_products` (`prod_id`, `prod_description`, `prod_unit_price`, `prod_enabled`) VALUES (1, 'IBM Netfinity 5000', 150.00, '0'),
(2, 'Accouting services - Barney Gumball (hours)', 140.00, '0'),
(3, 'Weekly ploughing service', 125.00, '0'),
(4, 'Bootleg homebrew', 15.50, '0'),
(5, 'Accomadation', 125.00, '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_sql_patchmanager`
-- 

CREATE TABLE `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL auto_increment,
  `sql_patch_ref` varchar(50) NOT NULL default '',
  `sql_patch` varchar(50) NOT NULL default '',
  `sql_release` varchar(25) NOT NULL default '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY  (`sql_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `si_sql_patchmanager`
-- 

INSERT INTO `si_sql_patchmanager` (`sql_id`, `sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`) VALUES (1, '1', 'Create si_sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM '),
(2, '2', 'Update invoice no details to have a default curren', '20060514', ''),
(3, '3', 'Add a row into the defaults table to handle the de', '20060514', ''),
(4, '4', 'Set the default number of line items to 5', '20060514', ''),
(5, '5', 'Add logo and invoice footer support to biller', '20060514', ''),
(6, '6', 'Add default invoice template option', '20060514', ''),
(7, '7', 'Edit tax description field lenght to 50', '20060526', ''),
(8, '8', 'Edit default invoice template field lenght to 50', '20060526', ''),
(9, '9', 'Add consulting style invoice', '20060531', ''),
(10, '10', 'Add enabled to biller', '20060815', ''),
(11, '11', 'Add enabled to customters', '20060815', ''),
(12, '12', 'Add enabled to prefernces', '20060815', ''),
(13, '13', 'Add enabled to products', '20060815', ''),
(14, '14', 'Add enabled to products', '20060815', ''),
(15, '15', 'Add tax_id into invoice_items table', '20060815', ''),
(16, '16', 'Add Payments table', '20060827', ''),
(17, '17', 'Adjust data type of quantuty field', '20060827', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_tax`
-- 

CREATE TABLE `si_tax` (
  `tax_id` int(11) NOT NULL auto_increment,
  `tax_description` varchar(50) default NULL,
  `tax_percentage` decimal(10,2) default NULL,
  `tax_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`tax_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `si_tax`
-- 

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `tax_enabled`) VALUES (1, 'GST (AUS)', 10.00, '1'),
(2, 'VAT (UK)', 10.00, '1'),
(3, 'Sales Tax (USA)', 10.00, '1'),
(4, 'GST (NZ)', 10.00, '1'),
(5, 'No Tax', 0.00, '1');
