SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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

INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES
 (1, 1, 'Mr Plough', '43 Evergreen Terace', '', 'Springfield', 'NY', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '', '', '', '', '', '', '', '', '7898-87987-87', '', '1')
,(2, 1, 'Homer Simpson', '43 Evergreen Terace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1')
,(3, 1, 'The Beer Baron', '43 Evergreen Terace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1')
,(4, 1, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1');

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
  PRIMARY KEY (`cf_id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES
 (1, 'biller_cf1',   NULL, '0', 1)
,(2, 'biller_cf2',   NULL, '0', 1)
,(3, 'biller_cf3',   NULL, '0', 1)
,(4, 'biller_cf4',   NULL, '0', 1)
,(5, 'customer_cf1', NULL, '0', 1)
,(6, 'customer_cf2', NULL, '0', 1)
,(7, 'customer_cf3', NULL, '0', 1)
,(8, 'customer_cf4', NULL, '0', 1)
,(9, 'product_cf1',  NULL, '0', 1)
,(10, 'product_cf2', NULL, '0', 1)
,(11, 'product_cf3', NULL, '0', 1)
,(12, 'product_cf4', NULL, '0', 1)
,(13, 'invoice_cf1', NULL, '0', 1)
,(14, 'invoice_cf2', NULL, '0', 1)
,(15, 'invoice_cf3', NULL, '0', 1)
,(16, 'invoice_cf4', NULL, '0', 1);

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

INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`, `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES
 (1, 1, 'Moe Sivloski', 'Moes Tavern', '45 Main Road', '', 'Springfield', 'NY', '65891', '', '04 1234 5698', '', '04 5689 4566', 'moe@moestavern.com', '', '', '', '', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href=&#39;http://en.wikipedia.org/wiki/Bar_%28establishment%29&#39; title=&#39;Bar (establishment)&#39;>bar</a> seen on <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The owner of the bar is <a href=&#39;http://en.wikipedia.org/wiki/Moe_Szyslak&#39; title=&#39;Moe Szyslak&#39;>Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href=&#39;http://en.wikipedia.org/wiki/Bart_Simpson&#39; title=&#39;Bart Simpson&#39;>Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href=&#39;http://en.wikipedia.org/wiki/Urine&#39; title=&#39;Urine&#39;>tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href=&#39;http://en.wikipedia.org/wiki/1979&#39; title=&#39;1979&#39;>1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href=&#39;http://en.wikipedia.org/wiki/Homer%27s_Enemy&#39; title=&#39;Homer&#39;s Enemy&#39;>Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns&#39; title=&#39;Who Shot Mr. Burns&#39;>Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href=&#39;http://en.wikipedia.org/wiki/Flaming_Moe%27s&#39; title=&#39;Flaming Moe&#39;s&#39;>Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href=&#39;http://en.wikipedia.org/wiki/Homer_the_Smithers&#39; title=&#39;Homer the Smithers&#39;>Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Principal_Charming&#39; title=&#39;Principal Charming&#39;>Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href=&#39;http://en.wikipedia.org/wiki/Duff_Beer&#39; title=&#39;Duff Beer&#39;>Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', '', '', '', '', '1')
,(2, 1, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', '', 'Springfield', 'NY', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mburns@spp.com', '', '', '', '', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href=&#39;http://en.wikipedia.org/wiki/Television&#39; title=&#39;Television&#39;>television</a> <a href=&#39;http://en.wikipedia.org/wiki/Animated_cartoon&#39; title=&#39;Animated cartoon&#39;>animated cartoon</a> series <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The plant has a <a href=&#39;http://en.wikipedia.org/wiki/Monopoly&#39; title=&#39;Monopoly&#39;>monopoly</a> on the city of <a href=&#39;http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29&#39; title=&#39;Springfield (The Simpsons)&#39;>Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href=&#39;http://en.wikipedia.org/wiki/Containment_building&#39; title=&#39;Containment building&#39;>containment buildings</a> and <a href=&#39;http://en.wikipedia.org/wiki/Cooling_tower&#39; title=&#39;Cooling tower&#39;>cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href=&#39;http://en.wikipedia.org/wiki/Nuclear_safety&#39; title=&#39;Nuclear safety&#39;>safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href=&#39;http://en.wikipedia.org/wiki/Plutonium&#39; title=&#39;Plutonium&#39;>plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href=&#39;http://en.wikipedia.org/wiki/Chewing_gum&#39; title=&#39;Chewing gum&#39;>Chewing gum</a>), dangerously high <a href=&#39;http://en.wikipedia.org/wiki/Geiger_counter&#39; title=&#39;Geiger counter&#39;>Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href=&#39;http://en.wikipedia.org/wiki/Radioactive&#39; title=&#39;Radioactive&#39;>radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', '1')
,(3, 1, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', '', 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', '', '03 9658 7457', 'kath@kathandkim.com.au', '', '', '', '', 'Kath Day-Knight (<a href=&#39;http://en.wikipedia.org/wiki/Jane_Turner&#39; title=&#39;Jane Turner&#39;>Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href=&#39;http://en.wikipedia.org/wiki/Glenn_Robbins&#39; title=&#39;Glenn Robbins&#39;>Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href=&#39;http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q&#39; title=&#39;List of French phrases used by English speakers&#39;>n&eacute;e</a> Day (<a href=&#39;http://en.wikipedia.org/wiki/Gina_Riley&#39; title=&#39;Gina Riley&#39;>Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href=&#39;http://en.wikipedia.org/wiki/Peter_Rowsthorn&#39; title=&#39;Peter Rowsthorn&#39;>Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href=&#39;http://en.wikipedia.org/wiki/Magda_Szubanski&#39; title=&#39;Magda Szubanski&#39;>Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', '13245-789798', '', '', '', '1');

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`, `domain_id`)
) ENGINE=MyISAM;

INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`) VALUES
 (1, 0, 'core', 'Core part of Simple Invoices - always enabled', '1');

CREATE TABLE IF NOT EXISTS `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(255) NOT NULL,
  `sub_node` varchar(255) DEFAULT NULL,
  `sub_node_2` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM;

INSERT INTO `si_index` (`id`, `node`, `sub_node`, `sub_node_2`, `domain_id`) VALUES
 (1, 'invoice', '1', '', 1);

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

INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES
 (1, 1, 3, '%', 10.000000, 12.500000)
,(2, 2, 1, '%', 10.000000, 12.500000)
,(3, 3, 4, '%', 0.000000, 0.000000)
,(4, 4, 1, '%', 10.000000, 14.000000)
,(5, 5, 4, '%', 0.000000, 0.000000);

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

INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `domain_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
 (1, 1, 1, 1.000000, 5, 125.000000, 12.500000, 125.000000, '', 137.500000)
,(2, 1, 1, 1.000000, 3, 125.000000, 12.500000, 125.000000, '', 137.500000)
,(3, 1, 1, 1.000000, 2, 140.000000, 0.000000, 140.000000, '', 140.000000)
,(4, 1, 1, 1.000000, 2, 140.000000, 14.000000, 140.000000, '', 154.000000)
,(5, 1, 1, 1.000000, 1, 150.000000, 0.000000, 150.000000, '', 150.000000);

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_ty_description` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`inv_ty_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES
 (1, 'Total')
,(2, 'Itemised')
,(3, 'Consulting');

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

INSERT INTO `si_invoices` (`id`, `index_id`, `domain_id`, `biller_id`, `customer_id`, `type_id`, `preference_id`, `date`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `note`) VALUES
 (1, 1, 1, 4, 3, 2, 1, '2008-12-30 00:00:00', '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `si_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL DEFAULT '1',
  `sqlquerie` text NOT NULL,
  `last_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`, `domain_id`)
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

INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`) VALUES
 (1, 1, 'Cash', '1')
,(2, 1, 'Credit Card', '1');

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

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES
 (1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(2, 1, 'Receipt', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(3, 1, 'Estimate', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left')
,(4, 1, 'Quote', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');

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

INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES
 (1, 1, 'Hourly charge', 150.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '')
,(2, 1, 'Accounting services', 140.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '')
,(3, 1, 'Ploughing service', 125.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '')
,(4, 1, 'Bootleg homebrew', 15.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '')
,(5, 1, 'Accomodation', 125.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');

CREATE TABLE IF NOT EXISTS `si_products_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attribute_type` VALUES
 ('1','list')
,('2','decimal')
,('3','free');

CREATE TABLE IF NOT EXISTS `si_products_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  `visible` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attributes` VALUES
 ('1','Size',  '1','1','1')
,('2','Colour','1','1','1');

CREATE TABLE IF NOT EXISTS `si_products_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_values` VALUES
 ('1','1','S','1')
,('2','1','M','1')
,('3','1','L','1')
,('4','2','Red','1')
,('5','2','White','1');

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) NOT NULL,
  `sql_release` varchar(25) NOT NULL DEFAULT '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM;

INSERT INTO `si_sql_patchmanager`(`sql_id`,`sql_patch_ref`,`sql_patch`,`sql_release`,`sql_statement`) VALUES
 (1,1,'Create sql_patchmanger table','20060514','CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM ')
,(2,2,'','','')
,(3,3,'','','')
,(4,4,'','','')
,(5,5,'','','')
,(6,6,'','','')
,(7,7,'','','')
,(8,8,'','','')
,(9,9,'','','')
,(10,10,'','','')
,(11,11,'','','')
,(12,12,'','','')
,(13,13,'','','')
,(14,14,'','','')
,(15,15,'','','')
,(16,16,'','','')
,(17,17,'','','')
,(18,18,'','','')
,(19,19,'','','')
,(20,20,'','','')
,(21,21,'','','')
,(22,22,'','','')
,(23,23,'','','')
,(24,24,'','','')
,(25,25,'','','')
,(26,26,'','','')
,(27,27,'','','')
,(28,28,'','','')
,(29,29,'','','')
,(30,30,'','','')
,(31,31,'','','')
,(32,32,'','','')
,(33,33,'','','')
,(34,34,'','','')
,(35,35,'','','')
,(36,36,'','','')
,(37,0,'Start','20060514','')
,(38,37,'','','')
,(39,38,'','','')
,(40,39,'','','')
,(41,40,'','','')
,(42,41,'','','')
,(43,42,'','','')
,(44,43,'','','')
,(45,44,'','','')
,(46,45,'','','')
,(47,46,'','','')
,(48,47,'','','')
,(49,48,'','','')
,(50,49,'','','')
,(51,50,'','','')
,(52,51,'','','')
,(53,52,'','','')
,(54,53,'','','')
,(55,54,'','','')
,(56,55,'','','')
,(57,56,'','','')
,(58,57,'','','')
,(59,58,'','','')
,(60,59,'','','')
,(61,60,'','','')
,(62,61,'','','')
,(63,62,'','','')
,(64,63,'','','')
,(65,64,'','','')
,(66,65,'','','')
,(67,66,'','','')
,(68,67,'','','')
,(69,68,'','','')
,(70,69,'','','')
,(71,70,'','','')
,(72,71,'','','')
,(73,72,'','','')
,(74,73,'','','')
,(75,74,'','','')
,(76,75,'','','')
,(77,76,'','','')
,(78,77,'','','')
,(79,78,'','','')
,(80,79,'','','')
,(81,80,'','','')
,(82,81,'','','')
,(83,82,'','','')
,(84,83,'','','')
,(85,84,'','','')
,(86,85,'','','')
,(87,86,'','','')
,(88,87,'','','')
,(89,88,'','','')
,(90,89,'','','')
,(91,90,'','','')
,(92,91,'','','')
,(93,92,'','','')
,(94,93,'','','')
,(95,94,'','','')
,(96,95,'','','')
,(97,96,'','','')
,(98,97,'','','')
,(99,98,'','','')
,(100,99,'','','')
,(101,100,'','','')
,(102,101,'','','')
,(103,102,'','','')
,(104,103,'','','')
,(105,104,'','','')
,(106,105,'','','')
,(107,106,'','','')
,(108,107,'','','')
,(109,108,'','','')
,(110,109,'','','')
,(111,110,'','','')
,(112,111,'','','')
,(113,112,'','','')
,(114,113,'','','')
,(115,114,'','','')
,(116,115,'','','')
,(117,116,'','','')
,(118,117,'','','')
,(119,118,'','','')
,(120,119,'','','')
,(121,120,'','','')
,(122,121,'','','')
,(123,122,'','','')
,(124,123,'','','')
,(125,124,'','','')
,(126,125,'','','')
,(127,126,'','','')
,(128,127,'','','')
,(129,128,'','','')
,(130,129,'','','')
,(131,130,'','','')
,(132,131,'','','')
,(133,132,'','','')
,(134,133,'','','')
,(135,134,'','','')
,(136,135,'','','')
,(137,136,'','','')
,(138,137,'','','')
,(139,138,'','','')
,(140,139,'','','')
,(141,140,'','','')
,(142,141,'','','')
,(143,142,'','','')
,(144,143,'','','')
,(145,144,'','','')
,(146,145,'','','')
,(147,146,'','','')
,(148,147,'','','')
,(149,148,'','','')
,(150,149,'','','')
,(151,150,'','','')
,(152,151,'','','')
,(153,152,'','','')
,(154,153,'','','')
,(155,154,'','','')
,(156,155,'','','')
,(157,156,'','','')
,(158,157,'','','')
,(159,158,'','','')
,(160,159,'','','')
,(161,160,'','','')
,(162,161,'','','')
,(163,162,'','','')
,(164,163,'','','')
,(165,164,'','','')
,(166,165,'','','')
,(167,166,'','','')
,(168,167,'','','')
,(169,168,'','','')
,(170,169,'','','')
,(171,170,'','','')
,(172,171,'','','')
,(173,172,'','','')
,(174,173,'','','')
,(175,174,'','','')
,(176,175,'','','')
,(177,176,'','','')
,(178,177,'','','')
,(179,178,'','','')
,(180,179,'','','')
,(181,180,'','','')
,(182,181,'','','')
,(183,182,'','','')
,(184,183,'','','')
,(185,184,'','','')
,(186,185,'','','')
,(187,186,'','','')
,(188,187,'','','')
,(189,188,'','','')
,(190,189,'','','')
,(191,190,'','','')
,(192,191,'','','')
,(193,192,'','','')
,(194,193,'','','')
,(195,194,'','','')
,(196,195,'','','')
,(197,196,'','','')
,(198,197,'','','')
,(199,198,'','','')
,(200,199,'','','')
,(201,200,'Update extensions table','20090529','UPDATE si_extensions SET id = 0 WHERE name = core LIMIT 1')
,(202,201,'Set domain_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET domain_id = 1')
,(203,202,'Set extension_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET extension_id = 1')
,(204,203,'Move all old consulting style invoices to itemised','20090704','UPDATE si_invoices SET type_id = 2 where type_id = 3')
,(205,204,'','','')
,(206,205,'','','')
,(207,206,'','','')
,(208,207,'','','')
,(209,208,'','','')
,(210,209,'','','')
,(211,210,'','','')
,(212,211,'','','')
,(213,212,'','','')
,(214,213,'','','')
,(215,214,'','','')
,(216,215,'','','')
,(217,216,'','','')
,(218,217,'','','')
,(219,218,'','','')
,(220,219,'','','')
,(221,220,'','','')
,(222,221,'','','')
,(223,222,'','','')
,(224,223,'','','')
,(225,224,'','','')
,(226,225,'','','')
,(227,226,'','','')
,(228,227,'','','')
,(229,228,'','','')
,(230,229,'','','')
,(231,230,'','','')
,(232,231,'','','')
,(233,232,'','','')
,(234,233,'','','')
,(235,234,'','','')
,(236,235,'','','')
,(237,236,'','','')
,(238,237,'','','')
,(239,238,'','','')
,(240,239,'','','')
,(241,240,'','','')
,(242,241,'','','')
,(243,242,'','','')
,(244,243,'','','')
,(245,244,'','','')
,(246,245,'','','')
,(247,246,'','','')
,(248,247,'','','')
,(249,248,'','','')
,(250,249,'','','')
,(251,250,'','','')
,(252,251,'','','')
,(253,252,'','','')
,(254,253,'','','')
,(255,254,'','','')
,(256,255,'','','')
,(257,256,'','','')
,(258,257,'','','')
,(259,258,'','','')
,(260,259,'','','')
,(261,260,'','','')
,(262,261,'','','')
,(263,262,'','','')
,(264,263,'','','')
,(265,264,'','','')
,(266,265,'','','')
,(267,266,'','','')
,(268,267,'','','')
,(269,268,'','','')
,(270,269,'','','')
,(271,270,'','','')
,(272,271,'','','')
,(273,272,'','','')
,(274,273,'','','')
,(275,274,'','','')
,(276,275,'','','')
,(277,276,'','','')
,(278,277,'','','')
,(279,278,'','','')
,(280,279,'','','')
,(281,280,'','','')
,(282,281,'','','')
,(283,282,'','','')
,(284,283,'','','');

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

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES
 ('1','biller','4','1','1')
,('2','customer','3','1','1')
,('3','tax','1','1','1')
,('4','preference','1','1','1')
,('5','line_items','5','1','1')
,('6','template','default','1','1')
,('7','payment_type','1','1','1')
,('8','language','en_GB','1','1')
,('9','dateformate','Y-m-d','1','1')
,('10','spreadsheet','xls','1','1')
,('11','wordprocessor','doc','1','1')
,('12','pdfscreensize','800','1','1')
,('13','pdfpapersize','A4','1','1')
,('14','pdfleftmargin','15','1','1')
,('15','pdfrightmargin','15','1','1')
,('16','pdftopmargin','15','1','1')
,('17','pdfbottommargin','15','1','1')
,('18','emailhost','localhost','1','1')
,('19','emailusername','','1','1')
,('20','emailpassword','','1','1')
,('21','logging','0','1','1')
,('22','delete','N','1','1')
,('23','tax_per_line_item','1','1','1')
,('24','inventory','0','1','1')
,('25','product_attributes','0','1','1')
,('26','large_dataset','0','1','1');

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_description` varchar(50) DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` varchar(1) DEFAULT NULL,
  `tax_enabled` varchar(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`domain_id`,`tax_id`)
) ENGINE=MyISAM;

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES
 (1, 'GST', 10.000000, '%', '1', 1)
,(2, 'VAT', 10.000000, '%', '1', 1)
,(3, 'Sales Tax', 10.000000, '%', '1', 1)
,(4, 'No Tax', 0.000000, '%', '1', 1)
,(5, 'Postage', 20.000000, '$', '1', 1);

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

INSERT INTO `si_user` (`id`, `email`, `role_id`, `domain_id`, `password`, `enabled`) VALUES
 (1, 'demo@simpleinvoices.org', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1);

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_domain` (`id`, `name`) VALUES
 (1, 'default');

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_role` (`id`, `name`) VALUES
 (1, 'administrator')
,(2, 'domain_administrator')
,(3,'user')
,(4,'viewer')
,(5,'customer');

