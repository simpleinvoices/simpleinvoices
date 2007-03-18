<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name",$LANG_customer_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="c_enabled">
<option value="1" selected>$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$customFieldLabel = getCustomFieldLabels("customer");
#get custom field labels


$temp = file_get_contents("./src/customers/add.html");
$temp = addslashes($temp); $content = "";

eval('$content = "'.$temp.'";');
echo $content;
?>