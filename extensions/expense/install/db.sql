CREATE TABLE  `si_expense` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`domain_id` INT( 11 ) NOT NULL ,
`account_id` INT( 11 ) NOT NULL ,
`biller_id` INT( 11 ) NOT NULL ,
`customer_id` INT( 11 ) NOT NULL ,
`invoice_id` INT( 11 ) NOT NULL ,
`date` DATE NOT NULL ,
`note` TEXT NOT NULL
) ENGINE = INNODB ;

CREATE TABLE  `si_expense_account` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`domain_id` INT( 11 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB;
