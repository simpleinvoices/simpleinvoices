<?php
class Taxes {

    /**
     * Get a tax record.
     * @param string $id Unique ID record to retrieve.
     * @param string $domain_id Domain ID logged into.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     * @throws PdoDbException
     */
    public static function getTaxRate($tax_id, $domain_id="") {
        global $LANG, $pdoDb;

        $pdoDb->addSimpleWhere("tax_id", $tax_id, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get($domain_id));

        $ca = new CaseStmt("tax_enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);
        $rows = $pdoDb->request("SELECT", "tax");
        return (empty($rows) ? $rows : $rows[0]);
    }


    /**
     * Get all active taxes records.
     * @param string $domain_id Domain ID logged into.
     * @return array Rows retrieved.
     * @throws PdoDbException
     */
    public static function getActiveTaxes() {
        global $LANG, $pdoDb;

        $pdoDb->addSimpleWhere("tax_enabled", ENABLED, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $pdoDb->setSelectAll(true);
        $pdoDb->setSelectList("'$LANG[enabled]' AS enabled");

        $pdoDb->setOrderBy("tax_description");
        $rows = $pdoDb->request("SELECT", "tax");
        return $rows;
    }


    /**
     * Get tax types
     * @return string[] Types of tax records (% - percentage, $ - dollars)
     */
    public static function getTaxTypes() {
        $types = array('%' => '%', '$' => '$');
        return $types;
    }

    /**
     * Get tax table rows.
     * @return array Rows retrieved.
     *         Note that a field named, "wording_for_enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     * @throws PdoDbException
     */
    public static function getTaxes() {
        global $LANG, $pdoDb;

        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $ca = new CaseStmt("tax_enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $pdoDb->setOrderBy("tax_description");

        $rows = $pdoDb->request("SELECT", "tax");
        return $rows;
    }

    /**
     * Get a default tax record.
     * @return array Default tax record.
     * @throws PdoDbException
     */
    public static function getDefaultTax() {
        global $pdoDb;

        $pdoDb->addSimpleWhere("s.name", "tax", "AND");
        $pdoDb->addSimpleWhere("s.domain_id", domain_id::get());

        $jn = new Join("LEFT", "tax", "t");
        $jn->addSimpleItem("t.tax_id", new DbField("s.value"));
        $pdoDb->addToJoins($jn);

        $pdoDb->setSelectAll(true);
        $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        return $rows[0];
    }

    /**
     * Insert a new tax rate.
     * @return string Standard "Save tab rate success/failure" message.
     * @throws PdoDbException
     */
    public static function insertTaxRate() {
        global $LANG, $pdoDb;
        // @formatter:off
        $pdoDb->setFauxPost(array('domain_id'       => domain_id::get(),
                                  'tax_description' => $_POST['tax_description'],
                                  'tax_percentage'  => $_POST['tax_percentage'],
                                  'type'            => $_POST['type'],
                                  'tax_enabled'     => $_POST['tax_enabled']));
        // @formatter:on
        if ($pdoDb->request("INSERT", "tax") === false) {
            return $LANG['save_tax_rate_failure'];
        }
        return $LANG['save_tax_rate_success'];
    }

    /**
     * Update tax rate.
     * @return string Standard "Save tab rate success/failure" message.
     * @throws PdoDbException
     */
    public static function updateTaxRate() {
        global $LANG, $pdoDb;
        // @formatter:off
        $pdoDb->addSimpleWhere("tax_id", $_GET['id']);
        $pdoDb->setFauxPost(array('tax_description' => $_POST['tax_description'],
                                  'tax_percentage'  => $_POST['tax_percentage'],
                                  'type'            => $_POST['type'],
                                  'tax_enabled'     => $_POST['tax_enabled']));
        // @formatter:on
        if ($pdoDb->request("UPDATE", "tax") === false) {
            return $LANG['save_tax_rate_failure'];
        }
        return $LANG['save_tax_rate_success'];
    }

    /**
     * Calculate the total tax for the line item
     * @param array $line_item_tax_id Tax values to apply.
     * @param int $quantity Number of units.
     * @param int $unit_price Price of each unit.
     * @param string $domain_id SI domain being processed.
     * @return float Total tax
     * @throws PdoDbException
     */
    public static function getTaxesPerLineItem($line_item_tax_id, $quantity, $unit_price, $domain_id = '') {
        $domain_id = domain_id::get($domain_id);
        $tax_total = 0;
        if (is_array($line_item_tax_id)) {
            foreach ($line_item_tax_id as $value) {
                $tax = self::getTaxRate($value, $domain_id);
                $tax_total += self::lineItemTaxCalc($tax, $unit_price, $quantity);
            }
        }
        return $tax_total;
    }

    /**
     * Calculate the total tax for this line item.
     * @param array $tax Taxes for the line item.
     * @param int $unit_price Price for each unit.
     * @param int $quantity Number of units to tax.
     * @return float Total tax for the line item.
     */
    public static function lineItemTaxCalc($tax, $unit_price, $quantity) {
        // Calculate tax as a percentage of unit price or dollars per unit.
        if ($tax['type'] == "%") {
            return (($tax['tax_percentage'] / 100) * $unit_price) * $quantity;
        }
        return $tax['tax_percentage'] * $quantity;
    }

}