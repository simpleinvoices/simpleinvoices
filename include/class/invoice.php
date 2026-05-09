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

		$pref_group = getPreference($this->preference_id, $this->domain_id);

		// Resolve currency from preference / si_currency (same logic as insertInvoice)
		require_once __DIR__ . '/class/CurrencySignHelper.php';
		$currency_sign = CurrencySignHelper::forDisplay($pref_group['pref_currency_sign'] ?? '');
		$currency_code = trim($pref_group['currency_code'] ?? '');

		$currency_id = null;
		$currency_position = '';
		if (!empty($pref_group['currency_id'])) {
			require_once __DIR__ . '/class/siCurrencies.php';
			$currRow = siCurrencies::getById((int) $pref_group['currency_id'], $this->domain_id);
			if ($currRow) {
				$currency_id = (int) $currRow['id'];
				$currency_position = $currRow['currency_position'] ?? '';
			}
		}
		if ($currency_position !== 'left' && $currency_position !== 'right') {
			$currency_position = trim($pref_group['currency_position'] ?? '');
		}
		if ($currency_position !== 'left' && $currency_position !== 'right') {
			$currency_position = CurrencySignHelper::defaultPositionForSign($currency_sign, $currency_code);
		}
		if (!$currency_id && ($currency_sign !== '' || $currency_code !== '')) {
			require_once __DIR__ . '/class/siCurrencies.php';
			$currRow = siCurrencies::findOrCreate($this->domain_id, $currency_sign, $currency_code, $currency_position);
			if ($currRow) {
				$currency_id = (int) $currRow['id'];
			}
		}
		$show_currency_code = !empty($pref_group['show_currency_code']) ? 1 : 0;

		// Denormalise currency_code and currency_locale from preference
		if ($currency_id > 0 && ($currency_code === '')) {
			$tmpCur = siCurrencies::getById($currency_id, $this->domain_id);
			if ($tmpCur) {
				$currency_code = $tmpCur['currency_code'] ?? '';
			}
		}
		$currency_locale = trim($pref_group['locale'] ?? '');

		if ($db_server == 'pgsql' || $db_server == 'sqlite') {
			$sql = "INSERT INTO ".TB_PREFIX."invoices (
					index_id, domain_id, biller_id, customer_id,
					type_id, preference_id, date, note,
					custom_field1, custom_field2, custom_field3, custom_field4,
					currency_sign, denorm_currency_code, denorm_currency_locale,
					currency_id, show_currency_code
				) VALUES (
					:index_id, :domain_id, :biller_id, :customer_id,
					:type_id, :preference_id, :date, :note,
					:customField1, :customField2, :customField3, :customField4,
					:currency_sign, :currency_code, :currency_locale,
					:currency_id, :show_currency_code
				)";
		} else {
			$sql = "INSERT INTO ".TB_PREFIX."invoices (
					id, index_id, domain_id, biller_id, customer_id,
					type_id, preference_id, date, note,
					custom_field1, custom_field2, custom_field3, custom_field4,
					currency_sign, denorm_currency_code, denorm_currency_locale,
					currency_id, show_currency_code
				) VALUES (
					NULL, :index_id, :domain_id, :biller_id, :customer_id,
					:type_id, :preference_id, :date, :note,
					:customField1, :customField2, :customField3, :customField4,
					:currency_sign, :currency_code, :currency_locale,
					:currency_id, :show_currency_code
				)";
		}

		$sth = dbQuery($sql,
			':index_id',       index::next('invoice', $pref_group['index_group'], $this->domain_id),
			':domain_id',      $this->domain_id,
			':biller_id',      $this->biller_id,
			':customer_id',    $this->customer_id,
			':type_id',        $this->type_id,
			':preference_id',  $this->preference_id,
			':date',           $this->date,
			':note',           trim($this->note),
			':customField1',  $this->custom_field1,
			':customField2',  $this->custom_field2,
			':customField3',  $this->custom_field3,
			':customField4',  $this->custom_field4,
			':currency_sign',  $currency_sign,
			':currency_code',  $currency_code,
			':currency_locale', $currency_locale,
			':currency_id',    $currency_id,
			':show_currency_code', $show_currency_code
		);

	    index::increment('invoice', $pref_group['index_group'], $this->domain_id);

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
		global $db_server;

		if(!empty($domain_id)) $this->domain_id = $domain_id;

		$index_name_expr = ($db_server === 'mysql')
			? "(SELECT CONCAT(p.pref_inv_wording,' ',i.index_id))"
			: "(p.pref_inv_wording || ' ' || CAST(i.index_id AS TEXT))";

		$sql = "SELECT
                    i.*,
		            i.date as date_original,
                    $index_name_expr as index_name,
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

	$rawDue = $invoice['due_date'] ?? null;
	$invoice['calc_due_date'] = (!empty($rawDue) && $rawDue !== '0000-00-00') ? date('Y-m-d', strtotime($rawDue)) : '';
	if ($invoice['calc_due_date'] !== '') {
		$invoice['due_date'] = siLocal::date($rawDue);
	} else {
		$invoice['due_date'] = '';
	}
	$invoice['payment_term_label'] = '';
	$invoice['payment_term_code'] = '';
	if (!empty($invoice['payment_term_id']) && function_exists('getPaymentTerm')) {
		$pt = getPaymentTerm($invoice['payment_term_id']);
		if ($pt) {
			$invoice['payment_term_label'] = $pt['term_label'];
			$invoice['payment_term_code'] = $pt['term_code'] ?? '';
		}
	}

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
		global $db_server;

		$domain_id = domain_id::get($domain_id);

		$index_name_expr = ($db_server === 'mysql')
			? "(SELECT CONCAT(p.pref_inv_wording,' ',i.index_id))"
			: "(p.pref_inv_wording || ' ' || CAST(i.index_id AS TEXT))";

		$sql = "SELECT
                    i.id as id,
                    $index_name_expr as index_name
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
        // Grid search: map legacy qtypes to denormalised columns on si_invoices
        $search_field_map = [
            'iv.index_id' => 'iv.index_id',
            'b.name'      => 'iv.denorm_biller_name',
            'c.name'      => 'iv.denorm_customer_name',
        ];

        if(empty($having)) $having = $this->having;
        $having_and = ($this->having_and) ? $this->having_and : false;

        $sort = $this->sort;

        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT ".$rp." OFFSET ".$start;
        /*SQL Limit - end*/


        /*SQL where - start*/
        $query = $this->query;
        $qtype = $this->qtype;
        $where = "";
        if (!(empty($query) || empty($qtype))) {
          if (isset($search_field_map[$qtype])) {
            $where .= ' AND ' . $search_field_map[$qtype] . ' LIKE :query ';
          } else {
            $this->query = $qtype = null;
            $this->qtype = $query = null;
          }
        }
        if ($this->biller) {
            $where .= ' AND iv.biller_id = ' . (int) $this->biller . ' ';
        }
        if ($this->customer) {
            $where .= ' AND iv.customer_id = ' . (int) $this->customer . ' ';
        }
        if ($this->where_field) $where .= " AND $this->where_field = '$this->where_value' ";
        /*SQL where - end*/
	

        /*Check that the sort field is OK*/
        $validFields = array('index_id','index_name','iv.id', 'biller', 'customer', 'invoice_total','owing','date','aging','type','preference','type_id');

        if (!in_array($sort, $validFields))
            $sort = "iv.id";

        if (strstr($type, "count") || $type === 'grid_total') {
            $limit = '';
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

        // List/grid uses denormalised columns on si_invoices (maintained by invoice_denorm).
        // Former HAVING filters apply on the outer query as WHERE (same semantics as HAVING did per invoice).
        $post_from_filter = '';
        if (trim($sql_having) !== '') {
            $post_from_filter = ' ' . str_replace('HAVING', 'WHERE', trim($sql_having));
        }
        $sort_outer = $sort;
        if ($sort_outer === 'iv.id') {
            $sort_outer = 'id';
        } elseif ($sort_outer === 'type') {
            $sort_outer = 'type_id';
        }

        // Paginate ids first (all DB backends); detail rows read denorm fields only - no line-item aggregate joins.
        $dirSql = (strtoupper((string) $dir) === 'ASC') ? 'ASC' : 'DESC';
        $can_fast_page = (
            $type !== 'grid_total'
            && !strstr($type, 'count')
            && trim($post_from_filter) === ''
            && empty($query)
            && empty($this->where_field)
            && in_array($sort, ['index_id', 'iv.id'], true)
            && $limit !== ''
        );
        $orderCol = ($sort === 'iv.id') ? 'iv.id' : 'iv.index_id';
        $fast_ids = [];
        if ($can_fast_page) {
            $sql_ids = "SELECT iv.id FROM " . TB_PREFIX . "invoices iv
                        WHERE iv.domain_id = :domain_id
                        $where
                        ORDER BY $orderCol $dirSql
                        $limit";
            $ids_sth = dbQuery($sql_ids, ':domain_id', $domain_id);
            $fast_ids = $ids_sth->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        global $db_server;

        switch ($config->database->adapter)
        {
            case "pdo_pgsql":
                $pgsql_age = "
                     EXTRACT(day FROM (now() - iv.date::timestamp))::int AS Age,
                     (CASE WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 0 THEN ''
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 30 THEN '0-30'
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 60 THEN '31-60'
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 90 THEN '61-90'
                           ELSE '90+'
                     END) AS aging,";
                if ($can_fast_page) {
                    if (count($fast_ids) === 0) {
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     TO_CHAR(iv.date, 'YYYY-MM-DD') AS date,
                     $pgsql_age
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE FALSE";
                    } else {
                        $inList = implode(',', array_map('intval', $fast_ids));
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     TO_CHAR(iv.date, 'YYYY-MM-DD') AS date,
                     $pgsql_age
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    AND iv.id IN ($inList)
                ORDER BY $orderCol $dirSql";
                    }
                } else {
                    $inner_sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     TO_CHAR(iv.date, 'YYYY-MM-DD') AS date,
                     EXTRACT(day FROM (now() - iv.date::timestamp))::int AS Age,
                     (CASE WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 0 THEN ''
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 30 THEN '0-30'
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 60 THEN '31-60'
                           WHEN EXTRACT(day FROM (now() - iv.date::timestamp))::int <= 90 THEN '61-90'
                           ELSE '90+'
                     END) AS aging,
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    $where";
                    $sql = "
                SELECT * FROM (
                $inner_sql
                ) AS si_invoice_grid
                $post_from_filter
                ORDER BY $sort_outer $dir
                $limit";
                }
                break;

            case "pdo_sqlite":
                $sqlite_age = "
                     CAST(julianday('now') - julianday(iv.date) AS INTEGER) AS Age,
                     (CASE WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 0 THEN ''
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 30 THEN '0-30'
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 60 THEN '31-60'
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 90 THEN '61-90'
                           ELSE '90+'
                     END) AS aging,";
                if ($can_fast_page) {
                    if (count($fast_ids) === 0) {
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     strftime('%Y-%m-%d', iv.date) AS date,
                     $sqlite_age
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE 0";
                    } else {
                        $inList = implode(',', array_map('intval', $fast_ids));
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     strftime('%Y-%m-%d', iv.date) AS date,
                     $sqlite_age
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    AND iv.id IN ($inList)
                ORDER BY $orderCol $dirSql";
                    }
                } else {
                    $inner_sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     strftime('%Y-%m-%d', iv.date) AS date,
                     CAST(julianday('now') - julianday(iv.date) AS INTEGER) AS Age,
                     (CASE WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 0 THEN ''
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 30 THEN '0-30'
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 60 THEN '31-60'
                           WHEN CAST(julianday('now') - julianday(iv.date) AS INTEGER) <= 90 THEN '61-90'
                           ELSE '90+'
                     END) AS aging,
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    $where";
                    $sql = "
                SELECT * FROM (
                $inner_sql
                ) AS si_invoice_grid
                $post_from_filter
                ORDER BY $sort_outer $dir
                $limit";
                }
                break;

            case "pdo_mysql":
            default:
                // Pre-aggregated joins; outer WHERE replaces former HAVING (one row per invoice).
                if ($type == '') {
                    $age_expr    = "DATEDIFF(NOW(), iv.date)";
                    $age_columns = "
                       $age_expr AS Age,
                       CASE WHEN $age_expr <= 0 THEN ''
                            WHEN $age_expr <= 30 THEN '0-30'
                            WHEN $age_expr <= 60 THEN '31-60'
                            WHEN $age_expr <= 90 THEN '61-90'
                            ELSE '90+'
                       END AS aging,";
                } else {
                    $age_columns = "
                       '' AS Age,
                       '' AS aging,";
                }

                if ($can_fast_page) {
                    if (count($fast_ids) === 0) {
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     DATE_FORMAT(iv.date,'%Y-%m-%d') AS date,
                     $age_columns
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id AND 1 = 0";
                    } else {
                        $inList = implode(',', array_map('intval', $fast_ids));
                        $sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     DATE_FORMAT(iv.date,'%Y-%m-%d') AS date,
                     $age_columns
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    AND iv.id IN ($inList)
                ORDER BY $orderCol $dirSql";
                    }
                } else {
                    $inner_sql = "
                SELECT
                     iv.id,
                     iv.index_id AS index_id,
                     iv.denorm_biller_name AS biller,
                     iv.denorm_customer_name AS customer,
                     iv.denorm_invoice_total AS invoice_total,
                     iv.denorm_amount_paid AS INV_PAID,
                     iv.denorm_amount_owing AS owing,
                     DATE_FORMAT(iv.date,'%Y-%m-%d') AS date,
                     $age_columns
                     iv.type_id AS type_id,
                     iv.denorm_preference_description AS preference,
                     iv.denorm_preference_status AS status,
                     iv.denorm_index_name AS index_name,
                     iv.currency_sign,
                     iv.denorm_currency_code,
                     iv.denorm_currency_locale
                FROM " . TB_PREFIX . "invoices iv
                WHERE iv.domain_id = :domain_id
                    $where";
                    $sql = "
                SELECT * FROM (
                $inner_sql
                ) AS si_invoice_grid
                $post_from_filter
                ORDER BY $sort_outer $dir
                $limit";
                }
                break;
        }

        if ($type === 'grid_total') {
            $sql = 'SELECT COUNT(*) AS cnt FROM (' . "\n" . $inner_sql . "\n"
                . ') AS si_invoice_grid ' . trim($post_from_filter);
        }
        
        if ($query) {
			$result =  dbQuery($sql,':domain_id', $domain_id, ':query', "%$query%");
		} else {
			$result =  dbQuery($sql,':domain_id', $domain_id);
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
				$logger->log('Invoice: '.$invoiceItem['invoice_id'].' Item id: '.$invoiceItem['id'].' Tax '.$key.' Tax ID: '.$value['tax_id'], LegacyLogger::INFO);
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
		$logger->log('Max Invoice: '.$count['max'], LegacyLogger::INFO);
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
