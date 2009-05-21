-- PostgreSQL 8.1 port
-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 

BEGIN;

CREATE TABLE si_users (
  user_id serial PRIMARY KEY,
  user_email varchar(255) NOT NULL,
  user_name varchar(255) NOT NULL,
  user_group varchar(255) NOT NULL,
  user_domain varchar(255) NOT NULL,
  user_password char(32) NOT NULL
);

COMMENT ON TABLE si_users IS $$User and authentication data$$;
COMMENT ON COLUMN si_users.user_email IS $$Email address functions as the user name for the purpose of authentication$$;
COMMENT ON COLUMN si_users.user_password IS $$md5 of the actual password$$;

INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES 
(1, 'demo@simpleinvoices.org', 'demo', '1', '1', 'fe01ce2a7fbac8fafaed7c982a04e229');
SELECT setval('si_users_user_id_seq', 1);

CREATE TABLE si_customfieldcategories (
	id serial PRIMARY KEY,
	name varchar(40) NOT NULL
);
INSERT INTO si_customFieldCategories (id, name) VALUES (1, 'biller');
INSERT INTO si_customFieldCategories (id, name) VALUES (2, 'customer');
INSERT INTO si_customFieldCategories (id, name) VALUES (3, 'product');
INSERT INTO si_customFieldCategories (id, name) VALUES (4, 'invoice');
SELECT setval('si_customfieldcategories_id_seq', 4);

CREATE TABLE si_customFields (
	id serial PRIMARY KEY,
	pluginId int NOT NULL,
	categorieId int NOT NULL REFERENCES si_customfieldcategories(id),
	name varchar(30) NOT NULL,
	description varchar(50) NOT NULL,
	active boolean NOT NULL default true,
	"order" int
);

CREATE TABLE si_customFieldValues (
	id serial PRIMARY KEY,
	customFieldId int NOT NULL REFERENCES si_customfields(id),
	itemId int NOT NULL,
	value text NOT NULL
);
COMMENT ON COLUMN si_customfieldvalues.itemid IS 'could be invoice-id,customer-id etc.';

CREATE TABLE si_tax (
  tax_id serial PRIMARY KEY,
  tax_description varchar(50),
  tax_percentage numeric(10,3),
  tax_enabled boolean NOT NULL default true
);

COMMENT ON TABLE si_tax IS $$Basic tax attributes$$;
COMMENT ON COLUMN si_tax.tax_description IS $$Brief description, any HTML is escaped on display$$;

INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(1, 'GST (AUS)', 10.000, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(2, 'VAT (UK)', 10.000, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(3, 'Sales Tax (USA)', 10.000, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(4, 'GST (NZ)', 12.500, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(5, 'No Tax', 0.000, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(6, 'IVA', 20.000, true);
INSERT INTO si_tax (tax_id, tax_description, tax_percentage, tax_enabled) VALUES 
(7, 'MWSt (DE)', 16.000, true);
SELECT setval('si_tax_tax_id_seq', 7);

CREATE TABLE si_preferences (
  pref_id serial PRIMARY KEY,
  pref_description varchar(50) ,
  pref_currency_sign varchar(50) ,
  pref_inv_heading varchar(50) ,
  pref_inv_wording varchar(50) ,
  pref_inv_detail_heading varchar(50) ,
  pref_inv_detail_line text,
  pref_inv_payment_method varchar(50) ,
  pref_inv_payment_line1_name varchar(50) ,
  pref_inv_payment_line1_value varchar(50) ,
  pref_inv_payment_line2_name varchar(50) ,
  pref_inv_payment_line2_value varchar(50) ,
  pref_enabled boolean NOT NULL default true
);

COMMENT ON TABLE si_preferences IS $$Invoice preferences, options concerning how an invoice is displayed$$;

INSERT INTO si_preferences (pref_id, pref_description, pref_currency_sign, pref_inv_heading, pref_inv_wording, pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method, pref_inv_payment_line1_name, pref_inv_payment_line1_value, pref_inv_payment_line2_name, pref_inv_payment_line2_value, pref_enabled) VALUES 
(1, 'Invoice - default', '$', 'Invoice', 'Invoice', 'Details', 'Payment is to be made within 14 days of the invoice being sent', 'Electronic Funds Transfer', 'Account name:', 'H. & M. Simpson', 'Account number:', '0123-4567-7890', '1');
INSERT INTO si_preferences (pref_id, pref_description, pref_currency_sign, pref_inv_heading, pref_inv_wording, pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method, pref_inv_payment_line1_name, pref_inv_payment_line1_value, pref_inv_payment_line2_name, pref_inv_payment_line2_value, pref_enabled) VALUES 
(2, 'Invoice - no payment details', '$', 'Invoice', 'Invoice', NULL, '', '', '', '', '', '', '1');
INSERT INTO si_preferences (pref_id, pref_description, pref_currency_sign, pref_inv_heading, pref_inv_wording, pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method, pref_inv_payment_line1_name, pref_inv_payment_line1_value, pref_inv_payment_line2_name, pref_inv_payment_line2_value, pref_enabled) VALUES 
(3, 'Receipt - default', '$', 'Receipt', 'Receipt', 'Details', '<br />This transaction has been paid in full, please keep this receipt as proof of purchase.<br /> Thank you', '', '', '', '', '', '1');
INSERT INTO si_preferences (pref_id, pref_description, pref_currency_sign, pref_inv_heading, pref_inv_wording, pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method, pref_inv_payment_line1_name, pref_inv_payment_line1_value, pref_inv_payment_line2_name, pref_inv_payment_line2_value, pref_enabled) VALUES 
(4, 'Estimate - default', '$', 'Estimate', 'Estimate', 'Details', '<br />This is an estimate of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1');
INSERT INTO si_preferences (pref_id, pref_description, pref_currency_sign, pref_inv_heading, pref_inv_wording, pref_inv_detail_heading, pref_inv_detail_line, pref_inv_payment_method, pref_inv_payment_line1_name, pref_inv_payment_line1_value, pref_inv_payment_line2_name, pref_inv_payment_line2_value, pref_enabled) VALUES 
(5, 'Quote - default', '$', 'Quote', 'Quote', 'Details', '<br />This is a quote of the final value of services rendered.<br />Thank you', '', '', '', '', '', '1');
SELECT setval('si_preferences_pref_id_seq', 5);

CREATE TABLE si_products (
  id serial PRIMARY KEY,
  description text NOT NULL,
  unit_price numeric(25,2),
  custom_field1 varchar(255),
  custom_field2 varchar(255),
  custom_field3 varchar(255),
  custom_field4 varchar(255),
  notes text NOT NULL,
  enabled boolean NOT NULL default true,
  visible boolean NOT NULL default true
);

COMMENT ON TABLE si_products IS $$Products, required by all invoice line items$$;

INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(1, 'IBM Netfinity 5000', 150.00, NULL, NULL, NULL, NULL, '', true, true);
INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(2, 'Accounting services - Barney Gumball (hours)', 140.00, 'CVF1', '', '', '', '', true, true);
INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(3, 'Weekly ploughing service', 125.00, NULL, NULL, NULL, NULL, '', true, true);
INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(4, 'Bootleg homebrew', 15.50, NULL, NULL, NULL, NULL, '', true, true);
INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(5, 'Accomodation', 125.00, NULL, NULL, NULL, NULL, '', true, true);
INSERT INTO si_products (id, description, unit_price, custom_field1, custom_field2, custom_field3, custom_field4, notes, enabled, visible) VALUES 
(6, 'For ploughing services for the period 01 Jan - 01 Feb 2006', 145.00, NULL, NULL, NULL, NULL, '', false, false);
SELECT setval('si_products_id_seq', 6);

-- SC: Unused auth challenge table
-- CREATE TABLE si_auth_challenges (
--   challenges_key int NOT NULL,
--   challenges_timestamp timestamp NOT NULL default CURRENT_TIMESTAMP
-- );
--
-- COMMENT ON TABLE si_auth_challenges IS $$Authentication challenges, used as part of MD5-HMAC auth$$;
--
-- CREATE OR REPLACE FUNCTION updated_auth_challenge() RETURNS trigger AS $$
-- BEGIN
-- 	UPDATE si_auth_challenges SET challenges_timestamp = current_timestamp
-- 	WHERE challenges_key = new.challenges_key;
-- END
-- $$ LANGUAGE plpgsql;
-- 
-- CREATE TRIGGER update_auth_challenge AFTER UPDATE ON si_auth_challenges
-- 	FOR EACH ROW EXECUTE PROCEDURE updated_auth_challenge();


CREATE TABLE si_biller (
  id serial PRIMARY KEY,
  name varchar(255),
  street_address varchar(255),
  street_address2 varchar(255),
  city varchar(255),
  state varchar(255),
  zip_code varchar(255),
  country varchar(255),
  phone varchar(255),
  mobile_phone varchar(255),
  fax varchar(255),
  email varchar(255),
  logo varchar(255),
  footer text,
  notes text,
  custom_field1 varchar(255),
  custom_field2 varchar(255),
  custom_field3 varchar(255),
  custom_field4 varchar(255),
  enabled boolean NOT NULL default true
);

COMMENT ON TABLE si_biller IS $$Biller details$$;

INSERT INTO si_biller (id, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, logo, footer, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(1, 'Mr Plough', '43 Evergreen Terace', '', 'Springfield', 'New York', '90245', '', '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@mrplough.com', 'ubuntulogo.png', '', '', '', '7898-87987-87', '', '', true);
INSERT INTO si_biller (id, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, logo, footer, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(2, 'Homer Simpson', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'homer@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
INSERT INTO si_biller (id, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, logo, footer, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(3, 'The Beer Baron', '43 Evergreen Terace', NULL, 'Springfield', 'New York', '90245', NULL, '04 5689 0456', '0456 4568 8966', '04 5689 8956', 'beerbaron@yahoo.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
INSERT INTO si_biller (id, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, logo, footer, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(4, 'Fawlty Towers', '13 Seaside Drive', NULL, 'Torquay', 'Brixton on Avon', '65894', 'United Kingdom', '089 6985 4569', '0425 5477 8789', '089 6985 4568', 'penny@fawltytowers.co.uk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, true);
SELECT setval('si_biller_id_seq', 4);

-- 
-- Table structure for table si_custom_fields
-- 

CREATE TABLE si_custom_fields (
  cf_id serial PRIMARY KEY,
  cf_custom_field varchar(255),
  cf_custom_label varchar(255),
  cf_display boolean NOT NULL default true
);

INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(1, 'biller_cf1', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(2, 'biller_cf2', 'Tax ID', false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(3, 'biller_cf3', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(4, 'biller_cf4', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(5, 'customer_cf1', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(6, 'customer_cf2', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(7, 'customer_cf3', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(8, 'customer_cf4', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(9, 'product_cf1', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(10, 'product_cf2', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(11, 'product_cf3', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(12, 'product_cf4', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(13, 'invoice_cf1', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(14, 'invoice_cf2', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(15, 'invoice_cf3', NULL, false);
INSERT INTO si_custom_fields (cf_id, cf_custom_field, cf_custom_label, cf_display) VALUES 
(16, 'invoice_cf4', NULL, false);
SELECT setval('si_custom_fields_cf_id_seq', 16);

CREATE TABLE si_customers (
  id serial PRIMARY KEY,
  attention varchar(255) ,
  name varchar(255) ,
  street_address varchar(255) ,
  street_address2 varchar(255) ,
  city varchar(255) ,
  state varchar(255) ,
  zip_code varchar(255) ,
  country varchar(255) ,
  phone varchar(255) ,
  mobile_phone varchar(255) ,
  fax varchar(255) ,
  email varchar(255) ,
  notes text,
  custom_field1 varchar(255) ,
  custom_field2 varchar(255) ,
  custom_field3 varchar(255) ,
  custom_field4 varchar(255) ,
  enabled boolean NOT NULL default true
);

COMMENt ON TABLE si_customers IS $$Customer details$$;

INSERT INTO si_customers (id, attention, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(1, 'Moe Sivloski', 'Moes Tarvern', '45 Main Road', NULL, 'Springfield', 'New York', '65891', '', '04 1234 5698', NULL, '04 5689 4566', 'moe@moestavern.com', '<p><strong>Moe&#39;s Tavern</strong> is a fictional <a href="http://en.wikipedia.org/wiki/Bar_%28establishment%29" title="Bar (establishment)">bar</a> seen on <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The owner of the bar is <a href="http://en.wikipedia.org/wiki/Moe_Szyslak" title="Moe Szyslak">Moe Szyslak</a>.</p> <p>In The Simpsons world, it is located on the corner of Walnut Street, neighboring King Toot&#39;s Music Store, across the street is the Moeview Motel, and a factory formerly owned by <a href="http://en.wikipedia.org/wiki/Bart_Simpson" title="Bart Simpson">Bart Simpson</a>, until it collapsed. The inside of the bar has a few pool tables and a dartboard. It is very dank and &quot;smells like <a href="http://en.wikipedia.org/wiki/Urine" title="Urine">tinkle</a>.&quot; Because female customers are so rare, Moe frequently uses the women&#39;s restroom as an office. Moe claimed that there haven&#39;t been any ladies at Moe&#39;s since <a href="http://en.wikipedia.org/wiki/1979" title="1979">1979</a> (though earlier episodes show otherwise). A jar of pickled eggs perpetually stands on the bar. Another recurring element is a rat problem. This can be attributed to the episode <a href="http://en.wikipedia.org/wiki/Homer%27s_Enemy" title="Homer&#39;s Enemy">Homer&#39;s Enemy</a> in which Bart&#39;s factory collapses, and the rats are then shown to find a new home at Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Who_Shot_Mr._Burns" title="Who Shot Mr. Burns">Who Shot Mr. Burns</a>,&quot; Moe&#39;s Tavern was forced to close down because Mr. Burns&#39; slant-drilling operation near the tavern caused unsafe pollution. It was stated in the &quot;<a href="http://en.wikipedia.org/wiki/Flaming_Moe%27s" title="Flaming Moe&#39;s">Flaming Moe&#39;s</a>&quot; episode that Moe&#39;s Tavern was on Walnut Street. The phone number would be 76484377, since in &quot;<a href="http://en.wikipedia.org/wiki/Homer_the_Smithers" title="Homer the Smithers">Homer the Smithers</a>,&quot; Mr. Burns tried to call Smithers but did not know his phone number. He tried the buttons marked with the letters for Smithers and called Moe&#39;s. In &quot;<a href="http://en.wikipedia.org/wiki/Principal_Charming" title="Principal Charming">Principal Charming</a>&quot; Bart is asked to call Homer by Principal Skinner, the number visible on the card is WORK: KLondike 5-6832 HOME: KLondike 5-6754 MOE&#39;S TAVERN: KLondike 5-1239 , Moe answers the phone and Bart asks for Homer Sexual. The bar serves <a href="http://en.wikipedia.org/wiki/Duff_Beer" title="Duff Beer">Duff Beer</a> and Red Tick Beer, a beer flavored with dogs.</p>', NULL, NULL, NULL, NULL, true);
INSERT INTO si_customers (id, attention, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(2, 'Mr Burns', 'Springfield Power Plant', '4 Power Plant Drive', 'street2', 'Springfield', 'New York', '90210', '', '04 1235 5698', '', '04 5678 7899', 'mr.burn@spp.com', '<p><strong>Springfield Nuclear Power Plant</strong> is a fictional electricity generating facility in the <a href="http://en.wikipedia.org/wiki/Television" title="Television">television</a> <a href="http://en.wikipedia.org/wiki/Animated_cartoon" title="Animated cartoon">animated cartoon</a> series <em><a href="http://en.wikipedia.org/wiki/The_Simpsons" title="The Simpsons">The Simpsons</a></em>. The plant has a <a href="http://en.wikipedia.org/wiki/Monopoly" title="Monopoly">monopoly</a> on the city of <a href="http://en.wikipedia.org/wiki/Springfield_%28The_Simpsons%29" title="Springfield (The Simpsons)">Springfield&#39;s</a> energy supply, but is sometimes mismanaged and endangers much of the town with its presence.</p> <p>Based on the plant&#39;s appearance and certain episode plots, it likely houses only a single &quot;unit&quot; or reactor (although, judging from the number of <a href="http://en.wikipedia.org/wiki/Containment_building" title="Containment building">containment buildings</a> and <a href="http://en.wikipedia.org/wiki/Cooling_tower" title="Cooling tower">cooling towers</a>, there is a chance it may have two). In one episode an emergency occurs and Homer resorts to the manual, which begins &quot;Congratulations on your purchase of a Fissionator 1952 Slow-Fission Reactor&quot;.</p> <p>The plant is poorly maintained, largely due to owner Montgomery Burns&#39; miserliness. Its <a href="http://en.wikipedia.org/wiki/Nuclear_safety" title="Nuclear safety">safety record</a> is appalling, with various episodes showing luminous rats in the bowels of the building, pipes and drums leaking radioactive waste, the disposal of waste in a children&#39;s playground, <a href="http://en.wikipedia.org/wiki/Plutonium" title="Plutonium">plutonium</a> used as a paperweight, cracked cooling towers (fixed in one episode using a piece of <a href="http://en.wikipedia.org/wiki/Chewing_gum" title="Chewing gum">Chewing gum</a>), dangerously high <a href="http://en.wikipedia.org/wiki/Geiger_counter" title="Geiger counter">Geiger counter</a> readings around the perimeter of the plant, and even a giant spider. In the opening credits a bar of some <a href="http://en.wikipedia.org/wiki/Radioactive" title="Radioactive">radioactive</a> substance is trapped in Homer&#39;s overalls and later disposed of in the street.</p>', '13245-789798', '', '', '', true);
INSERT INTO si_customers (id, attention, name, street_address, street_address2, city, state, zip_code, country, phone, mobile_phone, fax, email, notes, custom_field1, custom_field2, custom_field3, custom_field4, enabled) VALUES 
(3, 'Kath Day-Knight', 'Kath and Kim Pty Ltd', '82 Fountain Drive', NULL, 'Fountain Lakes', 'VIC', '3567', 'Australia', '03 9658 7456', NULL, '03 9658 7457', 'kath@kathandkim.com.au', 'Kath Day-Knight (<a href="http://en.wikipedia.org/wiki/Jane_Turner" title="Jane Turner">Jane Turner</a>) is an &#39;empty nester&#39; divorc&eacute;e who wants to enjoy time with her &quot;hunk o&#39; spunk&quot; Kel Knight (<a href="http://en.wikipedia.org/wiki/Glenn_Robbins" title="Glenn Robbins">Glenn Robbins</a>), a local &quot;purveyor of fine meats&quot;, but whose lifestyle is often cramped by the presence of her self-indulgent and spoilt rotten twenty-something daughter Kim Craig <a href="http://en.wikipedia.org/wiki/List_of_French_phrases_used_by_English_speakers#I_.E2.80.93_Q" title="List of French phrases used by English speakers">n&eacute;e</a> Day (<a href="http://en.wikipedia.org/wiki/Gina_Riley" title="Gina Riley">Gina Riley</a>). Kim enjoys frequent and lengthy periods of spiteful estrangement from her forgiving husband Brett Craig (<a href="http://en.wikipedia.org/wiki/Peter_Rowsthorn" title="Peter Rowsthorn">Peter Rowsthorn</a>) for imagined slights and misdemeanors, followed by loving reconciliations with him. During Kim and Brett&#39;s frequent rough patches Kim usually seeks solace from her servile &quot;second best friend&quot; Sharon Strzelecki (<a href="http://en.wikipedia.org/wiki/Magda_Szubanski" title="Magda Szubanski">Magda Szubanski</a>), screaming abuse at Sharon for minor infractions while issuing her with intricately-instructed tasks, such as stalking Brett. Kim and Brett had a baby in the final episode of the second series whom they named Epponnee-Raelene Kathleen Darlene Charlene Craig, shortened to Epponnee-Rae.', NULL, NULL, NULL, NULL, true);
SELECT setval('si_customers_id_seq', 3);

CREATE TABLE si_invoice_type (
  inv_ty_id serial PRIMARY KEY,
  inv_ty_description varchar(25) NOT NULL default ''
);

COMMENT ON TABLE si_invoice_type IS $$Type of invoice is used to determine interface options$$;

INSERT INTO si_invoice_type (inv_ty_id, inv_ty_description) VALUES 
(1, 'Total');
INSERT INTO si_invoice_type (inv_ty_id, inv_ty_description) VALUES 
(2, 'Itemised');
INSERT INTO si_invoice_type (inv_ty_id, inv_ty_description) VALUES 
(3, 'Consulting');
SELECT setval('si_invoice_type_inv_ty_id_seq', 3);

CREATE TABLE si_invoices (
  id serial PRIMARY KEY,
  biller_id int NOT NULL REFERENCES si_biller(id) default 0,
  customer_id int NOT NULL REFERENCES si_customers(id) default 0,
  type_id int NOT NULL REFERENCES si_invoice_type(inv_ty_id) default 0,
  preference_id int NOT NULL REFERENCES si_preferences(pref_id) default 0,
  "date" timestamp NOT NULL,
  custom_field1 varchar(50),
  custom_field2 varchar(50),
  custom_field3 varchar(50),
  custom_field4 varchar(50),
  note text,
  status_id int NOT NULL default 0
);

COMMENT ON TABLE si_invoices IS $$The invoices$$;

INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(1, 4, 3, 2, 1, '2007-02-03 00:00:00', NULL, NULL, NULL, NULL, 'Will be delivered via certified post');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(2, 1, 2, 1, 1, '2007-01-01 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(3, 2, 3, 3, 1, '2007-02-04 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(4, 2, 1, 2, 4, '2006-08-25 12:12:17', NULL, NULL, NULL, NULL, 'Weekly bootleg deliveries');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(5, 4, 3, 3, 5, '2007-01-16 00:00:00', NULL, NULL, NULL, NULL, '');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(6, 4, 3, 2, 3, '2006-08-25 12:13:37', NULL, NULL, NULL, NULL, '');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(7, 2, 2, 2, 1, '2006-12-10 22:32:48', NULL, NULL, NULL, NULL, 'this is a test<br />');
INSERT INTO si_invoices (id, biller_id, customer_id, type_id, preference_id, date, custom_field1, custom_field2, custom_field3, custom_field4, note) VALUES 
(8, 4, 3, 2, 1, '2007-02-06 00:00:00', NULL, NULL, NULL, NULL, '');
SELECT setval('si_invoices_id_seq', 8);

CREATE TABLE si_invoice_items (
  id serial PRIMARY KEY,
  invoice_id int NOT NULL REFERENCES si_invoices(id),
  quantity numeric NOT NULL default '0',
  product_id int REFERENCES si_products(id),
  unit_price numeric(25,2) default '0.00',
  tax_id int NOT NULL REFERENCES si_tax(tax_id),
  tax numeric(25,2) default '0.00',
  tax_amount numeric(25,2),
  gross_total numeric(25,2) default '0.00',
  description text,
  total numeric(25,2) default '0.00'
);
COMMENT ON TABLE si_invoice_items IS $$Invoice line items$$;
COMMENT ON COLUMN si_invoice_items.unit_price IS $$Price of a single unit of the product$$;
COMMENT ON COLUMN si_invoice_items.tax_id IS $$Tax is associated with line items, not the invoice, one tax only$$;
COMMENT ON COLUMN si_invoice_items.tax IS $$Percentage tax, i.e. 5.000 is 5%$$;
COMMENT ON COLUMN si_invoice_items.tax_amount IS $$Tax charged$$;
COMMENT ON COLUMN si_invoice_items.gross_total IS $$Pre-tax line item total$$;
COMMENT ON COLUMN si_invoice_items.total IS $$Post-tax line item total$$;

INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(1, 1, 1, 1, 150.00, '1', 10.00, 15.00, 150.00, '00', 165.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(2, 1, 2, 3, 125.00, '1', 10.00, 25.00, 250.00, '00', 275.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(3, 2, 1, 6, 145.00, '3', 10.00, 14.50, 145.00, 'For ploughing services for the period 01 Jan - 01 Feb 2006', 159.50);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(4, 3, 2, 2, 140.00, '1', 10.00, 28.00, 280.00, 'Accounting services - basic bookkeeping', 308.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(5, 3, 1, 2, 140.00, '1', 10.00, 14.00, 140.00, 'Accounting services - tax return for 2005', 154.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(6, 3, 2, 2, 140.00, '1', 10.00, 28.00, 280.00, 'Accounting serverice - general ledger work', 308.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(7, 4, 15, 4, 15.50, '4', 10.00, 23.25, 232.50, '00', 255.75);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(8, 5, 1, 2, 140.00, '4', 10.00, 14.00, 140.00, 'Quote for accounting service - hours', 154.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(9, 5, 2, 1, 150.00, '4', 10.00, 30.00, 300.00, 'Quote for new servers', 330.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(10, 6, 1, 1, 150.00, '4', 10.00, 15.00, 150.00, '00', 165.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(11, 6, 2, 4, 15.50, '4', 10.00, 3.10, 31.00, '00', 34.10);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(12, 6, 4, 5, 125.00, '4', 10.00, 50.00, 500.00, '00', 550.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(13, 7, 1, 2, 140.00, '1', 10.00, 14.00, 140.00, '00', 154.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(14, 7, 2, 5, 125.00, '1', 10.00, 25.00, 250.00, '00', 275.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(15, 8, 10, 5, 125.00, '1', 10.00, 125.00, 1250.00, '00', 1375.00);
INSERT INTO si_invoice_items (id, invoice_id, quantity, product_id, unit_price, tax_id, tax, tax_amount, gross_total, description, total) VALUES 
(16, 8, 10, 4, 15.50, '1', 10.00, 15.50, 155.00, '00', 170.50);
SELECT setval('si_invoice_items_id_seq', 16);

CREATE TABLE si_log (
  id bigserial PRIMARY KEY,
  timestamp timestamp NOT NULL default CURRENT_TIMESTAMP,
  userid int NOT NULL REFERENCES si_users(user_id),
  sqlquerie text NOT NULL,
  last_id int
);
COMMENT ON TABLE si_log IS $$Query logs for queries done through dbQuery$$;
COMMENT ON COLUMN si_log.last_id IS $$The value of the most recent sequence to increment in the session the entry was created in$$;

INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(1, '2007-09-28 15:41:20', '1', 'ALTER TABLE si_log ADD last_id INT NULL ;', 0);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(2, '2007-09-28 15:41:20', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (127,''Add last_id to logging table'',200709,''ALTER TABLE si_log ADD last_id INT NULL ;'')', 128);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(3, '2007-09-28 15:41:20', '1', 'CREATE TABLE IF NOT EXISTS si_users ( user_id int(11) NOT NULL auto_increment, user_email varchar(255) NOT NULL, user_name varchar(255) NOT NULL, user_group varchar(255) NOT NULL, user_domain varchar(255) NOT NULL, user_password varchar(255) NOT NULL, PRIMARY KEY (user_id) ) ;', 0);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(4, '2007-09-28 15:41:20', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (128,''Add si_user table'',200709,''CREATE TABLE IF NOT EXISTS si_users ( user_id int(11) NOT NULL auto_increment, user_email varchar(255) NOT NULL, user_name varchar(255) NOT NULL, user_group varchar(255) NOT NULL, user_domain varchar(255) NOT NULL, user_password varchar(255) NOT NULL, PRIMARY KEY (user_id) ) ;'')', 129);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(5, '2007-09-28 15:41:20', '1', 'INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES ('''', ''demo@simpleinvoices.org'', ''guest'', ''1'', ''1'', MD5(''demo''))', 1);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(6, '2007-09-28 15:41:20', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (129,''Fill si_user table with default values'',200709,''INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES (\\''\\'', \\''demo@simpleinvoices.org\\'', \\''guest\\'', \\''1\\'', \\''1\\'', MD5(\\''demo\\''))'')', 130);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(7, '2007-09-28 15:41:20', '1', 'CREATE TABLE IF NOT EXISTS si_auth_challenges ( challenges_key int(11) NOT NULL, challenges_timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);', 0);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(8, '2007-09-28 15:41:20', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (130,''Create si_auth_challenges table'',200709,''CREATE TABLE IF NOT EXISTS si_auth_challenges ( challenges_key int(11) NOT NULL, challenges_timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);'')', 131);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(9, '2007-09-28 15:41:20', '1', 'alter table si_tax change tax_percentage tax_percentage decimal (10,3) NULL', 0);
INSERT INTO si_log (id, timestamp, userid, sqlquerie, last_id) VALUES 
(10, '2007-09-28 15:41:20', '1', 'INSERT INTO si_sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (131,''Make tax field 3 decimal places'',200709,''alter table si_tax change tax_percentage tax_percentage decimal (10,3) NULL'')', 132);
SELECT setval('si_log_id_seq', 10);

CREATE TABLE si_payment_types (
  pt_id serial PRIMARY KEY,
  pt_description varchar(250) NOT NULL,
  pt_enabled boolean NOT NULL default true
);
COMMENT ON TABLE si_payment_types IS $$Different payment methods$$;

INSERT INTO si_payment_types (pt_id, pt_description, pt_enabled) VALUES 
(1, 'Cash', true);
INSERT INTO si_payment_types (pt_id, pt_description, pt_enabled) VALUES 
(2, 'Credit Card', true);
SELECT setval('si_payment_types_pt_id_seq', 2);

CREATE TABLE si_sql_patchmanager (
  sql_id serial PRIMARY KEY,
  sql_patch_ref varchar(50) NOT NULL default '',
  sql_patch text NOT NULL,
  sql_release varchar(25) NOT NULL default '',
  sql_statement text NOT NULL
);
COMMENT ON TABLE si_sql_patchmanager IS $$Tracking table for applied SQL patches$$;
COMMENT ON COLUMN si_sql_patchmanager.sql_patch IS $$Textual description of the patch applied$$;
COMMENT ON COLUMN si_sql_patchmanager.sql_statement IS $$The SQL that makes up the patch$$;
COMMENT ON COLUMN si_sql_patchmanager.sql_patch_ref IS $$User-visible patch identifier$$;
COMMENT ON COLUMN si_sql_patchmanager.sql_release IS $$Generally a date, not used by the application$$;

INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(1, '1', 'Create si_sql_patchmanger table', '20060514', 'CREATE TABLE si_sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM ');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(2, '2', 'Update invoice no details to have a default curren', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(3, '3', 'Add a row into the defaults table to handle the de', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(4, '4', 'Set the default number of line items to 5', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(5, '5', 'Add logo and invoice footer support to biller', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(6, '6', 'Add default invoice template option', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(7, '7', 'Edit tax description field lenght to 50', '20060526', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(8, '8', 'Edit default invoice template field lenght to 50', '20060526', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(9, '9', 'Add consulting style invoice', '20060531', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(10, '10', 'Add enabled to biller', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(11, '11', 'Add enabled to customters', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(12, '12', 'Add enabled to prefernces', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(13, '13', 'Add enabled to products', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(14, '14', 'Add enabled to products', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(15, '15', 'Add tax_id into invoice_items table', '20060815', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(16, '16', 'Add Payments table', '20060827', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(17, '17', 'Adjust data type of quantuty field', '20060827', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(18, '18', 'Create Payment Types table', '20060909', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(19, '19', 'Add info into the Payment Type table', '20060909', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(20, '20', 'Adjust accounts payments table to add a type field', '20060909', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(21, '21', 'Adjust the defautls table to add a payment type fi', '20060909', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(22, '22', 'Add note field to customer', '20061026', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(23, '23', 'Add note field to Biller', '20061026', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(24, '24', 'Add note field to Products', '20061026', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(25, '25', 'Add street address 2 to customers', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(26, '26', 'Add custom fields to customers', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(27, '27', 'Add mobile phone to customers', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(28, '28', 'Add street address 2 to billers', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(29, '29', 'Add custom fields to billers', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(30, '30', 'Creating the custom fields table', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(31, '31', 'Adding data to the custom fields table', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(32, '32', 'Adding custom fields to products', '20061211', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(33, '33', 'Alter product custom field 4', '20061214', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(34, '34', 'Reset invoice template to default refer Issue 70', '20070125', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(35, '35', 'Adding data to the custom fields table for invoice', '20070204', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(36, '36', 'Adding custom fields to the invoices table', '20070204', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(37, '0', 'Start', '20060514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(38, '37', 'Reset invoice template to default due to new invoi', '20070325', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(39, '38', 'Alter custom field table - field length now 255 fo', '20070325', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(40, '39', 'Alter custom field table - field length now 255 fo', '20070325', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(41, '40', 'Alter field name in si_partchmanager', '20070424', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(42, '41', 'Alter field name in si_account_payments', '20070424', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(43, '42', 'Alter field name b_name to name', '20070424', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(44, '43', 'Alter field name b_id to id', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(45, '44', 'Alter field name b_street_address to street_address', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(46, '45', 'Alter field name b_street_address2 to street_address2', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(47, '46', 'Alter field name b_city to city', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(48, '47', 'Alter field name b_state to state', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(49, '48', 'Alter field name b_zip_code to zip_code', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(50, '49', 'Alter field name b_country to country', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(51, '50', 'Alter field name b_phone to phone', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(52, '51', 'Alter field name b_mobile_phone to mobile_phone', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(53, '52', 'Alter field name b_fax to fax', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(54, '53', 'Alter field name b_email to email', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(55, '54', 'Alter field name b_co_logo to logo', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(56, '55', 'Alter field name b_co_footer to footer', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(57, '56', 'Alter field name b_notes to notes', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(58, '57', 'Alter field name b_enabled to enabled', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(59, '58', 'Alter field name b_custom_field1 to custom_field1', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(60, '59', 'Alter field name b_custom_field2 to custom_field2', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(61, '60', 'Alter field name b_custom_field3 to custom_field3', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(62, '61', 'Alter field name b_custom_field4 to custom_field4', '20070430', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(63, '62', 'Introduce system_defaults table', '20070503', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(64, '63', 'Insert date into the system_defaults table', '20070503', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(65, '64', 'Alter field name prod_id to id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(66, '65', 'Alter field name prod_description to description', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(67, '66', 'Alter field name prod_unit_price to unit_price', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(68, '67', 'Alter field name prod_custom_field1 to custom_field1', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(69, '68', 'Alter field name prod_custom_field2 to custom_field2', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(70, '69', 'Alter field name prod_custom_field3 to custom_field3', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(71, '70', 'Alter field name prod_custom_field4 to custom_field4', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(72, '71', 'Alter field name prod_notes to notes', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(73, '72', 'Alter field name prod_enabled to enabled', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(74, '73', 'Alter field name c_id to id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(75, '74', 'Alter field name c_attention to attention', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(76, '75', 'Alter field name c_name to name', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(77, '76', 'Alter field name c_street_address to street_address', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(78, '77', 'Alter field name c_street_address2 to street_address2', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(79, '78', 'Alter field name c_city to city', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(80, '79', 'Alter field name c_state to state', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(81, '80', 'Alter field name c_zip_code to zip_code', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(82, '81', 'Alter field name c_country to countyr', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(83, '82', 'Alter field name c_phone to phone', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(84, '83', 'Alter field name c_mobile_phone to mobile_phone', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(85, '84', 'Alter field name c_fax to fax', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(86, '85', 'Alter field name c_email to email', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(87, '86', 'Alter field name c_notes to notes', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(88, '87', 'Alter field name c_custom_field1 to custom_field1', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(89, '88', 'Alter field name c_custom_field2 to custom_field2', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(90, '89', 'Alter field name c_custom_field3 to custom_field3', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(91, '90', 'Alter field name c_custom_field4 to custom_field4', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(92, '91', 'Alter field name c_enabled to enabled', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(93, '92', 'Alter field name inv_id to id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(94, '93', 'Alter field name inv_biller_id to biller_id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(95, '94', 'Alter field name inv_customer_id to customer_id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(96, '95', 'Alter field name inv_type type_id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(97, '96', 'Alter field name inv_preference to preference_id', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(98, '97', 'Alter field name inv_date to date', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(99, '98', 'Alter field name invoice_custom_field1 to custom_field1', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(100, '99', 'Alter field name invoice_custom_field2 to custom_field2', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(101, '100', 'Alter field name invoice_custom_field3 to custom_field3', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(102, '101', 'Alter field name invoice_custom_field4 to custom_field4', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(103, '102', 'Alter field name inv_note to note ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(104, '103', 'Alter field name inv_it_id to id ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(105, '104', 'Alter field name inv_it_invoice_id to invoice_id ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(106, '105', 'Alter field name inv_it_quantity to quantity ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(107, '106', 'Alter field name inv_it_product_id to product_id ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(108, '107', 'Alter field name inv_it_unit_price to unit_price ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(109, '108', 'Alter field name inv_it_tax_id to tax_id  ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(110, '109', 'Alter field name inv_it_tax to tax  ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(111, '110', 'Alter field name inv_it_tax_amount to tax_amount  ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(112, '111', 'Alter field name inv_it_gross_total to gross_total ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(113, '112', 'Alter field name inv_it_description to description ', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(114, '113', 'Alter field name inv_it_total to total', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(115, '114', 'Add logging table', '20070514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(116, '115', 'Add logging systempreference', '20070514', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(117, '116', 'System defaults conversion patch - set default biller', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(118, '117', 'System defaults conversion patch - set default customer', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(119, '118', 'System defaults conversion patch - set default tax', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(120, '119', 'System defaults conversion patch - set default invoice preference', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(121, '120', 'System defaults conversion patch - set default number of line items', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(122, '121', 'System defaults conversion patch - set default invoice template', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(123, '122', 'System defaults conversion patch - set default paymemt type', '20070507', '');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(124, '123', 'Add option to delete invoices into the system_defaults table', '200709', 'INSERT INTO si_system_defaults (id, name, value) VALUES \n('''', ''delete'', ''N'');');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(125, '124', 'Set default language in new lang system', '200709', 'UPDATE si_system_defaults SET value = ''en-gb'' where name =''language'';');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(126, '125', 'Change log table that usernames are also possible as id', '200709', 'ALTER TABLE si_log CHANGE userid userid VARCHAR( 40 ) NOT NULL DEFAULT ''0''');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(127, '126', 'Add visible attribute to the products table', '200709', 'ALTER TABLE  si_products ADD  visible BOOL NOT NULL DEFAULT  ''1'';');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(128, '127', 'Add last_id to logging table', '200709', 'ALTER TABLE  si_log ADD  last_id INT NULL ;');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(129, '128', 'Add si_user table', '200709', 'CREATE TABLE IF NOT EXISTS si_users (\n			user_id int(11) NOT NULL auto_increment,\n			user_email varchar(255) NOT NULL,\n			user_name varchar(255) NOT NULL,\n			user_group varchar(255) NOT NULL,\n			user_domain varchar(255) NOT NULL,\n			user_password varchar(255) NOT NULL,\n			PRIMARY KEY  (user_id)\n			) ;');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(130, '129', 'Fill si_user table with default values', '200709', 'INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES \n('''', ''demo@simpleinvoices.org'', ''guest'', ''1'', ''1'', MD5(''demo''))');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(131, '130', 'Create si_auth_challenges table', '200709', 'CREATE TABLE IF NOT EXISTS si_auth_challenges (\n				challenges_key int(11) NOT NULL,\n				challenges_timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(132, '131', 'Make tax field 3 decimal places', '200709', 'alter table si_tax change tax_percentage tax_percentage decimal (10,3)  NULL');
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(133, '132', 'Create si_customFieldCategories table', '20070629', $$create table si_customfieldcategories (
id serial PRIMARY KEY,
name varchar(40) NOT NULL);
$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(134, '133', 'Insert si_customFieldCategories default values', '20070629', $$INSERT INTO si_customFieldCategories (id, name) VALUES (1, 'biller');
INSERT INTO si_customFieldCategories (id, name) VALUES (2, 'customer');
INSERT INTO si_customFieldCategories (id, name) VALUES (3, 'product');
INSERT INTO si_customFieldCategories (id, name) VALUES (4, 'invoice');
$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(135, '134', 'Create si_customFieldValues table', '20070629', $$CREATE TABLE si_customFieldValues (
id serial PRIMARY KEY,
customFieldId int NOT NULL,
itemId int NOT NULL,value text NOT NULL
);
COMMENT ON COLUMN si_customfieldvalues.itemid IS 'could be invocie-id,customer-i
d etc.';
$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(136, '135', 'Create si_customFields table', '20070629', $$CREATE TABLE si_customFields (
id serial PRIMARY KEY,
pluginId int NOT NULL,
categorieId int NOT NULL,
name varchar(30) NOT NULL,
description varchar(50) NOT NULL,
active boolean NOT NULL default true
);$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(137, '136', 'Add default status_id to invoices', '20071006', $$ALTER TABLE si_invoices ADD status_id INT DEFAULT 0 NOT NULL$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(138, '137', 'Custom field conversion', '20071006', $$SELECT 1+1$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(139, '138', 'Add custom field order', '20071006', $$ALTER TABLE si_customFields ADD "order" INT;$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(140, '139', 'Correct Foreign Key Tax ID Field Type in Invoice Items Table', '20071126', $$SELECT 1+1$$);
INSERT INTO si_sql_patchmanager (sql_id, sql_patch_ref, sql_patch, sql_release, sql_statement) VALUES 
(141, '140', 'Correct Foreign Key Invoice ID Field Type in AC Payments Table', '20071126', $$SELECT 1+1$$);
SELECT setval('si_sql_patchmanager_sql_id_seq', 141);


CREATE TABLE si_system_defaults (
  id serial PRIMARY KEY,
  name varchar(30) UNIQUE NOT NULL,
  value varchar(30) NOT NULL
);
COMMENT ON TABLE si_system_defaults IS $$Default values for various aspects of the system, such as the biller$$;

INSERT INTO si_system_defaults (id, name, value) VALUES 
(1, 'biller', '4');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(2, 'customer', '3');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(3, 'tax', '1');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(4, 'preference', '1');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(5, 'line_items', '5');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(6, 'template', 'default');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(7, 'payment_type', '1');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(8, 'language', 'en-gb');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(9, 'dateformat', 'Y-m-d');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(10, 'spreadsheet', 'xls');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(11, 'wordprocessor', 'doc');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(12, 'pdfscreensize', '800');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(13, 'pdfpapersize', 'A4');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(14, 'pdfleftmargin', '15');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(15, 'pdfrightmargin', '15');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(16, 'pdftopmargin', '15');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(17, 'pdfbottommargin', '15');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(18, 'emailhost', 'localhost');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(19, 'emailusername', '');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(20, 'emailpassword', '');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(21, 'logging', '0');
INSERT INTO si_system_defaults (id, name, value) VALUES 
(22, 'delete', 'N');
SELECT setval('si_system_defaults_id_seq', 22);

CREATE TABLE si_account_payments (
  id			serial NOT NULL PRIMARY KEY,
  ac_inv_id		int NOT NULL REFERENCES si_invoices(id),
  ac_amount		numeric(25,2) NOT NULL,
  ac_notes		text  NOT NULL,
  ac_date		timestamp NOT NULL,
  ac_payment_type	int NOT NULL REFERENCES si_payment_types(pt_id) default 1
);
COMMENT ON TABLE si_account_payments IS $$Payments for the invoices$$;

INSERT INTO si_account_payments (
	id, ac_inv_id, ac_amount, ac_notes, ac_date, ac_payment_type) VALUES 
(1, 1, 410.00, 'payment - cheque 14526', '2006-08-25 12:09:14', 1);
INSERT INTO si_account_payments (
	id, ac_inv_id, ac_amount, ac_notes, ac_date, ac_payment_type) VALUES 
(2, 4, 255.75, '', '2006-08-25 12:13:53', 1);
SELECT setval('si_account_payments_id_seq', 2);

CREATE TABLE si_defaults (
  def_id serial PRIMARY KEY,
  def_biller int REFERENCES si_biller(id),
  def_customer int REFERENCES si_customers(id),
  def_tax int REFERENCES si_tax(tax_id),
  def_inv_preference int REFERENCES si_preferences(pref_id),
  def_number_line_items int NOT NULL default 0,
  def_inv_template varchar(50) NOT NULL default 'default',
  def_payment_type int REFERENCES si_payment_types(pt_id) default 1
);

INSERT INTO si_defaults (def_id, def_biller, def_customer, def_tax, def_inv_preference, def_number_line_items, def_inv_template, def_payment_type) VALUES 
(1, 4, 3, 1, 1, 5, 'default', '1');
SELECT setval('si_defaults_def_id_seq', 1);

CREATE INDEX si_invoice_items_invoice_id_idx ON si_invoice_items(invoice_id);
CREATE INDEX si_account_payments_ac_inv_id_idx ON si_account_payments(ac_inv_id);
CREATE INDEX si_invoices_customer_id_idx ON si_invoices(customer_id);
CREATE INDEX si_invoices_biller_id_idx ON si_invoices(biller_id);
CREATE INDEX si_sql_patchmanager_sql_release_idx ON si_sql_patchmanager(sql_release);
CREATE INDEX si_sql_patchmanager_sql_patch_ref_idx ON si_sql_patchmanager(sql_patch_ref);
CREATE INDEX si_biller_name_idx ON si_biller(name);
CREATE INDEX si_customers_name_idx ON si_customers(name);

COMMIT;

VACUUM ANALYZE;
