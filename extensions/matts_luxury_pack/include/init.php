<?php

/*
function myNoticeStrictHandler($errstr, $errfile, $errline) {//$errno=null, 
}
set_error_handler('myNoticeStrictHandler', E_NOTICE | E_STRICT);
*/
/*if (!function_exists ('addDatabaseColumn'))
{		^*TAKES TOO LONG*/
	function addDatabaseColumn ($column, $table, $type, $length, $cannull=falue, $default=0, $after="")
	{
		global $LANG, $dbh;

		$sql = "SELECT `data_type` FROM `information_schema`.`columns` WHERE `table_name`='$table' AND `column_name`='$column';";
	//echo "<script type='text/javascript'>alert('sql1=$sql')</script>";
		if (($sth = $dbh->query ($sql)) === false)
		{
			// Non-critical error so continue with next action.
			error_log ("<extension name> - <function name>: Unable to perform request: $sql");
		} else
		{
			$row = $sth->fetch (PDO::FETCH_ASSOC);
			if ($row['data_type'] != $type)
			{
				$sql = "ALTER TABLE `$table` ADD COLUMN `$column` $type( $length )";
				$sql.= $cannull ? " NOT NULL" : "NULL";
				$sql.= $default ? " DEFAULT '$default'" : "";
				$sql.= $after ? " AFTER `$after`" : "";
				$sql.= ";";
	//echo "<script type='text/javascript'>alert('sql2=')</script>";
				if (($sth = $dbh->query ($sql)) === false)
				{
					// Non-critical error so continue with next action.
					error_log ("<extension name> - <function name>: Unable to perform request: $sql");
				}
			}
		}
		return true;
	}
/*}			*TAKES TOO LONG*/

/*
if (!function_exists ('getCustomField'))
{
	function getCustomField ($field, $domain_id=1)
	{
		$sql = "SELECT * FROM `".TB_PREFIX."custom_fields` WHERE `cf_custom_field` = '$field' AND `domain_id` = $domain_id LIMIT 1;";
		$sth = dbQuery ($sql);
		$result = $sth->fetch (PDO::FETCH_ASSOC);
		return $result['cf_custom_label'];
	}
}

if (!function_exists ('updateCustom'))
{
	function updateCustom ($newname, $field, $domain_id=1)
	{
	//	ensure getCustomField() returns "" first
		$sql = "UPDATE `".TB_PREFIX."custom_fields` SET `cf_custom_label` = '$newname' WHERE `cf_custom_field` = '$field' AND `domain_id` = $domain_id LIMIT 1;";
		$sth = dbQuery ($sql);
		return $sth->fetch (PDO::FETCH_ASSOC);
	}
}
*/

/********************* customer section ***********************/

include_once ('extensions/matts_luxury_pack/include/class/mycustomer.php');

function ncustomers()
{
	$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get());
	return $sth->fetch(PDO::FETCH_ASSOC);
}

