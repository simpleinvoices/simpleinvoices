<?php
class invoice {
	
    public $start_date;
    public $end_date;
    public $having;
    public $sort;

    public static function select($id)
    {
		global $logger;
		global $db;
	    global $auth_session;

		$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id and id = :id";
		$sth = $db->query($sql, ':id', $id, ':domain_id', $auth_session->domain_id);

        return $sth->fetch();

    }

    public static function get_all()
    {
		global $logger;
	    global $auth_session;

		$sql = "SELECT id FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id order by id";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        return $sth->fetchAll();

    }

    //change to non-static
    function select_all($type='', $dir='DESC', $rp='25', $page='1', $having='')
    {
        global $config;
        global $auth_session;

        if(empty($having))
        {
            $having = $this->having;
        }

        $sort = $this->sort;

        //SC: Safety checking values that will be directly subbed in
    /*
        if (intval($start) != $start) {
            $start = 0;
        }
        if (intval($limit) != $limit) {
            $limit = 15;
        }
    */
        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT ".$start.", ".$rp;
        /*SQL Limit - end*/

        /*SQL where - start*/
        $query = $_REQEUST['query'];
        $qtype = $_REQUEST['qtype'];

        $where = " WHERE iv.domain_id = :domain_id ";
        if ($query) $where = " WHERE iv.domain_id = :domain_id AND $qtype LIKE '%$query%' ";
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
                $sql_having = "HAVING owing > 0";
                break;
            case "paid":
                $sql_having = "HAVING owing =''";
                break;
            case "draft":
                $sql_having = "HAVING status = 0";
                break;
            case "open":
                $sql_having = "HAVING status = 1";
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
                       (SELECT SUM(coalesce(ii.total,  0)) FROM " .
                TB_PREFIX . "invoice_items ii WHERE ii.invoice_id = iv.id) AS invoice_total,
                       (SELECT SUM(coalesce(ac_amount, 0)) FROM " .
                TB_PREFIX . "payment ap WHERE ap.ac_inv_id = iv.id) AS INV_PAID,
                       (SELECT (coalesce(invoice_total,0) - coalesce(INV_PAID,0))) As owing,
                       DATE_FORMAT(date,'%Y-%m-%d') AS date,
                       (SELECT IF((owing = 0), 0, DateDiff(now(), date))) AS Age,
                       (SELECT (CASE   WHEN Age = 0 THEN ''
                                                       WHEN Age <= 14 THEN '0-14'
                                                       WHEN Age <= 30 THEN '15-30'
                                                       WHEN Age <= 60 THEN '31-60'
                                                      WHEN Age <= 90 THEN '61-90'
                                                       ELSE '90+'  END)) AS aging,
                       iv.type_id As type_id,
                       pf.pref_description AS preference,
                       pf.status AS status,
                       (SELECT CONCAT(pf.pref_description,' ',iv.index_id)) as index_name
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
			$tth = dbQuery($sql, ':id', $invoiceItem['product_id']) or die(htmlspecialchars(end($dbh->errorInfo())));
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
}
