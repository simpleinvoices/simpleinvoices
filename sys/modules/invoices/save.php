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

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('active_tab', '#money');

# Deal with op and add some basic sanity checking


if(!isset( $_POST['type']) && !isset($_POST['action'])) {
	exit("no save action");
}

$saved = false;
$type = $_POST['type'];



if ($_POST['action'] == "insert" ) {
	
	if(insertInvoice($type)) {
		$id = lastInsertId();
		//saveCustomFieldValues($_POST['categorie'],$invoice_id);
		$saved = true;
	}

    /*
    * 1 = Total Invoices
    */

	if($type==total_invoice && $saved) {

		$logger->log('Total style invoice created, ID: '.$id, Zend_Log::INFO);

		insertProduct(0,0);
		$product_id = lastInsertId();

		if (insertInvoiceItem($id,1,$product_id,1,$_POST['tax_id'][0],$_POST['description'],$_POST['unit_price'])) {
			//$saved = true;
		}
		else {
			die(end($dbh->errorInfo()));
		}
	}
	elseif ($saved) {
		
		$logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
		$i = 0;
		while ($i <= $_POST['max_items']) {
			$logger->log('i='.$i, Zend_Log::INFO);
			$logger->log('qty='.$_POST["quantity$i"], Zend_Log::INFO);
			if($_POST["quantity$i"] != null)
			{
				if (
						insertInvoiceItem($id,$_POST["quantity$i"],$_POST["products$i"],$i,$_POST["tax_id"][$i],$_POST["description$i"], $_POST["unit_price$i"] )
					) 
				{
		//			insert_invoice_item_tax(lastInsertId(), )
					//$saved = true;
				} else {
					die(end($dbh->errorInfo()));
				}
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
	}

	if($type == total_invoice && $saved) {
		$logger->log('Total style invoice updated, product ID: '.$_POST['products0'], Zend_Log::INFO);
		$sql = "UPDATE ".TB_PREFIX."products SET unit_price = :price, description = :description WHERE id = :id";
		dbQuery($sql,
			':price', $_POST['unit_price'],
			':description', $_POST['description0'],
			':id', $_POST['products0']
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
					insertInvoiceItem($id,$_POST["quantity$i"],$_POST["products$i"],$i,$_POST["tax_id"][$i],$_POST["description$i"], $_POST["unit_price$i"]);
				}
				
				if($_POST["line_item$i"] != "")
				{
					updateInvoiceItem($_POST["line_item$i"],$_POST["quantity$i"],$_POST["products$i"],$i,$_POST['tax_id'][$i],$_POST["description$i"],$_POST["unit_price$i"]);
					$saved;
					//$saved =  true;
/*
				}	
				else {
					die(end($dbh->errorInfo()));
*/
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
