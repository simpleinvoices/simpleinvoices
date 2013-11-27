<?php

class product
{
	public $domain_id;
    
	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

    public function count()
    {

         $sql = "SELECT count(id) as count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id ORDER BY id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id);
 
         return $sth->fetch();

    }

    public function get_all()
    {

         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id AND visible = 1 ORDER BY description, id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id);
 
         return $sth->fetchAll();

    }

    public function get($id)
    {

         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id AND id = :id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id, ':id',$id);
 
         return $sth->fetch();

    }

    public function select_all($type='', $dir, $sort, $rp, $page )
    {
        global $config;
        global $LANG;
        
        //SC: Safety checking values that will be directly subbed in
        if (intval($start) != $start) {
            $start = 0;
        }
        
        if (intval($rp) != $rp) {
            $rp = 25;
        }
        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT $start, $rp";
    
        if($type =="count")
        {
            unset($limit);
        }
        /*SQL Limit - end*/	
            
        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'DESC';
        }
        
        $query = $_POST['query'];
        $qtype = $_POST['qtype'];
        
        $where = "";
        if ($query) $where .= " AND :qtype LIKE '%:query%' ";
        
        
        /*Check that the sort field is OK*/
        $validFields = array('id','description','unit_price', 'enabled');

        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }
        
            $sql = "SELECT 
                        id, 
                        description,
                        unit_price, 
                        (SELECT COALESCE(SUM(quantity),0) FROM ".TB_PREFIX."invoice_items WHERE product_id = ".TB_PREFIX."products.id) AS qty_out ,
                        (SELECT COALESCE(SUM(quantity),0) FROM ".TB_PREFIX."inventory WHERE product_id = ".TB_PREFIX."products.id) AS qty_in ,
                        (SELECT COALESCE(reorder_level,0)) AS reorder_level ,
                        (SELECT qty_in - qty_out ) AS quantity,
                        (SELECT (CASE  WHEN enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
                    FROM 
                        ".TB_PREFIX."products  
                    WHERE 
                        visible = 1
                    AND domain_id = :domain_id
                        $where
                    ORDER BY 
                        $sort $dir 
                    $limit";
        
        
        if ($query) {
			$result = dbQuery($sql, ':domain_id', $this->domain_id, ':query', $query, ':qtype', $qtype);
		} else {
			$result = dbQuery($sql, ':domain_id', $this->domain_id);
		}

        return $result;
    }

}
