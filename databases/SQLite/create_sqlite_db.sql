


CREATE TABLE IF NOT EXISTS "si_account_payments" (
  "id" INTEGER PRIMARY KEY,
  "ac_inv_id" int(11) NOT NULL,
  "ac_amount" decimal(25,6) NOT NULL,
  "ac_notes" text NOT NULL,
  "ac_date" datetime NOT NULL,
  "ac_payment_type" int(10) NOT NULL default '1'
);

INSERT INTO "si_account_payments" ("id", "ac_inv_id", "ac_amount", "ac_notes", "ac_date", "ac_payment_type") VALUES
("", 1, 410.000000, 'payment - cheque 14526', '2006-08-25 12:09:14', 1);
INSERT INTO "si_account_payments" ("id", "ac_inv_id", "ac_amount", "ac_notes", "ac_date", "ac_payment_type") VALUES
("", 4, 255.750000, '', '2006-08-25 12:13:53', 1);



CREATE TABLE IF NOT EXISTS "si_biller" (
  "id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "name" varchar(255) default NULL,
  "street_address" varchar(255) default NULL,
  "street_address2" varchar(255) default NULL,
  "city" varchar(255) default NULL,
  "state" varchar(255) default NULL,
  "zip_code" varchar(255) default NULL,
  "country" varchar(255) default NULL,
  "phone" varchar(255) default NULL,
  "mobile_phone" varchar(255) default NULL,
  "fax" varchar(255) default NULL,
  "email" varchar(255) default NULL,
  "logo" varchar(255) default NULL,
  "footer" text,
  "notes" text,
  "custom_field1" varchar(255) default NULL,
  "custom_field2" varchar(255) default NULL,
  "custom_field3" varchar(255) default NULL,
  "custom_field4" varchar(255) default NULL,
  "enabled" varchar(1) NOT NULL default '1'

) ;



