 IF NOT EXISTS (
 SELECT
	column_name
 FROM
	INFORMATION_SCHEMA.columns
 WHERE
	table_name = 'si_invoices'
 AND column_name = 'ship_to_customer_id')
-- IF COL_LENGTH('si_invoices', 'ship_to_customer_id') IS NULL
-- BEGIN
 ALTER TABLE `si_invoices` ADD `ship_to_customer_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `customer_id`;
-- END
