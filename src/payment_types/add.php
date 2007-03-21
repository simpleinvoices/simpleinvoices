<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description","Payment type description");
jsFormValidationEnd();
jsEnd();



//include("./templates/default/payment_types/add2.tpl");
//echo $block;

?>