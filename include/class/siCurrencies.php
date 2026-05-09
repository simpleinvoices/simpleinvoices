<?php

/**
 * Currency management backed by si_currency database table.
 *
 * The table stores currency definitions (sign, code, position) per domain.
 * Preferences and invoices reference a currency row via currency_id,
 * while also keeping denormalized sign/code/position for fast rendering.
 */
class siCurrencies
{
    /**
     * Seed si_currency with preset currencies from CurrencySignHelper
     * if the table is empty for this domain.
     */
    public static function seedDefaults(int $domainId = 0): void
    {
        $domainId = domain_id::get($domainId);
        if (self::countForDomain($domainId) > 0) {
            return;
        }

        $seen = [];
        foreach (CurrencySignHelper::getPresetGroups() as $group) {
            foreach ($group['presets'] as $p) {
                $code = $p['code'] ?? '';
                if ($code === '' || isset($seen[$code])) {
                    continue;
                }
                $seen[$code] = true;
                $sign = html_entity_decode($p['value'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $position = $p['position'] ?? 'left';
                self::insert($domainId, $code, $sign, $position, false);
            }
        }
    }

    /**
     * Insert a currency row.
     */
    public static function insert(int $domainId, string $code, string $sign, string $position = 'left', bool $isDefault = false): int
    {
        global $db_server;
        $code = trim($code);
        $sign = trim($sign);
        if ($code === '' && $sign === '') {
            return 0;
        }
        $position = in_array(strtolower($position), ['left', 'right'], true)
            ? strtolower($position) : 'left';

        if ($db_server === 'pgsql' || $db_server === 'sqlite') {
            $sql = "INSERT INTO " . TB_PREFIX . "currency
                (domain_id, currency_code, currency_sign, currency_position, is_default, enabled)
                VALUES (:domain_id, :code, :sign, :position, :is_default, 1)";
        } else {
            $sql = "INSERT INTO " . TB_PREFIX . "currency
                (domain_id, currency_code, currency_sign, currency_position, is_default, enabled)
                VALUES (:domain_id, :code, :sign, :position, :is_default, 1)";
        }
        dbQuery($sql,
            ':domain_id', $domainId,
            ':code', $code,
            ':sign', $sign,
            ':position', $position,
            ':is_default', $isDefault ? 1 : 0
        );
        return (int) lastInsertId();
    }

    /**
     * Find a currency by code for a domain. Returns row or null.
     */
    public static function findByCode(int $domainId, string $code): ?array
    {
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE domain_id = :domain_id AND currency_code = :code AND enabled = 1
            LIMIT 1";
        $row = dbQuery($sql, ':domain_id', $domainId, ':code', trim($code))->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Find a currency by sign for a domain. Returns row or null.
     */
    public static function findBySign(int $domainId, string $sign): ?array
    {
        $decoded = html_entity_decode(trim($sign), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE domain_id = :domain_id AND enabled = 1
            AND (currency_sign = :sign OR currency_sign = :decoded)
            LIMIT 1";
        $row = dbQuery($sql, ':domain_id', $domainId, ':sign', trim($sign), ':decoded', $decoded)->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get a single currency by ID.
     */
    public static function getById(int $id, int $domainId = 0): ?array
    {
        $domainId = domain_id::get($domainId);
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE id = :id AND domain_id = :domain_id AND enabled = 1
            LIMIT 1";
        $row = dbQuery($sql, ':id', $id, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get all enabled currencies for a domain, ordered by code.
     */
    public static function getForDomain(int $domainId = 0): array
    {
        $domainId = domain_id::get($domainId);
        self::seedDefaults($domainId);
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE domain_id = :domain_id AND enabled = 1
            ORDER BY currency_code, currency_sign";
        $sth = dbQuery($sql, ':domain_id', $domainId);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the default currency for a domain.
     */
    public static function getDefault(int $domainId = 0): ?array
    {
        $domainId = domain_id::get($domainId);
        self::seedDefaults($domainId);
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE domain_id = :domain_id AND is_default = 1 AND enabled = 1
            LIMIT 1";
        $row = dbQuery($sql, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row;
        }
        $all = self::getForDomain($domainId);
        return $all[0] ?? null;
    }

    /**
     * Set the default currency for a domain.
     */
    public static function setDefault(int $currencyId, int $domainId = 0): void
    {
        $domainId = domain_id::get($domainId);
        dbQuery("UPDATE " . TB_PREFIX . "currency SET is_default = 0 WHERE domain_id = :domain_id",
            ':domain_id', $domainId);
        dbQuery("UPDATE " . TB_PREFIX . "currency SET is_default = 1 WHERE id = :id AND domain_id = :domain_id",
            ':id', $currencyId, ':domain_id', $domainId);
    }

    /**
     * Find or create a currency by sign+code. Returns the row.
     */
    public static function findOrCreate(int $domainId, string $sign, string $code = '', string $position = ''): ?array
    {
        $domainId = domain_id::get($domainId);
        if ($code !== '') {
            $row = self::findByCode($domainId, $code);
            if ($row) {
                return $row;
            }
        }
        if ($sign !== '') {
            $row = self::findBySign($domainId, $sign);
            if ($row) {
                return $row;
            }
        }
        if ($sign === '' && $code === '') {
            return null;
        }
        if ($position === '') {
            $position = CurrencySignHelper::defaultPositionForSign($sign, $code);
        }
        $id = self::insert($domainId, $code, $sign, $position);
        return $id > 0 ? self::getById($id, $domainId) : null;
    }

    /**
     * Resolve currency fields for a preference row.
     * If the preference has currency_id, look it up in si_currency.
     * Otherwise fall back to the denorm columns on the preference.
     */
    public static function resolveForPreference(array $preference): array
    {
        $domainId = (int) ($preference['domain_id'] ?? domain_id::get());
        $currencyId = (int) ($preference['currency_id'] ?? 0);

        if ($currencyId > 0) {
            $row = self::getById($currencyId, $domainId);
            if ($row) {
                return [
                    'id'                => (int) $row['id'],
                    'currency_sign'     => $row['currency_sign'] ?? '',
                    'currency_code'     => $row['currency_code'] ?? '',
                    'currency_position' => $row['currency_position'] ?? 'left',
                ];
            }
        }

        // Fall back to denorm columns
        $sign = CurrencySignHelper::forDisplay($preference['pref_currency_sign'] ?? '');
        $code = trim($preference['currency_code'] ?? '');
        $position = trim($preference['currency_position'] ?? '');
        if ($position !== 'left' && $position !== 'right') {
            $position = CurrencySignHelper::defaultPositionForSign($sign, $code);
        }

        return [
            'id'                => 0,
            'currency_sign'     => $sign,
            'currency_code'     => $code,
            'currency_position' => $position,
        ];
    }

    /**
     * Resolve currency fields for an invoice row.
     */
    public static function resolveForInvoice(array $invoice): array
    {
        $domainId = (int) ($invoice['domain_id'] ?? domain_id::get());
        $currencyId = (int) ($invoice['currency_id'] ?? 0);

        if ($currencyId > 0) {
            $row = self::getById($currencyId, $domainId);
            if ($row) {
                return [
                    'id'                => (int) $row['id'],
                    'currency_sign'     => $row['currency_sign'] ?? '',
                    'currency_code'     => $row['currency_code'] ?? '',
                    'currency_position' => $row['currency_position'] ?? 'left',
                ];
            }
        }

        $sign = CurrencySignHelper::forDisplay($invoice['currency_sign'] ?? '');
        $code = trim($invoice['currency_code'] ?? '');
        $position = trim($invoice['currency_position'] ?? '');
        if ($position !== 'left' && $position !== 'right') {
            $position = CurrencySignHelper::defaultPositionForSign($sign, $code);
        }

        return [
            'id'                => 0,
            'currency_sign'     => $sign,
            'currency_code'     => $code,
            'currency_position' => $position,
        ];
    }

    /**
     * Count currencies for a domain.
     */
    public static function countForDomain(int $domainId = 0): int
    {
        $domainId = domain_id::get($domainId);
        $sql = "SELECT COUNT(*) AS c FROM " . TB_PREFIX . "currency WHERE domain_id = :domain_id";
        $row = dbQuery($sql, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['c'] ?? 0);
    }

    /**
     * Get a currency by ID without enabled filter (for editing disabled currencies).
     */
    public static function getByIdAny(int $id, int $domainId = 0): ?array
    {
        $domainId = domain_id::get($domainId);
        $sql = "SELECT * FROM " . TB_PREFIX . "currency
            WHERE id = :id AND domain_id = :domain_id
            LIMIT 1";
        $row = dbQuery($sql, ':id', $id, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Update a currency row.
     */
    public static function update(int $id, int $domainId, string $code, string $sign, string $position, bool $enabled): bool
    {
        $domainId = domain_id::get($domainId);
        $code = trim($code);
        $sign = trim($sign);
        if ($code === '' && $sign === '') {
            return false;
        }
        $position = in_array(strtolower($position), ['left', 'right'], true)
            ? strtolower($position) : 'left';

        $sql = "UPDATE " . TB_PREFIX . "currency
            SET currency_code = :code, currency_sign = :sign, currency_position = :position, enabled = :enabled
            WHERE id = :id AND domain_id = :domain_id";
        dbQuery($sql,
            ':id', $id,
            ':domain_id', $domainId,
            ':code', $code,
            ':sign', $sign,
            ':position', $position,
            ':enabled', $enabled ? 1 : 0
        );
        return true;
    }
}
