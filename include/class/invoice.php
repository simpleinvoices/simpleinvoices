<?php
class invoice {
	
    public $start_date;
    public $end_date;
    public $having;
    public $having_and;
    public $having_and2;
    public $biller;
    public $customer;
    public $sort;
    public $where_field;
    public $where_value;

	public function insert()
	{
		//insert in si)invoice

		global $dbh;
		global $db_server;
		global $auth_session;
		
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

		$pref_group=getPreference($this->preference_id);

		$sth= dbQuery($sql,
			':index_id', index::next('invoice',$pref_group[index_group],$this->biller_id),
			':domain_id', $auth_session->domain_id,
			':biller_id', $this->biller_id,
			':customer_id', $this->customer_id,
			':type_id', $this->type_id,
			':preference_id', $this->preference_id,
			':date', $this->date,
			':note', $this->note,
			':custom_field1', $this->custom_field1,
			':custom_field2', $this->custom_field2,
			':custom_field3', $this->custom_field3,
			':custom_field4', $this->custom_field4
			);

	    index::increment('invoice',$pref_group[index_group],$this->biller_id);

	    //return $sth;
	    return lastInsertID();
		//insert into si_invoice_items

		//insert into 

	}

	public function insert_item()
	{	
		$sql = "INSERT INTO ".TB_PREFIX."invoice_items 
				(
					invoice_id, 
					quantity, 
					product_id, 
					unit_price, 
					tax_amount, 
					gross_total, 
					description, 
					total
				) 
				VALUES 
				(
					:invoice_id, 
					:quantity, 
					:product_id, 
					:unit_price, 
					:tax_amount, 
					:gross_total, 
					:description, 
					:total
				)";

		//echo $sql;
		dbQuery($sql,
			':invoice_id', $this->invoice_id,
			':quantity', $this->quantity,
			':product_id', $this->product_id,
			':unit_price', $this->unit_price,
		//	':tax_id', $tax[tax_id],
		//	':tax_percentage', $tax[tax_percentage],
			':tax_amount', $this->tax_amount,
			':gross_total', $this->gross_total,

			':description', $this->description,
			':total', $this->total

			);

		invoice_item_tax(lastInsertId(),$this->tax,$this->unit_price,$this->quantity,"insert");
	}

