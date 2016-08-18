ALTER TABLE `si_products` ADD `weight` INT( 11 ) NOT NULL DEFAULT '0' AFTER `custom_field4` ,
ADD `length` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'x(horz.) dim.' AFTER `weight` ,
ADD `width` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'z dim.' AFTER `length` ,
ADD `height` INT( 11 ) NOT NULL DEFAULT '0' COMMENT 'y(vert.) dim.' AFTER `width` ;

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES (NULL, 'product_lwhw', '1', '1', '1');

INSERT INTO `si_system_defaults` (`id`, `name`, `value`, `domain_id`, `extension_id`) VALUES (NULL, 'default_nrows', '15', '1', '1');