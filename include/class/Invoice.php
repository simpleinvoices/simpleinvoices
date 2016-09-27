<?php
require_once 'include/class/ProductAttributes.php';

class Invoice {
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

    public function __construct() {
        $this->domain_id = domain_id::get();
    }

    public function insert() {
        global $pdoDb;

        // @formatter:off
        $pref_group = getPreference($this->preference_id, $this->domain_id);
        $index_id   = index::next('invoice', $pref_group['index_group'], $this->domain_id);
        $pdoDb->setFauxPost(array('index_id'      => $index_id,
                                  'domain_id'     => $this->domain_id,
                                  'biller_id'     => $this->biller_id,
                                  'customer_id'   => $this->customer_id,
                                  'type_id'       => $this->type_id,
                                  'preference_id' => $this->preference_id,
                                  'date'          => $this->date,
                                  'note'          => trim($this->note),
                                  'custom_field1' => $this->custom_field1,
                                  'custom_field2' => $this->custom_field2,
                                  'custom_field3' => $this->custom_field3,
                                  'custom_field4' => $this->custom_field4));
        $id = $pdoDb->request("INSERT", "invoices");
        // @formatter:on

        index::increment('invoice', $pref_group['index_group'], $this->domain_id);
        return $id;
    }

    public function insert_item() {
        global $pdoDb;

        // @formatter:off
        $pdoDb->setFauxPost(array('invoice_id'  => $this->invoice_id,
                                  'domain_id'   => $this->domain_id,
                                  'quantity'    => $this->quantity,
                                  'product_id'  => $this->product_id,
                                  'unit_price'  => $this->unit_price,
                                  'tax_amount'  => $this->tax_amount,
                                  'gross_total' => $this->gross_total,
                                  'description' => trim($this->description),
                                  'total'       => $this->total,
                                  'attribute'   => $this->attribute));
        $id = $pdoDb("INSERT", "invoice_items");
        // @formatter:on

        invoice_item_tax($id, $this->tax, $this->unit_price, $this->quantity, 'insert', $this->domain_id);
        return $id;
    }

