<?php
require_once 'include/class/ProductAttributes.php';

class Invoice {

    /**
     * Insert a new invoice record
     * @param unknown $index_id
     * @param unknown $biller_id
     * @param unknown $customer_id
     * @param unknown $type_id
     * @param unknown $preference_id
     * @param unknown $date
     * @param unknown $note
     * @param unknown $custom_field1
     * @param unknown $custom_field2
     * @param unknown $custom_field3
     * @param unknown $custom_field4
     * @return integer Unique ID of the new invoice record.
     */
    public static function insert($biller_id, $customer_id  , $type_id      , $preference_id, $date,
                                  $note     , $custom_field1, $custom_field2, $custom_field3, $custom_field4) {
        global $pdoDb;
        $domain_id = domain_id::get();
        // @formatter:off
        $pref_group = Preferences::getPreference($preference_id, $domain_id);
        $index_id   = Index::next('invoice', $pref_group['index_group'], $domain_id);
        $clean_date = sqlDateWithTime($date);
        $pdoDb->setFauxPost(array('index_id'      => $index_id,
                                  'domain_id'     => $domain_id,
                                  'biller_id'     => $biller_id,
                                  'customer_id'   => $customer_id,
                                  'type_id'       => $type_id,
                                  'preference_id' => $preference_id,
                                  'date'          => $clean_date,
                                  'note'          => trim($note),
                                  'custom_field1' => $custom_field1,
                                  'custom_field2' => $custom_field2,
                                  'custom_field3' => $custom_field3,
                                  'custom_field4' => $custom_field4));
        $id = $pdoDb->request("INSERT", "invoices");
        // @formatter:on

        Index::increment('invoice', $pref_group['index_group'], $domain_id);
        return $id;
    }

    /**
     * Insert a new invoice_item and the invoice_item_tax records.
     * @param unknown $invoice_id
     * @param unknown $quantity
     * @param unknown $product_id
     * @param unknown $unit_price
     * @param unknown $tax_amount
     * @param unknown $gross_total
     * @param unknown $description
     * @param unknown $total
     * @param unknown $attribute
     * @param unknown $tax
     * @param unknown $unit_price
     * @return integer Unique ID of the new invoice_item record.
     */
    public static function insert_item($invoice_id , $quantity, $product_id, $unit_price, $tax_amount, $gross_total,
                                       $description, $total   , $attribute , $tax_id) {
        global $pdoDb;
        $domain_id = domain_id::get();

        if (!self::invoice_items_check_fk(null, $product_id, $tax_id, true)) return NULL;

        // @formatter:off
        $pdoDb->setFauxPost(array('invoice_id'  => $invoice_id,
                                  'domain_id'   => $domain_id,
                                  'quantity'    => $quantity,
                                  'product_id'  => $product_id,
                                  'unit_price'  => $unit_price,
                                  'tax_amount'  => $tax_amount,
                                  'gross_total' => $gross_total,
                                  'description' => trim($description),
                                  'total'       => $total,
                                  'attribute'   => $attribute));
        $id = $pdoDb->request("INSERT", "invoice_items");
        // @formatter:on

        self::invoice_item_tax($id, $tax_id, $unit_price, $quantity, false);
        return $id;
    }

