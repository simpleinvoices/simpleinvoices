<?php

/*
* Script: save.php
* 	Invoice save file
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* License:
*	 GPL v2 or above
*	 
* Website:
* 	http://www.simpleinvoices.or
*/


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "invoices";

$smarty->assign('pageActive', $pageActive);

# Deal with op and add some basic sanity checking


if(!isset( $_POST['type']) && !isset($_POST['action'])) {
	exit("no save action");
}

$saved = false;
$type = $_POST['type'];

if ($_POST['action'] == "insert" ) {
	
	if(insertInvoice($type)) {
		$invoice_id = lastInsertId();
		saveCustomFieldValues($_POST['categorie'],$invoice_id);
		$saved = true;
	}

	if($type==1 && $saved) {
		insertProduct(0,0);
		$product_id = lastInsertId();

		if (insertInvoiceItem($invoice_id,1,$product_id,$_POST['tax_id'],$_POST['description'])) {
			//$saved = true;
		}
		else {
			die(end($dbh->errorInfo()));
		}
	}
	elseif ($saved) {
		for($i=0;!empty($_POST["quantity$i"]) && $i < $_POST['max_items']; $i++) {
	
			if (insertInvoiceItem($invoice_id,$_POST["quantity$i"],$_POST["products$i"],$_POST['tax_id'],$_POST["description$i"]) ) {
				//$saved = true;
			} else {
				die(end($dbh->errorInfo()));
			}
		}
	}
} elseif ( $_POST['action'] == "edit") {

	//Get type id - so do add into redirector header

	$invoice_id = $_POST['invoice_id'];
	
	if (updateInvoice($_POST['invoice_id'])) {
		updateCustomFieldValues($_POST['categorie'],$_POST['invoice_id']);
		$saved = true;
	}

	if($type == 1 && $saved) {
		$sql = "UPDATE ".TB_PREFIX."products SET unit_price = :price, description = :description WHERE id = :id";
		dbQuery($sql,
			':price', $_POST['unit_price'],
			':description', $_POST['description0'],
			':id', $_POST['products0']
			);
	}

	for($i=0;(!empty($_POST["quantity$i"]) && $i < $_POST['max_items']);$i++) {
		
		if (updateInvoiceItem($_POST["id$i"],$_POST["quantity$i"],$_POST["products$i"],$_POST['tax_id'],$_POST["description$i"]) && $saved) {
			//$saved =  true;
		}
		else {
			die(end($dbh->errorInfo()));
		}
	}

}

//Get type id - so do add into redirector header
$smarty->assign('type', $type);
$smarty->assign('saved', $saved);
$smarty->assign('invoice_id', $invoice_id);

?>
