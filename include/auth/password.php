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
 * Authenticate user by email and password; return session data (no password) or false.
 * Uses parameterised queries and supports both bcrypt and legacy MD5 hashes.
 *
 * @param string $email User email (identity)
 * @param string $password Plain password
 * @return array|false User row for session (id, email, role_name, domain_id, user_id, etc.) or false
 */
function auth_authenticate_user($email, $password)
{
    global $zendDb;

    $patchesDone = getNumberOfDoneSQLPatches();

    // Patch 292+: current schema (email, password, role_id, domain_id, user_id, enabled)
    if ($patchesDone >= '292') {
        $row = $zendDb->fetchRow(
            "SELECT u.id, u.email, u.password, r.name AS role_name, u.domain_id, u.user_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.email = ? AND u.enabled = ?",
            [$email, ENABLED]
        );
        if ($row && auth_verify_password($password, $row['password'])) {
            unset($row['password']);
            return $row;
        }
        return false;
    }

    if ($patchesDone >= '184') {
        $row = $zendDb->fetchRow(
            "SELECT u.id, u.email, u.password, r.name AS role_name, u.domain_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id)
             WHERE u.email = ? AND u.enabled = ?",
            [$email, ENABLED]
        );
        if ($row && auth_verify_password($password, $row['password'])) {
            unset($row['password']);
            $row['user_id'] = 0;
            return $row;
        }
        return false;
    }

    if ($patchesDone >= '147') {
        $row = $zendDb->fetchRow(
            "SELECT u.user_id AS id, u.user_email AS email, u.user_password AS password,
                    r.name AS role_name, u.user_domain_id AS domain_id
             FROM " . TB_PREFIX . "user u
             LEFT JOIN " . TB_PREFIX . "user_role r ON (u.user_role_id = r.id)
             WHERE u.user_email = ?",
            [$email]
        );
        if ($row && auth_verify_password($password, $row['password'])) {
            unset($row['password']);
            $row['user_id'] = 0;
            return $row;
        }
        return false;
    }

    // Pre patch 147
    $row = $zendDb->fetchRow(
        "SELECT user_id AS id, user_email AS email, user_password AS password FROM "
        . TB_PREFIX . "users WHERE user_email = ?",
        [$email]
    );
    if ($row && auth_verify_password($password, $row['password'])) {
        unset($row['password']);
        $row['role_name'] = 'administrator';
        $row['domain_id'] = 1;
        $row['user_id'] = 0;
        return $row;
    }

    return false;
}