    /**
     * Insert a new <b>invoice_items</b> record.
     * @param integer $invoice_id <b>id</b>
     * @param unknown $quantity
     * @param unknown $product_id
     * @param unknown $line_number
     * @param unknown $tax_id
     * @param string $description
     * @param string $unit_price
     * @param string $attribute
     * @return integer <b>id</b> of new <i>invoice_items</i> record.
     */
    public static function insertInvoiceItem($invoice_id, $quantity, $product_id, $line_number   , $tax_id,
                                             $description = "", $unit_price = "", $attribute = "") {
        global $LANG;

        // do taxes
        $attr = array();
        if (!empty($attribute)) {
            foreach ($attribute as $k => $v) {
                if ($attribute[$v] !== '') {
                    $attr[$k] = $v;
                }
            }
        }

        $tax_amount  = Taxes::getTaxesPerLineItem($tax_id, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total       = $gross_total + $tax_amount;

        // Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
        if ($description == $LANG['description']) $description = "";

        self::insert_item($invoice_id , $quantity, $product_id       , $unit_price, $tax_amount, $gross_total,
                          $description, $total   , json_encode($attr), $tax_id);
        return id;
    }

    /**
     *
     * @param unknown $invoice_id
     * @return boolean <b>true</b> if update successful; otherwise <b>false</b>.
     */
    public static function updateInvoice($invoice_id) {
        global $pdoDb;

        $current_invoice = Invoice::select($_POST['id']);
        $current_pref_group = Preferences::getPreference($current_invoice['preference_id']);

        $new_pref_group = Preferences::getPreference($_POST['preference_id']);

        $index_id = $current_invoice['index_id'];

        if ($current_pref_group['index_group'] != $new_pref_group['index_group']) {
            $index_id = Index::increment('invoice', $new_pref_group['index_group']);
        }

        $type = $current_invoice['type_id'];
        // TODO: Add foriegn key logic to database definition
        if (!self::invoice_check_fk($_POST['biller_id'], $_POST['customer_id'], $type, $_POST['preference_id'])) return NULL;

        $pdoDb->addSimpleWhere("id", $invoice_id);
        $pdoDb->setFauxPost(array('index_id'      => $index_id,
                                  'biller_id'     => $_POST['biller_id'],
                                  'customer_id'   => $_POST['customer_id'],
                                  'preference_id' => $_POST['preference_id'],
                                  'date'          => sqlDateWithTime($_POST['date']),
                                  'note'          => trim($_POST['note']),
                                  'customField1'  => (isset($_POST['customField1']) ? $_POST['customField1'] : ''),
                                  'customField2'  => (isset($_POST['customField2']) ? $_POST['customField2'] : ''),
                                  'customField3'  => (isset($_POST['customField3']) ? $_POST['customField3'] : ''),
                                  'customField4'  => (isset($_POST['customField4']) ? $_POST['customField4'] : '')));
        $result = $pdoDb->request("UPDATE", "invoices");
        return $result;
    }

    /**
     * Update invoice_items table for a specific entry.
     * @param int $id Unique id for the record to be updated.
     * @param int $quantity Number of items
     * @param int $product_id Unique id of the si_products record for this item.
     * @param int $line_number Line item number for the position on the invoice.
     * @param int $tax_id Unique id for the taxes to apply to this line item.
     * @param string $description Extended description for this line item.
     * @param decimal $unit_price Price of each unit of this item.
     * @param string $attribute Attributes for invoice.
     * @return boolean true always returned.
     */
    public static function updateInvoiceItem($id    , $quantity   , $product_id, $line_number,
                                             $tax_id, $description, $unit_price, $attribute) {
        global $LANG, $pdoDb;

        $attr = array();
        if (is_array($attribute)) {
            foreach ($attribute as $k => $v) {
                if ($attribute[$v] !== '') {
                    $attr[$k] = $v;
                }
            }
        }
        $tax_amount  = Taxes::getTaxesPerLineItem($tax_id, $quantity, $unit_price);
        $gross_total = $unit_price * $quantity;
        $total       = $gross_total + $tax_amount;
        if ($description == $LANG['description']) $description = "";

        if (!self::invoice_items_check_fk(null, $product_id, $tax_id, true)) return NULL;

        // @formatter:off
        $pdoDb->addSimpleWhere("id", $id);
        $pdoDb->setFauxPost(array('quantity'    => $quantity,
                                  'product_id'  => $product_id,
                                  'unit_price'  => $unit_price,
                                  'tax_amount'  => $tax_amount,
                                  'gross_total' => $gross_total,
                                  'description' => $description,
                                  'total'       => $total,
                                  'attribute'   => json_encode($attr)));
        $pdoDb->request("UPDATE", "invoice_items");
        // @formatter:on

        self::invoice_item_tax($id, $tax_id, $unit_price, $quantity, true);
    }

    /**
     * Insert/update the multiple taxes for a invoice line item.
     * @return boolean <b>true</b> if successful, <b>false</b> if not.
     */
    public static function invoice_item_tax($invoice_item_id, $line_item_tax_id, $unit_price, $quantity, $update) {
        // if editing invoice delete all tax info then insert first then do insert again
        // probably can be done without delete - someone to look into this if required - TODO
        try {
            $domain_id = domain_id::get();
            $requests = new Requests();
            if ($update) {
                $request = new Request("DELETE", "invoice_item_tax");
                $request->addSimpleWhere("invoice_item_id", $invoice_item_id);
                $requests->add($request);
            }

            if (is_array($line_item_tax_id)) {
                foreach ($line_item_tax_id as $value) {
                    if (!empty($value)) {
                        // @formatter:off
                        $tax        = Taxes::getTaxRate($value, $domain_id);
                        $tax_amount = Taxes::lineItemTaxCalc($tax, $unit_price, $quantity);
                        $request = new Request("INSERT", "invoice_item_tax");
                        $request->setFauxPost(array('invoice_item_id' => $invoice_item_id,
                                                    'tax_id'          => $tax['tax_id'],
                                                    'tax_rate'        => $tax['tax_percentage'],
                                                    'tax_type'        => $tax['type'],
                                                    'tax_amount'      => $tax_amount));
                        // @formatter:on
                        $requests->add($request);
                    }
                }
            }
            $requests->process();
        } catch (PdoDbException $pde) {
            error_log("Invoice::invoice_item_tax(): Unable to process requests. Error: " . $pde->getMessage());
            return false;
        }
        return true;
    }

    /**
     *
     * @param unknown $id
     * @return Result
     */
    public static function select($id) {
        global $pdoDb;

        $domain_id = domain_id::get();
        // @formatter:off
        $pdoDb->setSelectList(array("i.*",
                                    new DbField("i.date", "date_original"),
                                    new DbField("p.pref_inv_wording", "preference"),
                                    new DbField("p.status")));
        $se = new Select(new FunctionStmt("CONCAT", "p.pref_inv_wording, ' ', i.index_id"), null, null, "index_name");
        $pdoDb->addToSelectStmts($se);

        $pdoDb->addToFunctions(new FunctionStmt("SUM", "ii.tax_amount", "total_tax"));

        $jn = new Join("LEFT", "preferences", "p");
        $jn->addSimpleItem("i.preference_id", new DbField("p.pref_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "invoice_items", "ii");
        $jn->addSimpleItem("ii.invoice_id", new DbField("i.id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("i.id", $id, "AND");
        $pdoDb->addSimpleWhere("i.domain_id", $domain_id);

        $rows = $pdoDb->request("SELECT", "invoices", "i");

        $invoice                  = $rows[0];
        $invoice['calc_date']     = date('Y-m-d', strtotime($invoice['date']));
        $invoice['date']          = siLocal::date($invoice['date']);
        $invoice['total']         = self::getInvoiceTotal($invoice['id']);
        $invoice['gross']         = self::getInvoiceGross($invoice['id']);
        $invoice['paid']          = Payment::calc_invoice_paid($invoice['id']);
        $invoice['owing']         = $invoice['total'] - $invoice['paid'];
        $invoice['invoice_items'] = self::getInvoiceItems($id);
        $invoice['total_tax']     = $invoice['total_tax'];
        $invoice['tax_grouped']   = self::taxesGroupedForInvoice($id);
        // @formatter:on
        return $invoice;
    }

    /**
     * Get all the inovice records with associated information.
     * @return array invoice records.
     */
    public static function get_all() {
        global $pdoDb;

        $pdoDb->setSelectList("i.id as id");

        $fn = new FunctionStmt("CONCAT", "p.pref_inv_wording, ' ', i.index_id");
        $pdoDb->addToSelectStmts(new Select($fn, null, null, "index_name"));

        $jn = new Join("LEFT", "preferences", "p");
        $jn->addSimpleItem("i.preference_id", new DbField("p.pref_id"), "AND");
        $jn->addSimpleItem("i.domain_id", new DbField("p.domain_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("i.domain_id", domain_id::get());

        $pdoDb->setOrderBy("index_name");

        $rows = $pdoDb->request("SELECT", "invoices", "i");
        return $rows;
    }

    public static function count() {
        global $pdoDb;

        domain_id::get();

        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "invoices");
        return $rows[0]['count'];
    }

    public static function getInvoice($id) {
        global $pdoDb;

        $domain_id = domain_id::get();

        $pdoDb->addSimpleWhere("id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $rows = $pdoDb->request("SELECT", "invoices");
        if (empty($rows)) {
            $invoice = array();
        } else {
            $invoice = $rows[0];

            // @formatter:off
            $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
            $invoice['date']      = siLocal::date($invoice['date']);
            $invoice['total']     = self::getInvoiceTotal($invoice['id']);
            $invoice['gross']     = self::getInvoiceGross($invoice['id']);
            $invoice['paid']      = Payment::calc_invoice_paid($invoice['id']);
            $invoice['owing']     = $invoice['total'] - $invoice['paid'];
            // invoice total tax
            $pdoDb->addToFunctions("SUM(tax_amount) AS total_tax");
            $pdoDb->addToFunctions("SUM(total) AS total");
            $pdoDb->addSimpleWhere("invoice_id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", $domain_id);
            $rows = $pdoDb->request("SELECT", "invoice_items");

            $invoice_item_tax = $rows[0];
            $invoice['total_tax']   = $invoice_item_tax['total_tax'];
            $invoice['tax_grouped'] = self::taxesGroupedForInvoice($id);
            // @formatter:on
        }
        return $invoice;
    }

    public static function getInvoices($q) {
        $q = strtolower($_GET["q"]);
        if (!$q) return;

        $invoices = self::select_all();
        foreach ($invoices as $invoice) {
            $invoice['calc_date'] = date('Y-m-d', strtotime($invoice['date']));
            $invoice['date']      = siLocal::date($invoice['date']);
            $invoice['total']     = self::getInvoiceTotal($invoice['id']);
            $invoice['paid']      = Payment::calc_invoice_paid($invoice['id']);
            $invoice['owing']     = $invoice['total'] - $invoice['paid'];

            if (strpos(strtolower($invoice['index_id']), $q) !== false) {
                // @formatter:off
                $invoice['id']    = htmlsafe($invoice['id']);
                $invoice['total'] = htmlsafe(number_format($invoice['total'],2));
                $invoice['paid']  = htmlsafe(number_format($invoice['paid'],2));
                $invoice['owing'] = htmlsafe(number_format($invoice['owing'],2));
                // @formatter:on
                echo "$invoice[id]|<table><tr><td class='details_screen'>$invoice[preference]:</td><td>$invoice[index_id]</td><td  class='details_screen'>Total: </td><td>$invoice[total] </td></tr><tr><td class='details_screen'>Biller: </td><td>$invoice[biller] </td><td class='details_screen'>Paid: </td><td>$invoice[paid] </td></tr><tr><td class='details_screen'>Customer: </td><td>$invoice[customer] </td><td class='details_screen'>Owing: </td><td><u>$invoice[owing]</u></td></tr></table>\n";
            }
        }

        return $invoice;
    }

    /**
     * Builds a <b>Havings</b> object for a predefined test.
     * Note: Valid parameters consist of an option and its parameter if a parameter is needed.
     * Here is the list of valid options:
     * <table>
     * <tr><th>Option</th><th>Parameter</th></tr>
     * <tr><td><b>date_between</b></td><td>array(start_date,end_date)</td></tr>
     * <tr><td><b>money_owed</b></td><td>n/a</td></tr>
     * <tr><td><b>paid</b></td><td>n/a</td></tr>
     * <tr><td><b>draft</b></td><td>n/a</td></tr>
     * <tr><td><b>real</b></td><td>n/a</td><tr>
     * </table>
     * @param string $option A valid option from the list above.
     * @param mixed $parms Parameter values required by the specified option.
     */
    public static function buildHavings($option, $parms=null) {
        $havings = new Havings();
        switch ($option) {
            case "date_between":
                $havings->add(true, "date", "BETWEEN", $parms, true);
                break;
            case "money_owed":
                $havings->addSimple("owing", ">", 0);
                $havings->addSimple("status", "=", ENABLED);
                break;
            case "paid":
                $havings->addSimple("owing", "=", "", "OR");
                $havings->addSimple("owing", "<", 0 );
                $havings->addSimple("status", "=", ENABLED);
                break;
            case "draft":
                $havings->addSimple("status", "<>", ENABLED);
                break;
            case "real":
                $havings->addSimple("status", "=", ENABLED);
                break;
        }
        return $havings;
    }

    /**
     * Standard invoice selection for display in flexgrid by xml files.
     * @param string $type Three setting:
     *        <ol>
     *        <li><b>count</b> - Accessed for row count based on select criteria. Excludes the <i>LIMIT</i>
     *                           setting and <i>aging</i> information to speed the calculation.</li>
     *        <li><b>noage</b> - Standard access but without aging information. Used by <i>Large Dataset</i>
     *                           system preferences setting to reduce access time.</li>
     *        <li><b>&nbsp;&nbsp;</b> - All other settings are result in normal access of data based on
     *                                  specified criteria.</li>
     *        </ol>
     * @param string $sort Field to order results.
     * @param string $dir Direction of the order (ASC, DESC, A or D).
     * @param string $rp Number of lines to report per page.
     * @param string $page Page number processed.
     * @param string $qtype Special query field name.
     * @param string $query Value to look for. Will be enclosed in wildcards and searched using <i>LIKE</i>.
     * @return array Selected rows.
     */
    public static function select_all($type="", $sort="", $dir="", $rp=null, $page="", $qtype="", $query="") {
        global $auth_session, $pdoDb;

        // If user role is customer or biller, then restrict invoices to those they have access to.
        if ($auth_session->role_name == 'customer') {
            $pdoDb->addSimpleWhere("c.id", $auth_session->user_id, "AND");
        } elseif ($auth_session->role_name == 'biller') {
            $pdoDb->addSimpleWhere("b.id", $auth_session->user_id, "AND");
        }

        // @formatter:off
        $count_type = ($type == "count");
        $noage_type = ($type == "noage" || $count_type);

        $validFields = array('index_id', 'index_name', 'iv.id', 'biller', 'customer'  , 'invoice_total',
                             'owing'   , 'date'      , 'aging', 'type'  , 'preference', 'type_id');
        // @formatter:on

        if (empty($sort) || !in_array($sort, $validFields)) $sort = "index_id";
        if (empty($dir)) $dir = "DESC";
        $pdoDb->setOrderBy(array($sort, $dir));

        // If caller pass a null value, that mean there is no limit.
        if (isset($rp) && !$count_type) {
            if (empty($rp  )) $rp    = "25";
            if (empty($page)) $page  = "1";
            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);
        }

        if (!(empty($query) || empty($qtype))) {
            if (in_array($qtype, array('iv.index_id', 'b.name', 'c.name'))) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }

        $pdoDb->setSelectList(array("iv.id",
                                    "iv.index_id AS index_id",
                                    "iv.type_id AS type_id",
                                    "b.name AS biller",
                                    "c.name AS customer",
                                    "pf.pref_description AS preference",
                                    "pf.status AS status"));

        $fn = new FunctionStmt("DATE_FORMAT", "date, '%Y-%m-%d'", "date");
        $pdoDb->addToFunctions($fn);

        $fn = new FunctionStmt("COALESCE", "SUM(ii.total),0");
        $fr = new FromStmt("invoice_items", "ii");
        $wh = new WhereClause();
        $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"));
        $se = new Select($fn, $fr, $wh, "invoice_total");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "SUM(ac_amount),0");
        $fr = new FromStmt("payment", "ap");
        $wh = new WhereClause();
        $wh->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"));
        $se = new Select($fn, $fr, $wh, "INV_PAID");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("", "(invoice_total - INV_PAID)");
        $se = new Select($fn, null, null, "owing");
        $pdoDb->addToSelectStmts($se);

        // Only run aging for real full query
        if ($noage_type) {
            $pdoDb->setSelectList("'' AS Age, '' AS aging");
        } else {
            $fn = new FunctionStmt("IF", "(owing = 0 OR owing < 0), 0, DateDiff(now(), date)");
            $se = new Select($fn, null, null, "Age");
            $pdoDb->addToSelectStmts($se);

            // @formatter:off
            $ca = new CaseStmt("Age");
            $ca->addWhen( "=",  "0",       "" );
            $ca->addWhen("<=", "14",   "0-14" );
            $ca->addWhen("<=", "30",  "15-30" );
            $ca->addWhen("<=", "60",  "31-60" );
            $ca->addWhen("<=", "90",  "61-90" );
            $ca->addWhen( ">", "90",     "90+", true);
            $pdoDb->addToSelectStmts(new Select($ca, null, null, "aging"));
            // @formatter:on
        }

        $fn = new FunctionStmt("CONCAT", "pf.pref_inv_wording, ' ', iv.index_id");
        $se = new Select($fn, null, null,"index_name");
        $pdoDb->addToSelectStmts($se);

        $jn = new Join("LEFT", "biller", "b");
        $jn->addSimpleItem("b.id", new DbField("iv.biller_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "customers", "c");
        $jn->addSimpleItem("c.id", new DbField("iv.customer_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("LEFT", "preferences", "pf");
        $jn->addSimpleItem("pf.pref_id", new DbField("iv.preference_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("iv.domain_id", domain_id::get());
        $pdoDb->setGroupBy("iv.index_id");

        $result = $pdoDb->request("SELECT", "invoices", "iv");
        return $result;
    }

    public static function getInvoiceItems($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("invoice_id", $id);
        $pdoDb->setOrderBy("id");
        $rows = $pdoDb->request("SELECT", "invoice_items");

        $invoiceItems = array();
        foreach($rows as $invoiceItem) {
            if (isset($invoiceItem['attribute'])) {
                $invoiceItem['attribute_decode'] = json_decode($invoiceItem['attribute'], true);
                foreach ($invoiceItem['attribute_decode'] as $key => $value) {
                    $invoiceItem['attribute_json'][$key]['name']    = ProductAttributes::getName($key);
                    $invoiceItem['attribute_json'][$key]['value']   = ProductAttributes::getValue($key, $value);
                    $invoiceItem['attribute_json'][$key]['type']    = ProductAttributes::getType($key);
                    $invoiceItem['attribute_json'][$key]['visible'] = ProductAttributes::getVisible($key);
                }
            }

            $pdoDb->addSimpleWhere("id", $invoiceItem['product_id']);
            $rows = $pdoDb->request("SELECT", "products");
            $invoiceItem['product'] = $rows[0];

            $tax = self::taxesGroupedForInvoiceItem($invoiceItem['id']);
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
     * Function getInvoiceGross
     * Used to get the gross total for a given Invoice number
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        gross totals from si_invoice_items will be summed.
     */
    private static function getInvoiceGross($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "gross_total", "gross_total"));
        $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
        $rows = $pdoDb->request("SELECT", "invoice_items");
        return $rows[0]['gross_total'];
    }

    /**
     * Function getInvoiceTotal
     * @param integer $invoice_id Unique ID (si_invoices id value) of invoice for which
     *        totals from si_invoice_items will be summed.
     * @return float
     */
    private static function getInvoiceTotal($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "total", "total"));
        $pdoDb->addSimpleWhere("invoice_id", $invoice_id); // domain_id not needed
        $rows = $pdoDb->request("SELECT", "invoice_items");
        return $rows[0]['total'];
    }

    /**
     * Function: calc_invoice_tax
     * Calculates the total tax for a given invoices
     * @params integer invoice_id The name of the field, ie. Custom Field 1, etc..
     * @return float Total tax amount.
     */
    private static function calc_invoice_tax($invoice_id) {
        global $pdoDb;
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "tax_amount", "total_tax"));
        $pdoDb->addSimpleWhere("invoice_id", $invoice_id);
        $rows = $pdoDb->request("SELECT", "invoice_items");
        return $rows[0]['total_tax'];
    }

    /** 
     * Purpose: to show a nice summary of total $ for tax for an invoice
     */
    public static function numberOfTaxesForInvoice($invoice_id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id);
        $pdoDb->setGroupBy("tax.tax_id");
        $pdoDb->addToFunctions(new FunctionStmt("DISTINCT", new DbField("tax.tax_id")));

        $jn = new Join("INNER", "invoice_item_tax", "item_tax");
        $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);
        $rows = $pdoDb->request("SELECT", "invoice_items", "item");
        return count($rows);
    }

    /**
     * Generates a nice summary of total $ for tax for an invoice
     * @param integer $invoice_id The <b>id</b> column for the invoice to get info for.
     * @return array Rows retrieve.
     */
    private static function taxesGroupedForInvoice($invoice_id) {
        global $pdoDb;
        $pdoDb->setSelectList(array(new DbField("tax.tax_description", "tax_name"),
                                    new DbField("item_tax.tax_rate", "tax_rate")));
        $pdoDb->addToFunctions(new FunctionStmt("SUM", "item_tax.tax_amount", "tax_amount"));
        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "*", "count"));

        $pdoDb->addSimpleWhere("item.invoice_id", $invoice_id);

        $jn = new Join("INNER", "invoice_item_tax", "item_tax");
        $jn->addSimpleItem("item_tax.invoice_item_id", new DbField("item.id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setGroupBy("tax.tax_id");

        $rows = $pdoDb->request("SELECT", "invoice_items", "item");

        return $rows;
    }

    /*
     * Function: taxesGroupedForInvoiceItem
     * Purpose: to show a nice summary of total $ for tax for an invoice item.
     * Used for invoice editing
     */
    private static function taxesGroupedForInvoiceItem($invoice_item_id) {
        global $pdoDb;

        $pdoDb->setSelectList(array("item_tax.id AS row_id",
                                    "tax.tax_description AS tax_name",
                                    "tax.tax_id AS tax_id"));

        $pdoDb->addSimpleWhere("item_tax.invoice_item_id", $invoice_item_id);

        $jn = new Join("LEFT", "tax", "tax");
        $jn->addSimpleItem("tax.tax_id", new DbField("item_tax.tax_id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setOrderBy("row_id");

        $rows = $pdoDb->request("SELECT", "invoice_item_tax", "item_tax");
        return $rows;
    }

    /**
     * Retrieve maximum invoice number assigned.
     * @param string $domain_id
     * @return Maximum invoice number assigned.
     */
    public static function maxIndexId($domain_id="") {
        global $pdoDb;

        $pdoDb->addToFunctions(new FunctionStmt("MAX", "index_id", "maxIndexId"));

        $pdoDb->addSimpleWhere("domain_id", domain_id::get($domain_id));

        $rows = $pdoDb->request("SELECT", "invoices");
        return $rows[0]['maxIndexId'];
    }

    public static function recur($invoice_id) {
        $invoice = self::select($invoice_id);
        // @formatter:off
        $id = self::insert($invoice['index_id'],
                           $invoice['biller_id'],
                           $invoice['customer_id'],
                           $invoice['type_id'],
                           $invoice['preference_id'],
                           $invoice['date'],
                           $invoice['note'],
                           $invoice['custom_field1'],
                           $invoice['custom_field2'],
                           $invoice['custom_field3'],
                           $invoice['custom_field4']);

        // insert each line item
        foreach ($invoice['invoice_items'] as $v) {
            self::insert_item($id,
                              $v['quantity'],
                              $v['product_id'],
                              $v['unit_price'],
                              $v['tax_amount'],
                              $v['gross_total'],
                              $v['description'],
                              $v['total'],
                              $v['attribute'],
                              $v['tax'],
                              $v['unit_price']);
        }
        // @formatter:on

        return $id;
    }

    /**
     * Manual verification of foreign keys.
     * Performs some manual FK checks on tables that the invoice table refers to.
     * Under normal conditions, this function will return true. Returning false
     * indicates that if the INSERT or UPDATE were to proceed, bad data could be
     * written to the database.
     * @param int $biller_id Unique ID for <b>si_biller</b> table.
     * @param int $customer_id Unique ID for <b>si_customers</b> table.
     * @param int $inv_ty_id Unique ID for <b>si_invoice_type</b> table.
     * @param int $pref_id Unique ID for <b>si_preferences</b> table.
     * @return boolean true if keys all test true; false otherwise.
     * TODO: Add FK logic to database.
     */
    private static function invoice_check_fk($biller_id, $customer_id, $inv_ty_id, $pref_id) {
        global $pdoDb;
        $domain_id = domain_id::get();

        // Check biller
        $pdoDb->addSimpleWhere("id", $biller_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "biller");
        if (empty($rows)) return false;

        // Check customer
        $pdoDb->addSimpleWhere("id", $customer_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "customers");
        if (empty($rows)) return false;

        // Check invoice type
        $pdoDb->addSimpleWhere("inv_ty_id", $inv_ty_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "invoice_type");
        if (empty($rows)) return false;

        // Check preferences
        $pdoDb->addSimpleWhere("pref_id", $pref_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setLimit(1);
        $rows = $pdoDb->request("SELECT", "preferences");
        if (empty($rows)) return false;

        // All good
        return true;
    }

    /**
     * Manual verification of foreign keys.
     * Performs some manual FK checks on tables that the invoice table refers to.
     * Under normal conditions, this function will return true. Returning false
     * indicates that if the INSERT or UPDATE were to proceed, bad data could be
     * written to the database.
     * @param int $invoice_id Unique ID for <b>si_invoices</b> table.
     * @param int $product_id Unique ID for <b>si_products</b> table.
     * @param int $tax_id Unique ID for <b>si_tax</b> table.
     * @param boolean $update <b>true</b> if check update constraints; <b>false</b> otherwise.
     * @return boolean true if keys all test true; false otherwise.
     * TODO: Add FK logic to database.
     */
    private static function invoice_items_check_fk($invoice_id, $product_id, $tax_id, $update) {
        global $pdoDb_admin;
        $domain_id = domain_id::get();
        // Check invoice
        if (!$update || !empty($invoice_id)) {
            $pdoDb_admin->addSimpleWhere("id", $invoice_id, "AND");
            $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
            $pdoDb_admin->setSelectList("id");
            $rows = $pdoDb_admin->request("SELECT", "invoices");
            if (empty($rows)) return false;
        }

        // Check product
        $pdoDb_admin->addSimpleWhere("id", $product_id, "AND");
        $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
        $pdoDb_admin->setSelectList("id");
        $rows = $pdoDb_admin->request("SELECT", "products");
        if (empty($rows)) return false;

        // Check tax id
        if (!empty($tax_id)) {
            if (is_array($tax_id)) {
                $tax_ids = $tax_id;
            } else {
                $tax_ids = array($tax_id);
            }
            foreach ($tax_ids as $tax_id) {
                $pdoDb_admin->addSimpleWhere("tax_id", $tax_id, "AND");
                $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
                $pdoDb_admin->setSelectList("tax_id");
                $rows = $pdoDb_admin->request("SELECT", "tax");
                if (empty($rows)) return false;
            }
        }

        return true;
    }

}
