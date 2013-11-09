<?php

class expenseaccount
{
	public $domain_id;
    
	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

    public function count()
    {

        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id);

        return $sth->fetch();
    }

    public function get_all()
    {
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id);
        
        return $sth->fetchAll();
    
    }

    public function select($id)
    {
        
        $sql = "SELECT * FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id and id = :id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id, ':id', $id);
        
        return $sth->fetch();
    
    }

    public function insert()
    {

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
            ':domain_id',$this->domain_id,	
            ':name', $_POST['name']
            );
    }

    public function update()
    {

        $sql = "UPDATE
            ".TB_PREFIX."expense_account
                SET
                    name = :name
                WHERE
                    id = :id
                    AND
                    domain_id = :domain_id
            ";

        return dbQuery($sql,
            ':id',$_GET['id'],	
            ':domain_id',$this->domain_id,	
            ':name', $_POST['name']
            );

    }

}
