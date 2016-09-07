<?php
class Biller {
    /**
     * Get all biller records.
     * @param boolean $active_only Set to <b>true</b> to get active billers only.
     *        Set to <b>false</b> or don't specify anything if you want all billers.
     * @return array Biller records retrieved.
     */
    public static function get_all($active_only=false) {
        global $LANG, $pdoDb;

        if ($active_only) {
            $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca =new CaseStmt("enabled", "wording_for_enabled");
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
     */
    public static function select($id) {
        global $LANG, $pdoDb;
        
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);

        $ca =new CaseStmt("enabled", "wording_for_enabled");
        $ca->addWhen("=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "biller");
        return $rows[0];
    }

}
