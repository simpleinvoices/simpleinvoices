SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;

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

INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(1, 1, 'Mr Plough', '43 Evergreen Terace', '', 'Springfield', 'NY', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '', '', '', '', '', '', '', '', '7898-87987-87', '', '1');
INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(2, 1, 'Homer Simpson', '43 Evergreen Terace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1');
INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(3, 1, 'The Beer Baron', '43 Evergreen Terace', NULL, 'Springfield', 'NY', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1');
INSERT INTO `si_biller` (`id`, `domain_id`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `logo`, `footer`, `paypal_business_name`, `paypal_notify_url`, `paypal_return_url`, `eway_customer_id`, `paymentsgateway_api_id`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(4, 1, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '1');

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

INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(1, 'biller_cf1', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(2, 'biller_cf2', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(3, 'biller_cf3', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(4, 'biller_cf4', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(5, 'customer_cf1', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(6, 'customer_cf2', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(7, 'customer_cf3', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(8, 'customer_cf4', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(9, 'product_cf1', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(10, 'product_cf2', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(11, 'product_cf3', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(12, 'product_cf4', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(13, 'invoice_cf1', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(14, 'invoice_cf2', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(15, 'invoice_cf3', NULL, '0', 1);
INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`, `domain_id`) VALUES(16, 'invoice_cf4', NULL, '0', 1);

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

INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`, `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(1, 1, 'Moe Sivloski', 'Moes Tavern', '45 Main Road', '', 'Springfield', 'NY', '65891', '', '04 1234 5698', '', '04 5689 4566', 'moe@moestavern.com', '', '', '', '', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href=&#39;http://en.wikipedia.org/wiki/Bar_%28establishment%29&#39; title=&#39;Bar (establishment)&#39;>bar</a> seen on <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The owner of the bar is <a href=&#39;http://en.wikipedia.org/wiki/Moe_Szyslak&#39; title=&#39;Moe Szyslak&#39;>Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href=&#39;http://en.wikipedia.org/wiki/Bart_Simpson&#39; title=&#39;Bart Simpson&#39;>Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href=&#39;http://en.wikipedia.org/wiki/Urine&#39; title=&#39;Urine&#39;>tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href=&#39;http://en.wikipedia.org/wiki/1979&#39; title=&#39;1979&#39;>1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href=&#39;http://en.wikipedia.org/wiki/Homer%27s_Enemy&#39; title=&#39;Homer&#39;s Enemy&#39;>Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns&#39; title=&#39;Who Shot Mr. Burns&#39;>Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href=&#39;http://en.wikipedia.org/wiki/Flaming_Moe%27s&#39; title=&#39;Flaming Moe&#39;s&#39;>Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href=&#39;http://en.wikipedia.org/wiki/Homer_the_Smithers&#39; title=&#39;Homer the Smithers&#39;>Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href=&#39;http://en.wikipedia.org/wiki/Principal_Charming&#39; title=&#39;Principal Charming&#39;>Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href=&#39;http://en.wikipedia.org/wiki/Duff_Beer&#39; title=&#39;Duff Beer&#39;>Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', '', '', '', '', '1');
INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`, `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(2, 1, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', '', 'Springfield', 'NY', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mburns@spp.com', '', '', '', '', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href=&#39;http://en.wikipedia.org/wiki/Television&#39; title=&#39;Television&#39;>television</a> <a href=&#39;http://en.wikipedia.org/wiki/Animated_cartoon&#39; title=&#39;Animated cartoon&#39;>animated cartoon</a> series <em><a href=&#39;http://en.wikipedia.org/wiki/The_Simpsons&#39; title=&#39;The Simpsons&#39;>The Simpsons</a></em>. The plant has a <a href=&#39;http://en.wikipedia.org/wiki/Monopoly&#39; title=&#39;Monopoly&#39;>monopoly</a> on the city of <a href=&#39;http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29&#39; title=&#39;Springfield (The Simpsons)&#39;>Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href=&#39;http://en.wikipedia.org/wiki/Containment_building&#39; title=&#39;Containment building&#39;>containment buildings</a> and <a href=&#39;http://en.wikipedia.org/wiki/Cooling_tower&#39; title=&#39;Cooling tower&#39;>cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href=&#39;http://en.wikipedia.org/wiki/Nuclear_safety&#39; title=&#39;Nuclear safety&#39;>safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href=&#39;http://en.wikipedia.org/wiki/Plutonium&#39; title=&#39;Plutonium&#39;>plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href=&#39;http://en.wikipedia.org/wiki/Chewing_gum&#39; title=&#39;Chewing gum&#39;>Chewing gum</a>), dangerously high <a href=&#39;http://en.wikipedia.org/wiki/Geiger_counter&#39; title=&#39;Geiger counter&#39;>Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href=&#39;http://en.wikipedia.org/wiki/Radioactive&#39; title=&#39;Radioactive&#39;>radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', '1');
INSERT INTO `si_customers` (`id`, `domain_id`, `attention`, `name`, `street_address`, `street_address2`, `city`, `state`, `zip_code`, `country`, `phone`, `mobile_phone`, `fax`, `email`, `credit_card_holder_name`, `credit_card_number`, `credit_card_expiry_month`, `credit_card_expiry_year`, `notes`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `enabled`) VALUES(3, 1, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', '', 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', '', '03 9658 7457', 'kath@kathandkim.com.au', '', '', '', '', 'Kath Day-Knight (<a href=&#39;http://en.wikipedia.org/wiki/Jane_Turner&#39; title=&#39;Jane Turner&#39;>Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href=&#39;http://en.wikipedia.org/wiki/Glenn_Robbins&#39; title=&#39;Glenn Robbins&#39;>Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href=&#39;http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q&#39; title=&#39;List of French phrases used by English speakers&#39;>n&eacute;e</a> Day (<a href=&#39;http://en.wikipedia.org/wiki/Gina_Riley&#39; title=&#39;Gina Riley&#39;>Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href=&#39;http://en.wikipedia.org/wiki/Peter_Rowsthorn&#39; title=&#39;Peter Rowsthorn&#39;>Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href=&#39;http://en.wikipedia.org/wiki/Magda_Szubanski&#39; title=&#39;Magda Szubanski&#39;>Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', '13245-789798', '', '', '', '1');

CREATE TABLE IF NOT EXISTS `si_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `enabled` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_extensions` (`id`, `domain_id`, `name`, `description`, `enabled`) VALUES(1, 0, 'core', 'Core part of Simple Invoices - always enabled', '1');

CREATE TABLE IF NOT EXISTS `si_index` (
  `id` int(11) NOT NULL,
  `node` varchar(255) NOT NULL,
  `sub_node` varchar(255) DEFAULT NULL,
  `sub_node_2` varchar(255) DEFAULT NULL,
  `domain_id` int(11) NOT NULL
) ENGINE=MyISAM;

INSERT INTO `si_index` (`id`, `node`, `sub_node`, `sub_node_2`, `domain_id`) VALUES(1, 'invoice', '1', '', 1);

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES(1, 1, 3, '%', 10.000000, 12.500000);
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES(2, 2, 1, '%', 10.000000, 12.500000);
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES(3, 3, 4, '%', 0.000000, 0.000000);
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES(4, 4, 1, '%', 10.000000, 14.000000);
INSERT INTO `si_invoice_item_tax` (`id`, `invoice_item_id`, `tax_id`, `tax_type`, `tax_rate`, `tax_amount`) VALUES(5, 5, 4, '%', 0.000000, 0.000000);

CREATE TABLE IF NOT EXISTS `si_invoice_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(10) NOT NULL DEFAULT '0',
  `quantity` decimal(25,6) NOT NULL DEFAULT '0.000000',
  `product_id` int(10) DEFAULT '0',
  `unit_price` decimal(25,6) DEFAULT '0.000000',
  `tax_amount` decimal(25,6) DEFAULT '0.000000',
  `gross_total` decimal(25,6) DEFAULT '0.000000',
  `description` text,
  `total` decimal(25,6) DEFAULT '0.000000',
  `attribute` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES(1, 1, 1.000000, 5, 125.000000, 12.500000, 125.000000, '', 137.500000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES(2, 1, 1.000000, 3, 125.000000, 12.500000, 125.000000, '', 137.500000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES(3, 1, 1.000000, 2, 140.000000, 0.000000, 140.000000, '', 140.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES(4, 1, 1.000000, 2, 140.000000, 14.000000, 140.000000, '', 154.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_amount`, `gross_total`, `description`, `total`) VALUES(5, 1, 1.000000, 1, 150.000000, 0.000000, 150.000000, '', 150.000000);

CREATE TABLE IF NOT EXISTS `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_ty_description` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`inv_ty_id`)
) ENGINE=MyISAM;

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES(1, 'Total');
INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES(2, 'Itemised');
INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES(3, 'Consulting');

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

INSERT INTO `si_invoices` (`id`, `index_id`, `domain_id`, `biller_id`, `customer_id`, `type_id`, `preference_id`, `date`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `note`) VALUES(1, 1, 1, 4, 3, 2, 1, '2008-12-30 00:00:00', '', '', '', '', '');

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

INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`) VALUES(1, 1, 'Cash', '1');
INSERT INTO `si_payment_types` (`pt_id`, `domain_id`, `pt_description`, `pt_enabled`) VALUES(2, 1, 'Credit Card', '1');

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

INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES(1, 1, 'Invoice', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');
INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES(2, 1, 'Receipt', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');
INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES(3, 1, 'Estimate', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');
INSERT INTO `si_preferences` (`pref_id`, `domain_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`, `status`, `locale`, `language`, `index_group`, `currency_code`, `include_online_payment`, `currency_position`) VALUES(4, 1, 'Quote', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1', 1, 'en_GB', 'en_GB', 1, 'USD', NULL, 'left');

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

INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES(1, 1, 'Hourly charge', 150.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');
INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES(2, 1, 'Accounting services', 140.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');
INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES(3, 1, 'Ploughing service', 125.000000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');
INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES(4, 1, 'Bootleg homebrew', 15.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');
INSERT INTO `si_products` (`id`, `domain_id`, `description`, `unit_price`, `default_tax_id`, `default_tax_id_2`, `cost`, `reorder_level`, `custom_field1`, `custom_field2`, `custom_field3`, `custom_field4`, `notes`, `enabled`, `visible`, `attribute`, `notes_as_description`, `show_description`) VALUES(5, 1, 'Accomodation', 125.500000, 1, 0, 0.000000, 0, '', '', '', '', '', '1', 1, '', '', '');

CREATE TABLE `si_products_attribute_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attribute_type` VALUES
('1','list'),
('2','decimal'),
('3','free');

CREATE TABLE `si_products_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  `visible` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_attributes` VALUES
('1','Size',  '1','1','1'),
('2','Colour','1','1','1');

CREATE TABLE `si_products_matrix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_attribute_number` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_matrix` VALUES
('1','1','1','1'),
('2','1','2','2'),
('3','2','1','2');

CREATE TABLE `si_products_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `enabled` varchar(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `si_products_values` VALUES
('1','1','S','1'),
('2','1','M','1'),
('3','1','L','1'),
('4','2','Red','1'),
('5','2','White','1');

CREATE TABLE IF NOT EXISTS `si_sql_patchmanager` (
  `sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_patch_ref` int(11) NOT NULL,
  `sql_patch` varchar(255) NOT NULL,
  `sql_release` varchar(25) NOT NULL DEFAULT '',
  `sql_statement` text NOT NULL,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM;

INSERT INTO `si_sql_patchmanager`  VALUES  (1,1,'Create sql_patchmanger table','20060514','CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM ');
INSERT INTO `si_sql_patchmanager`  VALUES  (2,2,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (3,3,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (4,4,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (5,5,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (6,6,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (7,7,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (8,8,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (9,9,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (10,10,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (11,11,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (12,12,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (13,13,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (14,14,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (15,15,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (16,16,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (17,17,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (18,18,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (19,19,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (20,20,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (21,21,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (22,22,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (23,23,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (24,24,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (25,25,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (26,26,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (27,27,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (28,28,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (29,29,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (30,30,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (31,31,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (32,32,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (33,33,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (34,34,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (35,35,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (36,36,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (37,0,'Start','20060514','');
INSERT INTO `si_sql_patchmanager`  VALUES  (38,37,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (39,38,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (40,39,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (41,40,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (42,41,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (43,42,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (44,43,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (45,44,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (46,45,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (47,46,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (48,47,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (49,48,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (50,49,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (51,50,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (52,51,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (53,52,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (54,53,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (55,54,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (56,55,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (57,56,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (58,57,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (59,58,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (60,59,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (61,60,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (62,61,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (63,62,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (64,63,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (65,64,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (66,65,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (67,66,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (68,67,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (69,68,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (70,69,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (71,70,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (72,71,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (73,72,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (74,73,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (75,74,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (76,75,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (77,76,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (78,77,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (79,78,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (80,79,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (81,80,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (82,81,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (83,82,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (84,83,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (85,84,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (86,85,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (87,86,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (88,87,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (89,88,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (90,89,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (91,90,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (92,91,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (93,92,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (94,93,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (95,94,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (96,95,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (97,96,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (98,97,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (99,98,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (100,99,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (101,100,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (102,101,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (103,102,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (104,103,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (105,104,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (106,105,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (107,106,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (108,107,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (109,108,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (110,109,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (111,110,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (112,111,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (113,112,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (114,113,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (115,114,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (116,115,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (117,116,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (118,117,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (119,118,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (120,119,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (121,120,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (122,121,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (123,122,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (124,123,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (125,124,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (126,125,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (127,126,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (128,127,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (129,128,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (130,129,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (131,130,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (132,131,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (133,132,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (134,133,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (135,134,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (136,135,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (137,136,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (138,137,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (139,138,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (140,139,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (141,140,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (142,141,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (143,142,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (144,143,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (145,144,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (146,145,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (147,146,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (148,147,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (149,148,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (150,149,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (151,150,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (152,151,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (153,152,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (154,153,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (155,154,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (156,155,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (157,156,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (158,157,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (159,158,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (160,159,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (161,160,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (162,161,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (163,162,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (164,163,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (165,164,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (166,165,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (167,166,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (168,167,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (169,168,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (170,169,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (171,170,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (172,171,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (173,172,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (174,173,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (175,174,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (176,175,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (177,176,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (178,177,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (179,178,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (180,179,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (181,180,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (182,181,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (183,182,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (184,183,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (185,184,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (186,185,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (187,186,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (188,187,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (189,188,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (190,189,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (191,190,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (192,191,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (193,192,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (194,193,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (195,194,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (196,195,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (197,196,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (198,197,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (199,198,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (200,199,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (201,200,'Update extensions table','20090529','UPDATE si_extensions SET id = 0 WHERE name = core LIMIT 1');
INSERT INTO `si_sql_patchmanager`  VALUES  (202,201,'Set domain_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET domain_id = 1');
INSERT INTO `si_sql_patchmanager`  VALUES  (203,202,'Set extension_id on system defaults table to 1','20090622','UPDATE si_system_defaults SET extension_id = 1');
INSERT INTO `si_sql_patchmanager`  VALUES  (204,203,'Move all old consulting style invoices to itemised','20090704','UPDATE si_invoices SET type_id = 2 where type_id = 3');
INSERT INTO `si_sql_patchmanager`  VALUES  (205,204,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (206,205,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (207,206,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (208,207,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (209,208,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (210,209,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (211,210,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (212,211,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (213,212,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (214,213,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (215,214,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (216,215,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (217,216,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (218,217,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (219,218,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (220,219,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (221,220,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (222,221,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (223,222,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (224,223,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (225,224,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (226,225,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (227,226,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (228,227,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (229,228,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (230,229,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (231,230,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (232,231,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (233,232,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (234,233,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (235,234,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (236,235,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (237,236,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (238,237,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (239,238,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (240,239,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (241,240,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (242,241,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (243,242,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (244,243,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (245,244,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (246,245,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (247,246,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (248,247,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (249,248,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (250,249,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (251,250,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (252,251,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (253,252,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (254,253,'','','');
INSERT INTO `si_sql_patchmanager`  VALUES  (255,254,'Product Matrix - update line items table','20130313','ALTER TABLE `si_invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (256,255,'Product Matrix - update line items table','20130313',' \n        CREATE TABLE `si_products_attributes` (\n            `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n            `name` VARCHAR( 255 ) NOT NULL,\n            `type_id` VARCHAR( 255 ) NOT NULL\n            ) ENGINE = MYISAM ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (257,256,'Product Matrix - update line items table','20130313','INSERT INTO `si_products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, \'Size\',\'1\'), (NULL,\'Colour\',\'1\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (258,257,'Product Matrix - update line items table','20130313','CREATE TABLE `si_products_values` (\n`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n`attribute_id` INT( 11 ) NOT NULL ,\n`value` VARCHAR( 255 ) NOT NULL\n) ENGINE = MYISAM ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (259,258,'Product Matrix - update line items table','20130313','INSERT INTO `si_products_values` (`id`, `attribute_id`,`value`) VALUES (NULL,\'1\', \'S\'),  (NULL,\'1\', \'M\'), (NULL,\'1\', \'L\'),  (NULL,\'2\', \'Red\'),  (NULL,\'2\', \'White\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (260,259,'Product Matrix - update line items table','20130313','CREATE TABLE `si_products_matrix` (\n`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n`product_id` INT( 11 ) NOT NULL ,\n`attribute_id` INT( 11 ) NOT NULL\n) ENGINE = MYISAM ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (261,260,'Product Matrix - update line items table','20130313','ALTER TABLE `si_products_matrix` ADD `product_attribute_number` INT( 11 ) NOT NULL AFTER `product_id` ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (262,261,'Product Matrix - update line items table','20130313','INSERT INTO `si_products_matrix` (`id`, `product_id`,`product_attribute_number`, `attribute_id`) VALUES (NULL,\'1\', \'1\', \'1\'),  (NULL,\'1\', \'2\', \'2\'), (NULL,\'2\', \'1\', \'2\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (263,262,'Add product attributes system preference','20130313','INSERT INTO si_system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , \'product_attributes\', \'0\', \'1\', \'1\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (264,263,'Product Matrix - update line items table','20130313','ALTER TABLE `si_products` ADD `attribute` VARCHAR( 255 ) NULL ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (265,264,'Product - use notes as default line item description','20130314','ALTER TABLE `si_products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (266,265,'Product - expand/show line item description','20130314','ALTER TABLE `si_products` ADD `show_description` VARCHAR( 1 ) NULL ;');
INSERT INTO `si_sql_patchmanager`  VALUES  (267,266,'Product - expand/show line item description','20130322','CREATE TABLE `si_products_attribute_type` (\n            `id` int(11) NOT NULL AUTO_INCREMENT,\n                `name` varchar(255) NOT NULL,\n                  PRIMARY KEY (`id`)\n              ) ENGINE=MyISAM;');
INSERT INTO `si_sql_patchmanager`  VALUES  (268,267,'Product Matrix - insert attribute types','20130325','INSERT INTO `si_products_attribute_type` (`id`, `name`) VALUES (NULL,\'list\'),  (NULL,\'decimal\'), (NULL,\'free\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (269,268,'Product Matrix - insert attribute types','20130327','ALTER TABLE  `si_products_attributes` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  \'1\',\n        ADD  `visible` VARCHAR( 1 ) NULL DEFAULT  \'1\';');
INSERT INTO `si_sql_patchmanager`  VALUES  (270,269,'Product Matrix - insert attribute types','20130327','ALTER TABLE  `si_products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  \'1\';');
INSERT INTO `si_sql_patchmanager`  VALUES  (271,270,'Make Simple Invoices faster - add index','20100419','ALTER TABLE `si_payment` ADD INDEX(`ac_inv_id`);');
INSERT INTO `si_sql_patchmanager`  VALUES  (272,271,'Make Simple Invoices faster - add index','20100419','ALTER TABLE `si_payment` ADD INDEX(`ac_amount`);');
INSERT INTO `si_sql_patchmanager`  VALUES  (273,272,'Add product attributes system preference','20130313','INSERT INTO si_system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , \'large_dataset\', \'0\', \'1\', \'1\');');
INSERT INTO `si_sql_patchmanager`  VALUES  (274,273,'Make Simple Invoices faster - add index','20130927','ALTER TABLE `si_invoice_items` ADD INDEX(`invoice_id`);');

CREATE TABLE IF NOT EXISTS `si_system_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  `domain_id` int(5) NOT NULL DEFAULT '0',
  `extension_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`domain_id`,`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES
('1','biller','4','1','1'),
('2','customer','3','1','1'),
('3','tax','1','1','1'),
('4','preference','1','1','1'),
('5','line_items','5','1','1'),
('6','template','default','1','1'),
('7','payment_type','1','1','1'),
('8','language','en_GB','1','1'),
('9','dateformate','Y-m-d','1','1'),
('10','spreadsheet','xls','1','1'),
('11','wordprocessor','doc','1','1'),
('12','pdfscreensize','800','1','1'),
('13','pdfpapersize','A4','1','1'),
('14','pdfleftmargin','15','1','1'),
('15','pdfrightmargin','15','1','1'),
('16','pdftopmargin','15','1','1'),
('17','pdfbottommargin','15','1','1'),
('18','emailhost','localhost','1','1'),
('19','emailusername','','1','1'),
('20','emailpassword','','1','1'),
('21','logging','0','1','1'),
('22','delete','N','1','1'),
('23','tax_per_line_item','1','1','1'),
('24','inventory','0','1','1'),
('25','product_attributes','0','1','1'),
('26','large_dataset','0','1','1');

CREATE TABLE IF NOT EXISTS `si_tax` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_description` varchar(50) DEFAULT NULL,
  `tax_percentage` decimal(25,6) DEFAULT '0.000000',
  `type` varchar(1) DEFAULT NULL,
  `tax_enabled` varchar(1) NOT NULL DEFAULT '1',
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY (`domain_id`,`tax_id`)
) ENGINE=MyISAM;

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES(1, 'GST', 10.000000, '%', '1', 1);
INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES(2, 'VAT', 10.000000, '%', '1', 1);
INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES(3, 'Sales Tax', 10.000000, '%', '1', 1);
INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES(4, 'No Tax', 0.000000, '%', '1', 1);
INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `type`, `tax_enabled`, `domain_id`) VALUES(5, 'Postage', 20.000000, '$', '1', 1);

CREATE TABLE IF NOT EXISTS `si_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `domain_id` int(11) NOT NULL DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  `enabled` int(1) NOT NULL,
  PRIMARY KEY (`domain_id`,`id`)
) ENGINE=MyISAM;

INSERT INTO `si_user` (`id`, `email`, `role_id`, `domain_id`, `password`, `enabled`) VALUES(1, 'demo@simpleinvoices.org', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229', 1);

CREATE TABLE IF NOT EXISTS `si_user_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_domain` (`id`, `name`) VALUES(1, 'default');

CREATE TABLE IF NOT EXISTS `si_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;

INSERT INTO `si_user_role` (`id`, `name`) VALUES(1, 'administrator'),(2,'user'),(3,'viewer');

SET character_set_client = @saved_cs_client;
