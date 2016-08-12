<?php
class Product {
    public static function count() {
        global $pdoDb;
        $pdoDb->addToFunctions("COUNT(id) as count");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        return $pdoDb->request("SELECT", "products");
    }

    public static function get_all() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), 'AND');
        $pdoDb->addSimpleWhere("visible", ENABLED);
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

        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if (!empty($qtype) && !empty($query)) {
            $valid_search_fields = array('id', 'description', 'unit_price');
            if ( in_array($qtype, $valid_search_fields) ) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("p.visible", ENABLED, "AND");
        $pdoDb->addSimpleWhere("p.domain_id", domain_id::get());

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

        $fn = new FunctionStmt("COALESCE", "SUM(ii.quantity),0");
        $fr = new FromStmt("invoice_items", "ii");
        $fr->addTable("invoices", "iv");
        $fr->addTable("preferences", "pr");
        $wh = new WhereClause();
        $wh->addSimpleItem("ii.product_id", new DbField("p.id"), "AND");
        $wh->addSimpleItem("ii.domain_id", new DbField("p.domain_id"), "AND");
        $wh->addSimpleItem("ii.invoice_id", new DbField("iv.id"), "AND");
        $wh->addSimpleItem("iv.preference_id", new DbField("pr.pref_id"), "AND");
        $wh->addSimpleItem("pr.status", ENABLED);
        $se = new Select($fn, $fr, $wh, "qty_out");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "SUM(inv.quantity),0");
        $fr = new FromStmt("inventory", "inv");
        $wc = new WhereClause();
        $wc->addSimpleItem("inv.product_id", new DbField("p.id"), "AND");
        $wc->addSimpleItem("inv.domain_id" , new DbField("p.domain_id"));
        $se = new Select($fn, $fr, $wc, "qty_in");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("COALESCE", "p.reorder_level,0");
        $se = new Select($fn, null, null, "reorder_level");
        $pdoDb->addToSelectStmts($se);

        $fn = new FunctionStmt("", "qty_in");
        $fn->addPart("-",  "qty_out");
        $se = new Select($fn, null, null, "quantity");
        $pdoDb->addToSelectStmts($se);

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
