SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET @saved_cs_client     = @@character_set_client;
SET @saved_col_client    = @@collation_database;
SET character_set_client = utf8mb4;
SET collation_database   = utf8mb4_unicode_ci;

-- Note on AUTO_INCREMENT with composite primary keys:
-- InnoDB requires the AUTO_INCREMENT column to be leftmost in at least one index.
-- Where the composite PK is (domain_id, id), id is not leftmost, so a plain
-- KEY (id) is added to each affected table.  This satisfies InnoDB without
-- changing the PK column order or the application's query patterns.
-- The per-domain-group auto-increment behaviour of MyISAM is not used by the
-- application (all queries scope by both domain_id and id), so the switch to a
-- globally-incrementing sequence under InnoDB is safe.

CREATE TABLE IF NOT EXISTS `si_biller` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `footer` text,
  `paymentsgateway_api_id` varchar(255) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `stripe_secret_key` text DEFAULT NULL,
  `stripe_webhook_secret` text DEFAULT NULL,
  `stripe_test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `paypal_client_id` varchar(255) DEFAULT NULL,
  `paypal_client_secret` text DEFAULT NULL,
  `paypal_test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `mollie_api_key` text DEFAULT NULL,
  `authorizenet_login_id` text DEFAULT NULL,
  `authorizenet_transaction_key` text DEFAULT NULL,
  `authorizenet_signature_key` text DEFAULT NULL,
  `authorizenet_test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `eway_api_key` text DEFAULT NULL,
  `eway_api_password` text DEFAULT NULL,
  `eway_test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `kofi_username` varchar(100) DEFAULT NULL,
  `coinbase_api_key` text DEFAULT NULL,
  `coinbase_webhook_secret` text DEFAULT NULL,
  `adyen_api_key` text DEFAULT NULL,
  `adyen_merchant_account` varchar(255) DEFAULT NULL,
  `adyen_hmac_key` text DEFAULT NULL,
  `adyen_live_prefix` varchar(100) DEFAULT NULL,
  `adyen_test_mode` TINYINT(1) NOT NULL DEFAULT 1,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_swift_bic` varchar(50) DEFAULT NULL,
  `bank_account_number` varchar(100) DEFAULT NULL,
  `bank_routing_sort_code` varchar(50) DEFAULT NULL,
  `tax_id_name_1` varchar(255) DEFAULT NULL,
  `tax_id_label_1` varchar(255) DEFAULT NULL,
  `tax_id_name_2` varchar(255) DEFAULT NULL,
  `tax_id_label_2` varchar(255) DEFAULT NULL,
  `biller_invoice_prefix` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` varchar(10) DEFAULT NULL,
  `recurrence` int(11) NOT NULL,
  `recurrence_type` varchar(11) NOT NULL,
  `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,
  `email_customer` TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `cron_id` varchar(25) DEFAULT NULL,
  `run_date` date NOT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`),
  UNIQUE KEY `CronIdUnq` (`domain_id`, `cron_id`, `run_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_custom_fields` (
  `cf_id` int(11) NOT NULL AUTO_INCREMENT,
  `cf_custom_field` varchar(255) DEFAULT NULL,
  `cf_custom_label` varchar(255) DEFAULT NULL,
  `cf_display` TINYINT(1) DEFAULT 1 NOT NULL,
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`cf_id`, `domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `attention` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `street_address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `notes` text,
  `custom_field1` varchar(255) DEFAULT NULL,
  `custom_field2` varchar(255) DEFAULT NULL,
  `custom_field3` varchar(255) DEFAULT NULL,
  `custom_field4` varchar(255) DEFAULT NULL,
  `tax_id_name_1` varchar(255) DEFAULT NULL,
  `tax_id_label_1` varchar(255) DEFAULT NULL,
  `tax_id_name_2` varchar(255) DEFAULT NULL,
  `tax_id_label_2` varchar(255) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`, `domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(255) NOT NULL,
  `sub_node` varchar(255) DEFAULT NULL,
  `sub_node_2` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(25,6) NOT NULL,
  `cost` decimal(25,6) DEFAULT NULL,
  `date` date NOT NULL,
  `note` text,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_invoice_item_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_item_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_type` CHAR(1) DEFAULT '%' NOT NULL,
  `tax_rate` decimal(25,6) NOT NULL,
  `tax_amount` decimal(25,6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UnqInvTax` (`invoice_item_id`, `tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  KEY `DomainInv` (`invoice_id`, `domain_id`),
  KEY `si_ii_dom_invoice` (`domain_id`, `invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_ty_description` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`inv_ty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_payment_terms` (
  `term_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `term_code` varchar(32) NOT NULL,
  `term_label` varchar(120) NOT NULL,
  `calc_kind` varchar(32) NOT NULL,
  `param_int` int(11) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `term_domain_code` (`domain_id`, `term_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `payment_term_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `currency_sign` varchar(50) DEFAULT NULL,
  `denorm_currency_code` varchar(10) DEFAULT NULL,
  `denorm_currency_locale` varchar(32) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `denorm_invoice_total` decimal(25,6) NOT NULL DEFAULT 0,
  `denorm_amount_paid` decimal(25,6) NOT NULL DEFAULT 0,
  `denorm_amount_owing` decimal(25,6) NOT NULL DEFAULT 0,
  `denorm_biller_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_customer_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_index_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_index_id` varchar(255) NOT NULL DEFAULT '',
  `denorm_preference_description` varchar(255) NOT NULL DEFAULT '',
  `denorm_preference_status` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `biller_id` (`biller_id`),
  KEY `customer_id` (`customer_id`),
  UNIQUE KEY `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`),
  KEY `IdxDI` (`index_id`, `preference_id`, `domain_id`),
  KEY `si_inv_dom_pref_date` (`domain_id`, `preference_id`, `date`),
  KEY `si_inv_dom_cust` (`domain_id`, `customer_id`),
  KEY `si_inv_dom_biller` (`domain_id`, `biller_id`),
  KEY `si_inv_dom_idxid` (`domain_id`, `index_id`),
  KEY `si_inv_dom_pstat_owing` (`domain_id`, `denorm_preference_status`, `denorm_amount_owing`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL DEFAULT '1',
  `sqlquerie` text NOT NULL,
  `last_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`, `domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_payment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ac_inv_id` int(11) NOT NULL,
  `ac_amount` decimal(25,6) NOT NULL,
  `ac_notes` text NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  `online_payment_id` varchar(255) DEFAULT NULL,
  `denorm_invoice_index_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_biller_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_customer_name` varchar(255) NOT NULL DEFAULT '',
  `denorm_currency_sign` varchar(50) NOT NULL DEFAULT '',
  `denorm_currency_code` varchar(10) NOT NULL DEFAULT '',
  `denorm_currency_locale` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`),
  KEY `domain_id` (`domain_id`),
  KEY `ac_inv_id` (`ac_inv_id`),
  KEY `ac_amount` (`ac_amount`),
  KEY `si_pay_dom_ac_date` (`domain_id`, `ac_date`),
  KEY `si_pay_dom_ac_inv` (`domain_id`, `ac_inv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_payment_types` (
  `pt_id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pt_description` varchar(250) NOT NULL,
  `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`domain_id`,`pt_id`),
  KEY `pt_id` (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `currency_code` varchar(10) NOT NULL DEFAULT '',
  `currency_sign` varchar(50) NOT NULL DEFAULT '',
  `currency_position` varchar(25) NOT NULL DEFAULT 'left',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `enabled` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_domain` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_preferences` (
  `pref_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `pref_description` varchar(255) DEFAULT NULL,
  `pref_inv_heading` varchar(255) DEFAULT NULL,
  `pref_inv_wording` varchar(255) DEFAULT NULL,
  `pref_inv_detail_heading` varchar(255) DEFAULT NULL,
  `pref_inv_detail_line` text,
  `pref_inv_payment_method` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line1_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line1_value` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line2_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line2_value` varchar(255) DEFAULT NULL,
  `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `locale` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `index_group` int(11) NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `include_online_payment` varchar(255) DEFAULT NULL,
  `payment_term_id` int(11) DEFAULT NULL,
  `pref_inv_payment_line0_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line0_value` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line3_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line3_value` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line4_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line4_value` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line5_name` varchar(255) DEFAULT NULL,
  `pref_inv_payment_line5_value` varchar(255) DEFAULT NULL,
  `pref_invoice_id_prefix` varchar(50) DEFAULT NULL,
  `pref_invoice_id_format` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`pref_id`),
  KEY `pref_id` (`pref_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `notes` text DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `visible` TINYINT(1) DEFAULT 1 NOT NULL,
  `attribute` varchar(255) DEFAULT NULL,
  `notes_as_description` CHAR(1) DEFAULT NULL,
  `show_description` CHAR(1) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_products_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_products_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `visible` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pa_domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_products_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pv_domain_id` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) DEFAULT NULL,
  `sql_release` varchar(25) DEFAULT NULL ,
  `sql_statement` text DEFAULT NULL,
  PRIMARY KEY (`sql_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_system_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) DEFAULT NULL,
  `domain_id` int(5) NOT NULL DEFAULT '0',
  `extension_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`),
  UNIQUE KEY `UnqNameInDomain` (`domain_id`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_global_config` (
  `name` varchar(64) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_description` varchar(50) DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` CHAR(1) DEFAULT '%' NOT NULL,
  `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`domain_id`,`tax_id`),
  KEY `tax_id` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(64) DEFAULT NULL,
  `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `auth_staff_email` varchar(255) DEFAULT NULL,
  `auth_customer_key` varchar(384) DEFAULT NULL,
  `preferred_language` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`domain_id`,`id`),
  KEY `id` (`id`),
  UNIQUE KEY `UnqAuthStaffEmail` (`auth_staff_email`),
  UNIQUE KEY `UnqAuthCustomerKey` (`auth_customer_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET character_set_client = @saved_cs_client;
SET collation_database   = @saved_col_client;
