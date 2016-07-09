<?php
class product {
    public $domain_id;

    public function __construct() {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    public function count() {
        $sql = "SELECT count(id) as count FROM " . TB_PREFIX . "products WHERE domain_id = :domain_id ORDER BY id";
        $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        return $sth->fetch();
    }

    public function get_all() {
        $sql = "SELECT * FROM " . TB_PREFIX . "products WHERE domain_id = :domain_id AND visible = 1 ORDER BY description, id";
        $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        return $sth->fetchAll();
    }

    public function get($id) {
        $sql = "SELECT * FROM " . TB_PREFIX . "products WHERE domain_id = :domain_id AND id = :id";
        $sth = dbQuery($sql, ':domain_id', $this->domain_id, ':id', $id);
        return $sth->fetch();
    }

    public function select_all($type = '', $dir, $sort, $rp, $page) {
        global $LANG;

        $valid_search_fields = array('id', 'description', 'unit_price');

        if (intval($rp) != $rp) {
            $rp = 25;
        }

        $start = (($page - 1) * $rp);
        $limit = "LIMIT $start, $rp";

        if ($type == "count") {
            $limit = "";
        }

        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'DESC';
        }

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

        // Check that the sort field is OK
        $validFields = array('id', 'description', 'unit_price', 'enabled');

        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }

        // @formatter:off
        $inv_itms = TB_PREFIX . "invoice_items";
        $inv      = TB_PREFIX . "invoices";
        $pref     = TB_PREFIX . "preferences";
        $prd      = TB_PREFIX . "products";
        $invent   = TB_PREFIX . "inventory";
        $inv_id          = $inv      . ".id";
        $inv_prefid      = $inv      . ".preference_id";
        $inv_itms_dom_id = $inv_itms . ".domain_id";
        $inv_itms_inv_id = $inv_itms . ".invoice_id";
        $prd_id          = $prd      . ".id";
        $pref_id         = $pref     . ".pref_id";
        $pref_stat       = $pref     . ".status";

        $enabled  = $LANG['enabled'];
        $disabled = $LANG['disabled'];

        $sql = "SELECT id, description, unit_price,
                       (SELECT COALESCE(SUM(quantity),0) FROM $inv_itms, $inv, $pref
                         WHERE product_id       = $prd_id
                           AND $inv_itms_dom_id = :domain_id
                           AND $inv_itms_inv_id = $inv_id
                           AND $inv_prefid      = $pref_id
                           AND $pref_stat       = 1 ) AS qty_out,
                        (SELECT COALESCE(SUM(quantity),0) FROM $invent
                          WHERE product_id = $prd_id
                            AND domain_id = :domain_id) AS qty_in,
                        (SELECT COALESCE(reorder_level,0)) AS reorder_level ,
                        (SELECT qty_in - qty_out ) AS quantity,
                        (SELECT (CASE WHEN enabled = 0 THEN '$disabled' ELSE '$enabled' END )) AS enabled
                FROM $prd
                WHERE visible = 1
                  AND domain_id = :domain_id
                  $where
                ORDER BY $sort $dir
                $limit;";
        // @formatter:on

        if (empty($query)) {
            $result = dbQuery($sql, ':domain_id', $this->domain_id);
        } else {
            $result = dbQuery($sql, ':domain_id', $this->domain_id, ':query', "%$query%");
        }
        return $result;
    }
}
