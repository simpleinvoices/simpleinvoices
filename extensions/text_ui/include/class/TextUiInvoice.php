<?php
class TextUiInvoice {

    public static function getInvoiceItems($inv_id = null) {
        global $pdoDb;

        $domain_id = domain_id::get();
        $id = (isset($inv_id) ? $inv_id : 0);
        $inv_offset = 0;
        $invoiceItems = array();
        $invoice = null;
        $pdoDb->setSelectList(array("id","index_id"));
        if ($id == 0) {
            $last_id = (isset($_SESSION['text_ui_last_index_id']) ? $_SESSION['text_ui_last_index_id'] : 0);
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $pdoDb->setOrderBy("index_id");
            $invoices = $pdoDb->request("SELECT", "invoices");
            foreach($invoices as $invoice) {
                $inv_offset++;
                if ($invoice['index_id'] > $last_id) {
                    break;
                }
            }
        } else {
            $pdoDb->addSimpleWhere("id", $id);
            $invoice = $pdoDb->request("SELECT", "invoices"); 
        }

        $_SESSION['text_ui_last_index_id'] = $invoice['index_id'];
        $id = $invoice['id'];

        $invoiceItems[] = $inv_offset;
        if ($id != 0) {
            $pdoDb->addSimpleWhere("invoice_id", $id); // unique ID no domain needed
            $rows = $pdoDb->request("SELECT", "invoice_items");
            foreach($rows as $invoiceItem) {
                $pdoDb->addSimpleWhere("id", $invoiceItem['product_id']); // no domain_id needed
                $products = $pdoDb->request("SELECT", "products");
                $invoiceItem['product'] = $products[0];;

                for($i=1; $i<=3; $i++) {
                    $pdoDb->addSimpleWhere("v.id", "attributes_$i");
                    $jn = new Join("INNER", "product_values", "v");
                    $on = new OnClause();
                    $on->addSimpleItem("v.attrbute_id", new DbField("a.id"));
                    $pdoDb->addToJoins($jn);
                    $pdoDb->addToFunctions(new FunctionStmt("CONCAT", "a.display_name, '-', v.value", "display"));
                    $attr = $pdoDb->request("SELECT", "products_attributes", "a");
                    $invoiceItem["attr$i"] = $attr[0];
                }

                $invoiceItems[] = $invoiceItem;
                // @formatter:on
            }
        }
        return $invoiceItems;
    }
}
