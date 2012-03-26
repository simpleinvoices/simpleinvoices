<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_TAX = new SimpleInvoices_Db_Table_Tax();
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

#get custom field labels
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();
$taxes = $SI_TAX->fetchAllActive();
//if valid then do save
if (array_key_exists('description', $_POST)) {
    if ($_POST['description'] != "" ) {
        include("sys/modules/products/save.php");
    }    
}

$smarty -> assign("defaults",$SI_SYSTEM_DEFAULTS->fetchAll());
$smarty -> assign('customFieldLabel',$customFieldLabel);
if (isset($save)) {
    $smarty -> assign('save',$save);
} else {
    $smarty -> assign('save','');
}
$smarty -> assign('taxes',$taxes);

$smarty -> assign('pageActive', 'product_add');
$smarty -> assign('active_tab', '#product');
