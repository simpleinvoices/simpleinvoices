<?php

class expensetax
{
    public static function get_all($expense_id)
    {
        
        global $db;
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense_item_tax WHERE  expense_id = :expense_id order by id";
        $sth  = $db->query($sql,':expense_id',$expense_id ) or die(htmlsafe(end($dbh->errorInfo())));
        
        return $sth->fetchAll();
    
    }

    public static function get_sum($expense_id)
    {
        
        global $db;
        
        $sql = "SELECT sum(tax_amount) as sum FROM ".TB_PREFIX."expense_item_tax WHERE  expense_id = :expense_id order by id";
        $sth  = $db->query($sql,':expense_id',$expense_id ) or die(htmlsafe(end($dbh->errorInfo())));
        
        return $sth->fetchColumn();
    
    }

    function grouped($expense_id)
    {
        $sql = "select 
                    t.tax_description as tax_name, 
                    sum(et.tax_amount) as tax_amount,
                    count(*) as count
                from 
                    si_expense_item_tax et, 
                    si_expense e,
                    si_tax t 
                where 
                    e.id = et.expense_id 
                AND 
                    t.tax_id = et.tax_id 
                AND
                    e.id = :expense_id
                GROUP BY 
                    t.tax_id;";
        $sth = dbQuery($sql, ':expense_id', $expense_id) or die(htmlsafe(end($dbh->errorInfo())));
        $result = $sth->fetchAll();

        return $result;

    }

}