function customersql($type='', $start, $dir, $sort, $rp, $page)
{
	global $config;
	global $LANG;
	global $auth_session;

	$valid_search_fields = array('c.id', 'c.name', 'c.attention', 'c.street_address');

	//SC: Safety checking values that will be directly subbed in
	if (intval ($page) != $page) {
		$start = 0;
	}

	if (intval($rp) != $rp) {
	        $rp = 25;
	}

	/// *SQL Limit - start* /
	$start = (($page-1) * $rp);
	$limit = "LIMIT $start, $rp";

	if ($type =="count")
	{
	        unset ($limit);
	        $limit;
	}
	/// *SQL Limit - end* /

	if (!preg_match ('/^(asc|desc)$/iD', $dir)) {
	        $dir = 'DESC';
	}

	$where = "";
	$query = isset ($_POST['query']) ? $_POST['query'] : null;
	$qtype = isset ($_POST['qtype']) ? $_POST['qtype'] : null;
	if ( ! (empty ($qtype) || empty ($query)) ) {
	        if ( in_array ($qtype, $valid_search_fields) ) {
	                $where = " AND $qtype LIKE :query ";
	        } else {
	                $qtype = null;
	                $query = null;
	        }
	}

	/// *Check that the sort field is OK* /
	$validFields = array ('CID', 'name', 'street_address', 'attention', 'customer_total', 'paid', 'owing', 'enabled');

	if (in_array ($sort, $validFields)) {
	        $sort = $sort;
	} else {
	        $sort = "CID";
	}

	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
	$sql = "SELECT
			c.id as CID
			, c.name as name
			, c.street_address as street_address
			, c.attention as attention
			, (SELECT (CASE  WHEN c.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
			, SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) AS customer_total
			, COALESCE(ap.amount,0) AS paid
			, (SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) - COALESCE(ap.amount,0)) AS owing
		FROM ".TB_PREFIX."customers c
		LEFT JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND iv.domain_id = c.domain_id)
		LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
		LEFT JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
		LEFT JOIN (SELECT iv3.customer_id, p.domain_id, SUM(COALESCE(p.ac_amount, 0)) AS amount
			FROM ".TB_PREFIX."payment p INNER JOIN si_invoices iv3
			ON (iv3.id = p.ac_inv_id AND iv3.domain_id = p.domain_id)
			GROUP BY iv3.customer_id, p.domain_id
			) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
		WHERE c.domain_id = :domain_id
			$where
		GROUP BY CID
		ORDER BY
			$sort $dir ";
		$sql .= (!empty($limit)) ? $limit : '';

	if (empty ($query)) {
		$result = dbQuery ($sql, ':domain_id', $auth_session->domain_id);
	} else {
		$result = dbQuery ($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
	}
	return $result;
}

/******************************** product section **************************/

