CREATE TABLE `si_products_attributes` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `si_products_attributes` (`id`, `name`, `display_name`) VALUES (NULL, 'Bottle Size','Size'), (NULL,'T-shirt Colour', 'Colour');

CREATE TABLE `si_products_values` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`attribute_id` INT( 11 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;


INSERT INTO `si_products_values` (`id`, `attribute_id`,`value`) VALUES (NULL,'1', 'S'),  (NULL,'1', 'M'), (NULL,'1', 'L'),  (NULL,'2', 'Red'),  (NULL,'2', 'White');


CREATE TABLE `si_products_matrix` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`product_id` INT( 11 ) NOT NULL ,
`attribute_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;


ALTER TABLE `si_products_matrix` ADD `product_attribute_number` INT( 11 ) NOT NULL AFTER `product_id` ;

INSERT INTO `si_products_matrix` (`id`, `product_id`,`product_attribute_number`, `attribute_id`) VALUES (NULL,'1', '1', '1'),  (NULL,'1', '2', '2'), (NULL,'2', '1', '2');

ALTER TABLE `si_invoice_items` ADD `attribute_1` INT( 11 ) NULL ,
ADD `attribute_2` INT( 11 ) NULL ,
ADD `attribute_3` INT( 11 ) NULL ;

ALTER TABLE `si_products_attributes` ADD `display_name` VARCHAR( 255 ) NOT NULL ;


