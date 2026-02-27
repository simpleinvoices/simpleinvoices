<?php
/*// 1 means that the variable has been translated and // zero means it hasnt been translated - this is used by a script to calculate how much of each file has been done
regex :%s/;/ /1/;// 1\/\/1/g - remove the spaces
 */

#all
$CLANG = array(
    'parent_customer' => "Parent customer",
    'sub_customer' => "Sub customer",
    'sub_customers' => "Sub customers"
    );

$LANG = array_merge($LANG,$CLANG);

