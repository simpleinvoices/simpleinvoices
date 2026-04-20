<?php
/*
 * Run multi-statement SQL like the web installer, and provision essential_data.json
 * for a new organisation (domain) on an existing database.
 *
 * License: GPL v3 or above
 */

/**
 * Execute a multi-statement SQL script by splitting on semicolons.
 *
 * PDO::prepare() only handles a single statement, so passing an entire
 * structure.sql file in one call fails on PostgreSQL and SQLite (and is
 * unreliable on MySQL). Strips -- / # comments, splits on ';', runs each
 * statement via dbQuery().
 */
function install_execute_sql_file($sql_content)
{
    $lines = explode("\n", $sql_content);
    $cleaned = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
            continue;
        }
        $cleaned[] = $trimmed;
    }

    $statements = array_filter(
        array_map('trim', explode(';', implode("\n", $cleaned))),
        'strlen'
    );

    foreach ($statements as $stmt) {
        dbQuery($stmt);
    }

    return true;
}

/**
 * Import essential_data.json for one domain (omit global/first-install-only tables).
 * Idempotent if bootstrap rows already exist.
 *
 * @param string|null $domainUiLanguage LOCALE/LANGUAGE for essential_data (e.g. chosen at registration).
 * @return bool true when domainHasEssentialBootstrapData is satisfied afterward
 */
function install_bootstrap_new_domain_essentials(int $targetDomainId, ?string $domainUiLanguage = null): bool
{
    if ($targetDomainId < 2) {
        return false;
    }
    if (domainHasEssentialBootstrapData($targetDomainId)) {
        return true;
    }
    if (!checkTableExists(TB_PREFIX . 'customers')) {
        return false;
    }

    try {
        $essentialSql = install_build_essential_data_sql($targetDomainId, false, $domainUiLanguage);
        if ($essentialSql !== '') {
            install_execute_sql_file($essentialSql);
        }
    } catch (Throwable $e) {
        return false;
    }

    return checkDataExists($targetDomainId);
}
