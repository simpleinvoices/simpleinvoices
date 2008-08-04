CREATE TABLE `si_products_attributes` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `si_products_attributes` (`id`, `name`) VALUES (NULL, 'Size'), (NULL, 'Colour');

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


INSERT INTO `si_products_matrix` (`id`, `product_id`,`attribute_id`) VALUES (NULL,'1', '1'),  (NULL,'1', '2'), (NULL,'2', '2');
