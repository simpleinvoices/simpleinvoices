<?php

class expense {
    public $domain_id;

    public function __construct() {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    public function count() {
        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        return $sth->fetch();
    }
    public function get_all() {
        $sql = "SELECT * FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id);
        return $sth->fetchAll();
    }

    public function add() {
        $add = array();
        //get expenseaccount
        $expenseaccountobj = new expenseaccount();
        $add['expense_account_all'] = $expenseaccountobj->get_all();

        //get customers with domain_id from session by constructor
        $customerobj = new customer();
        $add['customer_all'] = $customerobj->get_all();

        //get billers with domain_id from session by constructor
        $billerobj = new biller();
        $add['biller_all'] = $billerobj->get_all();

        //get invoices
        $invoiceobj = new invoice();
        $add['invoice_all'] = $invoiceobj->get_all();

        //get products
        $productobj = new product();
        $add['product_all'] = $productobj->get_all();

        return $add;
    }

    public function get($id) {
        $sql = "SELECT * FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id and id = :id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id ,':id',$id);
        return $sth->fetch();
    }

    public function detail() {
        $detail = array();
        //get expenseaccount
        $expenseaccountobj = new expenseaccount();
        $detail['expense_account_all'] = $expenseaccountobj->get_all();

        //get customers with domain_id from session by constructor
        $customerobj = new customer();
        $detail['customer']     = $customerobj->get();
        $detail['customer_all'] = $customerobj->get_all();

        //get billers with domain_id from session by constructor
        $billerobj = new biller();
        $detail['biller_all'] = $billerobj->get_all();

        //get invoices
        $invoiceobj = new invoice();
        $detail['invoice_all'] = $invoiceobj->get_all();

        //get products
        $productobj = new product();
        $detail['product_all'] = $productobj->get_all();

        return $detail;
    }

    public function save() {
        global $logger;
        // @formatter:off
        $sql = "INSERT into ".TB_PREFIX."expense
            (
                domain_id,
                amount,
                expense_account_id,
                biller_id,
                customer_id,
                invoice_id,
                product_id,
                date,
                status,
                note
            )
        VALUES
            (
                :domain_id,
                :amount,
                :expense_account_id,
                :biller_id,
                :customer_id,
                :invoice_id,
                :product_id,
                :date,
                :status,
                :note
            )";

        dbQuery($sql,
            ':domain_id'         ,$this->domain_id,
            ':amount'            , $_POST['amount'],
            ':expense_account_id', $_POST['expense_account_id'],
            ':biller_id'         , $_POST['biller_id'],
            ':invoice_id'        , $_POST['invoice_id'],
            ':product_id'        , $_POST['product_id'],
            ':customer_id'       , $_POST['customer_id'],
            ':date'              , $_POST['date'],
            ':status'            , $_POST['status'],
            ':note'              , $_POST['note']
            );
        // @formatter:on
        $logger->log("Exp ITEM tax- last insert ID-".lastInsertId(), Zend_Log::INFO);
        $this->expense_item_tax(lastInsertId(),$_POST['tax_id'][0],$_POST['amount'],"1","insert");
        return true;
    }

    public function update() {
        // @formatter:off
        $sql = "UPDATE ".TB_PREFIX."expense
                SET
                    amount = :amount,
                    expense_account_id = :expense_account_id,
                    biller_id = :biller_id,
                    customer_id = :customer_id,
                    invoice_id = :invoice_id,
                    product_id = :product_id,
                    date = :date,
                    status = :status,
                    note = :note
                WHERE
                    id = :id
                    AND
                    domain_id = :domain_id
            ";
        dbQuery($sql,
                ':id'                , $_POST['id'],
                ':domain_id'         , $this->domain_id,
                ':amount'            , $_POST['amount'],
                ':expense_account_id', $_POST['expense_account_id'],
                ':biller_id'         , $_POST['biller_id'],
                ':invoice_id'        , $_POST['invoice_id'],
                ':product_id'        , $_POST['product_id'],
                ':customer_id'       , $_POST['customer_id'],
                ':date'              , $_POST['date'],
                ':status'            , $_POST['status'],
                ':note'              , $_POST['note']
                );
        // @formatter:on
        $this->expense_item_tax($_POST['id'],$_POST['tax_id'][0],$_POST['amount'],"1","update");

        return true;
    }

    /*  Function: invoice_item_tax
     *  Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
     */
    public function expense_item_tax($expense_id,$line_item_tax_id,$unit_price,$quantity,$action="") {
        global $logger;
        $logger->log("Exp ITEM :: Key: ".$key." Value: ".$value, Zend_Log::INFO);

        //if editing invoice delete all tax info then insert first then do insert again
        //probably can be done without delete - someone to look into this if required - TODO
        if ($action =="update") {
            $sql_delete = "DELETE from ".TB_PREFIX."expense_item_tax
                           WHERE expense_id = :expense_id";
            dbQuery($sql_delete,':expense_id',$expense_id);

            $logger->log("Expense item: ".$expense_id." tax lines deleted", Zend_Log::INFO);
        }

        foreach($line_item_tax_id as $value) {
            if($value !== "") {
                $tax = getTaxRate($value);

                $logger->log("Expense - item tax :: Key: ".$key." Value: ".$value, Zend_Log::INFO);
                $logger->log('Expense - item tax :: tax rate: '.$tax['tax_percentage'], Zend_Log::INFO);

                $tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
                //get Total tax for line item
                // $tax_total = $tax_total + $tax_amount;

                $logger->log('Expense - item tax :: Qty: '.$quantity.' Unit price: '.$unit_price, Zend_Log::INFO);
                $logger->log('Expense - item tax :: Tax rate: '.$tax[tax_percentage].' Tax type: '.$tax['type'].' Tax $: '.$tax_amount, Zend_Log::INFO);

                // @formatter:off
                $sql = "INSERT INTO ".TB_PREFIX."expense_item_tax
                        (
                            expense_id,
                            tax_id,
                            tax_type,
                            tax_rate,
                            tax_amount
                        )
                        VALUES
                        (
                            :expense_id,
                            :tax_id,
                            :tax_type,
                            :tax_rate,
                            :tax_amount
                        )";

                dbQuery($sql,
                    ':expense_id', $expense_id,
                    ':tax_id'    , $tax[tax_id],
                    ':tax_type'  , $tax[type],
                    ':tax_rate'  , $tax[tax_percentage],
                    ':tax_amount', $tax_amount
                    );
                // @formatter:on
            }
        }
        //TODO fix this
        return true;
    }
}
