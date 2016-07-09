<?php

/*
 * Script: save.php
 *     Invoice save file
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   http://www.simpleinvoices.or
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
global $smarty;

checkLogin();

$smarty -> assign('pageActive', 'invoice_new');
$smarty -> assign('active_tab', '#money');

// Deal with op and add some basic sanity checking
if(!isset( $_POST['type']) && !isset($_POST['action'])) {
    exit("no save action");
}

$saved = false;
$type = $_POST['type'];

if ($_POST['action'] == "insert" ) {
    if(insertInvoice($type)) {
        $id = lastInsertId();
        $saved = true;
    }

    // 1 = Total Invoices
    if($type == total_invoice && $saved) {
        insertProduct(0,0);
        $product_id = lastInsertId();
        // @formatter:off
        insertInvoiceItem($id, 1, $product_id          ,
                               1, $_POST['tax_id'][0]  ,
                                  $_POST['description'], $_POST['unit_price']);
        // @formatter:on
    } elseif ($saved) {
        $i = 0;
        while ($i <= $_POST['max_items']) {
            if ($_POST["quantity$i"] != null) {
                // @formatter:off
                insertInvoiceItem($id, $_POST["quantity$i"]  , $_POST["products$i"]   ,
                                  $i , $_POST["tax_id"][$i]  , $_POST["description$i"],
                                       $_POST["unit_price$i"], $_POST["attribute"][$i]);
                // @formatter:on
            }
            $i++;
        }
    }
} elseif ( $_POST['action'] == "edit") {
    //Get type id - so do add into redirector header
    $id = $_POST['id'];
    if (updateInvoice($_POST['id'])) {
        $saved = true;
    }

    if($type == total_invoice && $saved) {
        // @formatter:off
        $sql = "UPDATE " . TB_PREFIX . "products
                SET unit_price  = :price,
                    description = :description
                WHERE id       = :id
                 AND domain_id = :domain_id";
        dbQuery($sql, ':price'      , $_POST['unit_price']  ,
                      ':description', $_POST['description0'],
                      ':id'         , $_POST['products0'],
                      ':domain_id'  , domain_id::get());
        // @formatter:on
    }

    // $logger->log('Max items:'.$_POST['max_items'], Zend_Log::INFO);
    $i = 0;
    while ($i <= $_POST['max_items']) {
        if ($_POST["delete$i"] == "yes") {
            delete('invoice_items','id',$_POST["line_item$i"]);
        } else {
            if ($_POST["quantity$i"] != null) {
                // @formatter:off
                //new line item added in edit page
                $item    = (isset($_POST["line_item$i"]  ) ? $_POST["line_item$i"  ] : "");
                $qty     = (isset($_POST["quantity$i"]   ) ? $_POST["quantity$i"   ] : "");
                $product = (isset($_POST["products$i"]   ) ? $_POST["products$i"   ] : "");
                $desc    = (isset($_POST["description$i"]) ? $_POST["description$i"] : "");
                $price   = (isset($_POST["unit_price$i"] ) ? $_POST["unit_price$i" ] : "");
                $attr    = (isset($_POST["attribute$i"]  ) ? $_POST["attribute$i"  ] : "");
                $tax_id  = (isset($_POST["tax_id$i"]     ) ? $_POST["tax_id$i"     ] : "");
                if ($item == "") {
                    insertInvoiceItem($id  , $qty, $product, $i, $tax_id, $desc, $price, $attr);
                } else {
                    updateInvoiceItem($item, $qty, $product, $i, $tax_id, $desc, $price, $attr);
                }
                // @formatter:on
            }
        }

        $i++;
    }
}

//Get type id - so do add into redirector header
$smarty->assign('saved', $saved);
$smarty->assign('id', $id);