    public function select($id, $domain_id = '') {
        if (!empty($domain_id)) $this->domain_id = $domain_id;

        // @formatter:off
        $sql = "SELECT i.*,
                       i.date as date_original,
                       (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name,
                       p.pref_inv_wording AS preference,
                       p.status
                FROM " . TB_PREFIX . "invoices i
                LEFT JOIN " . TB_PREFIX . "preferences p
                          ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
                WHERE i.domain_id = :domain_id
                  AND i.id = :id";
        $sth = dbQuery($sql, ':id', $id, ':domain_id', $this->domain_id);

        $invoice = $sth->fetch();
        $invoice['calc_date']     = date('Y-m-d', strtotime($invoice['date']));
        $invoice['date']          = siLocal::date($invoice['date']);
        $invoice['total']         = getInvoiceTotal($invoice['id'], $domain_id);
        $invoice['gross']         = $this->getInvoiceGross($invoice['id'], $this->domain_id);
        $invoice['paid']          = calc_invoice_paid($invoice['id'], $domain_id);
        $invoice['owing']         = $invoice['total'] - $invoice['paid'];
        $invoice['invoice_items'] = $this->getInvoiceItems($id, $this->domain_id);

        // Invoice total tax
        $sql2 = "SELECT SUM(tax_amount) AS total_tax,
                        SUM(total) AS total
                 FROM " . TB_PREFIX . "invoice_items
                 WHERE invoice_id =  :id AND domain_id = :domain_id";
        $sth2 = dbQuery($sql2, ':id', $id, ':domain_id', $this->domain_id);
        $result2 = $sth2->fetch(PDO::FETCH_ASSOC);

        $invoice['total_tax']   = $result2['total_tax'];
        $invoice['tax_grouped'] = taxesGroupedForInvoice($id);
        // @formatter:on
        return $invoice;
    }

    public function get_all($domain_id = '') {
        $domain_id = domain_id::get($domain_id);

        // @formatter:off
        $sql = "SELECT i.id as id,
                       (SELECT CONCAT(p.pref_inv_wording,' ',i.index_id)) as index_name
                FROM " . TB_PREFIX . "invoices i
                LEFT JOIN " . TB_PREFIX . "preferences p
                       ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
                WHERE i.domain_id = :domain_id
                ORDER BY index_name";

        $sth = dbQuery($sql, ':domain_id', $domain_id);
        // @formatter:on

        return $sth->fetchAll();
    }

    public function count($domain_id = '') {
        $domain_id = domain_id::get($domain_id);

        // @formatter:off
        $sql = "SELECT count(id) AS count
                FROM " . TB_PREFIX . "invoices
                WHERE domain_id = :domain_id";
        $sth = dbQuery($sql, ':domain_id', $domain_id);
        // @formatter:on
        return $sth;
    }

    function select_all($type = '', $dir = 'DESC', $rp = '25', $page = '1', $having = '') {
        global $config;

        $domain_id = domain_id::get($this->domain_id);
        $valid_search_fields = array('iv.index_id', 'b.name', 'c.name');

        if (empty($having)) $having = $this->having;
        $having_and = ($this->having_and) ? $this->having_and : false;

        $sort = $this->sort;

        // SQL Limit - start
        $start = (($page - 1) * $rp);
        $limit = "LIMIT " . $start . ", " . $rp;
        // SQL Limit - end

        // SQL where - start
        $query = $this->query;
        $qtype = $this->qtype;

        $where = "";
        if (!(empty($query) || empty($qtype))) {
            if (in_array($qtype, $valid_search_fields)) {
                $where .= " AND $qtype LIKE :query ";
            } else {
                $this->query = $qtype = null;
                $this->qtype = $query = null;
            }
        }
        if ($this->biller) $where .= " AND b.id = '$this->biller' ";
        if ($this->customer) $where .= " AND c.id = '$this->customer' ";
        if ($this->where_field) $where .= " AND $this->where_field = '$this->where_value' ";
        // SQL where - end

        // Check that the sort field is OK
        // @formatter:off
        $validFields = array('index_id',
                             'index_name',
                             'iv.id',
                             'biller',
                             'customer',
                             'invoice_total',
                             'owing',
                             'date',
                             'aging',
                             'type',
                             'preference',
                             'type_id');
        // @formatter:on

        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }

        if (strstr($type, "count")) {
            // unset($limit);
            $limit = "";
        }

        $sql_having = '';

        switch ($having) {
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

        switch ($having_and) {
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

        // @formatter:off
        switch ($config->database->adapter) {
            case "pdo_pgsql":
                $sql = "SELECT iv.id,
                               iv.index_id as index_id,
                               b.name AS Biller,
                               c.name AS Customer,
                               sum(ii.total) AS INV_TOTAL,
                               coalesce(SUM(ap.ac_amount), 0) AS INV_PAID,
                               (SUM(ii.total) - coalesce(sum(ap.ac_amount), 0)) AS INV_OWING,
                               to_char(date,'YYYY-MM-DD') AS Date,
                               (SELECT now()::date - iv.date) AS Age,
                               (CASE WHEN now()::date - iv.date <= '14 days'::interval THEN '0-14'
                                     WHEN now()::date - iv.date <= '30 days'::interval THEN '15-30'
                                     WHEN now()::date - iv.date <= '60 days'::interval THEN '31-60'
                                     WHEN now()::date - iv.date <= '90 days'::interval THEN '61-90'
                                     ELSE '90+'
                                END) AS Aging,
                               iv.type_id AS type_id,
                               p.pref_description AS type,
                               p.pref_inv_wording AS invoice_wording
                        FROM " . TB_PREFIX . "invoices iv
                        LEFT JOIN " . TB_PREFIX . "payment ap       ON ap.ac_inv_id  = iv.id
                        LEFT JOIN " . TB_PREFIX . "invoice_items ii ON ii.invoice_id = iv.id
                        LEFT JOIN " . TB_PREFIX . "biller b         ON b.id          = iv.biller_id
                        LEFT JOIN " . TB_PREFIX . "customers c      ON c.id          = iv.customer_id
                        LEFT JOIN " . TB_PREFIX . "preferences p    ON p.pref_id     = iv.preference_id
                        WHERE iv.domain_id = :domain_id
                              $where
                        GROUP BY iv.id, b.name, c.name, date, age, aging, type
                        ORDER BY $sort $dir
                        LIMIT $limit
                        OFFSET $start";
                break;

            case "pdo_mysql":
            default:
                $sql = "SELECT iv.id,
                               iv.index_id as index_id,
                               b.name AS biller,
                               c.name AS customer,
                               DATE_FORMAT(date,'%Y-%m-%d') AS date,
                       (SELECT coalesce(SUM(ii.total), 0) FROM " . TB_PREFIX . "invoice_items ii
                        WHERE ii.invoice_id = iv.id
                          AND ii.domain_id = :domain_id) AS invoice_total,
                       (SELECT coalesce(SUM(ac_amount), 0) FROM " . TB_PREFIX . "payment ap
                        WHERE ap.ac_inv_id = iv.id
                          AND ap.domain_id = :domain_id) AS INV_PAID,
                       (SELECT invoice_total - INV_PAID) As owing, ";

                // Only run aging for real full query ($type is empty for full query or count for count query)
                if ($type == '') {
                    $sql .= "(SELECT IF((owing = 0 OR owing < 0), 0, DateDiff(now(), date))) AS Age,
                             (SELECT (CASE WHEN Age  =  0 THEN ''
                                           WHEN Age <= 14 THEN '0-14'
                                           WHEN Age <= 30 THEN '15-30'
                                           WHEN Age <= 60 THEN '31-60'
                                           WHEN Age <= 90 THEN '61-90'
                                           ELSE '90+'  END)) AS aging, ";
                } else {
                    $sql .= "'' as Age,
                             '' as aging, ";
                }
                $sql .= "iv.type_id As type_id,
                         pf.pref_description AS preference,
                         pf.status AS status,
                         (SELECT CONCAT(pf.pref_inv_wording,' ',iv.index_id)) as index_name
                          FROM " . TB_PREFIX . "invoices iv
                          LEFT JOIN " . TB_PREFIX . "biller b
                                        ON (b.id = iv.biller_id AND b.domain_id  = iv.domain_id)
                          LEFT JOIN " . TB_PREFIX . "customers c
                                        ON (c.id = iv.customer_id AND c.domain_id  = iv.domain_id)
                          LEFT JOIN " . TB_PREFIX . "preferences pf
                                        ON (pf.pref_id = iv.preference_id AND pf.domain_id = iv.domain_id)
                          WHERE iv.domain_id = :domain_id
                                $where
                          GROUP BY iv.id
                          $sql_having
                          ORDER BY $sort $dir
                          $limit";
                break;
        }
        // @formatter:on

        if (empty($query)) {
            $result = dbQuery($sql, ':domain_id', $domain_id);
        } else {
            $result = dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
        }
        return $result;
    }

    public function select_all_where() {
        $domain_id = domain_id::get($this->domain_id);

        $where = "";
        if ($this->filter == "date") {
            $where = "AND date BETWEEN '$this->start_date' AND '$this->end_date'";
            // NOTE - Rich Rowley add the $where value to the $sql below. Previously
            //        it was defined here but not used below.
        }

        // @formatter:off
        $sql = "SELECT i.*,
                       p.pref_description AS preference
                FROM " . TB_PREFIX . "invoices i
                LEFT JOIN " . TB_PREFIX . "preferences p
                      ON (i.preference_id = p.pref_id AND i.domain_id = p.domain_id)
                WHERE i.domain_id = :domain_id
                      $where
                ORDER BY i.id";
        $sth = dbQuery($sql, ':domain_id', $domain_id);
        // @formatter:on

        return $sth->fetchAll();
    }

    public function getInvoiceItems($id, $domain_id = '') {
        if (!empty($domain_id)) $this->domain_id = $domain_id;

        // @formatter:off
        $sql = "SELECT * FROM " . TB_PREFIX . "invoice_items
                WHERE invoice_id = :id AND domain_id = :domain_id
                ORDER BY id";
        // @formatter:on
        $sth = dbQuery($sql, ':id', $id, ':domain_id', $this->domain_id);

        $invoiceItems = array();
        while ($invoiceItem = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (isset($invoiceItem['attribute'])) {
                $invoiceItem['attribute_decode'] = json_decode($invoiceItem['attribute'], true);
                foreach ($invoiceItem['attribute_decode'] as $key => $value) {
                    $invoiceItem['attribute_json'][$key]['name']    = ProductAttributes::getName($key);
                    $invoiceItem['attribute_json'][$key]['value']   = ProductAttributes::getValue($key, $value);
                    $invoiceItem['attribute_json'][$key]['type']    = ProductAttributes::getType($key);
                    $invoiceItem['attribute_json'][$key]['visible'] = ProductAttributes::getVisible($key);
                }
            }

            $sql = "SELECT * FROM " . TB_PREFIX . "products WHERE id = :id AND domain_id = :domain_id";
            $tth = dbQuery($sql, ':id', $invoiceItem['product_id'], ':domain_id', $this->domain_id);
            $invoiceItem['product'] = $tth->fetch(PDO::FETCH_ASSOC);

            $tax = taxesGroupedForInvoiceItem($invoiceItem['id']);
            foreach ($tax as $key => $value) {
                $invoiceItem['tax'][$key] = $value['tax_id'];
            }
            $invoiceItems[] = $invoiceItem;
        }

        return $invoiceItems;
    }

    /**
     * Get Invoice type.
     * @param string $id Invoice type ID.
     * @return array Associative array for <i>invoice_type</i> record accessed.
     */
    public static function getInvoiceType($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("inv_ty_id", $id);
        $result = $pdoDb->request("SELECT", "invoice_type");
        return $result;
    }

    /**
     * Function: are_there_any
     *
     * Used to see if there are any invoices in the database for a given domain
     * Called directly from index.php with Invoice::are_there_any()
     * and hence cannot use the $this property
     */
    public function are_there_any($domain_id = '') {
        if (!empty($domain_id)) $this->domain_id = $domain_id;

        $sql = "SELECT count(*) AS count FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
        $sth = dbQuery($sql, ':domain_id', $this->domain_id);

        $count = $sth->fetch();
        return $count['count'];
    }

    /**
     * Function getInvoiceGross
     *
     * Used to get the gross total for a given Invoice number
     */
    public function getInvoiceGross($invoice_id, $domain_id = '') {
        if (!empty($domain_id)) $this->domain_id = $domain_id;

        // @formatter:off
        $sql = "SELECT SUM(gross_total) AS gross_total
                FROM " . TB_PREFIX . "invoice_items
                WHERE invoice_id = :invoice_id AND domain_id = :domain_id";
        // @formatter:on
        $sth = dbQuery($sql, ':invoice_id', $invoice_id, ':domain_id', $this->domain_id);
        $res = $sth->fetch();

        return $res['gross_total'];
    }

    /**
     * Function Invoice::max
     *
     * Used to get the max Invoice id
     * is called directly from sql_patches.php with Invoice::max()
     * and hence $this->domain_id is not usable
     */
    public function max($domain_id = '') {
        global $patchCount;

        if ($patchCount < '179') {
            $sql = "SELECT MAX(id) AS max FROM " . TB_PREFIX . "invoices";
            $sth = dbQuery($sql);
        } else {
            if (!empty($domain_id)) $this->domain_id = $domain_id;
            $sql = "SELECT MAX(id) AS max FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id";
            $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        }

        $count = $sth->fetch();
        return $count['max'];
    }

    public function recur() {
        $invoice = $this->select($this->id, $this->domain_id);
        // @formatter:off
        $ni = new Invoice();
        $ni->domain_id     = $invoice['domain_id'];
        $ni->biller_id     = $invoice['biller_id'];
        $ni->customer_id   = $invoice['customer_id'];
        $ni->type_id       = $invoice['type_id'];
        $ni->preference_id = $invoice['preference_id'];
        $ni->date          = date('Y-m-d');
        $ni->custom_field1 = $invoice['custom_field1'];
        $ni->custom_field2 = $invoice['custom_field2'];
        $ni->custom_field3 = $invoice['custom_field3'];
        $ni->custom_field4 = $invoice['custom_field4'];
        $ni->note = $invoice['note'];
        $ni_id = $ni->insert();

        // insert each line item
        $nii = new Invoice();
        $nii->invoice_id = $ni_id;
        $nii->domain_id  = $ni->domain_id;
        foreach ($invoice['invoice_items'] as $v) {
            $nii->quantity    = $v['quantity'];
            $nii->product_id  = $v['product_id'];
            $nii->unit_price  = $v['unit_price'];
            $nii->tax_amount  = $v['tax_amount'];
            $nii->gross_total = $v['gross_total'];
            $nii->description = $v['description'];
            $nii->total       = $v['total'];
            $nii->attribute   = $v['attribute'];
            $nii->tax         = $v['tax'];
            $nii->insert_item();
        }
        // @formatter:on

        return $ni_id;
    }
}
