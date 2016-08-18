<?php

// /simple/extensions/matts_luxury_pack/include/class

class myinvoice extends invoice
{
	public $domain_id;
	public $defaults;

	public function __construct()
	{
		parent::__construct();
		$this->defaults = getSystemDefaults();
		$this->domain_id = isset($domain_id) ? domain_id::get($domain_id) : 1;
//	$logger->log('extensions/matts_luxury_pack/include/class/myinvoice.php', Zend_Log::INFO);
	}


	public static function numberof()
	{
		$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
		$sth = dbQuery($sql, ':domain_id', domain_id::get());
		return $sth->fetch(PDO::FETCH_ASSOC);
	}


    function select_all ($type='', $dir='DESC', $rp='25', $page='1', $having='')
    {
		global $config;

		$domain_id = domain_id::get($this->domain_id);
		$valid_search_fields = array('iv.index_id', 'b.name', 'c.name');

		if (empty($having)) $having = $this->having;
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
        $validFields = array('index_id','index_name','iv.id', 'biller', 'customer', 'invoice_total',
			'owing', 'date', 'aging', 'type', 'preference', 'type_id');

        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }

        if (strstr($type, "count"))
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
                     b.name AS biller,
                     c.name AS customer,
                     sum(ii.total) AS invoice_total,
                     coalesce(SUM(ap.ac_amount), 0)  AS INV_PAID,
                     (SUM(ii.total) - coalesce(sum(ap.ac_amount), 0)) AS owing,
                     to_char(date,'YYYY-MM-DD') AS Date ,
                     (SELECT now()::date - iv.date) AS Age,
                     (CASE WHEN now()::date - iv.date <= '14 days'::interval THEN '0-14'
                      WHEN now()::date - iv.date <= '30 days'::interval THEN '15-30'
                      WHEN now()::date - iv.date <= '60 days'::interval THEN '31-60'
                      WHEN now()::date - iv.date <= '90 days'::interval THEN '61-90'
                      ELSE '90+'
                     END) AS Aging,
                     iv.type_id AS type_id,
                     iv.customer_id AS customer_id,
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
						iv.customer_id AS customer_id,
						DATE_FORMAT(date,'%Y-%m-%d') AS date,";
                $sql .="
					(SELECT coalesce(SUM(ii.total), 0) FROM " .
						TB_PREFIX . "invoice_items ii WHERE ii.invoice_id = iv.id AND ii.domain_id = :domain_id) AS invoice_total,
					(SELECT coalesce(SUM(ac_amount), 0) FROM " .
						TB_PREFIX . "payment ap WHERE ap.ac_inv_id = iv.id AND ap.domain_id = :domain_id) AS INV_PAID,
					(SELECT invoice_total - INV_PAID) As owing,
                       ";

              //only run aging for real full query ($type is empty for full query or count for count query)
               if ($type == '')
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
                            '' AS Age,
                            '' AS aging,
                            ";
               }
               $sql .="iv.type_id AS type_id,
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

	
	public function insertNew($type)
	{
		global $db_server;
		global $logger;

		if ($db_server == 'mysql' && !_invoice_check_fk(
			$_POST['biller_id'], $_POST['customer_id'],
			$type, $_POST['preference_id'])) {
			return null;
		}
		$sql = "INSERT INTO
			".TB_PREFIX."invoices (
				id
				, index_id
				, domain_id
				, biller_id
				, customer_id";
		if ($this->defaults['use_ship_to'])		$sql.= ", ship_to_customer_id";
		$sql.= ", type_id
				, preference_id
				, date
				, note
				, custom_field1
				, custom_field2
				, custom_field3
				, custom_field4";
		if ($this->defaults['use_terms'])		$sql.= ", terms";
		$sql.= ")
			VALUES
			(
				NULL
				, :index_id
				, :domain_id
				, :biller_id
				, :customer_id";
		if ($this->defaults['use_ship_to'])		$sql.= ", :ship_to_customer_id";
		$sql.= ", :type_id
				, :preference_id
				, :date
				, :note
				, :customField1
				, :customField2
				, :customField3
				, :customField4";
		if ($this->defaults['use_terms'])		$sql.= ", :terms";
		$sql.= ")";

		if ($db_server == 'pgsql') {
			$sql = "INSERT INTO
				".TB_PREFIX."invoices (
				index_id
				, domain_id
				, biller_id
				, customer_id";
		if ($this->defaults['use_ship_to'])		$sql.= ", ship_to_customer_id";
		$sql.= ", type_id
				, preference_id
				, date
				, note
				, custom_field1
				, custom_field2
				, custom_field3
				, custom_field4";
		if ($this->defaults['use_terms'])		$sql.= ", terms";
		$sql.= "	)
			VALUES
			(
				:index_id
				, :domain_id
				, :biller_id
				, :customer_id";
		if ($this->defaults['use_ship_to'])		$sql.= ", :ship_to_customer_id";
		$sql.= ", :type_id
				, :preference_id
				, :date
				, :note
				, :customField1
				, :customField2
				, :customField3
				, :customField4";
		if ($this->defaults['use_terms'])		$sql.= ", :terms";
		$sql.= ")";
		}
	//$logger->log('myinvoice::insert:', Zend_Log::INFO);
	//$logger->log('sql='.$sql, Zend_Log::INFO);
		//echo $sql;
		$pref_group=getPreference($_POST['preference_id']);
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : $type;

		//also set the current time (if null or =00:00:00)
		$clean_date=SqlDateWithTime($_POST['date']);

		if ($this->defaults['use_ship_to'] && $this->defaults['use_terms']) {
			$sth= dbQuery($sql,
				#':index_id', index::next('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]),
				':index_id',			index::next('invoice', $pref_group['index_group'], $this->domain_id),
				':domain_id',			$this->domain_id,
				':biller_id',			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':ship_to_customer_id', $_POST['ship_to_customer_id'],
				':type_id',				$type_id,
				':preference_id',		$_POST['preference_id'],
				':date', 				$clean_date,
				':note', 				trim($_POST['note']),
				':customField1',		$_POST['customField1'],
				':customField2',		$_POST['customField2'],
				':customField3',		$_POST['customField3'],
				':customField4',		$_POST['customField4'],
				':terms',				$_POST['terms']
			);
		}
		elseif ($this->defaults['use_ship_to'] && !$this->defaults['use_terms']) {
			$sth= dbQuery($sql,
				#':index_id', index::next('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]),
				':index_id',			index::next('invoice', $pref_group['index_group'], $this->domain_id),
				':domain_id',			$this->domain_id,
				':biller_id',			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':ship_to_customer_id', $_POST['ship_to_customer_id'],
				':type_id',				$type_id,
				':preference_id',		$_POST['preference_id'],
				':date', 				$clean_date,
				':note', 				trim($_POST['note']),
				':customField1',		$_POST['customField1'],
				':customField2',		$_POST['customField2'],
				':customField3',		$_POST['customField3'],
				':customField4',		$_POST['customField4']
			);
		}
		elseif (!$this->defaults['use_ship_to'] && $this->defaults['use_terms']) {
			$sth= dbQuery($sql,
				#':index_id', index::next('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]),
				':index_id',			index::next('invoice', $pref_group['index_group'], $this->domain_id),
				':domain_id',			$this->domain_id,
				':biller_id',			$_POST['biller_id'],
				':type_id',				$type_id,
				':preference_id',		$_POST['preference_id'],
				':date', 				$clean_date,
				':note', 				trim($_POST['note']),
				':customField1',		$_POST['customField1'],
				':customField2',		$_POST['customField2'],
				':customField3',		$_POST['customField3'],
				':customField4',		$_POST['customField4'],
				':terms',				$_POST['terms']
			);
		}
		elseif (!$this->defaults['use_ship_to'] && !$this->defaults['use_terms']) {
			$sth= dbQuery($sql,
				#':index_id', index::next('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]),
				':index_id',			index::next('invoice', $pref_group['index_group'], $this->domain_id),
				':domain_id',			$this->domain_id,
				':biller_id',			$_POST['biller_id'],
				':type_id',				$type_id,
				':preference_id',		$_POST['preference_id'],
				':date', 				$clean_date,
				':note', 				trim($_POST['note']),
				':customField1',		$_POST['customField1'],
				':customField2',		$_POST['customField2'],
				':customField3',		$_POST['customField3'],
				':customField4',		$_POST['customField4']
			);
		}
		#index::increment('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]);
		// Needed only if si_index table exists
		index::increment('invoice', $pref_group['index_group'], $this->domain_id);

		return $sth;
	}


	public function update($id)
	{
		global $db_server;
		global $logger;

		$invoiceobj = new invoice();
		$current_invoice = $invoiceobj->select ($_POST['id']);
		$current_pref_group = getPreference ($current_invoice['preference_id']);

		$new_pref_group = getPreference ($_POST['preference_id']);

		$index_id = $current_invoice['index_id'];

	//	$logger->log('Curent Index Group: '.$description, Zend_Log::INFO);
	//	$logger->log('Description: '.$description, Zend_Log::INFO);

		if ($current_pref_group['index_group'] != $new_pref_group['index_group'])
		{
			$index_id = index::increment ('invoice',$new_pref_group['index_group']);
		}

		if ($db_server == 'mysql' && !_invoice_check_fk(
			$_POST['biller_id'], $_POST['customer_id'],
			$type, $_POST['preference_id'])) {
			return null;
		}
		$sql = "UPDATE
				".TB_PREFIX."invoices
			SET
				index_id = :index_id
				, biller_id = :biller_id
				, customer_id = :customer_id";
		if ($this->defaults['use_ship_to'])		$sql.=", ship_to_customer_id = :ship_to_customer_id";
		$sql.= ", preference_id = :preference_id
				, date = :date
				, note = :note
				, custom_field1 = :customField1
				, custom_field2 = :customField2
				, custom_field3 = :customField3
				, custom_field4 = :customField4 ";
		if ($this->defaults['use_terms'])		$sql.= ", terms = :terms ";
		$sql.= "WHERE
				id = :invoice_id
			AND domain_id = :domain_id";

	//$logger->log('myinvoice::update:', Zend_Log::INFO);
	//$logger->log('sql='.$sql, Zend_Log::INFO);
	//	echo "<script>alert('sql 1=$sql')</script>";
				
		if ($this->defaults['use_ship_to'] && $this->defaults['use_terms']) {
			return dbQuery($sql,
				':index_id', 			$index_id,
				':biller_id', 			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':ship_to_customer_id', $_POST['ship_to_customer_id'],
				':preference_id', 		$_POST['preference_id'],
				':date', 				$_POST['date'],
				':note', 				trim($_POST['note']),
				':customField1', 		$_POST['customField1'],
				':customField2', 		$_POST['customField2'],
				':customField3', 		$_POST['customField3'],
				':customField4', 		$_POST['customField4'],
				':terms',				$_POST['terms'],
				':invoice_id', 			$id,
				':domain_id', 			$this->domain_id
			);
		}
		elseif (!$this->defaults['use_ship_to'] && $this->defaults['use_terms']) {
			return dbQuery($sql,
				':index_id', 			$index_id,
				':biller_id', 			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':preference_id', 		$_POST['preference_id'],
				':date', 				$_POST['date'],
				':note', 				trim($_POST['note']),
				':customField1', 		$_POST['customField1'],
				':customField2', 		$_POST['customField2'],
				':customField3', 		$_POST['customField3'],
				':customField4', 		$_POST['customField4'],
				':terms',				$_POST['terms'],
				':invoice_id', 			$id,
				':domain_id', 			$this->domain_id
			);
		}
		elseif ($this->defaults['use_ship_to'] && !$this->defaults['use_terms']) {
			return dbQuery($sql,
				':index_id', 			$index_id,
				':biller_id', 			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':ship_to_customer_id', $_POST['ship_to_customer_id'],
				':preference_id', 		$_POST['preference_id'],
				':date', 				$_POST['date'],
				':note', 				trim($_POST['note']),
				':customField1', 		$_POST['customField1'],
				':customField2', 		$_POST['customField2'],
				':customField3', 		$_POST['customField3'],
				':customField4', 		$_POST['customField4'],
				':invoice_id', 			$id,
				':domain_id', 			$this->domain_id
			);
		}
		elseif (!$this->defaults['use_ship_to'] && !$this->defaults['use_terms']) {
			return dbQuery($sql,
				':index_id', 			$index_id,
				':biller_id', 			$_POST['biller_id'],
				':customer_id', 		$_POST['customer_id'],
				':preference_id', 		$_POST['preference_id'],
				':date', 				$_POST['date'],
				':note', 				trim($_POST['note']),
				':customField1', 		$_POST['customField1'],
				':customField2', 		$_POST['customField2'],
				':customField3', 		$_POST['customField3'],
				':customField4', 		$_POST['customField4'],
				':invoice_id', 			$id,
				':domain_id', 			$this->domain_id
			);
		}
	}


//if ($defaults['use_ship_to']) {
	public function insert_DeliveryNote($type_id)
	{
		global $db_server;
		global $logger;

		$_POST['preference_id'] = 5;
		//insert entry as new delivery note
		
		if ($db_server == 'mysql' && !_invoice_check_fk(
			$_POST['biller_id'], $_POST['customer_id'],
			$type, $_POST['preference_id'])) {
			return null;
		}
		$sql = "INSERT INTO
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
				custom_field4";
		if ($this->defaults['use_terms'])		$sql.= ", terms";
		$sql.= ")
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
				:customField1,
				:customField2,
				:customField3,
				:customField4";
		if ($this->defaults['use_terms'])		$sql.= ", :terms";
		$sql.= ")";

		if ($db_server == 'pgsql') {
			$sql = "INSERT INTO
				".TB_PREFIX."invoices (
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
					custom_field4";
		if ($this->defaults['use_terms'])		$sql.= ", terms";
		$sql.= ")
				VALUES
				(
					:index_id,
					:domain_id,
					:biller_id,
					:customer_id,
					:type_id,
					:preference_id,
					:date,
					:note,
					:customField1,
					:customField2,
					:customField3,
					:customField4";
		if ($this->defaults['use_terms'])		$sql.= ", :terms";
		$sql.= ")";
		}
	//$logger->log('myinvoice::insertDN:', Zend_Log::INFO);
	//$logger->log('sql='.$sql, Zend_Log::INFO);
		//echo $sql;
		$pref_group=getPreference($_POST['preference_id']);

		//also set the current time (if null or =00:00:00)
		$clean_date=SqlDateWithTime($_POST['date']);

		//set customer_id as ship_to_customer_id
		$sth= dbQuery($sql,
			#':index_id', index::next('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]),
			':index_id',		(index::next('invoice', $pref_group['index_group'], $this->domain_id)-1),
			':domain_id',		$this->domain_id,
			':biller_id',		$_POST['biller_id'],
			':customer_id', 	$_POST['ship_to_customer_id'],
			':type_id',			$type_id,
			':preference_id',	$_POST['preference_id'],
			':date', 			$clean_date,
			':note', 			trim($_POST['note']),
			':customField1',	$_POST['customField1'],
			':customField2',	$_POST['customField2'],
			':customField3',	$_POST['customField3'],
			':customField4',	$_POST['customField4'],
			':terms',			$_POST['terms']
			);

		#index::increment('invoice',$pref_group[index_group], $domain_id,$_POST[biller_id]);
		// Needed only if si_index table exists
		//index::increment('invoice', $pref_group[index_group], $domain_id);

		return $sth;
	}


	public function update_DeliveryNote($id)
	{
		global $db_server;
		global $logger;

		$_POST['preference_id'] = 5;
		$invoiceobj = new invoice();
		$current_invoice = $invoiceobj->select($_POST['id']);
		$current_pref_group = getPreference($current_invoice['preference_id']);

		$new_pref_group=getPreference($_POST['preference_id']);

		$index_id = $current_invoice['index_id'];

	//	$logger->log('Curent Index Group: '.$description, Zend_Log::INFO);
	//	$logger->log('Description: '.$description, Zend_Log::INFO);

		if ($current_pref_group['index_group'] != $new_pref_group['index_group'])
		{
			$index_id = index::increment('invoice',$new_pref_group['index_group']);
		}

		if ($db_server == 'mysql' && !_invoice_check_fk(
			$_POST['biller_id'], $_POST['customer_id'],
			$type, $_POST['preference_id'])) {
			return null;
		}
		$sql = "UPDATE
				".TB_PREFIX."invoices
			SET
				index_id = :index_id,
				biller_id = :biller_id,
				customer_id = :customer_id,
				preference_id = :preference_id,
				date = :date,
				note = :note,
				custom_field1 = :customField1,
				custom_field2 = :customField2,
				custom_field3 = :customField3,
				custom_field4 = :customField4 ";
		if ($this->defaults['use_terms'])		$sql.= ", terms = :terms ";
		$sql.= "WHERE
				id = :invoice_id
			AND domain_id = :domain_id";
				
	//$logger->log('myinvoice::updateDN:', Zend_Log::INFO);
	//$logger->log('sql='.$sql, Zend_Log::INFO);
		return dbQuery($sql,
			':index_id', 		$index_id,
			':biller_id', 		$_POST['biller_id'],
			':customer_id', 	$_POST['ship_to_customer_id'],
			':preference_id', 	$_POST['preference_id'],
			':date', 			$_POST['date'],
			':note', 			trim($_POST['note']),
			':customField1', 	$_POST['customField1'],
			':customField2', 	$_POST['customField2'],
			':customField3', 	$_POST['customField3'],
			':customField4', 	$_POST['customField4'],
			':terms', 			$_POST['terms'],
			':invoice_id', 		$id,
			':domain_id', 		$this->domain_id
			);
	}
//}
}
