-- phpMyAdmin SQL Dump
-- version 2.9.0
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 06, 2007 at 02:37 PM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
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
  `ac_payment_type` int(10) NOT NULL default '1',
  PRIMARY KEY  (`ac_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `si_account_payments`
-- 

INSERT INTO `si_account_payments` (`ac_id`, `ac_inv_id`, `ac_amount`, `ac_notes`, `ac_date`, `ac_payment_type`) VALUES 
(1, '1', 410.00, 'payment - cheque 14526', '2006-08-25 12:09:14', 1),
(2, '4', 255.75, '', '2006-08-25 12:13:53', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `si_biller`
-- 

CREATE TABLE `si_biller` (
  `b_id` int(10) NOT NULL auto_increment,
  `b_name` varchar(50) default NULL,
  `b_street_address` varchar(50) default NULL,
  `b_street_address2` varchar(50) default NULL,
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
  `b_notes` text,
  `b_custom_field1` varchar(50) default NULL,
  `b_custom_field2` varchar(50) default NULL,
  `b_custom_field3` varchar(50) default NULL,
  `b_custom_field4` varchar(50) default NULL,
  `b_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`b_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `si_biller`
-- 

INSERT INTO `si_biller` (`b_id`, `b_name`, `b_street_address`, `b_street_address2`, `b_city`, `b_state`, `b_zip_code`, `b_country`, `b_phone`, `b_mobile_phone`, `b_fax`, `b_email`, `b_co_logo`, `b_co_footer`, `b_notes`, `b_custom_field1`, `b_custom_field2`, `b_custom_field3`, `b_custom_field4`, `b_enabled`) VALUES 
(1, 'Mr Plough', '43 Evergreen Terace', '', 'Springfield', 'New York', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '', '', '7898-87987-87', '', '', '1'),
(2, 'Homer Simpson', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(3, 'The Beer Baron', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(4, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_custom_fields`
-- 

CREATE TABLE `si_custom_fields` (
  `cf_id` int(11) NOT NULL auto_increment,
  `cf_custom_field` varchar(50) collate utf8_unicode_ci NOT NULL,
  `cf_custom_label` varchar(50) collate utf8_unicode_ci default NULL,
  `cf_display` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`cf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- 
-- Dumping data for table `si_custom_fields`
-- 

INSERT INTO `si_custom_fields` (`cf_id`, `cf_custom_field`, `cf_custom_label`, `cf_display`) VALUES 
(1, 'biller_cf1', NULL, '0'),
(2, 'biller_cf2', 'Tax ID', '0'),
(3, 'biller_cf3', NULL, '0'),
(4, 'biller_cf4', NULL, '0'),
(5, 'customer_cf1', NULL, '0'),
(6, 'customer_cf2', NULL, '0'),
(7, 'customer_cf3', NULL, '0'),
(8, 'customer_cf4', NULL, '0'),
(9, 'product_cf1', NULL, '0'),
(10, 'product_cf2', NULL, '0'),
(11, 'product_cf3', NULL, '0'),
(12, 'product_cf4', NULL, '0'),
(13, 'invoice_cf1', NULL, '0'),
(14, 'invoice_cf2', NULL, '0'),
(15, 'invoice_cf3', NULL, '0'),
(16, 'invoice_cf4', NULL, '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_customers`
-- 

CREATE TABLE `si_customers` (
  `c_id` int(10) NOT NULL auto_increment,
  `c_attention` varchar(50) default NULL,
  `c_name` varchar(50) default NULL,
  `c_street_address` varchar(50) default NULL,
  `c_street_address2` varchar(50) default NULL,
  `c_city` varchar(50) default NULL,
  `c_state` varchar(50) default NULL,
  `c_zip_code` varchar(50) default NULL,
  `c_country` varchar(100) default NULL,
  `c_phone` varchar(50) default NULL,
  `c_mobile_phone` varchar(50) default NULL,
  `c_fax` varchar(50) default NULL,
  `c_email` varchar(50) default NULL,
  `c_notes` text,
  `c_custom_field1` varchar(50) default NULL,
  `c_custom_field2` varchar(50) default NULL,
  `c_custom_field3` varchar(50) default NULL,
  `c_custom_field4` varchar(50) default NULL,
  `c_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `si_customers`
-- 

INSERT INTO `si_customers` (`c_id`, `c_attention`, `c_name`, `c_street_address`, `c_street_address2`, `c_city`, `c_state`, `c_zip_code`, `c_country`, `c_phone`, `c_mobile_phone`, `c_fax`, `c_email`, `c_notes`, `c_custom_field1`, `c_custom_field2`, `c_custom_field3`, `c_custom_field4`, `c_enabled`) VALUES 
(1, 'Moe Sivloski', 'Moes Tarvern', '45 Main Road', NULL, 'Springfield', 'New York', '65891', '', '04 1234 5698', NULL, '04 5689 4566', 'moe@moestavern.com', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href="http://en.wikipedia.org/wiki/Bar_%28establishment%29" title="Bar (establishment)">bar</a> seen on <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The owner of the bar is <a href="http://en.wikipedia.org/wiki/Moe_Szyslak" title="Moe Szyslak">Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href="http://en.wikipedia.org/wiki/Bart_Simpson" title="Bart Simpson">Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href="http://en.wikipedia.org/wiki/Urine" title="Urine">tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href="http://en.wikipedia.org/wiki/1979" title="1979">1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href="http://en.wikipedia.org/wiki/Homer%27s_Enemy" title="Homer&#39;s Enemy">Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns" title="Who Shot Mr. Burns">Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href="http://en.wikipedia.org/wiki/Flaming_Moe%27s" title="Flaming Moe&#39;s">Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href="http://en.wikipedia.org/wiki/Homer_the_Smithers" title="Homer the Smithers">Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Principal_Charming" title="Principal Charming">Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href="http://en.wikipedia.org/wiki/Duff_Beer" title="Duff Beer">Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', NULL, NULL, NULL, NULL, '1'),
(2, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', 'street2', 'Springfield', 'New York', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mr.burn@spp.com', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href="http://en.wikipedia.org/wiki/Television" title="Television">television</a> <a href="http://en.wikipedia.org/wiki/Animated_cartoon" title="Animated cartoon">animated cartoon</a> series <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The plant has a <a href="http://en.wikipedia.org/wiki/Monopoly" title="Monopoly">monopoly</a> on the city of <a href="http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29" title="Springfield (The Simpsons)">Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href="http://en.wikipedia.org/wiki/Containment_building" title="Containment building">containment buildings</a> and <a href="http://en.wikipedia.org/wiki/Cooling_tower" title="Cooling tower">cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href="http://en.wikipedia.org/wiki/Nuclear_safety" title="Nuclear safety">safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href="http://en.wikipedia.org/wiki/Plutonium" title="Plutonium">plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href="http://en.wikipedia.org/wiki/Chewing_gum" title="Chewing gum">Chewing gum</a>), dangerously high <a href="http://en.wikipedia.org/wiki/Geiger_counter" title="Geiger counter">Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href="http://en.wikipedia.org/wiki/Radioactive" title="Radioactive">radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', '1'),
(3, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', NULL, 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', NULL, '03 9658 7457', 'kath@kathandkim.com.au', 'Kath Day-Knight (<a href="http://en.wikipedia.org/wiki/Jane_Turner" title="Jane Turner">Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href="http://en.wikipedia.org/wiki/Glenn_Robbins" title="Glenn Robbins">Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href="http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q" title="List of French phrases used by English speakers">n&eacute;e</a> Day (<a href="http://en.wikipedia.org/wiki/Gina_Riley" title="Gina Riley">Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href="http://en.wikipedia.org/wiki/Peter_Rowsthorn" title="Peter Rowsthorn">Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href="http://en.wikipedia.org/wiki/Magda_Szubanski" title="Magda Szubanski">Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', NULL, NULL, NULL, NULL, '1');

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
  `def_inv_template` varchar(50) NOT NULL default 'default',
  `def_payment_type` varchar(25) default '1',
  PRIMARY KEY  (`def_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `si_defaults`
-- 

INSERT INTO `si_defaults` (`def_id`, `def_biller`, `def_customer`, `def_tax`, `def_inv_preference`, `def_number_line_items`, `def_inv_template`, `def_payment_type`) VALUES 
(1, 4, 3, 1, 1, 5, 'default', '1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- 
-- Dumping data for table `si_invoice_items`
-- 

INSERT INTO `si_invoice_items` (`inv_it_id`, `inv_it_invoice_id`, `inv_it_quantity`, `inv_it_product_id`, `inv_it_unit_price`, `inv_it_tax_id`, `inv_it_tax`, `inv_it_tax_amount`, `inv_it_gross_total`, `inv_it_description`, `inv_it_total`) VALUES 
(1, 1, 1, 1, 150.00, '1', 10.00, 15.00, 150.00, '00', 165.00),
(2, 1, 2, 3, 125.00, '1', 10.00, 25.00, 250.00, '00', 275.00),
(3, 2, 1, 0, 0.00, '3', 10.00, 14.50, 145.00, 'For ploughing services for the period 01 Jan - 01 Feb 2006', 159.50),
(4, 3, 2, 2, 140.00, '1', 10.00, 28.00, 280.00, 'Accounting services - basic bookkeeping', 308.00),
(5, 3, 1, 2, 140.00, '1', 10.00, 14.00, 140.00, 'Accounting services - tax return for 2005', 154.00),
(6, 3, 2, 2, 140.00, '1', 10.00, 28.00, 280.00, 'Accounting serverice - general ledger work', 308.00),
(7, 4, 15, 4, 15.50, '4', 10.00, 23.25, 232.50, '00', 255.75),
(8, 5, 1, 2, 140.00, '4', 10.00, 14.00, 140.00, 'Quote for accounting service - hours', 154.00),
(9, 5, 2, 1, 150.00, '4', 10.00, 30.00, 300.00, 'Quote for new servers', 330.00),
(10, 6, 1, 1, 150.00, '4', 10.00, 15.00, 150.00, '00', 165.00),
(11, 6, 2, 4, 15.50, '4', 10.00, 3.10, 31.00, '00', 34.10),
(12, 6, 4, 5, 125.00, '4', 10.00, 50.00, 500.00, '00', 550.00),
(13, 7, 1, 2, 140.00, '1', 10.00, 14.00, 140.00, '00', 154.00),
(14, 7, 2, 5, 125.00, '1', 10.00, 25.00, 250.00, '00', 275.00),
(15, 8, 10, 5, 125.00, '1', 10.00, 125.00, 1250.00, '00', 1375.00),
(16, 8, 10, 4, 15.50, '1', 10.00, 15.50, 155.00, '00', 170.50);

-- --------------------------------------------------------

-- 
-- Table structure for table `si_invoice_type`
-- 

CREATE TABLE `si_invoice_type` (
  `inv_ty_id` int(11) NOT NULL auto_increment,
  `inv_ty_description` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`inv_ty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `si_invoice_type`
-- 

INSERT INTO `si_invoice_type` (`inv_ty_id`, `inv_ty_description`) VALUES 
(1, 'Total'),
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
  `invoice_custom_field1` varchar(50) default NULL,
  `invoice_custom_field2` varchar(50) default NULL,
  `invoice_custom_field3` varchar(50) default NULL,
  `invoice_custom_field4` varchar(50) default NULL,
  `inv_note` text,
  PRIMARY KEY  (`inv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `si_invoices`
-- 

INSERT INTO `si_invoices` (`inv_id`, `inv_biller_id`, `inv_customer_id`, `inv_type`, `inv_preference`, `inv_date`, `invoice_custom_field1`, `invoice_custom_field2`, `invoice_custom_field3`, `invoice_custom_field4`, `inv_note`) VALUES 
(1, 4, 3, 2, 1, '2007-02-03 00:00:00', NULL, NULL, NULL, NULL, 'Will be delivered via certified post'),
(2, 1, 2, 1, 1, '2007-01-01 00:00:00', NULL, NULL, NULL, NULL, ''),
(3, 2, 3, 3, 1, '2007-02-04 00:00:00', NULL, NULL, NULL, NULL, ''),
(4, 2, 1, 2, 4, '2006-08-25 12:12:17', NULL, NULL, NULL, NULL, 'Weekly bootleg deliveries'),
(5, 4, 3, 3, 5, '2007-01-16 00:00:00', NULL, NULL, NULL, NULL, ''),
(6, 4, 3, 2, 3, '2006-08-25 12:13:37', NULL, NULL, NULL, NULL, ''),
(7, 2, 2, 2, 1, '2006-12-10 22:32:48', NULL, NULL, NULL, NULL, 'this is a test<br />'),
(8, 4, 3, 2, 1, '2007-02-06 00:00:00', NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `si_payment_types`
-- 

CREATE TABLE `si_payment_types` (
  `pt_id` int(10) NOT NULL auto_increment,
  `pt_description` varchar(250) collate utf8_unicode_ci NOT NULL,
  `pt_enabled` varchar(1) collate utf8_unicode_ci NOT NULL default '1',
  PRIMARY KEY  (`pt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `si_payment_types`
-- 

INSERT INTO `si_payment_types` (`pt_id`, `pt_description`, `pt_enabled`) VALUES 
(1, 'Cash', '1'),
(2, 'Credit Card', '1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `si_preferences`
-- 

INSERT INTO `si_preferences` (`pref_id`, `pref_description`, `pref_currency_sign`, `pref_inv_heading`, `pref_inv_wording`, `pref_inv_detail_heading`, `pref_inv_detail_line`, `pref_inv_payment_method`, `pref_inv_payment_line1_name`, `pref_inv_payment_line1_value`, `pref_inv_payment_line2_name`, `pref_inv_payment_line2_value`, `pref_enabled`) VALUES 
(1, 'Invoice - default', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name:', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1'),
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
  `prod_custom_field1` varchar(50) default NULL,
  `prod_custom_field2` varchar(50) default NULL,
  `prod_custom_field3` varchar(50) default NULL,
  `prod_custom_field4` varchar(50) default NULL,
  `prod_notes` text NOT NULL,
  `prod_enabled` varchar(1) NOT NULL default '1',
  PRIMARY KEY  (`prod_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `si_products`
-- 

INSERT INTO `si_products` (`prod_id`, `prod_description`, `prod_unit_price`, `prod_custom_field1`, `prod_custom_field2`, `prod_custom_field3`, `prod_custom_field4`, `prod_notes`, `prod_enabled`) VALUES 
(1, 'IBM Netfinity 5000', 150.00, NULL, NULL, NULL, NULL, '', '1'),
(2, 'Accouting services - Barney Gumball (hours)', 140.00, 'CVF1', '', '', '', '', '1'),
(3, 'Weekly ploughing service', 125.00, NULL, NULL, NULL, NULL, '', '1'),
(4, 'Bootleg homebrew', 15.50, NULL, NULL, NULL, NULL, '', '1'),
(5, 'Accomadation', 125.00, NULL, NULL, NULL, NULL, '', '1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- 
-- Dumping data for table `si_sql_patchmanager`
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
(33, '33', 'Alter product custom field 4', '20061214', ''),
(34, '34', 'Reset invoice template to default refer Issue 70', '20070125', ''),
(35, '35', 'Adding data to the custom fields table for invoice', '20070204', ''),
(36, '36', 'Adding custom fields to the invoices table', '20070204', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `si_tax`
-- 

INSERT INTO `si_tax` (`tax_id`, `tax_description`, `tax_percentage`, `tax_enabled`) VALUES 
(1, 'GST (AUS)', 10.00, '1'),
(2, 'VAT (UK)', 10.00, '1'),
(3, 'Sales Tax (USA)', 10.00, '1'),
(4, 'GST (NZ)', 10.00, '1'),
(5, 'No Tax', 0.00, '1'),
(6, 'IVA', 20.00, '1'),
(7, 'MWSt (DE)', 16.00, '1');
