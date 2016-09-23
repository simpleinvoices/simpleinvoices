<?php
class invoice {
	
    public $id;
    public $domain_id;
	public $biller_id;
	public $customer_id;
	public $type_id;
	public $preference_id;
	public $date;
	public $note;
	public $custom_field1;
	public $custom_field2;
	public $custom_field3;
	public $custom_field4;

	public $invoice_id;
	public $quantity;
	public $product_id;
	public $unit_price;
	public $tax_amount;
	public $gross_total;
	public $description;
	public $total;
	public $attribute;
	public $tax;
	
    public $start_date;
    public $end_date;
    public $having;
    public $having_and;
    public $biller;
    public $customer;
	public $query;
	public $qtype;
    public $sort;
    public $where_field;
    public $where_value;

	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

	public function insert()
	{
		//insert in si_invoice

		global $db_server;
		
		$sql = "INSERT 
				INTO
			".TB_PREFIX."invoices (
				id, 
		 		index_id,
				domain_id,
				biller_id, 
				customer_id, 
				type_id,
				preference_id, 
				date, 
				note,
				custom_field1,
				custom_field2,
				custom_field3,
				custom_field4
			)
			VALUES
			(
				NULL,
				:index_id,
				:domain_id,
				:biller_id,
				:customer_id,
				:type_id,
				:preference_id,
				:date,
				:note,
				:custom_field1,
				:custom_field2,
				:custom_field3,
				:custom_field4
				)";

		$pref_group=getPreference($this->preference_id, $this->domain_id);

		$sth= dbQuery($sql,
			#':index_id', index::next('invoice',$pref_group['index_group'], $domain_id,$this->biller_id),
			':index_id',      index::next('invoice',$pref_group['index_group'], $this->domain_id),
			':domain_id',     $this->domain_id,
			':biller_id',     $this->biller_id,
			':customer_id',   $this->customer_id,
			':type_id',       $this->type_id,
			':preference_id', $this->preference_id,
			':date',          $this->date,
			':note',          trim($this->note),
			':custom_field1', $this->custom_field1,
			':custom_field2', $this->custom_field2,
			':custom_field3', $this->custom_field3,
			':custom_field4', $this->custom_field4
			);

	    #index::increment('invoice',$pref_group['index_group'], $domain_id,$this->biller_id);
	    index::increment('invoice',$pref_group['index_group'], $this->domain_id);

	    return lastInsertID();
	}

	public function insert_item()
	{	

		$sql = "INSERT INTO ".TB_PREFIX."invoice_items 
				(
					invoice_id, 
					domain_id, 
					quantity, 
					product_id, 
					unit_price, 
					tax_amount, 
					gross_total, 
					description, 
					total,
					attribute
				) 
				VALUES 
				(
					:invoice_id, 
					:domain_id, 
					:quantity, 
					:product_id, 
					:unit_price, 
					:tax_amount, 
					:gross_total, 
					:description, 
					:total,
					:attribute
				)";

		dbQuery($sql,
			':invoice_id',  $this->invoice_id,
			':domain_id',   $this->domain_id,
			':quantity',    $this->quantity,
			':product_id',  $this->product_id,
			':unit_price',  $this->unit_price,
			':tax_amount',  $this->tax_amount,
			':gross_total', $this->gross_total,
			':description', trim($this->description),
			':total',       $this->total,
			':attribute',   $this->attribute

			);
		$inv_item_id = lastInsertId();
		invoice_item_tax($inv_item_id, $this->tax, $this->unit_price, $this->quantity, 'insert', $this->domain_id);
		return $inv_item_id;
	}

    public function select($id, $domain_id='')
    {
		global $logger;

		if(!empty($domain_id)) $this->domain_id = $domain_id;

		$sql = "SELECT 
                    i.*,
		            i.date as date_original, 
                    (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name,
                    p.pref_inv_wording AS preference,
                    p.status
                FROM 
                    ".TB_PREFIX."invoices i LEFT JOIN 
					".TB_PREFIX."preferences p  
						ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
                WHERE 
                    i.domain_id = :domain_id 
                AND 
					i.id = :id";

		$sth = dbQuery($sql, ':id', $id, ':domain_id', $this->domain_id);

        $invoice = $sth->fetch();

	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );
	$invoice['total'] = getInvoiceTotal($invoice['id'], $domain_id);
	$invoice['gross'] = $this->getInvoiceGross($invoice['id'], $this->domain_id);
	$invoice['paid'] = calc_invoice_paid($invoice['id'], $domain_id);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	$invoice['invoice_items'] = $this->getInvoiceItems($id, $this->domain_id);

	#invoice total tax
	$sql2 ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :id AND domain_id = :domain_id";
	$sth2 = dbQuery($sql2, ':id', $id, ':domain_id', $this->domain_id);
	$result2 = $sth2->fetch();
	//$invoice['total'] = number_format($result['total'],2);
	$invoice['total_tax'] = $result2['total_tax'];
		
	$invoice['tax_grouped'] = taxesGroupedForInvoice($id);
	
	return $invoice;
    
	}

