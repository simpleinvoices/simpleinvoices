<?php

class expensetax
{
	public $domain_id;
    
	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

    public function get_all($expense_id)
    {
        global $db;
        $sql = "SELECT * FROM ".TB_PREFIX."expense_item_tax 
				WHERE  expense_id = :expense_id 
				ORDER BY id";
        $sth  = $db->query($sql,':expense_id',$expense_id );
        
        return $sth->fetchAll();
    
    }

    public function get_sum($expense_id)
    {
        global $db;
        $sql = "SELECT SUM(tax_amount) AS sum 
				FROM ".TB_PREFIX."expense_item_tax 
				WHERE  expense_id = :expense_id ORDER BY id";
        $sth  = $db->query($sql,':expense_id',$expense_id );
        
        return $sth->fetchColumn();
    
    }

    function grouped($expense_id)
    {
        global $db;
        $sql = "SELECT 
                      t.tax_description AS tax_name 
                    , SUM(et.tax_amount) AS tax_amount
                    , COUNT(*) AS count
                FROM 
                    ".TB_PREFIX."expense_item_tax et 
					INNER JOIN ".TB_PREFIX."expense e 
						ON (e.id = et.expense_id)
					INNER JOIN ".TB_PREFIX."tax t 
						ON (t.tax_id = et.tax_id AND t.domain_id = e.domain_id)
                WHERE 
                    e.id = :expense_id
				AND e.domain_id = :domain_id
                GROUP BY 
                    t.tax_id;";
        $sth = $db->uery($sql, ':expense_id', $expense_id, ':domain_id', $this->domain_id);
        $result = $sth->fetchAll();

        return $result;

    }

}

