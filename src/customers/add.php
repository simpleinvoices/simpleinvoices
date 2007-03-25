<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/* validataion code */

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name", $smarty -> get_config_vars("customer_name"));
jsFormValidationEnd();
jsEnd();

/* end validataion code */


$customFieldLabel = getCustomFieldLabels("customer");

#get custom field labels

$smarty -> assign('customFieldLabel',$customFieldLabel);

?>