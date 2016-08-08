<?php
class Product {
    public static function count() {
        global $pdoDb;
        $pdoDb->setSelectList(array());
        $pdoDb->addToFunctions("count(id) as count");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        return $pdoDb->request("SELECT", "products");
    }

    public static function get_all() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), 'AND');
        $pdoDb->addSimpleWhere("visible", "1");
        $pdoDb->setOrderBy("description");
        $pdoDb->setOrderBy("id");
        return $pdoDb->request("SELECT", "products");
    }

    public static function get($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        return $pdoDb->request("SELECT", "products");
    }

    public static function select_all($type = '', $dir, $sort, $rp, $page) {
        global $LANG, $pdoDb;

        $domain_id = domain_id::get();

        $where = "";
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if (!empty($qtype) && !empty($query)) {
            $valid_search_fields = array('id', 'description', 'unit_price');
            if ( in_array($qtype, $valid_search_fields) ) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("p.visible", ENABLED, "AND");
        $pdoDb->addSimpleWhere("p.domain_id", $domain_id);

        if (($type == "count")) {
            $pdoDb->addToFunctions("COUNT(*) as count");
            $rows = $pdoDb->request("SELECT", "products", "p");
            return $rows[0]['count'];
        }

        if (intval($rp) != $rp) $rp = 25;

        $start = (($page-1) * $rp);
        $pdoDb->setLimit($rp, $start);

        if (in_array($sort, array('p.id','p.description','p.unit_price', 'p.enabled'))) {
            if (!preg_match('/^(a|asc|d|desc)$/iD', $dir)) $dir = 'D';
            $pdoDb->setOrderBy(array($sort, $dir));
        } else {
            // Default to major sort for enabled items first and secondary sort for descriptions.
            $pdoDb->setOrderBy(array(array("p.enabled", "D"), array("p.description", "A")));
        }

        // @formatter:off
        $pdoDb->setSelectList(array("p.id", "p.description", "p.unit_price", "p.enabled"));

        $wc = new WhereClause();
        $wc->addSimpleItem("ii.product_id"   , new DbField("p.id")      , "AND");
        $wc->addSimpleItem("ii.domain_id"    , $domain_id               , "AND");
        $wc->addSimpleItem("ii.invoice_id"   , new DbField("iv.id")     , "AND");
        $wc->addSimpleItem("iv.preference_id", new DbField("pr.pref_id"), "AND");
        $wc->addSimpleItem("pr.status"       , ENABLED);
        $fr = new FromStmt("invoice_items", "ii");
        $fr->addTable("invoices"   , "iv");
        $fr->addTable("preferences", "pr");
        $select = new Select(new FunctionStmt("SUM","COALESCE(ii.quantity,0)"), $fr, $wc, "qty_out");
        $pdoDb->addToSelectStmts($select);

        $wc = new WhereClause();
        $wc->addSimpleItem("inv.product_id", new DbField("p.id"), "AND");
        $wc->addSimpleItem("inv.domain_id" , $domain_id);
        $fr = new FromStmt("inventory", "inv");
        $select = new Select(new FunctionStmt("SUM", "COALESCE(inv.quantity,0)"), $fr, $wc, "qty_in");
        $pdoDb->addToSelectStmts($select);

        $select = new Select(new FunctionStmt("COALESCE", "p.reorder_level,0"), null, null, "reorder_level");
        $pdoDb->addToSelectStmts($select);

        $select = new Select(new FunctionStmt("", "qty_in - qty_out"), null, null, "quantity");
        $pdoDb->addToSelectStmts($select);

        $cs = new CaseStmt("p.enabled");
        $cs->addWhen( "=", ENABLED, $LANG['enabled']);
        $cs->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $select = new Select($cs, null, null, "enabled");
        $pdoDb->addToSelectStmts($select);
        // @formatter:on

        $rows = $pdoDb->request("SELECT", "products", "p");
        return $rows;
    }
}
