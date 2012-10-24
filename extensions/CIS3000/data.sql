--
-- Dumping data for table `si_preferences`
--

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES
(5, 1, 'CIS3000 Payment', '£', 'Payment', 'Payment', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 0, NULL, NULL, 5, '', '', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE  `si_invoice_items` ADD  `date` DATETIME NULL ,
ADD  `type` VARCHAR( 10 ) NULL;

INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`) VALUES
(6, 1, 'Sub-contractor payment', 0.000000, 0, NULL, NULL, NULL, '', '', '', '', '', '1', 1);

INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`) VALUES
(3, 1, 'CIS3000', 'DESCRIPTION not available (in ./extensions/CIS3000/)', '1');

ALTER TABLE `si_customers`  ADD `utr_number` VARCHAR(255) NULL,  ADD `ni_number` VARCHAR(255) NULL,  ADD `verification_number` VARCHAR(255) NULL,  ADD `company_number` VARCHAR(255) NULL;

ALTER TABLE  `si_customers` ADD  `vat_number` VARCHAR( 255 ) NULL;
ALTER TABLE  `si_customers` ADD  `is_sub_contractor` VARCHAR( 255 ) NOT NULL DEFAULT  '0';

/*
CREATE  TABLE  `si_contractors` (  `id` int( 10  )  NOT  NULL  AUTO_INCREMENT ,
 `domain_id` int( 11  )  NOT  NULL DEFAULT  '1',
 `attention` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `name` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `street_address` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `street_address2` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `city` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `state` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `zip_code` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `country` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `phone` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `mobile_phone` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `fax` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `email` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `credit_card_holder_name` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `credit_card_number` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `credit_card_expiry_month` varchar( 2  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `credit_card_expiry_year` varchar( 4  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `notes` text COLLATE utf8_unicode_ci,
 `custom_field1` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `custom_field2` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `custom_field3` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `custom_field4` varchar( 255  )  COLLATE utf8_unicode_ci  DEFAULT NULL ,
 `enabled` varchar( 1  )  COLLATE utf8_unicode_ci NOT  NULL DEFAULT  '1',
 PRIMARY  KEY (  `domain_id` ,  `id`  )  ) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8 COLLATE  = utf8_unicode_ci
*/
