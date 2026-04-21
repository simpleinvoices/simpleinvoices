<?php

/*
* Script: save.php
* 	Invoice save file
*
* License:
*	 GPL v3 or above
*	 
* Website:
* 	http://www.simpleinvoices.or
*/

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$bladeView -> assign('pageActive', 'invoice_new');
$bladeView -> assign('active_tab', '#money');

# Deal with op and add some basic sanity checking


if(!isset( $_POST['type']) && !isset($_POST['action'])) {
	exit("no save action");
}

$saved = false;
$type = $_POST['type'];
$id = null;

$save_error = null;
$block_insert_duplicate_total_product = false;
if (isset($_POST['action']) && $_POST['action'] === 'insert' && isset($type) && (string) $type === (string) total_invoice) {
	$td = trim($_POST['description'] ?? '');
	if ($td !== '' && productDescriptionExists($td)) {
		$block_insert_duplicate_total_product = true;
		$save_error = 'duplicate_product_description';
	}
}

if ($_POST['action'] == "insert" ) {

	if (!$block_insert_duplicate_total_product && insertInvoice($type)) {
		$id = lastInsertId();
		//saveCustomFieldValues($_POST['categorie'],$invoice_id);
		$saved = true;
		dashboard_cache_clear((int) $auth_session->domain_id);
	}

    /*
    * 1 = Total Invoices
    */

	if($type == total_invoice && $saved) {

		$logger->log('Total style invoice created, ID: '.$id, LegacyLogger::INFO);

		insertProduct(0,0);
		$product_id = lastInsertId();

		insertInvoiceItem($id, 1 , $product_id, 1, invoice_tax_ids_for_line_from_post(0), $_POST['description'], $_POST['unit_price']);
	}
	elseif ($saved) {
		
		$logger->log('Max items:'.$_POST['max_items'], LegacyLogger::INFO);
		$i = 0;
		while ($i <= $_POST['max_items']) {
			$logger->log('i='.$i, LegacyLogger::INFO);
			$logger->log('qty='.$_POST["quantity$i"], LegacyLogger::INFO);
			if($_POST["quantity$i"] != null)
			{
				insertInvoiceItem($id, $_POST["quantity$i"], $_POST["products$i"], $i, invoice_tax_ids_for_line_from_post($i), $_POST["description$i"], $_POST["unit_price$i"], $_POST['attribute'][$i] ?? array());
			}
			$i++;
		}
	}
} elseif ( $_POST['action'] == "edit") {

	//Get type id - so do add into redirector header

	$id = $_POST['id'];
	
	if (updateInvoice($_POST['id'])) {
		//updateCustomFieldValues($_POST['categorie'],$_POST['invoice_id']);
		$saved = true;
		dashboard_cache_clear((int) $auth_session->domain_id);
	}

	if($type == total_invoice && $saved) {
		$logger->log('Total style invoice updated, product ID: '.$_POST['products0'], LegacyLogger::INFO);
		$pidEdit = (int) ($_POST['products0'] ?? 0);
		$dEdit = trim((string) ($_POST['description0'] ?? ''));
		if ($dEdit === '' || !productDescriptionExists($dEdit, $pidEdit)) {
			$sql = "UPDATE ".TB_PREFIX."products SET unit_price = :price, description = :description WHERE id = :id AND domain_id = :domain_id";
			dbQuery($sql,
				':price', $_POST['unit_price'],
				':description', $_POST['description0'],
				':id', $_POST['products0'],
				':domain_id', $auth_session->domain_id
				);
		}
	}

    
	$logger->log('Max items:'.$_POST['max_items'], LegacyLogger::INFO);
	$i = 0;
	while ($i <= $_POST['max_items']) 
	{
//	for($i=0;(!empty($_POST["quantity$i"]) && $i < $_POST['max_items']);$i++) {
		$logger->log('i='.$i, LegacyLogger::INFO);
		$logger->log('qty='.$_POST["quantity$i"], LegacyLogger::INFO);
		$logger->log('product='.$_POST["products$i"], LegacyLogger::INFO);

		if($_POST["delete$i"] == "yes")
		{
			delete('invoice_items','id',$_POST["line_item$i"]);
		}
		if($_POST["delete$i"] !== "yes")
		{
		
		
			if($_POST["quantity$i"] != null)
            {
	
				//new line item added in edit page
				if($_POST["line_item$i"] == "")
				{
					insertInvoiceItem($id,$_POST["quantity$i"],$_POST["products$i"],$i,invoice_tax_ids_for_line_from_post($i),$_POST["description$i"], $_POST["unit_price$i"],$_POST['attribute'][$i] ?? array());
				}
				
				if($_POST["line_item$i"] != "")
				{
					updateInvoiceItem($_POST["line_item$i"],$_POST["quantity$i"],$_POST["products$i"],$i,invoice_tax_ids_for_line_from_post($i),$_POST["description$i"],$_POST["unit_price$i"],$_POST['attribute'][$i] ?? array());
//					$saved;
					// $saved =  true;
				}
			}
		}

		$i++;

	}

}

//Get type id - so do add into redirector header
if ($saved && $id) {
	invoice_denorm::refreshForInvoice((int) $id, $auth_session->domain_id);
}
$bladeView->assign('saved', $saved);
$bladeView->assign('id', $id);
$bladeView->assign('save_error', $save_error);

?>
