<?php
class user {
    /**
     * Get all user role records.
     * @return Array of <b>user_role</b> records.
     */
    public static function getUserRoles() {
        global $pdoDb;
        $pdoDb->setOrderBy(new OrderBy("id"));
        $pdoDb->setSelectList(array("id", "name"));
        $rows = $pdoDb->request("SELECT", "user_role");
        return $rows;
    }

    /**
     * Get a specific <b>user</b> table record.
     * @param integer $id <b>id</b> number of he record to retrieve.
     * @return user record.
     */
    public static function getUser($id) {
        global $auth_session, $LANG, $pdoDb, $auth_session;
        $pdoDb->addSimpleWhere("u.id", $id, "AND");
        $pdoDb->addSimpleWhere("domain_id", $auth_session->domain_id);

        $list = array("u.*", "ur.name AS role_name");
        $pdoDb->setSelectList($list);

        $caseStmt = new CaseStmt("u.enabled", "enabled_txt");
        $caseStmt->addWhen( "=", ENABLED, $LANG['enabled']);
        $caseStmt->addWhen("!=", ENABLED, $LANG['disabled'], true);
        $pdoDb->addToCaseStmts($caseStmt);

        $join = new Join("LEFT", "user_role", "ur");
        $join->addSimpleItem("ur.id", new DbField("role_id"));
        $pdoDb->addToJoins($join);

        $rows = $pdoDb->request("SELECT", "user", "u");
        return $rows[0];
    }
}
