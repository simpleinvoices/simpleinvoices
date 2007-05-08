CREATE TABLE `si_account_payments` (
  `id` int(10) NOT NULL auto_increment,
  `ac_inv_id` varchar(10) collate utf8_unicode_ci NOT NULL,
  `ac_amount` double(25,2) NOT NULL,
  `ac_notes` text collate utf8_unicode_ci NOT NULL,
  `ac_date` datetime NOT NULL,
  `ac_payment_type` int(10) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;


CREATE TABLE `si_biller` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `street_address` varchar(50) default NULL,
  `street_address2` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `zip_code` varchar(50) default NULL,
  `country` varchar(100) default NULL,
  `phone` varchar(50) default NULL,
  `mobile_phone` varchar(50) default NULL,
  `fax` varchar(50) default NULL,
  `email` varchar(50) default NULL,
  `co_logo` varchar(50) default NULL,
  `co_footer` text,
  `notes` text,
  `custom_field1` varchar(50) default NULL,
  `custom_field2` varchar(50) default NULL,
  `custom_field3` varchar(50) default NULL,
  `custom_field4` varchar(50) default NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;


CREATE TABLE `si_custom_fields` (
  `cf_id` int(11) NOT NULL auto_increment,
  `cf_custom_field` varchar(50) collate utf8_unicode_ci NOT NULL,
  `cf_custom_label` varchar(50) collate utf8_unicode_ci default NULL,
  `cf_display` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;



CREATE TABLE `si_customers` (
  `id` int(10) NOT NULL auto_increment,
  `attention` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `street_address` varchar(50) default NULL,
  `street_address2` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `zip_code` varchar(50) default NULL,
  `country` varchar(100) default NULL,
  `phone` varchar(50) default NULL,
  `mobile_phone` varchar(50) default NULL,
  `fax` varchar(50) default NULL,
  `email` varchar(50) default NULL,
  `notes` text,
  `custom_field1` varchar(50) default NULL,
  `custom_field2` varchar(50) default NULL,
  `custom_field3` varchar(50) default NULL,
  `custom_field4` varchar(50) default NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;


CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL auto_increment,
  `inv_ty_description` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`inv_ty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


CREATE TABLE `si_invoices` (
  `inv_id` int(10) NOT NULL auto_increment,
  `inv_biller_id` int(10) NOT NULL default '0',
  `inv_customer_id` int(10) NOT NULL default '0',
  `inv_type` int(10) NOT NULL default '0',
  `inv_preference` int(10) NOT NULL default '0',
  `inv_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `invoice_custom_field1` varchar(50) default NULL,
  `invoice_custom_field2` varchar(50) default NULL,
  `invoice_custom_field3` varchar(50) default NULL,
  `invoice_custom_field4` varchar(50) default NULL,
  `inv_note` text,
  PRIMARY KEY  (`inv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;



CREATE TABLE `si_payment_types` (
  `pt_id` int(10) NOT NULL auto_increment,
  `pt_description` varchar(250) collate utf8_unicode_ci NOT NULL,
  `pt_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`pt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;



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


CREATE TABLE `si_products` (
  `id` int(11) NOT NULL auto_increment,
  `description` text NOT NULL,
  `unit_price` decimal(25,2) default NULL,
  `custom_field1` varchar(50) default NULL,
  `custom_field2` varchar(50) default NULL,
  `custom_field3` varchar(50) default NULL,
  `custom_field4` varchar(50) default NULL,
  `notes` text NOT NULL,
  `enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;



CREATE TABLE `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL auto_increment,
  `sql_patch_ref` varchar(50) NOT NULL default '',
  `sql_patch` varchar(50) NOT NULL default '',
  `sql_release` varchar(25) NOT NULL default '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY  (`sql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;


CREATE TABLE `si_tax` (
  `tax_id` int(11) NOT NULL auto_increment,
  `tax_description` varchar(50) default NULL,
  `tax_percentage` decimal(10,2) default NULL,
  `tax_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`tax_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

