<?php
class product {
    public $domain_id;

    public function __construct() {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    public function count() {
        global $pdoDb;
        $pdoDb->setSelectList(array());
        $pdoDb->addToFunctions("count(id) as count");
        $pdoDb->addSimpleWhere("domain_id", $this->domain_id);
        return $pdoDb->request("SELECT", "products");
    }

    public function get_all() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", $this->domain_id, 'AND');
        $pdoDb->addSimpleWhere("visible", "1");
        $pdoDb->setOrderBy("description");
        $pdoDb->setOrderBy("id");
        return $pdoDb->request("SELECT", "products");
    }

    public function get($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $this->domain_id);
        return $pdoDb->request("SELECT", "products");
    }

    public function select_all($type = '', $dir, $sort, $rp, $page) {
        global $LANG, $pdoDb;

        $valid_search_fields = array('id', 'description', 'unit_price');

        $where = "";
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if (!(empty($qtype) || empty($query))) {
            if (in_array($qtype, $valid_search_fields)) {
                $where = " AND $qtype LIKE :query ";
            } else {
                $qtype = null;
                $query = null;
            }
        }

        $where = "WHERE visible = 1 AND domain_id = $this->domain_id $where";

        $prd = TB_PREFIX . "products";
        if (($type == "count")) {
            $sql = "SELECT count(id) as count FROM $prd $where";
        } else {
            if (!in_array($sort, array('id', 'description', 'unit_price', 'enabled'))) $sort = "id";

            // @formatter:off
            $inv_itms        = TB_PREFIX . "invoice_items";
            $inv             = TB_PREFIX . "invoices";
            $pref            = TB_PREFIX . "preferences";
            $invent          = TB_PREFIX . "inventory";

            $inv_id          = $inv      . ".id";
            $inv_prefid      = $inv      . ".preference_id";
            $inv_itms_dom_id = $inv_itms . ".domain_id";
            $inv_itms_inv_id = $inv_itms . ".invoice_id";
            $prd_id          = $prd      . ".id";
            $pref_id         = $pref     . ".pref_id";
            $pref_stat       = $pref     . ".status";

            if (intval($rp) != $rp) $rp = 25;
            $start = (($page - 1) * $rp);
            $limit = "LIMIT $start, $rp";

            if (!preg_match('/^(asc|desc)$/iD', $dir)) $dir = 'DESC';

            $qty_out = "(SELECT COALESCE(SUM(quantity),0) FROM $inv_itms, $inv, $pref
                             WHERE product_id       = $prd_id
                               AND $inv_itms_dom_id = $this->domain_id
                               AND $inv_itms_inv_id = $inv_id
                               AND $inv_prefid      = $pref_id
                               AND $pref_stat       = 1 ) AS qty_out";

            $qty_in  = "(SELECT COALESCE(SUM(quantity),0) FROM $invent
                             WHERE product_id = $prd_id
                               AND domain_id = $this->domain_id) AS qty_in";

            $qty = "(SELECT qty_in - qty_out ) AS quantity";

            $reorder_lvl = "(SELECT COALESCE(reorder_level,0)) AS reorder_level";

            $enabled = "(SELECT (CASE WHEN enabled = '1' THEN '" .
                                      $LANG['enabled']  . "' ELSE '" .
                                      $LANG['disabled'] . "' END )) AS enabled";

            $sql = "SELECT id, description, unit_price, $qty_out, $qty_in, $reorder_lvl, $qty, $enabled
                    FROM $prd $where ORDER BY $sort $dir $limit;";
            // @formatter:on
        }

        $parms = array(':domain_id' => $this->domain_id);
        if (!empty($query)) $parms[':query'] = "%$query%";
        return $pdoDb->query($sql, $parms);
    }
}
