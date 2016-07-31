<?php
class ModifyExpenseTables {
    public static function modifyTables() {
        global $pdoDb;
        if (checkFieldExists(TB_PREFIX . 'expense', 'status') != false) return true;
        $domain_id = domain_id::get();

        // Update si_expense
        if (!$pdoDb->request("DROP", "expense")) return false;

        // @formatter:off
        $pdoDb->addTableColumns("id"                , "INT(11)"      , "NOT NULL AUTO_INCREMENT UNIQUE KEY");
        $pdoDb->addTableColumns("domain_id"         , "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("amount"            , "DECIMAL(25,6)", "NOT NULL");
        $pdoDb->addTableColumns("expense_account_id", "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("biller_id"         , "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("customer_id"       , "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("invoice_id"        , "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("product_id"        , "INT(11)"      , "NOT NULL");
        $pdoDb->addTableColumns("date"              , "DATE"         , "NOT NULL");
        $pdoDb->addTableColumns("note"              , "TEXT"         , "NOT NULL");
        $pdoDb->addTableEngine("InnoDb");
        if (!$pdoDb->request("CREATE TABLE", "expense")) return false;

        $pdoDb->addTableConstraints("compound(domain_id, id)", "ADD PRIMARY KEY");
        if (!$pdoDb->request("ALTER TABLE", "expense")) return false;

        // Update si_expense_account
        if (!$pdoDb->request("DROP", "expense_account")) return false;

        $pdoDb->addTableColumns("id"       , "INT(11)"     , "NOT NULL AUTO_INCREMENT UNIQUE KEY");
        $pdoDb->addTableColumns("domain_id", "INT(11)"     , "NOT NULL");
        $pdoDb->addTableColumns("name"     , "VARCHAR(255)", "NOT NULL");
        $pdoDb->addTableEngine("InnoDb");
        if (!$pdoDb->request("CREATE TABLE", "expense_account")) return false;

        $pdoDb->addTableConstraints("compound(domain_id, id)", "ADD PRIMARY KEY");
        if (!$pdoDb->request("ALTER TABLE", "expense_account")) return false;

        // Update si_expense_item_tax
        if (!$pdoDb->request("DROP", "expense_item_tax")) return false;

        $pdoDb->addTableColumns("id"        , "INT(11)"       , "NOT NULL AUTO_INCREMENT PRIMARY KEY");
        $pdoDb->addTableColumns("expense_id", "INT(11)"       , "NOT NULL");
        $pdoDb->addTableColumns("tax_id"    , "INT(11)"       , "NOT NULL");
        $pdoDb->addTableColumns("tax_type"  , "VARCHAR(1)"    , "NOT NULL");
        $pdoDb->addTableColumns("tax_rate"  , "DECIMAL(25, 6)", "NOT NULL");
        $pdoDb->addTableColumns("tax_amount", "DECIMAL(25, 6)", "NOT NULL");
        $pdoDb->addTableEngine("MYISAM");
        if (!$pdoDb->request("CREATE TABLE", "expense_item_tax")) return false;

        // Do this last to allow error exists 
        $pdoDb->addTableConstraints("status", "ADD ~ TINYINT(1) NOT NULL", true);
        if (!$pdoDb->request("ALTER TABLE", "expense")) return false;
        // @formatter:on
    }
}