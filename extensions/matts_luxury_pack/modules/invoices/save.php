<?php

/*
* Script: /simple/extensions/matts_luxury_pack/modules/invoices/save.php
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

$defaults = getSystemDefaults();
global $logger;
$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('active_tab', '#money');

# Deal with op and add some basic sanity checking

if (!isset ($_POST['type']) && !isset ($_POST['action'])) {
	exit("no save action");
}

$saved = false;
$type = $_POST['type'];

$logger->log('extensions/matts_luxury_pack/modules/invoices/save.php', Zend_Log::INFO);
$logger->log('action='.$_POST['action'], Zend_Log::INFO);
$myinvoice = new myinvoice;

if ($_POST['action'] == "insert" ) {
	
	if ($myinvoice->insertNew($type)) {
		$id = lastInsertId();
		//saveCustomFieldValues($_POST['categorie'],$invoice_id);
	//echo "insert_DeliveryNote";
		if (isset($_POST['ship_to_customer_id']) && $_POST['ship_to_customer_id']>0 && $defaults['use_ship_to'])
			if ($myinvoice->insert_DeliveryNote($type))
				$saved = true;
		$saved = true;
	}

    /*
    * 1 = Total Invoices
    */

	if( $type == total_invoice && $saved) {

		$logger->log('Total style invoice created, ID: '.$id, Zend_Log::INFO);

		insertProduct(0,0);
		$product_id = lastInsertId();

		insertInvoiceItem($id, 1 , $product_id, 1, $_POST['tax_id'][0], $_POST['description'], $_POST['unit_price']);
	}
	elseif ($saved) {
		
		$logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
		$i = 0;
		while ($i <= $_POST['max_items']) {
			$logger->log('i='.$i, Zend_Log::INFO);
			$logger->log('qty='.$_POST["quantity$i"], Zend_Log::INFO);
			if ($_POST["quantity$i"] != null)
			{
				insertInvoiceItem($id, $_POST["quantity$i"], $_POST["products$i"], $i, $_POST["tax_id"][$i], $_POST["description$i"], $_POST["unit_price$i"], isset($_POST["attribute"][$i]) ? $_POST["attribute"][$i] : null);
			}
			$i++;
		}
	}
} elseif ($_POST['action'] == "edit") {

	//Get type id - so do add into redirector header

	$id = $_POST['id'];
	$domain_id = isset($domain_id) ? domain_id::get($domain_id) : 1;
	
	if ($myinvoice->update($_POST['id'])) {
		//updateCustomFieldValues($_POST['categorie'],$_POST['invoice_id']);
		if (isset($_POST['ship_to_customer_id']) && $_POST['ship_to_customer_id']>0 && $defaults['use_ship_to'])
			if ($myinvoice->update_DeliveryNote($_POST['id']))
				$saved = true;
		$saved = true;
	}

	if ($type == total_invoice && $saved) {

		$logger->log('Total style invoice updated, product ID: '.$_POST['products0'], Zend_Log::INFO);
		$sql = "UPDATE ".TB_PREFIX."products
				SET 	unit_price = :price
					, description = :description
				WHERE 	id = :id
				AND 	domain_id = :domain_id";
		dbQuery($sql,
			':price', $_POST['unit_price'],
			':description', $_POST['description0'],
			':id', $_POST['products0'],
			':domain_id', $auth_session->domain_id
			);
	}

	$logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
	$i = 0;
	while ($i <= $_POST['max_items']) 
	{
//	for($i=0;(!empty($_POST["quantity$i"]) && $i < $_POST['max_items']);$i++) {
		$logger->log('i='.$i, Zend_Log::INFO);
		$logger->log('qty='.$_POST["quantity$i"], Zend_Log::INFO);
		$logger->log('product='.$_POST["products$i"], Zend_Log::INFO);

		if ($_POST["delete$i"] == "yes")
		{
			delete ('invoice_items','id',$_POST["line_item$i"]);
		}
		if ($_POST["delete$i"] !== "yes")
		{
			if ($_POST["quantity$i"] != null)
			{
				//new line item added in edit page
				if ($_POST["line_item$i"] == "")
				{
					insertInvoiceItem ($id, $_POST["quantity$i"], $_POST["products$i"], $i, $_POST["tax_id"][$i], $_POST["description$i"], $_POST["unit_price$i"],$_POST["attribute"][$i]);
				}
				
				if ($_POST["line_item$i"] != "")
				{
					updateInvoiceItem ($_POST["line_item$i"], $_POST["quantity$i"], $_POST["products$i"], $i, $_POST['tax_id'][$i], $_POST["description$i"], $_POST["unit_price$i"], isset($_POST["attribute"][$i]) ? $_POST["attribute"][$i] : null);
//					$saved;
					// $saved =  true;
				}
			}
		}
		$i++;
	}
}

//Get type id - so do add into redirector header
$smarty->assign('saved', $saved);
$smarty->assign('id', $id);
?>
