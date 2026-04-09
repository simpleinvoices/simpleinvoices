<?php
/*
 * Authentication helpers: secure password verification (bcrypt + legacy MD5).
 * Used by login and user management. Aligns with modern Laravel-style practices.
 *
 * License: GPL v3 or above
 */

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
 * Return true if the stored hash should be upgraded — covers both legacy MD5
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
 * Authenticate user by email and password; return session data (no password) or false.
 * Uses parameterised queries and supports both bcrypt and legacy MD5 hashes.
 *
 * @param string $email User email (identity)
 * @param string $password Plain password
 * @return array|false User row for session (id, email, role_name, domain_id, user_id, etc.) or false
 */
/**
 * Detect which user table schema variant is present.
 * Returns 'modern' (email + user_id cols), 'mid' (email only), 'legacy' (user_email cols), or 'oldest'.
 * Uses checkFieldExists() so it works with MySQL, PostgreSQL, and SQLite.
 */
function auth_detect_schema()
{
    // Modern schema: si_user with 'email' and 'user_id' columns (post-patch 292)
    if (checkFieldExists(TB_PREFIX . 'user', 'user_id')) {
        return 'modern';
    }

    // Mid schema: si_user with 'email' column but no 'user_id' (post-patch 184)
    if (checkFieldExists(TB_PREFIX . 'user', 'email')) {
        return 'mid';
    }

    // Legacy schema: si_user with 'user_email' column (post-patch 147)
    if (checkFieldExists(TB_PREFIX . 'user', 'user_email')) {
        return 'legacy';
    }

    // Oldest schema: si_users table
    return 'oldest';
}

function auth_authenticate_user($email, $password)
{
    // Upgrade a stored hash to bcrypt if it is MD5 or if bcrypt cost has changed.
    // domain_id is included in the WHERE to scope the update to the correct tenant row.
    $upgradeHash = static function (string $id, string $domain_id, string $plainPassword): void {
        $newHash = auth_hash_password($plainPassword);
        dbQuery(
            "UPDATE " . TB_PREFIX . "user SET password = :password WHERE id = :id AND domain_id = :domain_id",
            ':password', $newHash,
            ':id', $id,
            ':domain_id', $domain_id
        );
    };

    $schema = auth_detect_schema();

    if ($schema === 'modern') {
        $sth = dbQuery(
            "SELECT u.id, u.email, u.name, u.password, r.name AS role_name, u.domain_id, u.user_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.email = :email AND u.enabled = :enabled",
            ':email', $email,
            ':enabled', ENABLED
        );
        $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
        if ($row && auth_verify_password($password, $row['password'])) {
            if (auth_needs_rehash($row['password'])) {
                $upgradeHash($row['id'], $row['domain_id'], $password);
            }
            unset($row['password']);
            return $row;
        }
        return false;
    }

    if ($schema === 'mid') {
        $sth = dbQuery(
            "SELECT u.id, u.email, u.password, r.name AS role_name, u.domain_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.email = :email AND u.enabled = :enabled",
            ':email', $email,
            ':enabled', ENABLED
        );
        $row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
        if ($row && auth_verify_password($password, $row['password'])) {
            if (auth_needs_rehash($row['password'])) {
                $upgradeHash($row['id'], $row['domain_id'], $password);
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

    // Oldest schema: si_users table with user_email/user_password columns
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
        $row['user_id'] = 0;
        return $row;
    }

    return false;
}
