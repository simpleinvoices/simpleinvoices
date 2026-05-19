<?php
/*
 * Authentication helpers: secure password verification (bcrypt + legacy MD5).
 * Used by login and user management. Aligns with modern Laravel-style practices.
 *
 * License: GPL v3 or above
 */

/**
 * Extra SELECT columns for si_user when preferred_language exists (patch 343+).
 */
function auth_user_preferred_language_select_fragment(): string
{
	static $cached = null;
	if ($cached !== null) {
		return $cached;
	}
	$cached = checkFieldExists(TB_PREFIX . 'user', 'preferred_language')
		? ', u.preferred_language'
		: '';

	return $cached;
}

/**
 * Verify a plain-text password against a stored hash.
 * Supports: bcrypt/argon2 (password_verify) and legacy MD5 (for migration).
 *
 * @param string $password Plain password
 * @param string $storedHash Hash from database
 * @return bool
 */
function auth_verify_password($password, $storedHash)
{
    if ($storedHash === null || $storedHash === '') {
        return false;
    }
    // Modern: bcrypt ($2y$, $2a$) or argon2 ($argon2id$)
    if (strpos($storedHash, '$2y$') === 0 || strpos($storedHash, '$2a$') === 0
        || strpos($storedHash, '$argon2') === 0) {
        return password_verify($password, $storedHash);
    }
    // Legacy: 32-char MD5 hex
    if (strlen($storedHash) === 32 && ctype_xdigit($storedHash)) {
        return hash_equals($storedHash, md5($password));
    }
    return false;
}

/**
 * Hash a password for storage. Uses bcrypt (PASSWORD_BCRYPT) by default.
 *
 * @param string $password Plain password
 * @return string
 */