    public function get_all($domain_id='')
    {
		global $logger;

		$domain_id = domain_id::get($domain_id);

		$sql = "SELECT 
                    i.id as id,
                    (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name
                FROM 
                    ".TB_PREFIX."invoices i LEFT JOIN 
					".TB_PREFIX."preferences p  
						ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
                WHERE 
                    i.domain_id = :domain_id                    
                ORDER BY 
                    index_name";

		$sth = dbQuery($sql, ':domain_id', $domain_id);

        return $sth->fetchAll();

    }

    public function count($domain_id='')
    {
		global $logger;

		$domain_id = domain_id::get($domain_id);

		$sql = "SELECT count(id) AS count
                FROM ".TB_PREFIX."invoices 
                WHERE domain_id = :domain_id 
        ";
		$sth = dbQuery($sql, ':domain_id', $domain_id);

        return $sth;

    }
    function select_all($type='', $dir='DESC', $rp='25', $page='1', $having='')
    {
        global $config;

		$domain_id = domain_id::get($this->domain_id);
		$valid_search_fields = array('iv.index_id', 'b.name', 'c.name');

        if(empty($having)) $having = $this->having;
        $having_and = ($this->having_and) ? $this->having_and : false;

        $sort = $this->sort;

        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT ".$start.", ".$rp;
        /*SQL Limit - end*/

        /*SQL where - start*/
        $query = $this->query;
        $qtype = $this->qtype;

        $where = "";
        if (!(empty($query) || empty($qtype))) {
			if ( in_array($qtype, $valid_search_fields) ) {
				$where .= " AND $qtype LIKE :query ";
			} else {
				$this->query = $qtype = null;
				$this->qtype = $query = null;
			}
		}
        if ($this->biller)      $where .= " AND b.id = '$this->biller' ";
        if ($this->customer)    $where .= " AND c.id = '$this->customer' ";
        if ($this->where_field) $where .= " AND $this->where_field = '$this->where_value' ";
        /*SQL where - end*/
	

        /*Check that the sort field is OK*/
        $validFields = array('index_id','index_name','iv.id', 'biller', 'customer', 'invoice_total','owing','date','aging','type','preference','type_id');

        if (!in_array($sort, $validFields))
            $sort = "id";

        if(strstr($type,"count"))
        {
            //unset($limit);
            $limit="";
        }

		$sql_having = '';

        switch ($having) 
        {   
            case "date_between":
                $sql_having = "HAVING date between '$this->start_date' and '$this->end_date'";
                break;
            case "money_owed":
                $sql_having = "HAVING ( owing > 0 ) ";
				$having_and = "real";
                break;
            case "paid":
                $sql_having = "HAVING ( owing ='' )  OR ( owing < 0 )";
				$having_and = "real";
                break;
            case "draft":
                $sql_having = "HAVING ( status = 0 )";
                break;
            case "real":
                $sql_having = "HAVING ( status = 1 )";
                break;
        }

        switch ($having_and) 
        {   
            case "date_between":
                $sql_having .= "AND ( date between '$this->start_date' and '$this->end_date' )";
                break;
            case "money_owed":
                $sql_having .= "AND ( owing > 0 ) AND ( status = 1 ) ";
                break;
            case "paid":
                $sql_having .= "AND (( owing ='' ) OR ( owing < 0 )) AND ( status = 1 ) ";
                break;
            case "draft":
                $sql_having .= "AND ( status = 0 )";
                break;
            case "real":
                $sql_having .= "AND ( status = 1 )";
                break;
        }

        switch ($config->database->adapter)
        {
            case "pdo_pgsql":
               $sql = "
                SELECT
                     iv.id,
                     iv.index_id as index_id,
                     b.name AS Biller,
                     c.name AS Customer,
                     sum(ii.total) AS INV_TOTAL,
                     coalesce(SUM(ap.ac_amount), 0)  AS INV_PAID,
                     (SUM(ii.total) - coalesce(sum(ap.ac_amount), 0)) AS INV_OWING ,
                     to_char(date,'YYYY-MM-DD') AS Date ,
                     (SELECT now()::date - iv.date) AS Age,
                     (CASE WHEN now()::date - iv.date <= '14 days'::interval THEN '0-14'
                      WHEN now()::date - iv.date <= '30 days'::interval THEN '15-30'
                      WHEN now()::date - iv.date <= '60 days'::interval THEN '31-60'
                      WHEN now()::date - iv.date <= '90 days'::interval THEN '61-90'
                      ELSE '90+'
                     END) AS Aging,
                     iv.type_id As type_id,
                     p.pref_description AS type,
                     p.pref_inv_wording AS invoice_wording
                FROM
                     " . TB_PREFIX . "invoices iv
                     LEFT JOIN " . TB_PREFIX . "payment ap       ON ap.ac_inv_id = iv.id
                     LEFT JOIN " . TB_PREFIX . "invoice_items ii ON ii.invoice_id = iv.id
                     LEFT JOIN " . TB_PREFIX . "biller b         ON b.id = iv.biller_id
                     LEFT JOIN " . TB_PREFIX . "customers c      ON c.id = iv.customer_id
                     LEFT JOIN " . TB_PREFIX . "preferences p    ON p.pref_id = iv.preference_id
				WHERE iv.domain_id = :domain_id 
					$where
                GROUP BY
                    iv.id, b.name, c.name, date, age, aging, type
                ORDER BY
                    $sort $dir
                LIMIT $limit OFFSET $start";
                break;
            case "pdo_mysql":
            default:
               $sql ="
                SELECT  
                       iv.id,
                       iv.index_id as index_id,
                       b.name AS biller,
                       c.name AS customer,
                       DATE_FORMAT(date,'%Y-%m-%d') AS date,";

                $sql .="(SELECT coalesce(SUM(ii.total), 0) FROM " .
                TB_PREFIX . "invoice_items ii WHERE ii.invoice_id = iv.id AND ii.domain_id = :domain_id) AS invoice_total,
                       (SELECT coalesce(SUM(ac_amount), 0) FROM " .
                TB_PREFIX . "payment ap WHERE ap.ac_inv_id = iv.id AND ap.domain_id = :domain_id) AS INV_PAID,
                       (SELECT invoice_total - INV_PAID) As owing,
                       ";

              //only run aging for real full query ($type is empty for full query or count for count query)
               if($type == '')
               {
                    $sql .="
                       (SELECT IF((owing = 0 OR owing < 0), 0, DateDiff(now(), date))) AS Age,
                       (SELECT (CASE   WHEN Age = 0 THEN ''
                                                       WHEN Age <= 14 THEN '0-14'
                                                       WHEN Age <= 30 THEN '15-30'
                                                       WHEN Age <= 60 THEN '31-60'
                                                      WHEN Age <= 90 THEN '61-90'
                                                      ELSE '90+'  END)) AS aging,";
               } else {
                   $sql .="
                            '' as Age,
                            '' as aging,
                            ";
               }
               $sql .="iv.type_id As type_id,
                       pf.pref_description AS preference,
                       pf.status AS status,
                       (SELECT CONCAT(pf.pref_inv_wording,' ',iv.index_id)) as index_name
                FROM   " . TB_PREFIX . "invoices iv
                               LEFT JOIN " . TB_PREFIX . "biller b       ON (b.id = iv.biller_id           AND b.domain_id  = iv.domain_id)
                               LEFT JOIN " . TB_PREFIX . "customers c    ON (c.id = iv.customer_id         AND c.domain_id  = iv.domain_id)
                               LEFT JOIN " . TB_PREFIX . "preferences pf ON (pf.pref_id = iv.preference_id AND pf.domain_id = iv.domain_id)
                WHERE iv.domain_id = :domain_id
					$where
                GROUP BY
                    iv.id
				$sql_having
                ORDER BY
					$sort $dir
                $limit";
                break;
        }
        
        if (empty($query)) {
			$result =  dbQuery($sql,':domain_id', $domain_id);
		} else {
			$result =  dbQuery($sql,':domain_id', $domain_id, ':query', "%$query%");
		}
        return $result;
    }

    public function select_all_where()
    {
		global $logger;

		$domain_id = domain_id::get($this->domain_id);

        if($this->filter == "date")
        {
            $where = "AND date BETWEEN '$this->start_date' AND '$this->end_date'";
        }

		$sql = "SELECT i.*, p.pref_description AS preference FROM ".TB_PREFIX."invoices i LEFT JOIN ".TB_PREFIX."preferences p  ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id) WHERE i.domain_id = :domain_id ORDER BY i.id";
		$sth = dbQuery($sql, ':domain_id', $domain_id);

        return $sth->fetchAll();

    }

	public function getInvoiceItems($id, $domain_id='') {
	
		global $logger;
		
		if(!empty($domain_id)) $this->domain_id = $domain_id;

		$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id AND domain_id = :domain_id ORDER BY id";
		$sth = dbQuery($sql, ':id', $id, ':domain_id', $this->domain_id);
		
		$invoiceItems = null;
		
		for ($i=0; $invoiceItem = $sth->fetch(); $i++) {
		
//			$invoiceItem['quantity'] = $invoiceItem['quantity'];
//			$invoiceItem['unit_price'] = $invoiceItem['unit_price'];
//			$invoiceItem['tax_amount'] = $invoiceItem['tax_amount'];
//			$invoiceItem['gross_total'] = $invoiceItem['gross_total'];
//			$invoiceItem['total'] = $invoiceItem['total'];
			$invoiceItem['attribute_decode'] = json_decode($invoiceItem['attribute'],true);
			foreach ($invoiceItem['attribute_decode'] as $key => $value)
			{
				$invoiceItem['attribute_json'][$key]['name'] = product_attributes::getName($key);
				$invoiceItem['attribute_json'][$key]['value'] = product_attributes::getValue($key,$value);
				$invoiceItem['attribute_json'][$key]['type'] = product_attributes::getType($key);
				$invoiceItem['attribute_json'][$key]['visible'] = product_attributes::getVisible($key);
			}
			
			$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id AND domain_id = :domain_id";
			$tth = dbQuery($sql, ':id', $invoiceItem['product_id'], ':domain_id', $this->domain_id);
			$invoiceItem['product'] = $tth->fetch();	

			$tax = taxesGroupedForInvoiceItem($invoiceItem['id']);

			foreach ($tax as $key => $value)
			{
				$invoiceItem['tax'][$key] = $value['tax_id'];
				$logger->log('Invoice: '.$invoiceItem['invoice_id'].' Item id: '.$invoiceItem['id'].' Tax '.$key.' Tax ID: '.$value['tax_id'], Zend_Log::INFO);
			}
			$invoiceItems[$i] = $invoiceItem;
		}
		
		return $invoiceItems;
	}
    

    /**
    * Function: are_there_any
    * 
    * Used to see if there are any invoices in the database for a given domain
	* Called directly from index.php with invoice::are_there_any()
	* and hence cannot use the $this property
    **/
    public function are_there_any($domain_id='')
    {
		if(!empty($domain_id)) $this->domain_id = $domain_id;

		$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
		$sth = dbQuery($sql, ':domain_id', $this->domain_id);

        $count = $sth->fetch();
        return $count['count'];
    }

    /**
    * Function getInvoiceGross
    * 
    * Used to get the gross total for a given invoice number
    **/
    public function getInvoiceGross($invoice_id, $domain_id='') {
        global $LANG;
        
		if(!empty($domain_id)) $this->domain_id = $domain_id;

        $sql ="SELECT SUM(gross_total) AS gross_total FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :invoice_id AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $this->domain_id);
        $res = $sth->fetch();
        //echo "TOTAL".$res['total'];
        return $res['gross_total'];
    }
    /**
    * Function invoice::max
    * 
    * Used to get the max invoice id
	* is called directly from sql_patches.php with invoice::max()
	* and hence $this->domain_id is not usable 
    **/
    public function max($domain_id='') {

        global $logger;

        if ( getNumberOfDonePatches() < '179')
        {
            $sql ="SELECT MAX(id) AS max FROM ".TB_PREFIX."invoices";
            $sth = dbQuery($sql);
        } else {
            if(!empty($domain_id)) $this->domain_id = $domain_id;
            $sql ="SELECT MAX(id) AS max FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
            $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        }

        $count = $sth->fetch();
		$logger->log('Max Invoice: '.$count['max'], Zend_Log::INFO);
        return $count['max'];
    }

	public function recur()
	{
		$invoice = $this->select($this->id, $this->domain_id);
		$ni = new invoice();
		$ni->domain_id     = $invoice['domain_id'];
		// Next Index is obtained during insert
		// $ni->index_id     = $invoice['index_id'];
		$ni->biller_id     = $invoice['biller_id'];
		$ni->customer_id   = $invoice['customer_id'];
		$ni->type_id       = $invoice['type_id'];
		$ni->preference_id = $invoice['preference_id'];
		//$ni->date = $invoice['date_original'];
		// Use todays date
		$ni->date          = date('Y-m-d');
		$ni->custom_field1 = $invoice['custom_field1'];
		$ni->custom_field2 = $invoice['custom_field2'];
		$ni->custom_field3 = $invoice['custom_field3'];
		$ni->custom_field4 = $invoice['custom_field4'];
		$ni->note          = $invoice['note'];
		$ni_id = $ni->insert();

		//insert each line item
		$nii = new invoice();
		$nii->invoice_id = $ni_id;
		$nii->domain_id  = $ni->domain_id;

		foreach ($invoice['invoice_items'] as $k => $v)
		{
			$nii->quantity    = $v['quantity'];
			$nii->product_id  = $v['product_id'];
			$nii->unit_price  = $v['unit_price'];
			$nii->tax_amount  = $v['tax_amount'];
			$nii->gross_total = $v['gross_total'];
			$nii->description = $v['description'];
			$nii->total       = $v['total'];
			$nii->attribute   = $v['attribute'];
			$nii->tax         = $v['tax'];
			$nii_id = $nii->insert_item();
		}
		
		return $ni_id;
	}
}
