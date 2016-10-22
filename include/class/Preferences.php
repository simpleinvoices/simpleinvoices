<?php
class Preferences {

    /**
     * Get a specific <b>si_preferences</b> record.
     * @param string $id Unique ID record to retrieve.
     * @param string $domain_id Domain ID logged into.
     * @return array Row retrieved. Test for "=== false" to check for failure.
     */
    public static function getPreference($id, $domain_id = '') {
        global $LANG, $pdoDb;
        $domain_id = domain_id::get($domain_id);

        $pdoDb->addSimpleWhere("pref_id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $domain_id);

        $ca = new CaseStmt("status", "status_wording");
        $ca->addWhen( "=", ENABLED, $LANG['real']);
        $ca->addWhen("!=", ENABLED, $LANG['draft'], true);
        $pdoDb->addToCaseStmts($ca);

        $ca = new CaseStmt("pref_enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $rows = $pdoDb->request("SELECT", "preferences");
        return (empty($rows) ? false : $rows[0]);
    }

    /**
     * Get all preferences records.
     * @param string $domain_id Domain ID logged into.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     *         Note that a field named, "enabled", was added to store the $LANG
     *         enable or disabled word depending on the "pref_enabled" setting
     *         of the record.
     */
    public static function getPreferences($domain_id = '') {
        global $LANG, $pdoDb;
        $domain_id = domain_id::get($domain_id);

        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $pdoDb->setOrderBy("pref_description");

        $ca = new CaseStmt("pref_enabled", "enabled");
        $ca->addWhen( "=", ENABLED, $LANG['enabled']);
        $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($ca);

        $pdoDb->setSelectAll(true);

        $preferences = $pdoDb->request("SELECT", "preferences");
        return $preferences;
    }

    /**
     * Get active preferences records for the current domain.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function getActivePreferences() {
        global $pdoDb;

        $pdoDb->addSimpleWhere("pref_enabled", ENABLED, "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->setOrderBy("pref_description");
        $rows = $pdoDb->request("SELECT", "preferences");
        return $rows;
    }

    /**
     * Get a default preference information.
     * @return array Preference row and system default setting for it.
     */
    public static function getDefaultPreference() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("s.domain_id", domain_id::get());
        $jn = new Join("LEFT", "preferences", "p");
        $jn->addSimpleItem("p.domain_id", new DbField("s.domain_id"), "AND");
        $jn->addSimpleItem("p.pref_id", new DbField("s.value"));
        $pdoDb->addToJoins($jn);

        $rows = $pdoDb->request("SELECT", "system_defaults", "s");
        return $rows;
    }

}
