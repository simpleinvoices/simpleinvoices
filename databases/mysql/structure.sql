-- phpMyAdmin SQL Dump
-- version 3.1.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2009 at 08:11 PM
-- Server version: 5.0.75
-- PHP Version: 5.2.6-3ubuntu2

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `simple_invoices`
--

-- --------------------------------------------------------

--
-- Table structure for table `si_biller`
--

CREATE TABLE IF NOT EXISTS `si_biller` (
  `id` int(10) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `street_address` varchar(255) collate utf8_unicode_ci default NULL,
  `street_address2` varchar(255) collate utf8_unicode_ci default NULL,
  `city` varchar(255) collate utf8_unicode_ci default NULL,
  `state` varchar(255) collate utf8_unicode_ci default NULL,
  `zip_code` varchar(255) collate utf8_unicode_ci default NULL,
  `country` varchar(255) collate utf8_unicode_ci default NULL,
  `phone` varchar(255) collate utf8_unicode_ci default NULL,
  `mobile_phone` varchar(255) collate utf8_unicode_ci default NULL,
  `fax` varchar(255) collate utf8_unicode_ci default NULL,
  `email` varchar(255) collate utf8_unicode_ci default NULL,
  `logo` varchar(255) collate utf8_unicode_ci default NULL,
  `footer` text collate utf8_unicode_ci,
  `notes` text collate utf8_unicode_ci,
  `custom_field1` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field2` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field3` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field4` varchar(255) collate utf8_unicode_ci default NULL,
  `enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_customers`
--

CREATE TABLE IF NOT EXISTS `si_customers` (
  `id` int(10) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `attention` varchar(255) collate utf8_unicode_ci default NULL,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `street_address` varchar(255) collate utf8_unicode_ci default NULL,
  `street_address2` varchar(255) collate utf8_unicode_ci default NULL,
  `city` varchar(255) collate utf8_unicode_ci default NULL,
  `state` varchar(255) collate utf8_unicode_ci default NULL,
  `zip_code` varchar(255) collate utf8_unicode_ci default NULL,
  `country` varchar(255) collate utf8_unicode_ci default NULL,
  `phone` varchar(255) collate utf8_unicode_ci default NULL,
  `mobile_phone` varchar(255) collate utf8_unicode_ci default NULL,
  `fax` varchar(255) collate utf8_unicode_ci default NULL,
  `email` varchar(255) collate utf8_unicode_ci default NULL,
  `notes` text collate utf8_unicode_ci,
  `custom_field1` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field2` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field3` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field4` varchar(255) collate utf8_unicode_ci default NULL,
  `enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_custom_fields`
--

CREATE TABLE IF NOT EXISTS `si_custom_fields` (
  `cf_id` int(11) NOT NULL auto_increment,
  `cf_custom_field` varchar(255) collate utf8_unicode_ci default NULL,
  `cf_custom_label` varchar(255) collate utf8_unicode_ci default NULL,
  `cf_display` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_extensions`
--

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` varchar(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoices`
--

CREATE TABLE IF NOT EXISTS `si_invoices` (
  `id` int(10) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `biller_id` int(10) NOT NULL default '0',
  `customer_id` int(10) NOT NULL default '0',
  `type_id` int(10) NOT NULL default '0',
  `preference_id` int(10) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `custom_field1` varchar(50) collate utf8_unicode_ci default NULL,
  `custom_field2` varchar(50) collate utf8_unicode_ci default NULL,
  `custom_field3` varchar(50) collate utf8_unicode_ci default NULL,
  `custom_field4` varchar(50) collate utf8_unicode_ci default NULL,
  `note` text collate utf8_unicode_ci,
  PRIMARY KEY  (`domain_id`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_items`
--

CREATE TABLE IF NOT EXISTS `si_invoice_items` (
  `id` int(10) NOT NULL auto_increment,
  `invoice_id` int(10) NOT NULL default '0',
  `quantity` decimal(25,6) NOT NULL default '0.000000',
  `product_id` int(10) default '0',
  `unit_price` decimal(25,6) default '0.000000',
  `tax_amount` decimal(25,6) default '0.000000',
  `gross_total` decimal(25,6) default '0.000000',
  `description` text,
  `total` decimal(25,6) default '0.000000',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_item_tax`
--

CREATE TABLE IF NOT EXISTS `si_invoice_item_tax` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_item_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_type` varchar(1) collate utf8_unicode_ci NOT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_invoice_type`
--

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL auto_increment,
  `inv_ty_description` varchar(25) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`inv_ty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_log`
--

CREATE TABLE IF NOT EXISTS `si_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `userid` varchar(40) collate utf8_unicode_ci NOT NULL default '0',
  `sqlquerie` text collate utf8_unicode_ci NOT NULL,
  `last_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment`
--

CREATE TABLE IF NOT EXISTS `si_payment` (
  `id` int(10) NOT NULL auto_increment,
  `ac_inv_id` int(11) NOT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text collate utf8_unicode_ci NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL default '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_payment_types`
--

CREATE TABLE IF NOT EXISTS `si_payment_types` (
  `pt_id` int(10) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `pt_description` varchar(250) collate utf8_unicode_ci NOT NULL,
  `pt_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`pt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_preferences`
--

CREATE TABLE IF NOT EXISTS `si_preferences` (
  `pref_id` int(11) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `pref_description` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_currency_sign` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_heading` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_wording` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_detail_heading` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_detail_line` text collate utf8_unicode_ci,
  `pref_inv_payment_method` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_payment_line1_name` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_payment_line1_value` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_payment_line2_name` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_inv_payment_line2_value` varchar(50) collate utf8_unicode_ci default NULL,
  `pref_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`pref_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_products`
--

CREATE TABLE IF NOT EXISTS `si_products` (
  `id` int(11) NOT NULL auto_increment,
  `domain_id` int(11) NOT NULL default '1',
  `description` text collate utf8_unicode_ci NOT NULL,
  `unit_price` decimal(25,6) default '0.000000',
  `default_tax_id` int(11) default NULL,
  `default_tax_id_2` int(11) default NULL,
  `custom_field1` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field2` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field3` varchar(255) collate utf8_unicode_ci default NULL,
  `custom_field4` varchar(255) collate utf8_unicode_ci default NULL,
  `notes` text collate utf8_unicode_ci NOT NULL,
  `enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  `visible` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_sql_patchmanager`
--

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL auto_increment,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) collate utf8_unicode_ci NOT NULL,
  `sql_release` varchar(25) collate utf8_unicode_ci NOT NULL default '',
  `sql_statement` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`sql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_system_defaults`
--

CREATE TABLE IF NOT EXISTS `si_system_defaults` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) collate utf8_unicode_ci NOT NULL,
  `value` varchar(30) collate utf8_unicode_ci NOT NULL,
  `domain_id` int(5) NOT NULL default '0',
  `extension_id` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_tax`
--

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL auto_increment,
  `tax_description` varchar(50) collate utf8_unicode_ci default NULL,
  `tax_percentage` decimal(25,6) default '0.000000',
  `type` varchar(1) collate utf8_unicode_ci default NULL,
  `tax_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY  (`tax_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_user`
--

CREATE TABLE IF NOT EXISTS `si_user` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) collate utf8_unicode_ci default NULL,
  `role_id` int(11) default NULL,
  `domain_id` int(11) default NULL,
  `password` varchar(255) collate utf8_unicode_ci default NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_domain`
--

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `si_user_role`
--

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

