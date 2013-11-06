<?php
class inventory {
	
 	public $start_date;
 	public $domain_id;

	public function insert()
	{

		$domain_id = domain_id::get($this->domain_id);

        
	        $sql = "INSERT INTO ".TB_PREFIX."inventory (
				domain_id,
				product_id,
				quantity,
				cost,
				date,
				note
			) VALUES (
				:domain_id,
				:product_id,
				:quantity,
				:cost,
				:date,
				:note
			)";
        	$sth = dbQuery($sql,
				':domain_id',$domain_id, 
				':product_id',$this->product_id,
				':quantity',$this->quantity,
				':cost',$this->cost,
				':date',$this->date,
				':note',$this->note
			);
        
 	       return $sth;

	}

	public function update()
	{

		$domain_id = domain_id::get($this->domain_id);
        
	        $sql = "UPDATE 
				".TB_PREFIX."inventory
			SET 
				product_id = :product_id,
				quantity = :quantity,
				cost = :cost,
				date = :date,
				note = :note
			WHERE 
				id = :id 
				AND 
				domain_id = :domain_id
			";
        	$sth = dbQuery($sql,
				':id',$this->id, 
				':domain_id',$domain_id, 
				':product_id',$this->product_id,
				':quantity',$this->quantity,
				':cost',$this->cost,
				':date',$this->date,
				':note',$this->note
			);
        
 	       return $sth;
	}

	public function delete()
	{

	}

    public function select_all($type='', $dir='DESC', $rp='25', $page='1')
	{
		global $LANG;

		/*SQL Limit - start*/
		$start = (($page-1) * $rp);
		$limit = "LIMIT ".$start.", ".$rp;
		/*SQL Limit - end*/

		/*SQL where - start*/
		$query = (isset($_POST['query'])) ? $_POST['query'] : "" ;
		$qtype = (isset($_POST['qtype'])) ? $_POST['qtype'] : "" ;

		$where = (isset($_POST['query'])) ? "  AND $qtype LIKE '%$query%' " : "";
		/*SQL where - end*/
		

		/*Check that the sort field is OK*/
		if (!empty($this->sort)) {
		    $sort = $this->sort;
		} else {
		    $sort = "id";
		}

		if($type =="count")
		{
		    //unset($limit);
		    $limit="";
		}


		$sql = "SELECT
				iv.id as id,
				iv.product_id ,
				iv.date ,
				iv.quantity ,
                p.description,
                (select coalesce(p.reorder_level,0) as reorder_level),
				iv.cost,
				iv.quantity * iv.cost as total_cost
			FROM 
				".TB_PREFIX."products p,
				".TB_PREFIX."inventory iv
			 WHERE 
				iv.domain_id = :domain_id
				and
                p.id = iv.product_id
			$where
			GROUP BY
			    iv.id
			ORDER BY
			$sort $dir
			$limit";

		$sth = dbQuery($sql,':domain_id',domain_id::get($this->domain_id));
		if($type =="count")
		{
			return $sth->rowCount();
		} else {
			return $sth->fetchAll();
		}
	}

	public function select()
	{
		global $LANG;

		$sql = "SELECT
				iv.*,
                p.description
			FROM 
				".TB_PREFIX."products p,
				".TB_PREFIX."inventory iv
			 WHERE 
				iv.domain_id = :domain_id
				and
                p.id = iv.product_id
				and
                iv.id = :id;";
		$sth = dbQuery($sql,':domain_id',domain_id::get($this->domain_id), ':id',$this->id);

		return $sth->fetch();
	}



	public function check_reorder_level()
	{
        global $db;
        global $auth_session;

        $domain_id = domain_id::get($this->domain_id);

        //sellect qty and reorder level

        $inventory = new inventory();
        $sth = $inventory->select_all('count');

        $inventory_all = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $email="";
        foreach ($inventory_all as $row) 
        {
             if($row['quantity'] <= $row['reorder_level'])
             {

                $message = "The quantity of Product: ".$row['description']." is ".siLocal::number($row['quantity']).", which is equal to or below its reorder level of ".$row['reorder_level'];
                $return['row_'.$row['id']]['message'] = $message;
                $email_message .= $message . "<br />\n";
             }

        }

        //print_r($return);
        #$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
        $email = new email();
        $email -> notes = $email_message;
        $email -> from = $email->get_admin_email();
        $email -> to = $email->get_admin_email();
        #$email -> bcc = "justin@localhost";
        $email -> subject = "Simple Invoices reorder level email";
        $email -> send ();

        return $return;
        
    }

}
