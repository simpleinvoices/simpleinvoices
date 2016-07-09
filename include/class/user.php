<?php
class user {
    public static function getUserRoles() {
        $sql = "SELECT id, name FROM " . TB_PREFIX . "user_role ORDER BY id";
        $result = dbQuery($sql);
        return $result->fetchAll();
    }

    public static function getUser($id) {
        global $auth_session;
        global $LANG;
        $enabled = $LANG['enabled'];
        $disabled = $LANG['disabled'];

        $sql = "SELECT u.*, ur.name AS role_name, user_id,
                       (SELECT (CASE WHEN u.enabled = " . ENABLED .
                                   " THEN '$enabled' ELSE '$disabled' END )) AS lang_enabled
                FROM " . TB_PREFIX . "user u
                LEFT JOIN " . TB_PREFIX . "user_role ur ON (u.role_id = ur.id)
                WHERE u.domain_id = :domain_id
                  AND u.id = :id";
        $result = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id);
        return $result->fetch();
    }
}