    public static function select($id)
    {
		global $logger;
		global $db;
	    global $auth_session;

		$sql = "SELECT 
                    i.*,
		            i.date as date_original, 
                    (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name,
                    p.pref_inv_wording AS preference,
                    p.status
                FROM 
                    ".TB_PREFIX."invoices i, 
                    ".TB_PREFIX."preferences p 
                WHERE 
                    i.domain_id = :domain_id 
                    and
                    i.preference_id = p.pref_id
                    and 
                    i.id = :id";
		$sth = $db->query($sql, ':id', $id, ':domain_id', $auth_session->domain_id);

        $invoice = $sth->fetch();

	$invoice['calc_date'] = date('Y-m-d', strtotime( $invoice['date'] ) );
	$invoice['date'] = siLocal::date( $invoice['date'] );
	$invoice['total'] = getInvoiceTotal($invoice['id']);
	$invoice['gross'] = invoice::getInvoiceGross($invoice['id']);
	$invoice['paid'] = calc_invoice_paid($invoice['id']);
	$invoice['owing'] = $invoice['total'] - $invoice['paid'];

	$invoice['invoice_items'] = invoice::getInvoiceItems($id);

	#invoice total tax
	$sql2 ="SELECT SUM(tax_amount) AS total_tax, SUM(total) AS total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :id";
	$sth2 = dbQuery($sql2, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
	$result2 = $sth2->fetch();
	//$invoice['total'] = number_format($result['total'],2);
	$invoice['total_tax'] = $result2['total_tax'];
		
	$invoice['tax_grouped'] = taxesGroupedForInvoice($id);
	
	return $invoice;
    
	}

    public static function get_all()
    {
		global $logger;
	    global $auth_session;

		$sql = "SELECT 
                    i.id as id,
                    (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name
                FROM 
                    ".TB_PREFIX."invoices i, 
                    ".TB_PREFIX."preferences p 
                WHERE 
                    i.domain_id = :domain_id 
                    and
                    i.preference_id = p.pref_id
                order by 
                    index_name";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        return $sth->fetchAll();

    }

    function select_all($type='', $dir='DESC', $rp='25', $page='1', $having='')
    {
        global $config;
        global $auth_session;

        if(empty($having))
        {
            $having = $this->having;
        }

        if ($this->having_and) $having_and  = $this->having_and;
        $sort = $this->sort;

        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT ".$start.", ".$rp;
        /*SQL Limit - end*/

        /*SQL where - start*/
        $query = $this->query;
        $qtype = $this->qtype;

        $where = " WHERE iv.domain_id = :domain_id ";
        if ($query) $where = " WHERE iv.domain_id = :domain_id AND $qtype LIKE '%$query%' ";
        if ($this->biller) $where .= " AND b.id = '$this->biller' ";
        if ($this->customer) $where .= " AND c.id = '$this->customer' ";
        if ($this->where_field) $where .= " AND $this->where_field = '$this->where_value' ";
        /*SQL where - end*/
	

        /*Check that the sort field is OK*/
        $validFields = array('index_name','iv.id', 'biller', 'customer', 'invoice_total','owing','date','aging','type','preference','type_id');

        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }

        if($type =="count")
        {
            //unset($limit);
            $limit="";
        }

        switch ($having) 
        {   
            case "date_between":
                $sql_having = "HAVING date between '$this->start_date' and '$this->end_date'";
                break;
            case "money_owed":
                $sql_having = "HAVING ( owing > 0 ) ";
                break;
            case "paid":
                $sql_having = "HAVING ( owing ='' )  OR ( owing < 0 )";
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
                $sql_having .= "AND ( owing > 0 ) ";
                break;
            case "paid":
                $sql_having .= "AND ( owing ='' ) OR ( owing < 0 )";
                break;
            case "draft":
                $sql_having .= "AND ( status = 0 )";
                break;
            case "real":
                $sql_having .= "AND ( status = 1 )";
                break;
        }

        switch ($having_and2) 
        {   
            case "date_between":
                $sql_having .= "AND ( date between '$this->start_date' and '$this->end_date' )";
                break;
            case "money_owed":
                $sql_having .= "AND ( owing > 0 ) ";
                break;
            case "paid":
                $sql_having .= "AND ( owing ='' ) OR ( owing < 0 )";
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
                     p.pref_description AS Type,
                     p.pref_inv_wording AS invoice_wording
                FROM
                     " . TB_PREFIX . "invoices iv
                     LEFT JOIN " . TB_PREFIX . "payment ap ON ap.ac_inv_id = iv.id
                     LEFT JOIN " . TB_PREFIX . "invoice_items ii ON ii.invoice_id = iv.id
                     LEFT JOIN " . TB_PREFIX . "biller b ON b.id = iv.biller_id
                     LEFT JOIN " . TB_PREFIX . "customers c ON c.id = iv.customer_id
                     LEFT JOIN " . TB_PREFIX . "preferences p ON p.pref_id = iv.preference_id
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
                       (SELECT coalesce(SUM(ii.total), 0) FROM " .
                TB_PREFIX . "invoice_items ii WHERE ii.invoice_id = iv.id) AS invoice_total,
                       (SELECT coalesce(SUM(ac_amount), 0) FROM " .
                TB_PREFIX . "payment ap WHERE ap.ac_inv_id = iv.id) AS INV_PAID,
                       (SELECT (coalesce(invoice_total,0) - coalesce(INV_PAID,0))) As owing,
                       DATE_FORMAT(date,'%Y-%m-%d') AS date,
                       (SELECT IF((owing = 0 OR owing < 0), 0, DateDiff(now(), date))) AS Age,
                       (SELECT (CASE   WHEN Age = 0 THEN ''
                                                       WHEN Age <= 14 THEN '0-14'
                                                       WHEN Age <= 30 THEN '15-30'
                                                       WHEN Age <= 60 THEN '31-60'
                                                      WHEN Age <= 90 THEN '61-90'
                                                       ELSE '90+'  END)) AS aging,
                       iv.type_id As type_id,
                       pf.pref_description AS preference,
                       pf.status AS status,
                       (SELECT CONCAT(pf.pref_inv_wording,' ',iv.index_id)) as index_name
                FROM   " . TB_PREFIX . "invoices iv
                               LEFT JOIN " . TB_PREFIX . "biller b ON b.id = iv.biller_id
                               LEFT JOIN " . TB_PREFIX . "customers c ON c.id = iv.customer_id
                               LEFT JOIN " . TB_PREFIX . "preferences pf ON pf.pref_id = iv.preference_id
                $where
                GROUP BY
                    iv.id
                $sql_having
                ORDER BY
                $sort $dir
                $limit";
                break;
        }
        
        $result =  dbQuery($sql,':domain_id', $auth_session->domain_id) or die(end($dbh->errorInfo()));
        return $result;
    }

    public function select_all_where()
    {
		global $logger;
	    global $auth_session;
        if($this->filter == "date")
        {
            $where = "and date between '$this->start_date' and '$this->end_date'";
        }

		$sql = "SELECT i.*, p.pref_description as preference FROM ".TB_PREFIX."invoices i,".TB_PREFIX."preferences p  WHERE i.domain_id = :domain_id and i.preference_id = p.pref_id  order by i.id";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        return $sth->fetchAll();

    }

	public static function getInvoiceItems($id) {
	
		global $logger;
		$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id order by id";
		$sth = dbQuery($sql, ':id', $id);
		
		$invoiceItems = null;
		
		for($i=0;$invoiceItem = $sth->fetch();$i++) {
		
			$invoiceItem['quantity'] = $invoiceItem['quantity'];
			$invoiceItem['unit_price'] = $invoiceItem['unit_price'];
			$invoiceItem['tax_amount'] = $invoiceItem['tax_amount'];
			$invoiceItem['gross_total'] = $invoiceItem['gross_total'];
			$invoiceItem['total'] = $invoiceItem['total'];
			
			$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id";
			$tth = dbQuery($sql, ':id', $invoiceItem['product_id']) or die(htmlsafe(end($dbh->errorInfo())));
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
    **/
    public static function are_there_any()
    {
	    global $auth_session;

		$sql = "SELECT count(*) as count FROM ".TB_PREFIX."invoices where domain_id = :domain_id limit 2";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        $count = $sth->fetch();
        return $count['count'];
    }

    /**
    * Function getInvoiceGross
    * 
    * Used to get the gross total for a given invoice number
    **/
    public static function getInvoiceGross($invoice_id) {
        global $LANG;
        
        $sql ="SELECT SUM(gross_total) AS gross_total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :invoice_id";
        $sth = dbQuery($sql, ':invoice_id', $invoice_id);
        $res = $sth->fetch();
        //echo "TOTAL".$res['total'];
        return $res['gross_total'];
    }
    /**
    * Function invoice::max
    * 
    * Used to get the max invoice id
    **/
    public static function max() {
        global $auth_session;
        global $logger;
        $db=new db();
        if ( getNumberOfDonePatches() < '179')
        {
            $sql ="SELECT max(id) as max FROM ".TB_PREFIX."invoices";
		    $sth = $db->query($sql);
        } else {
            $sql ="SELECT max(id) as max FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
		    $sth = $db->query($sql, ':domain_id', $auth_session->domain_id);
        }

        $count = $sth->fetch();
		$logger->log('Max Invoice: '.$count['max'], Zend_Log::INFO);
        return $count['max'];
    }

	public function recur()
	{
		$invoice = invoice::select($this->id);
		$ni = new invoice();
		$ni->biller_id = $invoice['biller_id'];
		$ni->customer_id = $invoice['customer_id'];
		$ni->type_id = $invoice['type_id'];
		$ni->preference_id = $invoice['preference_id'];
		//$ni->date = $invoice['date_original'];
		$ni->date = date('Y-m-d');
		$ni->custom_field1 = $invoice['custom_field1'];
		$ni->custom_field2 = $invoice['custom_field2'];
		$ni->custom_field3 = $invoice['custom_field3'];
		$ni->custom_field4 = $invoice['custom_field4'];
		$ni->note = $invoice['note'];
		$ni_id = $ni->insert();
		//insert each line item
		foreach ($invoice['invoice_items'] as $key => $value)
		{
			$nii = new invoice();
			$nii->invoice_id=$ni_id;
			$nii->quantity=$invoice['invoice_items'][$key]['quantity'];
			$nii->product_id=$invoice['invoice_items'][$key]['product_id'];
			$nii->unit_price=$invoice['invoice_items'][$key]['unit_price'];
			$nii->tax_amount=$invoice['invoice_items'][$key]['tax_amount'];
			$nii->gross_total=$invoice['invoice_items'][$key]['gross_total'];
			$nii->description=$invoice['invoice_items'][$key]['description'];
			$nii->total=$invoice['invoice_items'][$key]['total'];
			$nii->tax=$invoice['invoice_items'][$key]['tax'];
			$nii_id = $nii->insert_item();
		}
		
		return $ni_id;
	}
}