function auth_hash_password($password)
{
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Return true if the stored hash should be upgraded - covers both legacy MD5
 * and bcrypt hashes whose cost no longer matches the current setting.
 *
 * @param string $storedHash Hash from database
 * @return bool
 */
function auth_needs_rehash($storedHash)
{
    // Legacy MD5: always upgrade
    if (strlen($storedHash) === 32 && ctype_xdigit($storedHash)) {
        return true;
    }
    // bcrypt/argon2: upgrade if cost or algorithm has changed
    return password_needs_rehash($storedHash, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Normalised login email (trim + lowercase), or null if empty.
 */
function auth_normalize_email($email)
{
    $e = trim((string) $email);
    if ($e === '') {
        return null;
    }
    return strtolower($e);
}

/**
 * Whether si_user has auth_staff_email / auth_customer_key (post-patch 332+).
 */
function auth_user_has_identity_columns()
{
    return checkFieldExists(TB_PREFIX . 'user', 'auth_staff_email');
}

/**
 * Compute auth_staff_email and auth_customer_key for INSERT/UPDATE.
 *
 * @param int|string|null $roleId
 * @param int|string      $domainId
 * @param string|null     $email
 * @return array{0: ?string, 1: ?string} [auth_staff_email, auth_customer_key]
 */
function auth_identity_columns_for_role($roleId, $domainId, $email)
{
    $norm = auth_normalize_email($email);
    $staff = null;
    $cust  = null;
    if ($norm !== null && $roleId !== null && $roleId !== '') {
        $rid = (int) $roleId;
        if (in_array($rid, [1, 2, 3, 4, 6, 7], true)) {
            $staff = $norm;
        } elseif ($rid === 5) {
            $cust = (int) $domainId . ':' . $norm;
        }
    }
    return [$staff, $cust];
}

/**
 * Whether an email is already used as a staff/biller login (global identity; unique auth_staff_email).
 */
function auth_is_staff_login_email_in_use($email): bool
{
    $norm = auth_normalize_email((string) $email);
    if ($norm === null) {
        return false;
    }
    if (auth_user_has_identity_columns()) {
        $sth = dbQuery(
            'SELECT 1 FROM ' . TB_PREFIX . 'user WHERE auth_staff_email = :n LIMIT 1',
            ':n',
            $norm
        );

        return (bool) ($sth && $sth->fetch());
    }

    $schema = auth_detect_schema();
    if ($schema === 'modern' || $schema === 'mid') {
        $sth = dbQuery(
            "SELECT 1 FROM " . TB_PREFIX . "user u
             INNER JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE LOWER(TRIM(u.email)) = :norm
             AND r.name IN ('administrator','domain_administrator','user','operator','biller','viewer')
             LIMIT 1",
            ':norm',
            $norm
        );

        return (bool) ($sth && $sth->fetch());
    }

    return false;
}

/**
 * Upgrade a stored hash to bcrypt if it is MD5 or if bcrypt cost has changed.
 */
function auth_upgrade_user_password_hash($id, $domainId, $plainPassword)
{
    $newHash = auth_hash_password($plainPassword);
    dbQuery(
        "UPDATE " . TB_PREFIX . "user SET password = :password WHERE id = :id AND domain_id = :domain_id",
        ':password', $newHash,
        ':id', $id,
        ':domain_id', $domainId
    );
}

/**
 * Authenticate staff or biller by email and password (shared login). Never matches customer rows.
 *
 * @param string $email User email (identity)
 * @param string $password Plain password
 * @return array|false Session row (id, email, role_name, domain_id, user_id, etc.) or false
 */
function auth_authenticate_staff_user($email, $password)
{
    $schema = auth_detect_schema();

    if ($schema === 'modern') {
        $norm = auth_normalize_email($email);
        if ($norm === null) {
            return false;
        }
        $emailPredicate = auth_user_has_identity_columns()
            ? 'u.auth_staff_email = :norm '
            : 'LOWER(TRIM(u.email)) = :norm ';
        $prefSel        = auth_user_preferred_language_select_fragment();
        $sth            = dbQuery(
            "SELECT u.id, u.email, u.name, u.password, r.name AS role_name, u.domain_id, u.user_id{$prefSel}
             FROM " . TB_PREFIX . "user u
             INNER JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE " . $emailPredicate . "AND u.enabled = :enabled
             AND r.name IN ('administrator','domain_administrator','user','operator','biller','viewer')",
            ':norm', $norm,
            ':enabled', ENABLED
        );
        $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
        if ($row && auth_verify_password($password, $row['password'])) {
            if (auth_needs_rehash($row['password'])) {
                auth_upgrade_user_password_hash($row['id'], $row['domain_id'], $password);
            }
            unset($row['password']);
            return $row;
        }
        return false;
    }

    if ($schema === 'mid') {
        $norm = auth_normalize_email($email);
        if ($norm === null) {
            return false;
        }
        $prefSel = auth_user_preferred_language_select_fragment();
        $sth     = dbQuery(
            "SELECT u.id, u.email, u.password, r.name AS role_name, u.domain_id{$prefSel}
             FROM " . TB_PREFIX . "user u
             INNER JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE LOWER(TRIM(u.email)) = :norm AND u.enabled = :enabled
             AND r.name IN ('administrator','domain_administrator','user','operator','biller','viewer')",
            ':norm', $norm,
            ':enabled', ENABLED
        );
        $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
        if ($row && auth_verify_password($password, $row['password'])) {
            if (auth_needs_rehash($row['password'])) {
                auth_upgrade_user_password_hash($row['id'], $row['domain_id'], $password);
            }
            unset($row['password']);
            $row['user_id'] = 0;
            return $row;
        }
        return false;
    }

    if ($schema === 'legacy') {
        $sth = dbQuery(
            "SELECT u.user_id AS id, u.user_email AS email, u.user_password AS password,
                    r.name AS role_name, u.user_domain_id AS domain_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.user_role_id = r.id)
             WHERE u.user_email = :email",
            ':email', $email
        );
        $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
        if ($row && auth_verify_password($password, $row['password'])) {
            unset($row['password']);
            $row['user_id'] = 0;
            return $row;
        }
        return false;
    }

    $sth = dbQuery(
        "SELECT user_id AS id, user_email AS email, user_password AS password
         FROM " . TB_PREFIX . "users WHERE user_email = :email",
        ':email', $email
    );
    $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
    if ($row && auth_verify_password($password, $row['password'])) {
        unset($row['password']);
        $row['role_name'] = 'administrator';
        $row['domain_id'] = 1;
        $row['user_id']   = 0;
        return $row;
    }

    return false;
}

/**
 * Authenticate customer login for a specific domain (customer portal only).
 *
 * @param string $email
 * @param string $password
 * @param int    $domainId
 * @return array|false
 */
function auth_authenticate_customer_user($email, $password, $domainId)
{
    if (auth_detect_schema() !== 'modern') {
        return false;
    }
    $norm = auth_normalize_email($email);
    if ($norm === null) {
        return false;
    }
    $domainId = (int) $domainId;
    if ($domainId < 1) {
        return false;
    }

    $prefSelCust = auth_user_preferred_language_select_fragment();
    if (auth_user_has_identity_columns()) {
        $key = $domainId . ':' . $norm;
        $sth = dbQuery(
            "SELECT u.id, u.email, u.name, u.password, r.name AS role_name, u.domain_id, u.user_id{$prefSelCust}
             FROM " . TB_PREFIX . "user u
             INNER JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.auth_customer_key = :akey AND u.enabled = :enabled AND r.name = 'customer'",
            ':akey', $key,
            ':enabled', ENABLED
        );
    } else {
        $sth = dbQuery(
            "SELECT u.id, u.email, u.name, u.password, r.name AS role_name, u.domain_id, u.user_id{$prefSelCust}
             FROM " . TB_PREFIX . "user u
             INNER JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.domain_id = :domain_id AND u.enabled = :enabled AND r.name = 'customer'
             AND LOWER(TRIM(u.email)) = :norm",
            ':domain_id', $domainId,
            ':enabled', ENABLED,
            ':norm', $norm
        );
    }
    $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
    if ($row && auth_verify_password($password, $row['password'])) {
        if (auth_needs_rehash($row['password'])) {
            auth_upgrade_user_password_hash($row['id'], $row['domain_id'], $password);
        }
        unset($row['password']);
        return $row;
    }
    return false;
}

/**
 * Detect which user table schema variant is present.
 * Returns 'modern' (email + user_id cols), 'mid' (email only), 'legacy' (user_email cols), or 'oldest'.
 */
function auth_detect_schema()
{
    if (checkFieldExists(TB_PREFIX . 'user', 'user_id')) {
        return 'modern';
    }
    if (checkFieldExists(TB_PREFIX . 'user', 'email')) {
        return 'mid';
    }
    if (checkFieldExists(TB_PREFIX . 'user', 'user_email')) {
        return 'legacy';
    }
    return 'oldest';
}

/**
 * @deprecated Use auth_authenticate_staff_user() for the shared login page.
 */
function auth_authenticate_user($email, $password)
{
    return auth_authenticate_staff_user($email, $password);
}