INSERT INTO "si_biller" ("id", "domain_id", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "logo", "footer", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(1, 1, 'Mr Plough', '43 Evergreen Terace', '', 'Springfield', 'New York', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '', '', '7898-87987-87', '', '', '1');
INSERT INTO "si_biller" ("id", "domain_id", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "logo", "footer", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(2, 1, 'Homer Simpson', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1');
INSERT INTO "si_biller" ("id", "domain_id", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "logo", "footer", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(3, 1, 'The Beer Baron', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1');
INSERT INTO "si_biller" ("id", "domain_id", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "logo", "footer", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(4, 1, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1');



CREATE TABLE IF NOT EXISTS "si_customers" (
  "id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "attention" varchar(255) default NULL,
  "name" varchar(255) default NULL,
  "street_address" varchar(255) default NULL,
  "street_address2" varchar(255) default NULL,
  "city" varchar(255) default NULL,
  "state" varchar(255) default NULL,
  "zip_code" varchar(255) default NULL,
  "country" varchar(255) default NULL,
  "phone" varchar(255) default NULL,
  "mobile_phone" varchar(255) default NULL,
  "fax" varchar(255) default NULL,
  "email" varchar(255) default NULL,
  "notes" text,
  "custom_field1" varchar(255) default NULL,
  "custom_field2" varchar(255) default NULL,
  "custom_field3" varchar(255) default NULL,
  "custom_field4" varchar(255) default NULL,
  "enabled" varchar(1) NOT NULL default '1'

) ;



INSERT INTO "si_customers" ("id", "domain_id", "attention", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(1, 1, 'Moe Sivloski', 'Moes Tarvern', '45 Main Road', NULL, 'Springfield', 'New York', '65891', '', '04 1234 5698', NULL, '04 5689 4566', 'moe@moestavern.com', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href="http://en.wikipedia.org/wiki/Bar_%28establishment%29" title="Bar (establishment)">bar</a> seen on <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The owner of the bar is <a href="http://en.wikipedia.org/wiki/Moe_Szyslak" title="Moe Szyslak">Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href="http://en.wikipedia.org/wiki/Bart_Simpson" title="Bart Simpson">Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href="http://en.wikipedia.org/wiki/Urine" title="Urine">tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href="http://en.wikipedia.org/wiki/1979" title="1979">1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href="http://en.wikipedia.org/wiki/Homer%27s_Enemy" title="Homer&#39;s Enemy">Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns" title="Who Shot Mr. Burns">Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href="http://en.wikipedia.org/wiki/Flaming_Moe%27s" title="Flaming Moe&#39;s">Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href="http://en.wikipedia.org/wiki/Homer_the_Smithers" title="Homer the Smithers">Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Principal_Charming" title="Principal Charming">Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href="http://en.wikipedia.org/wiki/Duff_Beer" title="Duff Beer">Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', NULL, NULL, NULL, NULL, '1');
INSERT INTO "si_customers" ("id", "domain_id", "attention", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(2, 1, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', 'street2', 'Springfield', 'New York', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mr.burn@spp.com', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href="http://en.wikipedia.org/wiki/Television" title="Television">television</a> <a href="http://en.wikipedia.org/wiki/Animated_cartoon" title="Animated cartoon">animated cartoon</a> series <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The plant has a <a href="http://en.wikipedia.org/wiki/Monopoly" title="Monopoly">monopoly</a> on the city of <a href="http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29" title="Springfield (The Simpsons)">Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href="http://en.wikipedia.org/wiki/Containment_building" title="Containment building">containment buildings</a> and <a href="http://en.wikipedia.org/wiki/Cooling_tower" title="Cooling tower">cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href="http://en.wikipedia.org/wiki/Nuclear_safety" title="Nuclear safety">safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href="http://en.wikipedia.org/wiki/Plutonium" title="Plutonium">plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href="http://en.wikipedia.org/wiki/Chewing_gum" title="Chewing gum">Chewing gum</a>), dangerously high <a href="http://en.wikipedia.org/wiki/Geiger_counter" title="Geiger counter">Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href="http://en.wikipedia.org/wiki/Radioactive" title="Radioactive">radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', '1');
INSERT INTO "si_customers" ("id", "domain_id", "attention", "name", "street_address", "street_address2", "city", "state", "zip_code", "country", "phone", "mobile_phone", "fax", "email", "notes", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "enabled") VALUES
(3, 1, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', NULL, 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', NULL, '03 9658 7457', 'kath@kathandkim.com.au', 'Kath Day-Knight (<a href="http://en.wikipedia.org/wiki/Jane_Turner" title="Jane Turner">Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href="http://en.wikipedia.org/wiki/Glenn_Robbins" title="Glenn Robbins">Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href="http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q" title="List of French phrases used by English speakers">n&eacute;e</a> Day (<a href="http://en.wikipedia.org/wiki/Gina_Riley" title="Gina Riley">Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href="http://en.wikipedia.org/wiki/Peter_Rowsthorn" title="Peter Rowsthorn">Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href="http://en.wikipedia.org/wiki/Magda_Szubanski" title="Magda Szubanski">Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', NULL, NULL, NULL, NULL, '1');



CREATE TABLE IF NOT EXISTS "si_custom_fields" (
  "cf_id" INTEGER PRIMARY KEY,
  "cf_custom_field" varchar(255)  default NULL,
  "cf_custom_label" varchar(255)  default NULL,
  "cf_display" varchar(1) NOT NULL default '1'

)  ;



INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(1, 'biller_cf1', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(2, 'biller_cf2', 'Tax ID', '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(3, 'biller_cf3', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(4, 'biller_cf4', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(5, 'customer_cf1', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(6, 'customer_cf2', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(7, 'customer_cf3', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(8, 'customer_cf4', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(9, 'product_cf1', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(10, 'product_cf2', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(11, 'product_cf3', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(12, 'product_cf4', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(13, 'invoice_cf1', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(14, 'invoice_cf2', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(15, 'invoice_cf3', NULL, '0');
INSERT INTO "si_custom_fields" ("cf_id", "cf_custom_field", "cf_custom_label", "cf_display") VALUES
(16, 'invoice_cf4', NULL, '0');


CREATE TABLE IF NOT EXISTS "si_defaults" (
  "def_id" INTEGER PRIMARY KEY,
  "def_biller" int(25) default NULL,
  "def_customer" int(25) default NULL,
  "def_tax" int(25) default NULL,
  "def_inv_preference" int(25) default NULL,
  "def_number_line_items" int(25) NOT NULL default '0',
  "def_inv_template" varchar(50) NOT NULL default 'default',
  "def_payment_type" varchar(25) default '1'
) ;



INSERT INTO "si_defaults" ("def_id", "def_biller", "def_customer", "def_tax", "def_inv_preference", "def_number_line_items", "def_inv_template", "def_payment_type") VALUES
(1, 4, 3, 1, 1, 5, 'default', '1');



CREATE TABLE IF NOT EXISTS "si_invoices" (
  "id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "biller_id" int(10) NOT NULL default '0',
  "customer_id" int(10) NOT NULL default '0',
  "type_id" int(10) NOT NULL default '0',
  "preference_id" int(10) NOT NULL default '0',
  "date" datetime NOT NULL default '0000-00-00 00:00:00',
  "custom_field1" varchar(50) default NULL,
  "custom_field2" varchar(50) default NULL,
  "custom_field3" varchar(50) default NULL,
  "custom_field4" varchar(50) default NULL,
  "note" text
)  ;


INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(1, 1, 4, 3, 2, 1, '2007-02-03 00:00:00', NULL, NULL, NULL, NULL, 'Will be delivered via certified post');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(2, 1, 1, 2, 1, 1, '2007-01-01 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(3, 1, 2, 3, 3, 1, '2007-02-04 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(4, 1, 2, 1, 2, 4, '2006-08-25 12:12:17', NULL, NULL, NULL, NULL, 'Weekly bootleg deliveries');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(5, 1, 4, 3, 3, 5, '2007-01-16 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(6, 1, 4, 3, 2, 3, '2006-08-25 12:13:37', NULL, NULL, NULL, NULL, '');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(7, 1, 2, 2, 2, 1, '2006-12-10 22:32:48', NULL, NULL, NULL, NULL, 'this is a test<br />');
INSERT INTO "si_invoices" ("id", "domain_id", "biller_id", "customer_id", "type_id", "preference_id", "date", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "note") VALUES
(8, 1, 4, 3, 2, 1, '2007-02-06 00:00:00', NULL, NULL, NULL, NULL, '');


CREATE TABLE IF NOT EXISTS "si_invoice_items" (
  "id" INTEGER PRIMARY KEY,
  "invoice_id" int(10) NOT NULL default '0',
  "quantity" decimal(25,6) NOT NULL default '0.000000',
  "product_id" int(10) default '0',
  "unit_price" decimal(25,6) default '0.000000',
  "tax_id" int(11) NOT NULL default '0',
  "tax" decimal(25,6) default '0.000000',
  "tax_amount" decimal(25,6) default '0.000000',
  "gross_total" decimal(25,6) default '0.000000',
  "description" text,
  "total" decimal(25,6) default '0.000000'
)  ;


INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(1, 1, 1.000000, 1, 150.000000, 1, 10.000000, 15.000000, 150.000000, '00', 165.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(2, 1, 2.000000, 3, 125.000000, 1, 10.000000, 25.000000, 250.000000, '00', 275.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(3, 2, 1.000000, 6, 145.000000, 3, 10.000000, 14.500000, 145.000000, 'For ploughing services for the period 01 Jan - 01 Feb 2006', 159.500000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(4, 3, 2.000000, 2, 140.000000, 1, 10.000000, 28.000000, 280.000000, 'Accounting services - basic bookkeeping', 308.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(5, 3, 1.000000, 2, 140.000000, 1, 10.000000, 14.000000, 140.000000, 'Accounting services - tax return for 2005', 154.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(6, 3, 2.000000, 2, 140.000000, 1, 10.000000, 28.000000, 280.000000, 'Accounting serverice - general ledger work', 308.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(7, 4, 15.000000, 4, 15.500000, 4, 10.000000, 23.250000, 232.500000, '00', 255.750000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(8, 5, 1.000000, 2, 140.000000, 4, 10.000000, 14.000000, 140.000000, 'Quote for accounting service - hours', 154.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(9, 5, 2.000000, 1, 150.000000, 4, 10.000000, 30.000000, 300.000000, 'Quote for new servers', 330.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(10, 6, 1.000000, 1, 150.000000, 4, 10.000000, 15.000000, 150.000000, '00', 165.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(11, 6, 2.000000, 4, 15.500000, 4, 10.000000, 3.100000, 31.000000, '00', 34.100000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(12, 6, 4.000000, 5, 125.000000, 4, 10.000000, 50.000000, 500.000000, '00', 550.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(13, 7, 1.000000, 2, 140.000000, 1, 10.000000, 14.000000, 140.000000, '00', 154.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(14, 7, 2.000000, 5, 125.000000, 1, 10.000000, 25.000000, 250.000000, '00', 275.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(15, 8, 10.000000, 5, 125.000000, 1, 10.000000, 125.000000, 1250.000000, '00', 1375.000000);
INSERT INTO `si_invoice_items` (`id`, `invoice_id`, `quantity`, `product_id`, `unit_price`, `tax_id`, `tax`, `tax_amount`, `gross_total`, `description`, `total`) VALUES
(16, 8, 10.000000, 4, 15.500000, 1, 10.000000, 15.500000, 155.000000, '00', 170.500000);



CREATE TABLE IF NOT EXISTS "si_invoice_type" (
  "inv_ty_id" INTEGER PRIMARY KEY,
  "inv_ty_description" varchar(25) NOT NULL default ''

) ;


INSERT INTO "si_invoice_type" ("inv_ty_id", "inv_ty_description") VALUES
(1, 'Total');
INSERT INTO "si_invoice_type" ("inv_ty_id", "inv_ty_description") VALUES
(2, 'Itemised');
INSERT INTO "si_invoice_type" ("inv_ty_id", "inv_ty_description") VALUES
(3, 'Consulting');


CREATE TABLE IF NOT EXISTS "si_log" (
  "id" INTEGER PRIMARY KEY,
  "timestamp" timestamp NOT NULL default CURRENT_TIMESTAMP,
  "userid" varchar(40) NOT NULL default '0',
  "sqlquerie" text NOT NULL
)  ;



CREATE TABLE IF NOT EXISTS "si_payment_types" (
  "pt_id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "pt_description" varchar(250)  NOT NULL,
  "pt_enabled" varchar(1) NOT NULL default '1'
) ;



INSERT INTO "si_payment_types" ("pt_id", "domain_id", "pt_description", "pt_enabled") VALUES
(1, 1, 'Cash', '1');
INSERT INTO "si_payment_types" ("pt_id", "domain_id", "pt_description", "pt_enabled") VALUES
(2, 1, 'Credit Card', '1');


CREATE TABLE IF NOT EXISTS "si_preferences" (
  "pref_id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "pref_description" varchar(50) default NULL,
  "pref_currency_sign" varchar(50) default NULL,
  "pref_inv_heading" varchar(50) default NULL,
  "pref_inv_wording" varchar(50) default NULL,
  "pref_inv_detail_heading" varchar(50) default NULL,
  "pref_inv_detail_line" text,
  "pref_inv_payment_method" varchar(50) default NULL,
  "pref_inv_payment_line1_name" varchar(50) default NULL,
  "pref_inv_payment_line1_value" varchar(50) default NULL,
  "pref_inv_payment_line2_name" varchar(50) default NULL,
  "pref_inv_payment_line2_value" varchar(50) default NULL,
  "pref_enabled" varchar(1) NOT NULL default '1'

) ;



INSERT INTO "si_preferences" ("pref_id", "domain_id", "pref_description", "pref_currency_sign", "pref_inv_heading", "pref_inv_wording", "pref_inv_detail_heading", "pref_inv_detail_line", "pref_inv_payment_method", "pref_inv_payment_line1_name", "pref_inv_payment_line1_value", "pref_inv_payment_line2_name", "pref_inv_payment_line2_value", "pref_enabled") VALUES
(1, 1, 'Invoice - default', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name:', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1');
INSERT INTO "si_preferences" ("pref_id", "domain_id", "pref_description", "pref_currency_sign", "pref_inv_heading", "pref_inv_wording", "pref_inv_detail_heading", "pref_inv_detail_line", "pref_inv_payment_method", "pref_inv_payment_line1_name", "pref_inv_payment_line1_value", "pref_inv_payment_line2_name", "pref_inv_payment_line2_value", "pref_enabled") VALUES
(2, 1, 'Invoice - no payment details', '$', 'Invoice', 'Invoice', NULL, '', '', '', '', '', '', '1');
INSERT INTO "si_preferences" ("pref_id", "domain_id", "pref_description", "pref_currency_sign", "pref_inv_heading", "pref_inv_wording", "pref_inv_detail_heading", "pref_inv_detail_line", "pref_inv_payment_method", "pref_inv_payment_line1_name", "pref_inv_payment_line1_value", "pref_inv_payment_line2_name", "pref_inv_payment_line2_value", "pref_enabled") VALUES
(3, 1, 'Receipt - default', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1');
INSERT INTO "si_preferences" ("pref_id", "domain_id", "pref_description", "pref_currency_sign", "pref_inv_heading", "pref_inv_wording", "pref_inv_detail_heading", "pref_inv_detail_line", "pref_inv_payment_method", "pref_inv_payment_line1_name", "pref_inv_payment_line1_value", "pref_inv_payment_line2_name", "pref_inv_payment_line2_value", "pref_enabled") VALUES
(4, 1, 'Estimate - default', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1');
INSERT INTO "si_preferences" ("pref_id", "domain_id", "pref_description", "pref_currency_sign", "pref_inv_heading", "pref_inv_wording", "pref_inv_detail_heading", "pref_inv_detail_line", "pref_inv_payment_method", "pref_inv_payment_line1_name", "pref_inv_payment_line1_value", "pref_inv_payment_line2_name", "pref_inv_payment_line2_value", "pref_enabled") VALUES
(5, 1, 'Quote - default', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1');


CREATE TABLE IF NOT EXISTS "si_products" (
  "id" INTEGER PRIMARY KEY,
  "domain_id" int(11) NOT NULL default '1',
  "description" text NOT NULL,
  "unit_price" decimal(25,6) default '0.000000',
  "custom_field1" varchar(255) default NULL,
  "custom_field2" varchar(255) default NULL,
  "custom_field3" varchar(255) default NULL,
  "custom_field4" varchar(255) default NULL,
  "notes" text NOT NULL,
  "enabled" varchar(1) NOT NULL default '1',
  "visible" tinyint(1) NOT NULL default '1'
)  ;


INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(1, 1, 'IBM Netfinity 5000', 150.000000, NULL, NULL, NULL, NULL, '', '1', 1);
INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(2, 1, 'Accounting services - Barney Gumball (hours)', 140.000000, 'CVF1', '', '', '', '', '1', 1);
INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(3, 1, 'Weekly ploughing service', 125.000000, NULL, NULL, NULL, NULL, '', '1', 1);
INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(4, 1, 'Bootleg homebrew', 15.500000, NULL, NULL, NULL, NULL, '', '1', 1);
INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(5, 1, 'Accomodation', 125.000000, NULL, NULL, NULL, NULL, '', '1', 1);
INSERT INTO "si_products" ("id", "domain_id", "description", "unit_price", "custom_field1", "custom_field2", "custom_field3", "custom_field4", "notes", "enabled", "visible") VALUES
(6, 1, 'For ploughing services for the period 01 Jan - 01 Feb 2006', 145.000000, NULL, NULL, NULL, NULL, '', '0', 0);


CREATE TABLE IF NOT EXISTS "si_sql_patchmanager" (
  "sql_id" INTEGER PRIMARY KEY,
  "sql_patch_ref" int(11) NOT NULL,
  "sql_patch" varchar(255) NOT NULL,
  "sql_release" varchar(25) NOT NULL default '',
  "sql_statement" text NOT NULL
)  ;



INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(1, 1, 'Create sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) ENGINE = MYISAM ');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(2, 2, 'Update invoice no details to have a default currency sign', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(3, 3, 'Add a row into the defaults table to handle the default number of line items', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(4, 4, 'Set the default number of line items to 5', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(5, 5, 'Add logo and invoice footer support to biller', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(6, 6, 'Add default invoice template option', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(7, 7, 'Edit tax description field length to 50', '20060526', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(8, 8, 'Edit default invoice template field length to 50', '20060526', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(9, 9, 'Add consulting style invoice', '20060531', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(10, 10, 'Add enabled to biller', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(11, 11, 'Add enabled to customers', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(12, 12, 'Add enabled to preferences', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(13, 13, 'Add enabled to products', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(14, 14, 'Add enabled to products', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(15, 15, 'Add tax_id into invoice_items table', '20060815', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(16, 16, 'Add Payments table', '20060827', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(17, 17, 'Adjust data type of quantity field', '20060827', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(18, 18, 'Create Payment Types table', '20060909', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(19, 19, 'Add info into the Payment Type table', '20060909', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(20, 20, 'Adjust accounts payments table to add a type field', '20060909', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(21, 21, 'Adjust the defaults table to add a payment type field', '20060909', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(22, 22, 'Add note field to customer', '20061026', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(23, 23, 'Add note field to Biller', '20061026', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(24, 24, 'Add note field to Products', '20061026', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(25, 25, 'Add street address 2 to customers', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(26, 26, 'Add custom fields to customers', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(27, 27, 'Add mobile phone to customers', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(28, 28, 'Add street address 2 to billers', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(29, 29, 'Add custom fields to billers', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(30, 30, 'Creating the custom fields table', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(31, 31, 'Adding data to the custom fields table', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(32, 32, 'Adding custom fields to products', '20061211', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(33, 33, 'Alter product custom field 4', '20061214', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(34, 34, 'Reset invoice template to default refer Issue 70', '20070125', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(35, 35, 'Adding data to the custom fields table for invoices', '20070204', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(36, 36, 'Adding custom fields to the invoices table', '20070204', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(37, 0, 'Start', '20060514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(38, 37, 'Reset invoice template to default due to new invoice template system', '20070325', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(39, 38, 'Alter custom field table - field length now 255 for field name', '20070325', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(40, 39, 'Alter custom field table - field length now 255 for field name', '20070325', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(41, 40, 'Alter field name in sql_patchmanager', '20070424', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(42, 41, 'Alter field name in account_payments', '20070424', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(43, 42, 'Alter field name b_name to name', '20070424', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(44, 43, 'Alter field name b_id to id', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(45, 44, 'Alter field name b_street_address to street_address', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(46, 45, 'Alter field name b_street_address2 to street_address2', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(47, 46, 'Alter field name b_city to city', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(48, 47, 'Alter field name b_state to state', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(49, 48, 'Alter field name b_zip_code to zip_code', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(50, 49, 'Alter field name b_country to country', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(51, 50, 'Alter field name b_phone to phone', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(52, 51, 'Alter field name b_mobile_phone to mobile_phone', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(53, 52, 'Alter field name b_fax to fax', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(54, 53, 'Alter field name b_email to email', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(55, 54, 'Alter field name b_co_logo to logo', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(56, 55, 'Alter field name b_co_footer to footer', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(57, 56, 'Alter field name b_notes to notes', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(58, 57, 'Alter field name b_enabled to enabled', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(59, 58, 'Alter field name b_custom_field1 to custom_field1', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(60, 59, 'Alter field name b_custom_field2 to custom_field2', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(61, 60, 'Alter field name b_custom_field3 to custom_field3', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(62, 61, 'Alter field name b_custom_field4 to custom_field4', '20070430', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(63, 62, 'Introduce system_defaults table', '20070503', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(64, 63, 'Insert date into the system_defaults table', '20070503', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(65, 64, 'Alter field name prod_id to id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(66, 65, 'Alter field name prod_description to description', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(67, 66, 'Alter field name prod_unit_price to unit_price', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(68, 67, 'Alter field name prod_custom_field1 to custom_field1', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(69, 68, 'Alter field name prod_custom_field2 to custom_field2', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(70, 69, 'Alter field name prod_custom_field3 to custom_field3', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(71, 70, 'Alter field name prod_custom_field4 to custom_field4', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(72, 71, 'Alter field name prod_notes to notes', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(73, 72, 'Alter field name prod_enabled to enabled', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(74, 73, 'Alter field name c_id to id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(75, 74, 'Alter field name c_attention to attention', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(76, 75, 'Alter field name c_name to name', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(77, 76, 'Alter field name c_street_address to street_address', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(78, 77, 'Alter field name c_street_address2 to street_address2', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(79, 78, 'Alter field name c_city to city', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(80, 79, 'Alter field name c_state to state', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(81, 80, 'Alter field name c_zip_code to zip_code', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(82, 81, 'Alter field name c_country to country', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(83, 82, 'Alter field name c_phone to phone', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(84, 83, 'Alter field name c_mobile_phone to mobile_phone', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(85, 84, 'Alter field name c_fax to fax', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(86, 85, 'Alter field name c_email to email', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(87, 86, 'Alter field name c_notes to notes', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(88, 87, 'Alter field name c_custom_field1 to custom_field1', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(89, 88, 'Alter field name c_custom_field2 to custom_field2', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(90, 89, 'Alter field name c_custom_field3 to custom_field3', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(91, 90, 'Alter field name c_custom_field4 to custom_field4', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(92, 91, 'Alter field name c_enabled to enabled', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(93, 92, 'Alter field name inv_id to id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(94, 93, 'Alter field name inv_biller_id to biller_id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(95, 94, 'Alter field name inv_customer_id to customer_id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(96, 95, 'Alter field name inv_type type_id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(97, 96, 'Alter field name inv_preference to preference_id', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(98, 97, 'Alter field name inv_date to date', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(99, 98, 'Alter field name invoice_custom_field1 to custom_field1', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(100, 99, 'Alter field name invoice_custom_field2 to custom_field2', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(101, 100, 'Alter field name invoice_custom_field3 to custom_field3', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(102, 101, 'Alter field name invoice_custom_field4 to custom_field4', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(103, 102, 'Alter field name inv_note to note ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(104, 103, 'Alter field name inv_it_id to id ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(105, 104, 'Alter field name inv_it_invoice_id to invoice_id ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(106, 105, 'Alter field name inv_it_quantity to quantity ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(107, 106, 'Alter field name inv_it_product_id to product_id ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(108, 107, 'Alter field name inv_it_unit_price to unit_price ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(109, 108, 'Alter field name inv_it_tax_id to tax_id  ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(110, 109, 'Alter field name inv_it_tax to tax  ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(111, 110, 'Alter field name inv_it_tax_amount to tax_amount  ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(112, 111, 'Alter field name inv_it_gross_total to gross_total ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(113, 112, 'Alter field name inv_it_description to description ', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(114, 113, 'Alter field name inv_it_total to total', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(115, 114, 'Add logging table', '20070514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(116, 115, 'Add logging system preference', '20070514', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(117, 116, 'System defaults conversion patch - set default biller', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(118, 117, 'System defaults conversion patch - set default customer', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(119, 118, 'System defaults conversion patch - set default tax', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(120, 119, 'System defaults conversion patch - set default invoice preference', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(121, 120, 'System defaults conversion patch - set default number of line items', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(122, 121, 'System defaults conversion patch - set default invoice template', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(123, 122, 'System defaults conversion patch - set default payment type', '20070507', '');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(124, 123, 'Add option to delete invoices into the system_defaults table', '200709', 'INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES \n('''', ''delete'', ''N'');');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(125, 124, 'Set default language in new lang system', '200709', 'UPDATE "si_system_defaults" SET value = ''en-gb'' where name =''language'';');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(126, 125, 'Change log table that usernames are also possible as id', '200709', 'ALTER TABLE "si_log" CHANGE "userid" "userid" VARCHAR( 40 ) NOT NULL DEFAULT ''0''');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(127, 126, 'Add visible attribute to the products table', '200709', 'ALTER TABLE  "si_products" ADD  "visible" BOOL NOT NULL DEFAULT  ''1'';');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(128, 127, 'Add last_id to logging table', '200709', 'ALTER TABLE  "si_log" ADD  "last_id" INT NULL ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(129, 128, 'Add user table', '200709', 'CREATE TABLE IF NOT EXISTS "si_users" (\n			"user_id" int(11) NOT NULL auto_increment,\n			"user_email" varchar(255) NOT NULL,\n			"user_name" varchar(255) NOT NULL,\n			"user_group" varchar(255) NOT NULL,\n			"user_domain" varchar(255) NOT NULL,\n			"user_password" varchar(255) NOT NULL,\n			PRIMARY KEY  ("user_id")\n			) ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(130, 129, 'Fill user table with default values', '200709', 'INSERT INTO "si_users" ("user_id", "user_email", "user_name", "user_group", "user_domain", "user_password") VALUES \n('''', ''demo@simpleinvoices.org'', ''demo'', ''1'', ''1'', MD5(''demo''))');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(131, 130, 'Create auth_challenges table', '200709', 'CREATE TABLE IF NOT EXISTS "si_auth_challenges" (\n				"challenges_key" int(11) NOT NULL,\n				"challenges_timestamp" timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(132, 131, 'Make tax field 3 decimal places', '200709', 'ALTER TABLE "si_tax" CHANGE "tax_percentage" "tax_percentage" DECIMAL (10,3)  NULL');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(133, 132, 'Correct Foreign Key Tax ID Field Type in Invoice Items Table', '20071126', 'ALTER TABLE  "si_invoice_items" CHANGE "tax_id" "tax_id" int  DEFAULT ''0'' NOT NULL ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(134, 133, 'Correct Foreign Key Invoice ID Field Type in Ac Payments Table', '20071126', 'ALTER TABLE  "si_account_payments" CHANGE "ac_inv_id" "ac_inv_id" int  NOT NULL ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(135, 134, 'Drop non-int compatible default from si_sql_patchmanager', '20071218', 'SELECT 1+1;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(136, 135, 'Change sql_patch_ref type in sql_patchmanager to int', '20071218', 'ALTER TABLE  "si_sql_patchmanager" change "sql_patch_ref" "sql_patch_ref" int NOT NULL ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(137, 136, 'Create domain mapping table', '200712', 'CREATE TABLE si_user_domain (\n	    "id" int(11) NOT NULL auto_increment  PRIMARY KEY,\n            "name" varchar(255) UNIQUE NOT NULL\n            ) ENGINE=InnoDB;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(138, 137, 'Insert default domain', '200712', 'INSERT INTO si_user_domain (name)\n        VALUES (''default'');');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(139, 138, 'Add domain_id to payment_types table', '200712', 'ALTER TABLE "si_payment_types" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "pt_id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(140, 139, 'Add domain_id to preferences table', '200712', 'ALTER TABLE "si_preferences" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "pref_id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(141, 140, 'Add domain_id to products table', '200712', 'ALTER TABLE "si_products" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(142, 141, 'Add domain_id to billers table', '200712', 'ALTER TABLE "si_biller" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(143, 142, 'Add domain_id to invoices table', '200712', 'ALTER TABLE "si_invoices" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(144, 143, 'Add domain_id to customers table', '200712', 'ALTER TABLE "si_customers" ADD "domain_id" INT DEFAULT ''1'' NOT NULL AFTER "id" ;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(145, 144, 'Change group field to user_role_id in users table', '20080102', 'ALTER TABLE "si_users" CHANGE "user_group" "user_role_id" INT  DEFAULT ''1'' NOT NULL;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(146, 145, 'Change domain field to user_domain_id in users table', '20080102', 'ALTER TABLE "si_users" CHANGE "user_domain" "user_domain_id" INT  DEFAULT ''1'' NOT NULL;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(147, 146, 'Drop old auth_challenges table', '20080102', 'DROP TABLE IF EXISTS "si_auth_challenges";');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(148, 147, 'Create user_role table', '20080102', 'CREATE TABLE si_user_role (\n	    "id" int(11) NOT NULL auto_increment  PRIMARY KEY,\n            "name" varchar(255) UNIQUE NOT NULL\n            ) ENGINE=InnoDB;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(149, 148, 'Insert default user group', '20080102', 'INSERT INTO si_user_role (name) VALUES (''administrator'');');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(150, 149, 'Table = Account_payments Field = ac_amount : change field type and length to decimal', '20080128', 'ALTER TABLE "si_account_payments" CHANGE "ac_amount" "ac_amount" DECIMAL( 25, 6 ) NOT NULL;');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(151, 150, 'Table = Invoice_items Field = quantity : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "quantity" "quantity" DECIMAL( 25, 6 ) NOT NULL DEFAULT ''0'' ');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(152, 151, 'Table = Invoice_items Field = unit_price : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "unit_price" "unit_price" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' ');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(153, 152, 'Table = Invoice_items Field = tax : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "tax" "tax" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' ');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(154, 153, 'Table = Invoice_items Field = tax_amount : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "tax_amount" "tax_amount" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00''');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(155, 154, 'Table = Invoice_items Field = gross_total : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "gross_total" "gross_total" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00''');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(156, 155, 'Table = Invoice_items Field = total : change field type and length to decimal', '20080128', 'ALTER TABLE "si_invoice_items" CHANGE "total" "total" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00'' ');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(157, 156, 'Table = Products Field = unit_price : change field type and length to decimal', '20080128', 'ALTER TABLE "si_products" CHANGE "unit_price" "unit_price" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00''');
INSERT INTO "si_sql_patchmanager" ("sql_id", "sql_patch_ref", "sql_patch", "sql_release", "sql_statement") VALUES
(158, 157, 'Table = Tax Field = quantity : change field type and length to decimal', '20080128', 'ALTER TABLE "si_tax" CHANGE "tax_percentage" "tax_percentage" DECIMAL( 25, 6 ) NULL DEFAULT ''0.00''');


CREATE TABLE IF NOT EXISTS "si_system_defaults" (
  "id" INTEGER PRIMARY KEY,
  "name" varchar(30) UNIQUE NOT NULL,
  "value" varchar(30) NOT NULL
) ;


INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(1, 'biller', '4');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(2, 'customer', '3');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(3, 'tax', '1');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(4, 'preference', '1');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(5, 'line_items', '5');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(6, 'template', 'default');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(7, 'payment_type', '1');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(8, 'language', 'en-gb');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(9, 'dateformat', 'Y-m-d');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(10, 'spreadsheet', 'xls');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(11, 'wordprocessor', 'doc');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(12, 'pdfscreensize', '800');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(13, 'pdfpapersize', 'A4');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(14, 'pdfleftmargin', '15');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(15, 'pdfrightmargin', '15');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(16, 'pdftopmargin', '15');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(17, 'pdfbottommargin', '15');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(18, 'emailhost', 'localhost');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(19, 'emailusername', '');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(20, 'emailpassword', '');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(21, 'logging', '0');
INSERT INTO "si_system_defaults" ("id", "name", "value") VALUES
(22, 'delete', 'N');


CREATE TABLE IF NOT EXISTS "si_tax" (
  "tax_id" INTEGER PRIMARY KEY,
  "tax_description" varchar(50) default NULL,
  "tax_percentage" decimal(25,6) default '0.000000',
  "tax_enabled" varchar(1) NOT NULL default '1'
) ;



INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(1, 'GST (AUS)', 10.000000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(2, 'VAT (UK)', 10.000000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(3, 'Sales Tax (USA)', 10.000000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(4, 'GST (NZ)', 12.500000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(5, 'No Tax', 0.000000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(6, 'IVA', 20.000000, '1');
INSERT INTO "si_tax" ("tax_id", "tax_description", "tax_percentage", "tax_enabled") VALUES
(7, 'MWSt (DE)', 16.000000, '1');


CREATE TABLE IF NOT EXISTS "si_users" (
  "user_id" INTEGER PRIMARY KEY,
  "user_email" varchar(255) NOT NULL,
  "user_name" varchar(255) NOT NULL,
  "user_role_id" int(11) NOT NULL default '1',
  "user_domain_id" int(11) NOT NULL default '1',
  "user_password" varchar(255) NOT NULL
)  ;


INSERT INTO "si_users" ("user_id", "user_email", "user_name", "user_role_id", "user_domain_id", "user_password") VALUES
(1, 'demo@simpleinvoices.org', 'demo', 1, 1, 'fe01ce2a7fbac8fafaed7c982a04e229');

-- --------------------------------------------------------

--
-- Table structure for table "si_user_domain"
--

CREATE TABLE IF NOT EXISTS "si_user_domain" (
  "id" INTEGER PRIMARY KEY,
  "name" varchar(255) NOT NULL
) ;



INSERT INTO "si_user_domain" ("id", "name") VALUES
(1, 'default');


CREATE TABLE IF NOT EXISTS "si_user_role" (
  "id" INTEGER PRIMARY KEY,
  "name" varchar(255) NOT NULL
);


INSERT INTO "si_user_role" ("id", "name") VALUES
(1, 'administrator');
