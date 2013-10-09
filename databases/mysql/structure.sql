SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET @saved_cs_client     = @@character_set_client;
SET @saved_col_client    = @@collation_database;
SET character_set_client = utf8;
SET collation_database   = utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_biller` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `footer` text,
  `paypal_business_name` varchar(255) DEFAULT NULL,
  `paypal_notify_url` varchar(255) DEFAULT NULL,
  `paypal_return_url` varchar(255) DEFAULT NULL,
  `eway_customer_id` varchar(255) DEFAULT NULL,
  `paymentsgateway_api_id` varchar(255) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(10) DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_type` varchar(11) NOT NULL,
  `email_biller` int(1) DEFAULT NULL,
  `email_customer` int(1) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `cron_id` varchar(25) DEFAULT NULL,
  `run_date` date NOT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_custom_fields` (
  `cf_id` int(11) NOT NULL AUTO_INCREMENT,
  `cf_custom_field` varchar(255) DEFAULT NULL,
  `cf_custom_label` varchar(255) DEFAULT NULL,
  `cf_display` varchar(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`cf_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `attention` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `credit_card_holder_name` varchar(255) DEFAULT NULL,
  `credit_card_number` varchar(255) DEFAULT NULL,
  `credit_card_expiry_month` varchar(2) DEFAULT NULL,
  `credit_card_expiry_year` varchar(4) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(255) NOT NULL,
  `sub_node` varchar(255) DEFAULT NULL,
  `sub_node_2` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,6) NOT NULL,
  `cost` decimal(25,6) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_invoice_item_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_item_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_type` varchar(1) NOT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UnqInvTax` (`invoice_item_id`, `tax_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_invoice_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) NOT NULL DEFAULT '0',
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `quantity` decimal(25,6) NOT NULL DEFAULT '0.000000',
  `product_id` int(10) DEFAULT '0',
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `tax_amount` decimal(25,6) DEFAULT '0.000000',
  `gross_total` decimal(25,6) DEFAULT '0.000000',
  `description` text,
  `total` decimal(25,6) DEFAULT '0.000000',
  `attribute` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `DomainInv` (`invoice_id`, `domain_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_ty_description` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`inv_ty_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_invoices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `index_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `biller_id` int(10) NOT NULL DEFAULT '0',
  `customer_id` int(10) NOT NULL DEFAULT '0',
  `type_id` int(10) NOT NULL DEFAULT '0',
  `preference_id` int(10) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `custom_field1` varchar(50) DEFAULT NULL,
  `custom_field2` varchar(50) DEFAULT NULL,
  `custom_field3` varchar(50) DEFAULT NULL,
  `custom_field4` varchar(50) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `domain_id` (`domain_id`),
  KEY `biller_id` (`biller_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` varchar(40) NOT NULL DEFAULT '0',
  `sqlquerie` text NOT NULL,
  `last_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_payment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ac_inv_id` int(11) NOT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  `online_payment_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `domain_id` (`domain_id`),
  KEY `ac_inv_id` (`ac_inv_id`),
  KEY `ac_amount` (`ac_amount`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_payment_types` (
  `pt_id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pt_description` varchar(250) NOT NULL,
  `pt_enabled` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`domain_id`,`pt_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_preferences` (
  `pref_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pref_description` varchar(50) DEFAULT NULL,
  `pref_currency_sign` varchar(50) DEFAULT NULL,
  `pref_inv_heading` varchar(50) DEFAULT NULL,
  `pref_inv_wording` varchar(50) DEFAULT NULL,
  `pref_inv_detail_heading` varchar(50) DEFAULT NULL,
  `pref_inv_detail_line` text,
  `pref_inv_payment_method` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line1_name` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line2_name` varchar(50) DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(50) DEFAULT NULL,
  `pref_enabled` varchar(1) NOT NULL DEFAULT '1',
  `status` int(1) NOT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `index_group` int(11) NOT NULL,
  `currency_code` varchar(25) DEFAULT NULL,
  `include_online_payment` varchar(255) DEFAULT NULL,
  `currency_position` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`pref_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `default_tax_id` int(11) DEFAULT NULL,
  `default_tax_id_2` int(11) DEFAULT NULL,
  `cost` decimal(25,6) DEFAULT '0.000000',
  `reorder_level` int(11) DEFAULT NULL,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `notes` text NOT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '1',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `attribute` varchar(255) DEFAULT NULL,
  `notes_as_description` varchar(1) DEFAULT NULL,
  `show_description` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_products_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_products_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  `visible` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_products_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) NOT NULL,
  `sql_release` varchar(25) NOT NULL DEFAULT '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_system_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  `domain_id` int(5) NOT NULL DEFAULT '0',
  `extension_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`,`id`),
  UNIQUE KEY `UnqNameInDomain` (`domain_id`, `name`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_description` varchar(50) DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` varchar(1) DEFAULT NULL,
  `tax_enabled` varchar(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`domain_id`,`tax_id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(64) DEFAULT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  UNIQUE KEY `UnqEMailPwd` (`email`, `password`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

SET character_set_client = @saved_cs_client;
SET collation_database   = @saved_col_client;
