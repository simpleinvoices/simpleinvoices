<?php

class Biller
{
    /**
     * Get all biller records.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     * @throws PdoDbException
     */
    public static function get_all($active_only = false)
    {
        global $LANG, $pdoDb;

        if ($active_only) {
            $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca = new CaseStmt("enabled", "wording_for_enabled");
        $ca->addWhen("=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setOrderBy("name");

        $pdoDb->setSelectAll(true);

        $billers = $pdoDb->request("SELECT", "biller");
        return $billers;
    }

    /**
     * Retrieve a specified biller record.
     * @param string $id ID of the biller to retrieve.
     * @return array Associative array for record retrieved.
     * @throws PdoDbException
     */
    public static function select($id)
    {
        global $LANG, $pdoDb;

        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);

        $ca = new CaseStmt("enabled", "wording_for_enabled");
        $ca->addWhen("=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "biller");
        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Get a default biller name.
     * @return string Default biller name
     * @throws PdoDbException
     */
    public static function getDefaultBiller()
    {
        global $pdoDb;
        $pdoDb->addSimpleWhere("s.name", "biller", "AND");
        $pdoDb->addSimpleWhere("s.domain_id", domain_id::get());
        $jn = new Join('LEFT', 'biller', 'b');
        $jn->addSimpleItem("b.id", new DbField("s.value"), "AND");
        $jn->addSimpleItem("b.domain_id", new DbField("s.domain_id"));
        $pdoDb->addToJoins($jn);
        $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        if (empty($rows)) return $rows;
        return $rows[0];
    }

    /**
     * Insert a new biller record
     * @return integer|boolean ID if successful, test "=== false" if failed.
     * @throws PdoDbException
     */
    public static function insertBiller()
    {
        global $pdoDb;
        $_POST['domain_id'] = domain_id::get();
        if (empty($_POST['custom_field1'])) $_POST['custom_field1'] = "";
        if (empty($_POST['custom_field2'])) $_POST['custom_field2'] = "";
        if (empty($_POST['custom_field3'])) $_POST['custom_field3'] = "";
        if (empty($_POST['custom_field4'])) $_POST['custom_field4'] = "";

        $_POST['notes'] = (empty($_POST['note']) ? "" : trim($_POST['note']));

        $pdoDb->setExcludedFields("id");
        $id = $pdoDb->request("INSERT", "biller");
        return !empty($id);
    }

    /**
     * Update <b>biller</b> table record.
     * @return boolean <b>true</b> if update successful
     * @throws PdoDbException
     */
    public static function updateBiller()
    {
        global $pdoDb;
        // The fields to be update must be in the $_POST array indexed by their
        // actual field name.
        $pdoDb->setExcludedFields(array("id", "domain_id"));
        $pdoDb->addSimpleWhere("id", $_GET['id'], 'AND');
        $pdoDb->addSimpleWhere('domain_id', domain_id::get());

        $result = $pdoDb->request("UPDATE", "biller");
        return $result;
    }

    /**
     * Calculate the number of invoices in the database
     * @return integer Count of invoices in the database
     * @throws PdoDbException
     */
    public static function count()
    {
        global $pdoDb;

        domain_id::get();

        $pdoDb->addToFunctions(new FunctionStmt("COUNT", "id", "count"));
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "biller");
        return $rows[0]['count'];
    }

    /**
     * Selection of record for the xml list screen
     * @param string $type - 'count' if only count of records desired, otherwise selection of records to display.
     * @param $start - Record to start out.
     * @param $dir - Sort order (ASC or DESC)
     * @param $sort - Field to sort on
     * @param $rp - Number of records to select for this page
     * @param $page - Pages processed.
     * @return mixed - Count if 'count' requested, Rows selected from biller table.
     * @throws PdoDbException
     */
    function sql($type = '', $start, $dir, $sort, $rp, $page)
    {
        global $LANG, $pdoDb;

        $count_type = ($type == "count");

        // If caller pass a null value, that mean there is no limit.
        if (isset($rp) && !$count_type) {
            if (empty($rp)) $rp = "25";
            if (empty($page)) $page = "1";
            $start = (($page - 1) * $rp);
            $pdoDb->setLimit($rp, $start);
        }

        if (!(empty($_POST['query']) || empty($_POST['qtype']))) {
            $query = $_POST['query'];
            $qtype = $_POST['qtype'];
            if (in_array($qtype, array("id", "name", "email"))) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        if ($type == "count") {
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "biller");
            return $rows[0]['count'];
        }

        $expr_list = array("id", "domain_id", "name", "email");
        $pdoDb->setSelectList($expr_list);
        $pdoDb->setGroupBy($expr_list);

        $case = new CaseStmt("enabled");
        $case->addWhen("=", ENABLED, $LANG['enabled']);
        $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($case);

        if (empty($sort) ||
            !in_array($sort, array("id", "name", "email", 'enabled'))) $sort = "id";
        if (empty($dir)) $dir = "DESC";
        $pdoDb->setOrderBy(array($sort, $dir));

        $rows = $pdoDb->request("SELECT", "biller");

        return $rows;
    }

}
