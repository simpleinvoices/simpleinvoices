--
-- Dumping data for table `si_preferences`
--

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES
(5, 1, 'CIS3000 Payment', '£', 'Payment', 'Payment', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 0, NULL, NULL, 5, '', '', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


ALTER TABLE  `si_invoice_items` ADD  `date` DATETIME NULL ,
ADD  `type` VARCHAR( 10 ) NULL

INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`) VALUES
(6, 1, 'Sub-contractor payment', 0.000000, 0, NULL, NULL, NULL, '', '', '', '', '', '1', 1);

