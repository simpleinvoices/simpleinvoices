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
        // @formatter:off
        $add = array();
        $add['expense_account_all'] = ExpenseAccount::get_all();
        $add['customer_all']        = Customer::get_all(true);
        $add['biller_all']          = Biller::get_all();
        $add['invoice_all']         = Invoice::get_all();
        $add['product_all']         = Product::select_all();
        // @formatter:on
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
        // @formatter:off
        $detail = array();
        $detail['expense_account_all'] = ExpenseAccount::get_all();
        $detail['customer_all']        = Customer::get_all(true);
        $detail['biller_all']          = Biller::get_all();
        $detail['invoice_all']         = Invoice::get_all();
        $detail['product_all']         = Product::select_all();
        // @formatter:on
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

    /*  Function: expense_item_tax
     *  Purpose: insert/update the multiple taxes per line item into the si_expense_item_tax table
     */
    public static function expense_item_tax($expense_id,$line_item_tax_id,$unit_price,$quantity,$action="") {
        if (!is_array($line_item_tax_id)) return false;

        //if editing invoice delete all tax info then insert first then do insert again
        //probably can be done without delete - someone to look into this if required - TODO
        try {
            $requests = new Requests();
            if ($action == "update") {
                $request = new Request("DELETE", expense_item_tax);
                $request->addSimpleWhere("expense_id", $expense_id);
            }

            foreach($line_item_tax_id as $value) {
                if($value !== "") {
                    $tax = Taxes::getTaxRate($value);

                    $tax_amount = Taxes::lineItemTaxCalc($tax, $unit_price,$quantity);

                    $request = new Request("INSERT", "expense_item_tax");
                    $request->setExcludedFields("id");
                    $request->setFauxPost(array("expense_id" => $expense_id,
                                                "tax_id"     => $tax['tax_id'],
                                                "tax_type"   => $tax['type'],
                                                "tax_rate"   => $tax['tax_percentage'],
                                                "tax_amount" => $tax_amount));
                    $requests->add($request);
                }
            }
            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Expense::expense_item_tax(): Unable to process requests. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }
}
