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
        global $db;
        $sql = "SELECT count(id) as count FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = $db->query($sql,':domain_id',$this->domain_id);

        return $sth->fetch();
    }

    public function get_all()
    {
        global $db;
        $sql = "SELECT * FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id ORDER BY id";
        $sth  = $db->query($sql,':domain_id',$this->domain_id);
        
        return $sth->fetchAll();
    
    }

    public function select($id)
    {
        global $db;
        $sql = "SELECT * FROM ".TB_PREFIX."expense_account WHERE domain_id = :domain_id and id = :id";
        $sth  = $db->query($sql,':domain_id',$this->domain_id, ':id', $id);
        
        return $sth->fetch();
    
    }

    public function insert()
    {
        global $db;
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

        return $db->query($sql,
            ':domain_id',$this->domain_id,	
            ':name', $_POST['name']
            );
    }

    public function update()
    {
        global $db;
        $sql = "UPDATE
            ".TB_PREFIX."expense_account
                SET
                    name = :name
                WHERE
                    id = :id
                    AND
                    domain_id = :domain_id
            ";

        return $db->query($sql,
            ':id',$_GET['id'],	
            ':domain_id',$this->domain_id,	
            ':name', $_POST['name']
            );

    }

}
