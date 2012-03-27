<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

#get the invoice id
$product_id = $_GET['id'];

$product = $SI_PRODUCTS->getProductById($product_id);

#get custom field labels
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();

$pageActive = "products";

$smarty->assign('pageActive', $pageActive);
$smarty -> assign('product',$product);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$sql = "select * from ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$attributes = $sth->fetchAll();
$smarty -> assign("attributes", $attributes);

$sql_matrix = "select * from ".TB_PREFIX."products_matrix where product_id = $product_id order by id";
$sth_matrix =  dbQuery($sql_matrix);
$matrix = $sth_matrix->fetchAll();
$smarty -> assign("matrix", $matrix);


$sql_matrix1 = "select * from ".TB_PREFIX."products_matrix where product_id = $product_id and product_attribute_number = 1 ";
$sth_matrix1 =  dbQuery($sql_matrix1);
$matrix1 = $sth_matrix1->fetch();
$smarty -> assign("matrix1", $matrix1);

$sql_matrix2 = "select * from ".TB_PREFIX."products_matrix where product_id = $product_id and product_attribute_number = 2 ";
$sth_matrix2 =  dbQuery($sql_matrix2);
$matrix2 = $sth_matrix2->fetch();
$smarty -> assign("matrix2", $matrix2);

$sql_matrix3 = "select * from ".TB_PREFIX."products_matrix where product_id = $product_id and product_attribute_number = 3 ";
$sth_matrix3 =  dbQuery($sql_matrix3);
$matrix3 = $sth_matrix3->fetch();
$smarty -> assign("matrix3", $matrix3);
?>
