<?php
class inventory {
	
 	public $start_date;
 	public $domain_id;

	public function insert()
	{
        	global $db;
        	global $auth_session;

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
        	$sth = $db->query($sql,
				':domain_id',$domain_id, 
				':product_id',$this->product_id,
				':quantity',$this->quantity,
				':cost',$this->cost,
				':date',$this->date,
				':note',$this->note
			) or die(htmlspecialchars(end($dbh->errorInfo())));
        
 	       return $sth;

	}

	public function update()
	{
        	global $db;

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
        	$sth = $db->query($sql,
				':id',$this->id, 
				':domain_id',$domain_id, 
				':product_id',$this->product_id,
				':quantity',$this->quantity,
				':cost',$this->cost,
				':date',$this->date,
				':note',$this->note
			) or die(htmlspecialchars(end($dbh->errorInfo())));
        
 	       return $sth;
	}

	public function delete()
	{

	}

    public function select_all($type='', $dir='DESC', $rp='25', $page='1')
	{
		global $LANG;
		global $db;
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
                p.reorder_level
			FROM 
				".TB_PREFIX."products p,
				".TB_PREFIX."inventory iv
			 WHERE 
				iv.domain_id = :domain_id
				and
                p.id = iv.product_id
			GROUP BY
			    iv.id
			ORDER BY
			$sort $dir
			$limit";

		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id)) or die(htmlspecialchars(end($dbh->errorInfo())));
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
		global $db;

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
		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id), ':id',$this->id) or die(htmlspecialchars(end($dbh->errorInfo())));

		return $sth->fetch();
	}

	public function check_reorder_level()
	{
        global $db;
        global $auth_session;

        $domain_id = domain_id::get($this->domain_id);

        //sellect qty and reorder level

        $inventory = new inventory();
        $inventory_all = $inventory->select_all('count');

        foreach ($inventory_all as $row) 
        {
             if($row['quantity'] <= $row['reorder_level'])
             {

                $return[$row['id']]['message'] = "The quantity of Product: ".$row['description']." is below the reorder level of ".$row['reorder_level'];
                 
             }

        }

        print_r($return);
        #$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
        $email = new email();

            $email_body = new email_body();
            $email_body->email_type = 'cron_invoice';
            $email_body->customer_name = $customer['name'];
            $email_body->invoice_name = $invoice['index_name'];
            $email_body->biller_name = $biller['name'];
        
        $email -> notes = print_r($return);
        $email -> from = "simpleinvoices@localhost";
        $email -> to = "justin@localhost";
        $email -> subject = "Simple Invoices reorder level email";
        $email -> send ();

        return $return;
        
    }

}
