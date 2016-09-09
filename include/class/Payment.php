<?php
class Payment {
    public static function count($filter, $ol_pmt_id) {
        global $pdoDb;

        if ($filter == "online_payment_id") {
            $pdoDb->addSimpleWhere("ap.online_payment_id", $ol_pmt_id, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->addToFunctions("COUNT(DISTINCT ap.id) AS count");
        $rows = $pdoDb->request("SELECT", "payment", "ap");
        return $rows[0]['count'];
    }

    public static function select_by_date($start_date, $end_date, $filter, $ol_pmt_id) {
        global $pdoDb;

        if ($filter == "date") {
            $pdoDb->addToFunctions("COUNT(DISTINCT ap.id) AS count");
            $wi = new WhereItem(false, "ap.ac_date", "BETWEEN", array($start_date, $end_date), false, "AND");
            $pdoDb->addToWhere($wi);
        } else if ($filter == "online_payment_id") {
            $pdoDb->addSimpleWhere("ap.online_payment_id", "$ol_pmt_id", "AND");
        }
        $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());
        // @formatter:off
        $pdoDb->setSelectList( array("ap.*",
                                     "iv.index_id AS index_id",
                                     "iv.id AS invoice_id",
                                     "pref.pref_description AS preference",
                                     "pt.pt_description AS type",
                                     "c.name AS cname",
                                     "b.name AS bname"));
        // @formatter:on

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_payment_type", new DbField("pt.pt_id"));
        $pdoDb->addToJoins(array("LEFT", "payment_types", "pt", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "invoices", "iv"));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("c.id"));
        $oc->addSimpleItem("iv.domain_id", new DbField("c.comain_id"));
        $pdoDb->addToJoins(array("LEFT", "customers", "c"));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.biller_id", new DbField("b.id"));
        $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.preference_id", new DbField("pref.pref_id"));
        $oc->addSimpleItem("iv.domain_id", new DbField("pref.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "preferences", "pref", $oc));

        $pdoDb->setOrderBy(array("ap.id", "D"));

        $rows = $pdoDb->request("SELECT", "payment", "ap");
        return $rows;
    }
    
    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of invoice to retrieve payments for.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getInvoicePayments($id) {
        global $pdoDb;

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "payment", "ap", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "customers", "c", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

        $pdoDb->addSimpleWhere("iv.id", $id, "AND");
        $pdoDb->addSimpleWhere("iv.domain_id", domain_id::get());

        $pdoDb->setOrderBy(array("ap.id", "D"));

        $pdoDb->setSelectList(array("ap.*", "c.name AS cname", "b.name AS bname"));

        return $pdoDb->request("SELECT", "invoices", "iv");
    }
    
    /**
     * Get a specific payment type record.
     * @param int $id Unique ID of customer to retrieve payments for.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getCustomerPayments($id) {
        global $pdoDb;

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "invoices", "iv", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "payment", "ap", $oc));
        
        $oc = new OnClause();
        $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

        $pdoDb->addSimpleWhere("c.id", $id, "AND");
        $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());

        $pdoDb->setOrderBy(array("ap.id", "D"));

        $pdoDb->setSelectList(array("ap.*", "c.name AS cname", "b.name AS bname"));

        return $pdoDb->request("SELECT", "customers", "c");
    }

    /**
     * Get a specific payment record.
     * @param int $id Unique ID of record to retrieve.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     */
    public static function select($id) {
        global $pdoDb;

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "invoices", "iv", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "customers", "c", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

        $pdoDb->addSimpleWhere("ap.id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());

        $pdoDb->setSelectList(array("ap.*", "c.id AS customer_id", "c.name AS customer",
                                    "b.id AS biller_id", "b.name AS biller"));

        $rows = $pdoDb->request("SELECT", "payment", "ap");
        $payment = $rows[0];
        $payment['date'] = siLocal::date($payment['ac_date']);

        return $payment;
    }

    /**
     * Get a all payment records.
     * @param string $domain_id Domain ID logged into.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function select_all($only_enabled=false) {
        global $pdoDb;

        $oc = new OnClause();
        $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "invoices", "iv", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "customers", "c", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.biller_id", new DbField("b.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("b.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "biller", "b", $oc));

        $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());

        $pdoDb->setOrderBy(array("ap.id", "D"));

        $pdoDb->setSelectList(array("ap.*", "c.name AS cname", "b.name AS bname"));

        return $pdoDb->request("SELECT", "payment", "ap");
    }
    
    /**
     * Add payment type description to retrieved payment records.
     * @param array $payments Array of <i>Payment</i> object to update.
     * @return array <i>Payment</i> records with payment type description added.
     */
    public static function progressPayments($payments) {
        global $pdoDb;

        $progressPayments = array();
        foreach($payments as $payment) {
            $pdoDb->addSimpleWhere("pt_id", $payment['ac_payment_type'], "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            $pdoDb->setSelectList("pt_description");
            $result = $pdoDb->request("SELECT", "payment_types");
            if (empty($result)) {
                $payment['descripiton'] = "";
            } else {
                $payment['description'] = $result[0]['pt_description'];
            }
            $progressPayments[] = $payment;
        }
        return $progressPayments;
    }

    /**
     * Insert a new payment record
     * @param array $list <i>Faux Post</i> list of record's values.
     * @return integer <b>ID</b> of record inserted. Test for <i>=== false</i> for failure.
     */
    public static function insert($list) {
        global $pdoDb;

        $pdoDb->setExcludedFields(array("id" => 1));
        $pdoDb->setFauxPost($list);
        $result = $pdoDb->request("INSERT", "payment");
        return $result;
    }
}
