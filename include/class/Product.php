<?php
class Product {
    public static function count() {
        global $pdoDb;
        $pdoDb->addToFunctions("COUNT(id) as count");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        return $pdoDb->request("SELECT", "products");
    }

    public static function select($id) {
        global $pdoDb, $LANG;

        $cs = new CaseStmt("enabled", "wording_for_enabled");
        $cs->addWhen("=", ENABLED, $LANG['enabled']);
        $cs->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($cs);

        $pdoDb->addSimpleWhere("id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "products");
        return $rows['0'];
    }

    public static function select_all($active = true) {
        global $pdoDb, $LANG;

        $cs = new CaseStmt("enabled", "wording_for_enabled");
        $cs->addWhen("=", ENABLED, $LANG['enabled']);
        $cs->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($cs);

        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), 'AND');
        $pdoDb->addSimpleWhere("enabled", ENABLED);

        $pdoDb->setOrderBy(array(array("description","A"), array("id","A")));

        $pdoDb->setSelectAll(true);

        return $pdoDb->request("SELECT", "products");
    }

    public static function xml_select($type, $dir, $sort, $rp, $page) {
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

        $ca = new CaseStmt("p.enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);
        // @formatter:on

        $rows = $pdoDb->request("SELECT", "products", "p");
        return $rows;
    }

    /**
     * Insert a new record in the products table.
     * @param number $enabled Product enabled/disabled status used if not present in
     *        the <b>$_POST</b> array. Set to 1 (default) for enabled; 0 for disabled.
     * @param number $visible Flags record seen in list. Defaults to 1 (visible).
     *        Set to 0 for not visible.
     * @param string $domain_id Domain user is logged into.
     * @return PDO statement object on success, false on failure.
     */
    public static function insertProduct($enabled=ENABLED, $visible=1) {
        global $pdoDb;

        $cflgs_enabled = isExtensionEnabled('custom_flags');

        if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];

        if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) return false;

        $attr = array();
        foreach ($attributes as $v) {
            if (isset($_POST['attribute' . $v['id']]) && $_POST['attribute' . $v['id']] == 'true') {
                $attr[$v['id']] = $_POST['attribute' . $v['id']];
            }
        }

        // @formatter:off
        $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
        $show_description     = (isset($_POST['show_description']    ) && $_POST['show_description'    ] == 'true' ? 'Y' : NULL);

        if ($cflgs_enabled) {
            $custom_flags = '0000000000';
            for ($i = 1; $i <= 10; $i++) {
                if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                    $custom_flags = substr_replace($custom_flags, ENABLED, $i, 1);
                }
            }
        }

        $fauxPost = array('domain_id'            => domain_id::get(),
                          'description'          => (isset($_POST['description']   ) ? $_POST['description']    : ""),
                          'unit_price'           => (isset($_POST['unit_price']    ) ? $_POST['unit_price']     : "0"),
                          'cost'                 => (isset($_POST['cost']          ) ? $_POST['cost']           : "0"),
                          'reorder_level'        => (isset($_POST['reorder_level'] ) ? $_POST['reorder_level']  : "0"),
                          'custom_field1'        => (isset($_POST['custom_field1'] ) ? $_POST['custom_field1']  : ""),
                          'custom_field2'        => (isset($_POST['custom_field2'] ) ? $_POST['custom_field2']  : ""),
                          'custom_field3'        => (isset($_POST['custom_field3'] ) ? $_POST['custom_field3']  : ""),
                          'custom_field4'        => (isset($_POST['custom_field4'] ) ? $_POST['custom_field4']  : ""),
                          'notes'                => (isset($_POST['notes']         ) ? $_POST['notes']          : ""),
                          'default_tax_id'       => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                          'enabled'              => $enabled,
                          'visible'              => $visible,
                          'attribute'            => json_encode($attr),
                          'notes_as_description' => $notes_as_description,
                          'show_description'     => $show_description);
        if ($cflgs_enabled) {
            $fauxPost['custom_flags'] = $custom_flags;
        }
        $pdoDb->setFauxPost($fauxPost);
        $pdoDb->setExcludedFields("id");
        // @formatter:on

        if ($pdoDb->request("INSERT", "products") === false) return false;
        return true;
    }

    /**
     * Update a product record.
     * @param string $domain_id Domain user is logged into.
     * @return PDO statement object on success, false on failure.
     */
    public static function updateProduct() {
        global $pdoDb;

        $cflgs_enabled = isExtensionEnabled('custom_flags');

        if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) return false;

        $attr = array();
        foreach ($attributes as $v) {
            $tmp = (isset($_POST['attribute' . $v['id']]) ? $_POST['attribute' . $v['id']] : "");
            if ($tmp == 'true') {
                $attr[$v['id']] = $tmp;
            }
        }

        // @formatter:off
        $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
        $show_description     = (isset($_POST['show_description'])     && $_POST['show_description']     == 'true' ? 'Y' : NULL);

        if ($cflgs_enabled) {
            $custom_flags = '0000000000';
            for ($i = 1; $i <= 10; $i++) {
                if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                    $custom_flags = substr_replace($custom_flags, ENABLED, $i - 1, 1);
                }
            }
        }

        $fauxPost = array('description'          => (isset($_POST['description'])    ? $_POST['description']    : ""),
                          'enabled'              => (isset($_POST['enabled'])        ? $_POST['enabled']        : ""),
                          'notes'                => (isset($_POST['notes'])          ? $_POST['notes']          : ""),
                          'default_tax_id'       => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                          'custom_field1'        => (isset($_POST['custom_field1'])  ? $_POST['custom_field1']  : ""),
                          'custom_field2'        => (isset($_POST['custom_field2'])  ? $_POST['custom_field2']  : ""),
                          'custom_field3'        => (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : ""),
                          'custom_field4'        => (isset($_POST['custom_field4'])  ? $_POST['custom_field4']  : ""),
                          'unit_price'           => (isset($_POST['unit_price'])     ? $_POST['unit_price']     : "0"),
                          'cost'                 => (isset($_POST['cost'])           ? $_POST['cost']           : "0"),
                          'reorder_level'        => (isset($_POST['reorder_level'])  ? $_POST['reorder_level']  : "0"),
                          'attribute'            => json_encode($attr),
                          'notes_as_description' => $notes_as_description,
                          'show_description'     => $show_description);
        if ($cflgs_enabled) {
            $fauxPost['custom_flags'] = $custom_flags;
        }
        $pdoDb->setFauxPost($fauxPost);

        $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $pdoDb->setExcludedFields(array("id", "domain_id"));
        // @formatter:on

        return $pdoDb->request("UPDATE", "products");
    }
}
