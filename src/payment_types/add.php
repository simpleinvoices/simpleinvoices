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



$temp = file_get_contents("./src/payment_types/add.html");
$temp = addslashes($temp); $content = "";

eval('$content = "'.$temp.'";');
echo $content;

?>