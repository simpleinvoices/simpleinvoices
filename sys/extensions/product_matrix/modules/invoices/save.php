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

$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();

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

    /*
    * 1 = Total Invoices
    */

	if($type==1 && $saved) {
		(isset($_POST['enabled'])) ? $enabled = $_POST['enabled']  : $enabled = $enabled ;
        (isset($_POST['visible'])) ? $visible = $_POST['visible']  : $visible = $visible ;
        
        $data = array(
            'description'       => $_POST['description'],
            'detail'            => $_POST['detail'],
            'unit_price'        => $_POST['unit_price'],
            'default_tax_id'    => $_POST['default_tax_id'],
            'default_tax_id_2'  => NULL,
            'cost'              => $_POST['cost'],
            'reorder_level'     => $_POST['reoder_level'],
            'custom_field1'     => $_POST['custom_field1'],
            'custom_field2'     => $_POST['custom_field2'],
            'custom_field3'     => $_POST['custom_field3'],
            'custom_field4'     => $_POST['custom_field4'],
            'notes'             => $_POST['notes'],
            'enabled'           => 0,
            'visible'           => 0
        );
        
        $SI_PRODUCTS->insert($data);
		$product_id = $SI_PRODUCTS->getLastInsertId();

		if (matrix_invoice::insertInvoiceItem($invoice_id,1,$product_id,$_POST['tax_id'],$_POST['description'])) {
			//$saved = true;
		}
		else {
			die(end($dbh->errorInfo()));
		}
	}
	elseif ($saved) {
		for($i=0;!empty($_POST["quantity$i"]) && $i < $_POST['max_items']; $i++) {

			if($type == 4) {
                $data = array(
                    'description'       => $_POST["description$i"],
                    'detail'            => NULL,
                    'unit_price'        => $_POST["price$i"],
                    'default_tax_id'    => NULL,
                    'default_tax_id_2'  => NULL,
                    'cost'              => NULL,
                    'reorder_level'     => NULL,
                    'custom_field1'     => NULL,
                    'custom_field2'     => NULL,
                    'custom_field3'     => NULL,
                    'custom_field4'     => NULL,
                    'notes'             => $_POST["notes$i"],
                    'enabled'           => 0,
                    'visible'           => 0
                );
                
                
                $SI_PRODUCTS->insert($data);
				$product = $SI_PRODUCTS->getLastInsertId();
			}
			else {
				$product = $_POST["products$i"];
			}
			if (matrix_invoice::insertInvoiceItem($invoice_id,$_POST["quantity$i"],$product,$_POST['tax_id'],$_POST["description$i"],$_POST["attr1-$i"],$_POST["attr2-$i"], $_POST["attr3-$i"], $_POST["unit_price$i"])) {
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
		
		if (matrix_invoice::updateInvoiceItem($_POST["id$i"],$_POST["quantity$i"],$_POST["products$i"],$_POST['tax_id'],$_POST["description$i"],$_POST["attr1-$i"],$_POST["attr2-$i"], $_POST["attr3-$i"],$_POST["unit_price$i"] ) && $saved) {
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
