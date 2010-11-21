CREATE TABLE  `si_expense` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`amount` DECIMAL(25,6) NOT NULL ,
`domain_id` INT( 11 ) NOT NULL ,
`expense_account_id` INT( 11 ) NOT NULL ,
`biller_id` INT( 11 ) NOT NULL ,
`customer_id` INT( 11 ) NOT NULL ,
`invoice_id` INT( 11 ) NOT NULL ,
`product_id` INT( 11 ) NOT NULL ,
`date` DATE NOT NULL ,
`note` TEXT NOT NULL
) ENGINE = INNODB ;

CREATE TABLE  `si_expense_account` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`domain_id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB;

INSERT INTO `si_expense_account` (`id`, `domain_id`, `name`) VALUES (NULL, '1', 'Car expense'), (NULL, '1', 'IT costs');

CREATE TABLE `si_expense_item_tax` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`expense_id` INT( 11 ) NOT NULL ,
`tax_id` INT( 11 ) NOT NULL ,
`tax_type` VARCHAR( 1 ) NOT NULL ,
`tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
`tax_amount` DECIMAL( 25, 6 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;
