<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#get custom field labels
$customFieldLabel = getCustomFieldLabels();
$taxes = getActiveTaxes();
//if valid then do save
if (array_key_exists('description', $_POST)) {
    if ($_POST['description'] != "" ) {
        include("sys/modules/products/save.php");
    }    
}

$smarty -> assign("defaults",getSystemDefaults());
$smarty -> assign('customFieldLabel',$customFieldLabel);
if (isset($save)) {
    $smarty -> assign('save',$save);
} else {
    $smarty -> assign('save','');
}
$smarty -> assign('taxes',$taxes);

$smarty -> assign('pageActive', 'product_add');
$smarty -> assign('active_tab', '#product');
