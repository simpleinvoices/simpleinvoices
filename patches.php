<?php

sql="
INSERT INTO `si_sql_patchmanager` ( `sql_id` , `sql_patch` , `sql_release` , `sql_statement` )
VALUES (
'', 'Update currency sign for Invoice - no details', '20050510', 'UPDATE `si_preferences` SET `pref_currency_sign` = '$',
`pref_inv_detail_heading` = NULL WHERE `pref_id` =2 LIMIT 1 ;
'
);
";

INSERT INTO `si_sql_patchmanager` ( `sql_id` , `sql_patch` , `sql_release` , `sql_statement` )
VALUES (
'', 'Add default number of line items into defaults', '20050510', 'ALTER TABLE `si_defaults` ADD `def_number_line_items` INT( 25 ) NOT NULL ;'
);

INSERT INTO `si_sql_patchmanager` ( `sql_id` , `sql_patch` , `sql_release` , `sql_statement` )
VALUES (
'', 'Set the default num of line items to 5', '20050510', 'UPDATE `si_defaults` SET `def_number_line_items` = ''5'' WHERE `def_id` =1 LIMIT 1 ;'
);

INSERT INTO `si_sql_patchmanager` ( `sql_id` , `sql_patch` , `sql_release` , `sql_statement` )
VALUES 
 (
'', 'Create si_sql_patchmanger table', '20050510', 'CREATE TABLE `si_sql_patchmanager` ( `sql_id` INT NOT NULL AUTO_INCREMENT , `sql_patch` VARCHAR( 50 ) NOT NULL , `sql_release` VARCHAR( 25 ) NOT NULL , ''sql_statement` TEXT NOT NULL , PRIMARY KEY ( `sql_id` ) ); '
);




?>
