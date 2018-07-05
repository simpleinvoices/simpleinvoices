<?php
class ExpenseTax {

    public static function get_all($expense_id) {
        global $pdoDb;
        $pdoDb->setOrderBy("id");
        $pdoDb->addSimpleWhere("expense_id", $expense_id);
        return $pdoDb->request("SELECT", "expense_item_tax");
    }

    public static function get_sum($expense_id) {
        global $pdoDb;
        $pdoDb->addToFunctions("SUM(tax_amount) AS sum");
        $pdoDb->addSimpleWhere("expense_id", $expense_id);
        $rows = $pdoDb->request("SELECT", "expense_item_tax");
        return $rows[0]['sum'];
    }

    public static function grouped($expense_id) {
        global $pdoDb;
        $pdoDb->addToJoins(array("INNER", "expense", "e", "e.id", "et.expense_id"));

        $onClause = new OnClause();
        $onClause->addSimpleItem("t.tax_id", "et.tax_id", "AND");
        $onClause->addSimpleItem("t.domain_id", "e.domain_id");
        $pdoDb->addToJoins(array("INNER", "tax", "t", $onClause));

        $pdoDb->addSimpleWhere("e.id", $expense_id, "AND");
        $pdoDb->addSimpleWhere("e.domain_id", domain_id::get());

        $pdoDb->setGroupBy("t.tax_id");

        $pdoDb->addToFunctions("SUM(et.tax_amount) AS tax_amount");
        $pdoDb->addToFunctions("COUNT(*) AS count");

        $pdoDb->setSelectList("t.tax_description AS tax_name");

        return $pdoDb->request("SELECT", "expense_item_tax", "et");
    }
}

