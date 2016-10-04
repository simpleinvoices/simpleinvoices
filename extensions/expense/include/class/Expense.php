<?php

class Expense {
    public static function count() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->addToFunctions("count(*) as count");
        $rows = $pdoDb->request("SELECT", "expense");
        return $rows[0]['count'];
    }
    
    public static function get_all() {
        global $pdoDb;
        $pdoDb->setOrderBy("id");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        return $pdoDb->request("SELECT", "expense");
    }

    public static function add() {
        $add = array();
        $add['expense_account_all'] = ExpenseAccount::get_all();

        //get customers with domain_id from session by constructor
        $add['customer_all'] = Customer::get_all(true);

        //get billers with domain_id from session by constructor
        $add['biller_all'] = Biller::get_all();

        //get invoices
        $invoiceobj = new Invoice();
        $add['invoice_all'] = $invoiceobj->get_all();

        //get products
        $add['product_all'] = Product::select_all();

        return $add;
    }

    public static function get($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);
        $rows =  $pdoDb->request("SELECT", "expense");
        return $rows;
    }

    public static function detail() {
        $detail = array();
        //get ExpenseAccount
        $detail['expense_account_all'] = ExpenseAccount::get_all();

        //get customers with domain_id from session by constructor
        $detail['customer_all'] = Customer::get_all(true);

        //get billers with domain_id from session by constructor
        $detail['biller_all'] = Biller::get_all();

        //get invoices
        $invoiceobj = new Invoice();
        $detail['invoice_all'] = $invoiceobj->get_all();

        //get products
        $detail['product_all'] = Product::select_all();

        return $detail;
    }

    public static function save() {
        global $logger, $pdoDb;
        $pdoDb->setExcludedFields("id");
        $id = $pdoDb->request("INSERT", "expense");

        $logger->log("Exp ITEM tax- last insert ID-$id", Zend_Log::INFO);
        $line_item_tax_id = (isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "");
        self::expense_item_tax($id, $line_item_tax_id, $_POST['amount'], "1", "insert");
        return true;
    }

    public static function update() {
        global $pdoDb;
        $pdoDb->setExcludedFields(array("id", "domain_id"));
        $pdoDb->addSimpleWhere("domain_id", $_POST['domain_id'], "AND");
        $pdoDb->addSimpleWhere("id", $_GET['id']);
        $result = $pdoDb->request("UPDATE", "expense");

        if (!$result) return false;
        $line_item_tax_id = (isset($_POST['tax_id'][0]) ? $_POST['tax_id'][0] : "");
        self::expense_item_tax($_GET['id'], $line_item_tax_id, $_POST['amount'], "1", "update");
        return true;
    }

    /*  Function: invoice_item_tax
     *  Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
     */
    public static function expense_item_tax($expense_id,$line_item_tax_id,$unit_price,$quantity,$action="") {
        global $pdoDb;
        if (!is_array($line_item_tax_id)) return false;

        //if editing invoice delete all tax info then insert first then do insert again
        //probably can be done without delete - someone to look into this if required - TODO
        try {
            $pdoDb->begin();
            if ($action == "update") {
                $pdoDb->addSimpleWhere("expense_id", $expense_id);
                $pdoDb->request("DELETE", expense_item_tax);
            }
    
            foreach($line_item_tax_id as $value) {
                if($value !== "") {
                    $tax = getTaxRate($value);

                    $tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
    
                    $pdoDb->setExcludedFields("id");
                    $list = array("expense_id" => $expense_id,
                                  "tax_id"     => $tax['tax_id'],
                                  "tax_type"   => $tax['type'],
                                  "tax_rate"   => $tax['tax_percentage'],
                                  "tax_amount" => $tax_amount);
                    $pdoDb->setFauxPost($list);
                    $pdoDb->request("INSERT", "expense_item_tax");
                }
            }
            $pdoDb->commit();
        } catch (PdoDbException $pdi) {
            $pdoDb->clearAll(true);
            return false;
        }
        return true;
    }
}
