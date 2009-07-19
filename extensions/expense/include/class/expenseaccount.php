<?php

class expenseaccount
{

    public static function count()
    {

        global $dbh;
        global $auth_session;
        
        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

        return $sth->fetch();
    }
    public static function get_all()
    {
        
        global $dbh;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
        return $sth->fetchAll();
    
    }

    public static function save()
    {

        global $auth_session;
        
        $sql = "INSERT into
            ".TB_PREFIX."expense_account
            (
                domain_id,
                name
            )
        VALUES
            (	
                :domain_id,
                :name
            )";

        return dbQuery($sql,
            ':domain_id',$auth_session->domain_id,	
            ':name', $_POST['name']
            );


    }
}
