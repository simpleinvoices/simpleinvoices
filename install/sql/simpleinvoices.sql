-- 
-- Tabellenstruktur fuer Tabelle `si_account_payments`
-- 

CREATE TABLE `si_account_payments` (
  `id` int(10) NOT NULL auto_increment,
  `ac_inv_id` varchar(10) collate utf8_unicode_ci NOT NULL,
  `ac_amount` double(25,2) NOT NULL,
  `ac_notes` text collate utf8_unicode_ci NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_biller`
-- 

CREATE TABLE `si_biller` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `street_address` varchar(255) default NULL,
  `street_address2` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `zip_code` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `mobile_phone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `logo` varchar(255) default NULL,
  `footer` text,
  `notes` text,
  `custom_field1` varchar(255) default NULL,
  `custom_field2` varchar(255) default NULL,
  `custom_field3` varchar(255) default NULL,
  `custom_field4` varchar(255) default NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_customers`
-- 

CREATE TABLE `si_customers` (
  `id` int(10) NOT NULL auto_increment,
  `attention` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `street_address` varchar(255) default NULL,
  `street_address2` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `zip_code` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `mobile_phone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `notes` text,
  `custom_field1` varchar(255) default NULL,
  `custom_field2` varchar(255) default NULL,
  `custom_field3` varchar(255) default NULL,
  `custom_field4` varchar(255) default NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_custom_fields`
-- 

CREATE TABLE `si_custom_fields` (
  `cf_id` int(11) NOT NULL auto_increment,
  `cf_custom_field` varchar(255) collate utf8_unicode_ci default NULL,
  `cf_custom_label` varchar(255) collate utf8_unicode_ci default NULL,
  `cf_display` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_defaults`
-- 

CREATE TABLE `si_defaults` (
  `def_id` int(10) NOT NULL auto_increment,
  `def_biller` int(25) default NULL,
  `def_customer` int(25) default NULL,
  `def_tax` int(25) default NULL,
  `def_inv_preference` int(25) default NULL,
  `def_number_line_items` int(25) NOT NULL default '0',
  `def_inv_template` varchar(50) NOT NULL default 'default',
  `def_payment_type` varchar(25) default '1',
  PRIMARY KEY  (`def_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_invoices`
-- 

CREATE TABLE `si_invoices` (
  `id` int(10) NOT NULL auto_increment,
  `biller_id` int(10) NOT NULL default '0',
  `customer_id` int(10) NOT NULL default '0',
  `type_id` int(10) NOT NULL default '0',
  `preference_id` int(10) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `custom_field1` varchar(50) default NULL,
  `custom_field2` varchar(50) default NULL,
  `custom_field3` varchar(50) default NULL,
  `custom_field4` varchar(50) default NULL,
  `note` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_invoice_items`
-- 

CREATE TABLE `si_invoice_items` (
  `id` int(10) NOT NULL auto_increment,
  `invoice_id` int(10) NOT NULL default '0',
  `quantity` float NOT NULL default '0',
  `product_id` int(10) default '0',
  `unit_price` double(25,2) default '0.00',
  `tax_id` varchar(25) NOT NULL default '0',
  `tax` double(25,2) default '0.00',
  `tax_amount` double(25,2) default NULL,
  `gross_total` double(25,2) default '0.00',
  `description` text,
  `total` double(25,2) default '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_invoice_type`
-- 

CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL auto_increment,
  `inv_ty_description` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`inv_ty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_payment_types`
-- 

CREATE TABLE `si_payment_types` (
  `pt_id` int(10) NOT NULL auto_increment,
  `pt_description` varchar(250) collate utf8_unicode_ci NOT NULL,
  `pt_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`pt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_preferences`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_products`
-- 

CREATE TABLE `si_products` (
  `id` int(11) NOT NULL auto_increment,
  `description` text NOT NULL,
  `unit_price` decimal(25,2) default NULL,
  `custom_field1` varchar(255) default NULL,
  `custom_field2` varchar(255) default NULL,
  `custom_field3` varchar(255) default NULL,
  `custom_field4` varchar(255) default NULL,
  `notes` text NOT NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Tabellenstruktur fuer Tabelle `si_sql_patchmanager`
-- 

CREATE TABLE `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL auto_increment,
  `sql_patch_ref` varchar(50) NOT NULL default '',
  `sql_patch` varchar(255) NOT NULL,
  `sql_release` varchar(25) NOT NULL default '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY  (`sql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=104 ;

-- 
-- Daten fuer Tabelle `si_sql_patchmanager`
-- 

INSERT INTO `si_sql_patchmanager` (`sql_id`, `sql_patch_ref`, `sql_patch`, `sql_release`, `sql_statement`) VALUES 
(1, '1', 'Create si_sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM '),
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
(17, '17', 'Adjust data type of quantuty field', '20060827', ''),
(18, '18', 'Create Payment Types table', '20060909', ''),
(19, '19', 'Add info into the Payment Type table', '20060909', ''),
(20, '20', 'Adjust accounts payments table to add a type field', '20060909', ''),
(21, '21', 'Adjust the defautls table to add a payment type fi', '20060909', ''),
(22, '22', 'Add note field to customer', '20061026', ''),
(23, '23', 'Add note field to Biller', '20061026', ''),
(24, '24', 'Add note field to Products', '20061026', ''),
(25, '25', 'Add street address 2 to customers', '20061211', ''),
(26, '26', 'Add custom fields to customers', '20061211', ''),
(27, '27', 'Add mobile phone to customers', '20061211', ''),
(28, '28', 'Add street address 2 to billers', '20061211', ''),
(29, '29', 'Add custom fields to billers', '20061211', ''),
(30, '30', 'Creating the custom fields table', '20061211', ''),
(31, '31', 'Adding data to the custom fields table', '20061211', ''),
(32, '32', 'Adding custom fields to products', '20061211', ''),
(33, '0', 'Start', '20060514', ''),
(34, '33', 'Alter product custom field 4', '20061214', ''),
(35, '34', 'Reset invoice template to default refer Issue 70', '20070125', ''),
(36, '35', 'Adding data to the custom fields table for invoice', '20070204', ''),
(37, '36', 'Adding custom fields to the invoices table', '20070204', ''),
(38, '37', 'Reset invoice template to default due to new invoi', '20070325', ''),
(39, '38', 'Alter custom field table - field length now 255 fo', '20070325', ''),
(40, '39', 'Alter custom field table - field length now 255 fo', '20070325', ''),
(41, '40', 'Alter field name in si_partchmanager', '20070424', ''),
(42, '41', 'Alter field name in si_account_payments', '20070424', ''),
(43, '42', 'Alter field name b_name to name', '20070424', ''),
(44, '43', 'Alter field name b_id to id', '20070430', ''),
(45, '44', 'Alter field name b_street_address to street_address', '20070430', ''),
(46, '45', 'Alter field name b_street_address2 to street_address2', '20070430', ''),
(47, '46', 'Alter field name b_city to city', '20070430', ''),
(48, '47', 'Alter field name b_state to state', '20070430', ''),
(49, '48', 'Alter field name b_zip_code to zip_code', '20070430', ''),
(50, '49', 'Alter field name b_country to country', '20070430', ''),
(51, '50', 'Alter field name b_phone to phone', '20070430', ''),
(52, '51', 'Alter field name b_mobile_phone to mobile_phone', '20070430', ''),
(53, '52', 'Alter field name b_fax to fax', '20070430', ''),
(54, '53', 'Alter field name b_email to email', '20070430', ''),
(55, '54', 'Alter field name b_co_logo to logo', '20070430', ''),
(56, '55', 'Alter field name b_co_footer to footer', '20070430', ''),
(57, '56', 'Alter field name b_notes to notes', '20070430', ''),
(58, '57', 'Alter field name b_enabled to enabled', '20070430', ''),
(59, '58', 'Alter field name b_custom_field1 to custom_field1', '20070430', ''),
(60, '59', 'Alter field name b_custom_field2 to custom_field2', '20070430', ''),
(61, '60', 'Alter field name b_custom_field3 to custom_field3', '20070430', ''),
(62, '61', 'Alter field name b_custom_field4 to custom_field4', '20070430', ''),
(63, '62', 'Introduce system_defaults table', '20070503', ''),
(64, '63', 'Insert date into the system_defaults table', '20070503', ''),
(65, '64', 'Alter field name prod_id to id', '20070507', ''),
(66, '65', 'Alter field name prod_description to description', '20070507', ''),
(67, '66', 'Alter field name prod_unit_price to unit_price', '20070507', ''),
(68, '67', 'Alter field name prod_custom_field1 to custom_field1', '20070507', ''),
(69, '68', 'Alter field name prod_custom_field2 to custom_field2', '20070507', ''),
(70, '69', 'Alter field name prod_custom_field3 to custom_field3', '20070507', ''),
(71, '70', 'Alter field name prod_custom_field4 to custom_field4', '20070507', ''),
(72, '71', 'Alter field name prod_notes to notes', '20070507', ''),
(73, '72', 'Alter field name prod_enabled to enabled', '20070507', ''),
(74, '73', 'Alter field name c_id to id', '20070507', ''),
(75, '74', 'Alter field name c_attention to attention', '20070507', ''),
(76, '75', 'Alter field name c_name to name', '20070507', ''),
(77, '76', 'Alter field name c_street_address to street_address', '20070507', ''),
(78, '77', 'Alter field name c_street_address2 to street_address2', '20070507', ''),
(79, '78', 'Alter field name c_city to city', '20070507', ''),
(80, '79', 'Alter field name c_state to state', '20070507', ''),
(81, '80', 'Alter field name c_zip_code to zip_code', '20070507', ''),
(82, '81', 'Alter field name c_country to countyr', '20070507', ''),
(83, '82', 'Alter field name c_phone to phone', '20070507', ''),
(84, '83', 'Alter field name c_mobile_phone to mobile_phone', '20070507', ''),
(85, '84', 'Alter field name c_fax to fax', '20070507', ''),
(86, '85', 'Alter field name c_email to email', '20070507', ''),
(87, '86', 'Alter field name c_notes to notes', '20070507', ''),
(88, '87', 'Alter field name c_custom_field1 to custom_field1', '20070507', ''),
(89, '88', 'Alter field name c_custom_field2 to custom_field2', '20070507', ''),
(90, '89', 'Alter field name c_custom_field3 to custom_field3', '20070507', ''),
(91, '90', 'Alter field name c_custom_field4 to custom_field4', '20070507', ''),
(92, '91', 'Alter field name c_enabled to enabled', '20070507', ''),
(93, '92', 'Alter field name inv_id to id', '20070507', ''),
(94, '93', 'Alter field name inv_biller_id to biller_id', '20070507', ''),
(95, '94', 'Alter field name inv_customer_id to customer_id', '20070507', ''),
(96, '95', 'Alter field name inv_type type_id', '20070507', ''),
(97, '96', 'Alter field name inv_preference to preference_id', '20070507', ''),
(98, '97', 'Alter field name inv_date to date', '20070507', ''),
(99, '98', 'Alter field name invoice_custom_field1 to custom_field1', '20070507', ''),
(100, '99', 'Alter field name invoice_custom_field2 to custom_field2', '20070507', ''),
(101, '100', 'Alter field name invoice_custom_field3 to custom_field3', '20070507', ''),
(102, '101', 'Alter field name invoice_custom_field4 to custom_field4', '20070507', ''),
(103, '102', 'Alter field name inv_note to note ', '20070507', '');

-- 
-- Tabellenstruktur fuer Tabelle `si_system_defaults`
-- 

CREATE TABLE `si_system_defaults` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- 
-- Daten fuer Tabelle `si_system_defaults`
-- 

INSERT INTO `si_system_defaults` (`id`, `name`, `value`) VALUES 
(1, 'biller', '4'),
(2, 'customer', '3'),
(3, 'tax', '1'),
(4, 'preference', '1'),
(5, 'line_items', '5'),
(6, 'template', 'simple'),
(7, 'payment_type', '1'),
(8, 'language', 'de'),
(9, 'language', 'en'),
(10, 'language', 'es'),
(11, 'language', 'es_ca'),
(12, 'language', 'es_gl'),
(13, 'language', 'fi'),
(14, 'language', 'fr'),
(15, 'language', 'nl'),
(16, 'language', 'pt'),
(17, 'language', 'ro'),
(18, 'dateformat', 'Y-m-d'),
(19, 'dateformat', 'Y-m-d h:m'),
(20, 'dateformat', 'm-d-Y'),
(21, 'dateformat', 'm-d-Y h:m'),
(22, 'dateformat', 'd-m-Y'),
(23, 'dateformat', 'd-m-Y h:m'),
(24, 'dateformat', 'j.n.Y'),
(25, 'dateformat', 'Y-m-d'),
(26, 'spreadsheet', 'xls'),
(27, 'spreadsheet', 'ods'),
(28, 'wordprocessor', 'doc'),
(29, 'wordprocessor', 'odt'),
(30, 'pdfscreensize', '640'),
(31, 'pdfscreensize', '800'),
(32, 'pdfscreensize', '1024'),
(33, 'pdfpapersize', 'A4'),
(34, 'pdfleftmargin', '15'),
(35, 'pdfrightmargin', '15'),
(36, 'pdftopmargin', '15'),
(37, 'pdfbottommargin', '15'),
(38, 'theme', 'google');

-- 
-- Tabellenstruktur fuer Tabelle `si_tax`
-- 

CREATE TABLE `si_tax` (
  `tax_id` int(11) NOT NULL auto_increment,
  `tax_description` varchar(50) default NULL,
  `tax_percentage` decimal(10,2) default NULL,
  `tax_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`tax_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