include_once ('extensions/matts_luxury_pack/include/class/myproduct.php');
/*
addDatabaseColumn ('price_list', TB_PREFIX.'customers', 'int', '11');
addDatabaseColumn ('unit_list_price2', TB_PREFIX.'products', 'DECIMAL', '25, 6');
addDatabaseColumn ('unit_list_price3', TB_PREFIX.'products', 'DECIMAL', '25, 6');
addDatabaseColumn ('unit_list_price4', TB_PREFIX.'products', 'DECIMAL', '25, 6');
*/
//$result = $sth->fetch (PDO::FETCH_ASSOC);
//return $result['cf_custom_label'];
/*
if (getCustomField ('product_cf2')=='' && getCustomField ('product_cf3')=='' && getCustomField ('product_cf4')=='')
{
	//not set
	updateCustom ('Price 2', 'product_cf2');
	updateCustom ('Price 3', 'product_cf3');
	updateCustom ('Price 4', 'product_cf4');
}
if (getCustomField ('product_cf2')=='Price 2' && getCustomField ('product_cf3')=='Price 3' && getCustomField ('product_cf4')=='Price 4')
	//already done
*/
/*
function insert_Product ($enabled=1,$visible=1, $domain_id='') {
    global $logger;
	$domain_id = domain_id::get ($domain_id);
	
	if (isset ($_POST['enabled'])) $enabled = $_POST['enabled'];
    //select all attribts
    $sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
    $sth =  dbQuery($sql);
    $attributes = $sth->fetchAll();

	$logger->log ('Attr: '.var_export ($attributes,true), Zend_Log::INFO);
    $attr = array();
    foreach ($attributes as $k=>$v)
    {
    	$logger->log ('Attr key: '.$k, Zend_Log::INFO);
    	$logger->log ('Attr value: '.var_export ($v,true), Zend_Log::INFO);
    	$logger->log( 'Attr set value: '.$k, Zend_Log::INFO);
        if ($_POST['attribute'.$v[id]] == 'true')
        {
            //$attr[$k]['attr_id'] = $v['id'];
            $attr[$v['id']] = $_POST['attribute'.$v[id]];
//            $attr[$k]['a$v['id']] = $_POST['attribute'.$v[id]];
        }
        
    }
	$logger->log ('Attr array: '.var_export ($attr,true), Zend_Log::INFO);
	$notes_as_description = ($_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
    $show_description =  ($_POST['show_description'] == 'true' ? 'Y' : NULL) ;

	$sql = "INSERT into
		".TB_PREFIX."products
		(
			domain_id, 
			description,
			unit_price, 
			unit_list_price2, 
			unit_list_price3, 
			unit_list_price4, 
			cost,
			reorder_level,
			custom_field1, 
			custom_field2,
			custom_field3,
			custom_field4, 
			notes, 
			default_tax_id, 
			enabled, 
			visible,
			attribute,
			notes_as_description,
			show_description
		) VALUES (	
			:domain_id,
			:description,
			:unit_price,
			:unit_list_price2,
			:unit_list_price3,
			:unit_list_price4,
			:cost,
			:reorder_level,
			:custom_field1,
			:custom_field2,
			:custom_field3,
			:custom_field4,
			:notes,
			:default_tax_id,
			:enabled,
			:visible,
			:attribute,
			:notes_as_description,
			:show_description
		)";

	return dbQuery ($sql,
		':domain_id',			$domain_id,	
		':description', 		$_POST['description'],
		':unit_price', 			$_POST['unit_price'],
		':unit_price_list2', 		$_POST['unit_price_list2'],
		':unit_price_list3', 		$_POST['unit_price_list3'],
		':unit_price_list4', 		$_POST['unit_price_list4'],
		':cost', 			$_POST['cost'],
		':reorder_level', 		$_POST['reorder_level'],
		':custom_field1', 		$_POST['custom_field1'],
		':custom_field2', 		$_POST['custom_field2'],
		':custom_field3', 		$_POST['custom_field3'],
		':custom_field4', 		$_POST['custom_field4'],
		':notes', 			"". $_POST['notes'],
		':default_tax_id', 		$_POST['default_tax_id'],
		':enabled', 			$enabled,
		':visible', 			$visible,
		':attribute', 			json_encode($attr),
		':notes_as_description', 	$notes_as_description,
		':show_description', 		$show_description
		);
}

function update_Product ($domain_id='') {

	$domain_id = domain_id::get ($domain_id);

	//select all attributes
	$sql = "SELECT * FROM ".TB_PREFIX."products_attributes";
	$sth =  dbQuery ($sql);
	$attributes = $sth->fetchAll();

	$attr = array();
	foreach ($attributes as $k=>$v)
	{
		if (isset($_POST['attribute'.$v['id']]) && $_POST['attribute'.$v['id']] == 'true')
		{
			$attr[$v['id']] = $_POST['attribute'.$v['id']];
		}
	}
	$notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL) ;
	$show_description =  (isset($_POST['show_description']) && $_POST['show_description'] == 'true' ? 'Y' : NULL) ;

	$sql = "UPDATE ".TB_PREFIX."products
			SET	description = :description,
				enabled = :enabled,
				default_tax_id = :default_tax_id,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				unit_price = :unit_price,
				unit_list_price2 = :unit_list_price2,
				unit_list_price3 = :unit_list_price3,
				unit_list_price4 = :unit_list_price4,
				cost = :cost,
				reorder_level = :reorder_level,
				attribute = :attribute,
				notes_as_description = :notes_as_description,
				show_description = :show_description
			WHERE	id = :id
			AND	domain_id = :domain_id";

//	echo "<script>alert('sql=$sql')</script>";

	return dbQuery ($sql,
		':domain_id',			$domain_id, 
		':description', 		$_POST['description'],
		':enabled', 			$_POST['enabled'],
		':notes', 			$_POST['notes'],
		':default_tax_id', 		$_POST['default_tax_id'],
		':custom_field1', 		$_POST['custom_field1'],
		':custom_field2', 		$_POST['custom_field2'],
		':custom_field3', 		$_POST['custom_field3'],
		':custom_field4', 		$_POST['custom_field4'],
		':unit_price', 			$_POST['unit_price'],
		':unit_list_price2', 		$_POST['unit_list_price2'],
		':unit_list_price3', 		$_POST['unit_list_price3'],
		':unit_list_price4', 		$_POST['unit_list_price4'],
		':cost', 			$_POST['cost'],
		':reorder_level', 		$_POST['reorder_level'],
		':attribute', 			json_encode($attr),
		':notes_as_description', 	$notes_as_description,
		':show_description', 		$show_description,
		':id', 				$_GET['id']
	);
}
*/
/******************************** invoice section *******************************/

