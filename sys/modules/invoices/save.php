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

        $ii = new invoice;
        $ii->invoice_id = $id;
        $ii->quantity = '1';
        $ii->product_id = $product_id;
        $ii->line_number = '1';
        $ii->line_item_tax_id = $_POST['tax_id'][0];
        $ii->description = $_POST['description'];
        $ii->unit_price = $_POST['unit_price'];

		if ($ii->insert_item()) {
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
                $ii = new invoice;
                $ii->invoice_id = $id;
                $ii->quantity = $_POST["quantity$i"];
                $ii->product_id = $_POST["products$i"];
                $ii->line_number = $i;
                $ii->line_item_tax_id = $_POST["tax_id"][$i];
                $ii->description = $_POST["description$i"];
                $ii->unit_price = $_POST["unit_price$i"] ;

				if (
                        $ii->insert_item()
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
                    $ii = new invoice;
                    $ii->invoice_id = $id;
                    $ii->quantity = $_POST["quantity$i"];
                    $ii->product_id = $_POST["products$i"];
                    $ii->line_number = $i;
                    $ii->line_item_tax_id = $_POST["tax_id"][$i];
                    $ii->description = $_POST["description$i"];
                    $ii->unit_price = $_POST["unit_price$i"] ;
                    $ii->insert_item();
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