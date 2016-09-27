<?php
class ExpenseAccount {

    /**
     * Get count of expense_account records for the current domain.
     * @return integer count of records.
     */
    public static function count() {
        global $pdoDb;
        $pdoDb->addToFunctions("count(*) as count");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "expense_account");
        return $rows[0]['count'];
    }

    /**
     * Get all records for the current domain_id.
     * @return array Rows retrieved.
     */
    public static function get_all() {
        global $pdoDb;
        $pdoDb->setOrderBy("id");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $rows = $pdoDb->request("SELECT", "expense_account");
        return $rows;
    }

    /**
     * Retrieve <i>expense_account</i> record for the current domain and the specified <b>$id</b>
     * @param number $id ID of expense record to retrieve.
     * @return mixed|Result
     */
    public static function select($id) {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), 'AND');
        $pdoDb->addSimpleWhere("id", $id);
        $rows = $pdoDb->request("SELECT", "expense_account");
        return $rows[0];
    }

    /**
     * Insert a new <i>expense_account</i> record.
     * @return boolean <b>true</b> if record inserted, <b>false</b> if an error occurred.
     */
    public static function insert() {
        global $pdoDb;
        $pdoDb->setExcludedFields("id");
        $pdoDb->setFauxPost(array("domain_id" => domain_id::get(), "name" => $_POST["name"]));
        return $pdoDb->request("INSERT", "expense_account");
    }

    /**
     * Update <i>expense_account</i> record.
     * @return boolean <b>true</b> if record inserted, <b>false</b> if an error occurred.
     */
    public static function update() {
        global $pdoDb;
        $pdoDb->setExcludedFields(array("id", "domain_id"));
        $pdoDb->setFauxPost(array("name" => $_POST["name"]));
        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $_GET["id"]);
        return $pdoDb->request("UPDATE", "expense_account");
    }
}
