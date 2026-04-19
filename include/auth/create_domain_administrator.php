<?php
/*
 * Shared: create user_domain + domain_administrator user (tenant signup / admin UI).
 *
 * License: GPL v3 or above
 */

/**
 * Create a new domain (organisation) and its domain administrator login.
 *
 * @return array{success:bool,error:?string}
 */
function auth_try_create_domain_with_administrator(
    string $name,
    string $admin_email,
    string $admin_name,
    string $admin_password
): array {
    require_once __DIR__ . '/password.php';

    $name           = trim($name);
    $admin_email    = trim($admin_email);
    $admin_name     = trim($admin_name);
    $admin_password = (string) $admin_password;

    if ($name === '') {
        return ['success' => false, 'error' => 'Domain name is required.'];
    }
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
        return ['success' => false, 'error' => 'Domain name may only contain letters, numbers, hyphens and underscores.'];
    }
    if ($admin_email === '') {
        return ['success' => false, 'error' => 'Domain administrator email is required.'];
    }
    if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Please enter a valid email address for the domain administrator.'];
    }
    if ($admin_password === '') {
        return ['success' => false, 'error' => 'Domain administrator password is required.'];
    }
    if (strlen($admin_password) < 4) {
        return ['success' => false, 'error' => 'Domain administrator password must be at least 4 characters.'];
    }

    $dup_domain = dbQuery(
        'SELECT id FROM ' . TB_PREFIX . 'user_domain WHERE LOWER(name) = LOWER(:name) LIMIT 1',
        ':name',
        $name
    );
    if ($dup_domain && $dup_domain->fetch(PDO::FETCH_ASSOC)) {
        return ['success' => false, 'error' => 'That domain name is already in use. Please choose a different name.'];
    }

    $role_sth = dbQuery(
        "SELECT id FROM " . TB_PREFIX . "user_role WHERE name = 'domain_administrator' LIMIT 1"
    );
    $role_row = $role_sth ? $role_sth->fetch(PDO::FETCH_ASSOC) : false;
    $role_id  = (int) ($role_row['id'] ?? 0);
    if ($role_id < 1) {
        return ['success' => false, 'error' => 'Could not create domain — the domain_administrator role is missing from the database.'];
    }

    global $dbh;
    $password_hash = auth_hash_password($admin_password);
    $tx_ok         = false;
    try {
        $dbh->beginTransaction();

        dbQuery(
            "INSERT INTO " . TB_PREFIX . "user_domain (name) VALUES (:name)",
            ':name', $name
        );
        $new_domain_id = (int) lastInsertId();
        $domain_row    = $new_domain_id > 0
            ? dbQuery(
                "SELECT id FROM " . TB_PREFIX . "user_domain WHERE id = :id AND name = :name LIMIT 1",
                ':id', $new_domain_id,
                ':name', $name
            )->fetch(PDO::FETCH_ASSOC)
            : false;
        if (!$domain_row) {
            throw new RuntimeException('domain_insert');
        }

        list($authStaffEmail, $authCustomerKey) = auth_identity_columns_for_role(
            $role_id,
            $new_domain_id,
            $admin_email
        );
        dbQuery(
            "INSERT INTO " . TB_PREFIX . "user
             (email, name, password, role_id, domain_id, enabled, user_id, auth_staff_email, auth_customer_key)
             VALUES (:email, :name, :password, :role_id, :domain_id, 1, 0, :auth_staff_email, :auth_customer_key)",
            ':email', $admin_email,
            ':name', $admin_name !== '' ? $admin_name : null,
            ':password', $password_hash,
            ':role_id', $role_id,
            ':domain_id', $new_domain_id,
            ':auth_staff_email', $authStaffEmail,
            ':auth_customer_key', $authCustomerKey
        );

        $user_check = dbQuery(
            "SELECT id FROM " . TB_PREFIX . "user
             WHERE domain_id = :domain_id AND email = :email LIMIT 1",
            ':domain_id', $new_domain_id,
            ':email', $admin_email
        );
        if (!$user_check || !$user_check->fetch(PDO::FETCH_ASSOC)) {
            throw new RuntimeException('user_insert');
        }

        $dbh->commit();
        $tx_ok = true;
    } catch (Throwable $e) {
        if ($dbh->inTransaction()) {
            $dbh->rollBack();
        }
        $tx_ok = false;
    }

    if ($tx_ok) {
        return ['success' => true, 'error' => null];
    }

    return ['success' => false, 'error' => 'Could not create the domain and administrator account — the domain name or email may already be in use.'];
}
