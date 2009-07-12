<?php

class expense
{

    public static function count()
    {

        global $dbh;
        global $auth_session;
        
        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

        return $sth->fetch();
    }
    public static function get_all()
    {
        
        global $dbh;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
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

        return $add;

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
                :date,
                :note
            )";

        return dbQuery($sql,
            ':domain_id',$auth_session->domain_id,	
            ':amount', $_POST['amount'],
            ':expense_account_id', $_POST['expense_account_id'],
            ':biller_id', $_POST['biller_id'],
            ':invoice_id', $_POST['invoice_id'],
            ':customer_id', $_POST['customer_id'],
            ':date', $_POST['date'],
            ':note', $_POST['note']
            );

    }
}

?>
