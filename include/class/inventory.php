<?php
class inventory {
	
 	public $start_date;
 	public $domain_id;

	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

	public function insert()
	{
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
				':domain_id',$this->domain_id, 
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
			AND domain_id = :domain_id
			";
        	$sth = dbQuery($sql,
				':id',$this->id, 
				':domain_id',$this->domain_id, 
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
		$valid_search_fields = array('p.description', 'inv.date', 'inv.quantity', 'inv.cost', 'inv.quantity * inv.cost');

		// LIMIT/OFFSET and ORDER BY identifiers cannot be bound as SQL values.
		// Normalize pagination and map sort keys to fixed SQL expressions before
		// interpolating them into the query.
		$rp = filter_var(is_scalar($rp) ? $rp : null, FILTER_VALIDATE_INT);
		$rp = ($rp === false || $rp < 1) ? 25 : min($rp, 500);
		$page = filter_var(is_scalar($page) ? $page : null, FILTER_VALIDATE_INT);
		$page = ($page === false || $page < 1) ? 1 : min($page, 1000000);
		$start = ($page - 1) * $rp;

		$sortFields = array(
			'id'          => 'inv.id',
			'date'        => 'inv.date',
			'description' => 'p.description',
			'quantity'    => 'inv.quantity',
			'cost'        => 'inv.cost',
			'total_cost'  => '(inv.quantity * inv.cost)',
		);
		$sortKey = is_string($this->sort) ? $this->sort : '';
		$sort = $sortFields[$sortKey] ?? 'inv.id';
		$dir = (is_scalar($dir) && strtoupper((string) $dir) === 'ASC') ? 'ASC' : 'DESC';
		$limit = ($type === 'count') ? '' : " LIMIT $rp OFFSET $start";

		/*SQL where - start*/
		$where = "";
		$query = isset($_POST['query']) && is_scalar($_POST['query']) ? (string) $_POST['query'] : null;
		$qtype = isset($_POST['qtype']) && is_scalar($_POST['qtype']) ? (string) $_POST['qtype'] : null;
		if ( ! (empty($qtype) || empty($query)) ) {
			if ( is_string($qtype) && in_array($qtype, $valid_search_fields, true) ) {
				$where = " AND $qtype LIKE :query ";
			} else {
				$qtype = null;
				$query = null;
			}
		}
		/*SQL where - end*/
		

		$select = ($type === 'count')
			? 'COUNT(*) AS total'
			: 'inv.id AS id, inv.product_id, inv.date, p.description, COALESCE(p.reorder_level, 0) AS reorder_level, inv.quantity, inv.cost, inv.quantity * inv.cost AS total_cost';
		$orderBy = ($type === 'count') ? '' : "ORDER BY $sort $dir";

		$sql = "SELECT $select
			FROM
				".TB_PREFIX."products p
				LEFT JOIN ".TB_PREFIX."inventory inv
					ON (p.id = inv.product_id AND p.domain_id = inv.domain_id)
			WHERE
				inv.domain_id = :domain_id
				$where
			$orderBy
			$limit";

		if (empty($query)) {
			$sth = dbQuery($sql, ':domain_id', $this->domain_id);
		} else {
			$sth = dbQuery($sql, ':domain_id', $this->domain_id, ':query', "%$query%");
		}

		return ($type === 'count') ? (int) $sth->fetchColumn() : $sth->fetchAll();
	}

	public function select()
	{
		global $LANG;

		$sql = "SELECT
				iv.*,
                p.description
			FROM 
				".TB_PREFIX."products p
				LEFT JOIN ".TB_PREFIX."inventory iv 
					ON (p.id = iv.product_id AND p.domain_id = iv.domain_id)
			WHERE 
				iv.domain_id = :domain_id
			AND iv.id = :id;";
		$sth = dbQuery($sql, ':domain_id', $this->domain_id, ':id', $this->id);

		return $sth->fetch();
	}



	public function check_reorder_level()
	{
        //select qty and reorder level

        $inventory = new product();
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
