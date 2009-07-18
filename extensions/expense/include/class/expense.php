<?php

class expense
{

    public static function count()
    {

        global $db;
        global $auth_session;
        
        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth = $db->query($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

        return $sth->fetch();
    }
    public static function get_all()
    {
        
        global $db;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth  = $db->query($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
        return $sth->fetchAll();
    
    }

    public static function add()
    {
        //get customers
        $add['expense_account_all'] = expenseaccount::get_all();
        //get customers
        $add['customer_all'] = customer::get_all();
        //get billers
        $add['biller_all'] = biller::get_all();
        //get invoices
        $add['invoice_all'] = invoice::get_all();
        //get products
        $add['product_all'] = product::get_all();

        return $add;

    }
    public static function get($id)
    {
        
        global $db;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id and id = :id";
        $sth  = $db->query($sql,':domain_id',$auth_session->domain_id ,':id',$id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
        return $sth->fetch();
    
    }

    public static function detail()
    {
        //get customers
        $detail['expense_account_all'] = expenseaccount::get_all();
        //get customers
        $detail['customer'] = customer::get();

        $detail['customer_all'] = customer::get_all();
        //get billers
        $detail['biller_all'] = biller::get_all();
        //get invoices
        $detail['invoice_all'] = invoice::get_all();
        //get products
        $detail['product_all'] = product::get_all();

        return $detail;

    }

    public static function save()
    {

        global $auth_session;
        
        $sql = "INSERT into
            ".TB_PREFIX."expense
            (
                domain_id,
                amount,
                expense_account_id,
                biller_id,
                customer_id,
                invoice_id,
                product_id,
                date,
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
                :note
            )";

        return dbQuery($sql,
            ':domain_id',$auth_session->domain_id,	
            ':amount', $_POST['amount'],
            ':expense_account_id', $_POST['expense_account_id'],
            ':biller_id', $_POST['biller_id'],
            ':invoice_id', $_POST['invoice_id'],
            ':product_id', $_POST['product_id'],
            ':customer_id', $_POST['customer_id'],
            ':date', $_POST['date'],
            ':note', $_POST['note']
            );
	

        expense::expense_item_tax(lastInsertId(),$line_item_tax_id,$unit_price,$quantity,"insert");

    }

    public static function update()
    {

        global $db;
        global $auth_session;
        
        $sql = "UPDATE
            ".TB_PREFIX."expense
                SET
                    amount = :amount,
                    expense_account_id = :expense_account_id,
                    biller_id = :biller_id,
                    customer_id = :customer_id,
                    invoice_id = :invoice_id,
                    product_id = :product_id,
                    date = :date,
                    note = :note
                WHERE
                    id = :id
                    AND
                    domain_id = :domain_id
            ";

        return $db->query($sql,
            ':id',$_POST['id'],	
            ':domain_id',$auth_session->domain_id,	
            ':amount', $_POST['amount'],
            ':expense_account_id', $_POST['expense_account_id'],
            ':biller_id', $_POST['biller_id'],
            ':invoice_id', $_POST['invoice_id'],
            ':product_id', $_POST['product_id'],
            ':customer_id', $_POST['customer_id'],
            ':date', $_POST['date'],
            ':note', $_POST['note']
            );

    }

    /*
    Function: invoice_item_tax
    Purpose: insert/update the multiple taxes per line item into the si_invoice_item_tax table
    */
    public static function expense_item_tax($invoice_item_id,$line_item_tax_id,$unit_price,$quantity,$action="") {
        
        global $logger;

        //if editing invoice delete all tax info then insert first then do insert again
        //probably can be done without delete - someone to look into this if required - TODO
        if ($action =="update")
        {

            $sql_delete = "DELETE from
                                ".TB_PREFIX."expense_item_tax
                           WHERE
                                espencse_item_id = :expense_item_id";
            $logger->log("Expense item: ".$invoice_item_id." tax lines deleted", Zend_Log::INFO);

            dbQuery($sql_delete,':expense_item_id',$invoice_item_id);


        }

        foreach($line_item_tax_id as $key => $value) 
        {
            if($value !== "")
            {
                $tax = getTaxRate($value);

                $logger->log("ITEM :: Key: ".$key." Value: ".$value, Zend_Log::INFO);
                $logger->log('ITEM :: tax rate: '.$tax['tax_percentage'], Zend_Log::INFO);

                $tax_amount = lineItemTaxCalc($tax,$unit_price,$quantity);
                //get Total tax for line item
                $tax_total = $tax_total + $tax_amount;

                $logger->log('ITEM :: Qty: '.$quantity.' Unit price: '.$unit_price, Zend_Log::INFO);
                $logger->log('ITEM :: Tax rate: '.$tax[tax_percentage].' Tax type: '.$tax['type'].' Tax $: '.$tax_amount, Zend_Log::INFO);

                $sql = "INSERT 
                            INTO 
                        ".TB_PREFIX."expense_item_tax 
                        (
                            expense_item_id, 
                            tax_id, 
                            tax_type, 
                            tax_rate, 
                            tax_amount
                        ) 
                        VALUES 
                        (
                            :expense_item_id, 
                            :tax_id,
                            :tax_type,
                            :tax_rate,
                            :tax_amount
                        )";

                dbQuery($sql,
                    ':expense_item_id', $expense_item_id,
                    ':tax_id', $tax[tax_id],
                    ':tax_type', $tax[type],
                    ':tax_rate', $tax[tax_percentage],
                    ':tax_amount', $tax_amount
                    );
            }
        }
        //TODO fix this
        return true;
    }
}

?>