include_once ('extensions/matts_luxury_pack/include/class/myinvoice.php');
/*addDatabaseColumn ('ship_to_customer_id', TB_PREFIX.'invoices', 'int', '11', false, 0, 'customer_id');*/

/*if (!function_exists ('ninvoices'))
{*/
	function ninvoices()
	{
		$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
		$sth = dbQuery($sql, ':domain_id', domain_id::get());
		return $sth->fetch(PDO::FETCH_ASSOC);
	}/*
}*/

/****************************** payments section *********************************/

//include ('extensions/matts_luxury_pack/include/class/mypayments.php');

// /simple/extensions/payment_rows_per_page/incude.init.php
/*function npayment()
{
	$sql = "SELECT count(*) AS count FROM ".TB_PREFIX."payment WHERE domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get());
	return $sth->fetch(PDO::FETCH_ASSOC);
}
*/
/*if (!function_exists ('paymentssql'))
{*/
	function paymentssql($type='', $dir, $sort, $rp, $page )
	{
		global $config;
		global $auth_session;

		$valid_search_fields = array('ap.id','b.name', 'c.name');

		//SC: Safety checking values that will be directly subbed in
		if (isset($start) && intval($start) != $start) {
			$start = 0;
		}
		if (isset($limit) && intval($limit) != $limit) {
			$limit = 25;
		}
		if (!preg_match('/^(asc|desc)$/iD', $dir)) {
			$dir = 'DESC';
		}

		/*SQL Limit - start*/
		$start = (($page-1) * $rp);
		$limit = "LIMIT $start, $rp";

		if ($type =="count")
		{
			unset($limit);
		}
		/*SQL Limit - end*/

		$where = "";
		$query = isset($_POST['query']) ? $_POST['query'] : null;
		$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
		if (!(empty($qtype) || empty($query)) ) {
			if (in_array($qtype, $valid_search_fields) ) {
				$where = " AND $qtype LIKE :query ";
			} else {
				$qtype = null;
				$query = null;
			}
		}


		/*Check that the sort field is OK*/
		$validFields = array('ap.id', 'ac_inv_id', 'description', 'unit_price','enabled');

		if (in_array($sort, $validFields)) {
			$sort = $sort;
		} else {
			$sort = "ap.id";
		}

		$sql = "SELECT 
					ap.*
					, c.name as cname
					, (SELECT CONCAT(pr.pref_inv_wording,' ',iv.index_id)) as index_name
					, b.name as bname
					, pt.pt_description AS description
					, ac_notes AS notes
					, DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
				FROM 
					".TB_PREFIX."payment ap
					INNER JOIN ".TB_PREFIX."invoices iv      ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
					INNER JOIN ".TB_PREFIX."customers c      ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
					INNER JOIN ".TB_PREFIX."biller b         ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
					INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = ap.domain_id)
					INNER JOIN ".TB_PREFIX."payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
				WHERE 
					ap.domain_id = :domain_id ";

		#if coming from another page where you want to filter by just one invoice
		if (!empty($_GET['id'])) {

			$id = $_GET['id'];
			
			$sql .= " 
				AND ap.ac_inv_id = :invoice_id
				$where
				ORDER BY 
					$sort $dir ";
				$sql .= (!empty($limit)) ? $limit : '';
			
			if (empty($query)) {
				$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id);
			} else {
				$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id, ':query', "%$query%");
			}
		}
		#if coming from another page where you want to filter by just one customer
		elseif (!empty($_GET['c_id'])) {
			
			//$query = getCustomerPayments($_GET['c_id']);
			$id = $_GET['c_id'];
			$sql .= " 
				AND c.id = :id 
				ORDER BY 
					$sort $dir ";
				$sql .= (!empty($limit)) ? $limit : '';

			$result = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id);
			
		}
		#if you want to show all invoices - no filters
		else {
			//$query = getPayments();
			
			$sql .= " 
					$where
				ORDER BY 
					$sort $dir ";
			$sql .= (!empty($limit)) ? $limit : '';
						
			if (empty($query)) {
				$result =  dbQuery($sql, ':domain_id', $auth_session->domain_id);
			} else {
				$result =  dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
			}
		}
		
		return $result;
	}/*
}*/
