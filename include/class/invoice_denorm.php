<?php

/**
 * Denormalised invoice list columns (see sql patches: denorm_* on si_invoices / si_payment).
 * Templates and detailed views should continue to compute totals from line items + payments for accuracy.
 */
class invoice_denorm
{
    /**
     * Recompute denormalised columns for one invoice from line items, payments, biller, customer, preference.
     */
    public static function refreshForInvoice(int $invoiceId, $domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        if ($invoiceId < 1) {
            return;
        }

        $sqlInv = 'SELECT id, index_id, biller_id, customer_id, preference_id, type_id, date
            FROM ' . TB_PREFIX . 'invoices
            WHERE id = :id AND domain_id = :domain_id';
        $sth = dbQuery($sqlInv, ':id', $invoiceId, ':domain_id', $domainId);
        $inv = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$inv) {
            return;
        }

        $sqlItems = 'SELECT COALESCE(SUM(total), 0) AS s FROM ' . TB_PREFIX . 'invoice_items
            WHERE invoice_id = :id AND domain_id = :domain_id';
        $total = (float) (dbQuery($sqlItems, ':id', $invoiceId, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC)['s'] ?? 0);

        $sqlPay = 'SELECT COALESCE(SUM(ac_amount), 0) AS s FROM ' . TB_PREFIX . 'payment
            WHERE ac_inv_id = :id AND domain_id = :domain_id';
        $paid = (float) (dbQuery($sqlPay, ':id', $invoiceId, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC)['s'] ?? 0);

        $owing = $total - $paid;

        $billerName = '';
        $sqlB = 'SELECT name FROM ' . TB_PREFIX . 'biller WHERE id = :id AND domain_id = :domain_id';
        $rb = dbQuery($sqlB, ':id', $inv['biller_id'], ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        if ($rb) {
            $billerName = (string) $rb['name'];
        }

        $custName = '';
        $sqlC = 'SELECT name FROM ' . TB_PREFIX . 'customers WHERE id = :id AND domain_id = :domain_id';
        $rc = dbQuery($sqlC, ':id', $inv['customer_id'], ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        if ($rc) {
            $custName = (string) $rc['name'];
        }

        $prefDesc = '';
        $prefWording = '';
        $prefStatus = 0;
        $sqlP = 'SELECT pref_description, pref_inv_wording, status FROM ' . TB_PREFIX . 'preferences
            WHERE pref_id = :pid AND domain_id = :domain_id';
        $rp = dbQuery($sqlP, ':pid', $inv['preference_id'], ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC);
        if ($rp) {
            $prefDesc = (string) $rp['pref_description'];
            $prefWording = (string) $rp['pref_inv_wording'];
            $prefStatus = (int) $rp['status'];
        }

        $indexName = trim($prefWording . ' ' . $inv['index_id']);

        $sqlUp = 'UPDATE ' . TB_PREFIX . 'invoices SET
            denorm_invoice_total = :tot,
            denorm_amount_paid = :paid,
            denorm_amount_owing = :owing,
            denorm_biller_name = :bname,
            denorm_customer_name = :cname,
            denorm_index_name = :iname,
            denorm_preference_description = :pdesc,
            denorm_preference_status = :pstat
            WHERE id = :id AND domain_id = :domain_id';
        dbQuery($sqlUp,
            ':tot', $total,
            ':paid', $paid,
            ':owing', $owing,
            ':bname', $billerName,
            ':cname', $custName,
            ':iname', $indexName,
            ':pdesc', $prefDesc,
            ':pstat', $prefStatus,
            ':id', $invoiceId,
            ':domain_id', $domainId
        );

        self::syncPaymentDenormForInvoice($invoiceId, $domainId);
    }

    /**
     * Copy display denorm fields from invoice onto all payment rows for that invoice.
     */
    public static function syncPaymentDenormForInvoice(int $invoiceId, $domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        global $db_server;

        if ($db_server === 'mysql') {
            $sql = 'UPDATE ' . TB_PREFIX . 'payment p
                INNER JOIN ' . TB_PREFIX . 'invoices iv
                    ON (p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id)
                SET p.denorm_invoice_index_name = iv.denorm_index_name,
                    p.denorm_biller_name = iv.denorm_biller_name,
                    p.denorm_customer_name = iv.denorm_customer_name,
                    p.denorm_currency_sign = COALESCE(iv.currency_sign, \'\')
                WHERE p.ac_inv_id = :iid AND p.domain_id = :domain_id';
        } elseif ($db_server === 'pgsql') {
            $sql = 'UPDATE ' . TB_PREFIX . 'payment p SET
                    denorm_invoice_index_name = iv.denorm_index_name,
                    denorm_biller_name = iv.denorm_biller_name,
                    denorm_customer_name = iv.denorm_customer_name,
                    denorm_currency_sign = COALESCE(iv.currency_sign, \'\')
                FROM ' . TB_PREFIX . 'invoices iv
                WHERE p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id
                AND p.ac_inv_id = :iid AND p.domain_id = :domain_id';
        } else {
            $sql = 'UPDATE ' . TB_PREFIX . 'payment AS p SET
                denorm_invoice_index_name = (SELECT iv.denorm_index_name FROM ' . TB_PREFIX . 'invoices iv
                    WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id),
                denorm_biller_name = (SELECT iv.denorm_biller_name FROM ' . TB_PREFIX . 'invoices iv
                    WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id),
                denorm_customer_name = (SELECT iv.denorm_customer_name FROM ' . TB_PREFIX . 'invoices iv
                    WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id),
                denorm_currency_sign = COALESCE((SELECT iv.currency_sign FROM ' . TB_PREFIX . 'invoices iv
                    WHERE iv.id = p.ac_inv_id AND iv.domain_id = p.domain_id), \'\')
                WHERE p.ac_inv_id = :iid AND p.domain_id = :domain_id';
        }
        dbQuery($sql, ':iid', $invoiceId, ':domain_id', $domainId);
    }

    public static function refreshAllForBiller(int $billerId, $domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        $sth = dbQuery(
            'SELECT id FROM ' . TB_PREFIX . 'invoices WHERE biller_id = :b AND domain_id = :d',
            ':b', $billerId,
            ':d', $domainId
        );
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            self::refreshForInvoice((int) $row['id'], $domainId);
        }
    }

    public static function refreshAllForCustomer(int $customerId, $domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        $sth = dbQuery(
            'SELECT id FROM ' . TB_PREFIX . 'invoices WHERE customer_id = :c AND domain_id = :d',
            ':c', $customerId,
            ':d', $domainId
        );
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            self::refreshForInvoice((int) $row['id'], $domainId);
        }
    }

    public static function refreshAllForPreference(int $prefId, $domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        $sth = dbQuery(
            'SELECT id FROM ' . TB_PREFIX . 'invoices WHERE preference_id = :p AND domain_id = :d',
            ':p', $prefId,
            ':d', $domainId
        );
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            self::refreshForInvoice((int) $row['id'], $domainId);
        }
    }

    /**
     * @return array{checked:int, mismatches:int}
     */
    public static function verifyDomain($domainId = null): array
    {
        $domainId = domain_id::get($domainId);
        global $db_server;

        $sql = '
            SELECT COUNT(*) AS c FROM (
                SELECT iv.id
                FROM ' . TB_PREFIX . 'invoices iv
                LEFT JOIN (
                    SELECT invoice_id, domain_id, SUM(total) AS s
                    FROM ' . TB_PREFIX . 'invoice_items
                    GROUP BY invoice_id, domain_id
                ) ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                LEFT JOIN (
                    SELECT ac_inv_id, domain_id, SUM(ac_amount) AS s
                    FROM ' . TB_PREFIX . 'payment
                    GROUP BY ac_inv_id, domain_id
                ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
                WHERE iv.domain_id = :domain_id
                AND (
                    ABS(COALESCE(ii.s, 0) - iv.denorm_invoice_total) > 0.000001
                    OR ABS(COALESCE(ap.s, 0) - iv.denorm_amount_paid) > 0.000001
                    OR ABS((COALESCE(ii.s, 0) - COALESCE(ap.s, 0)) - iv.denorm_amount_owing) > 0.000001
                )
            ) t';
        if ($db_server === 'pgsql') {
            $sql = '
                SELECT COUNT(*)::int AS c FROM (
                    SELECT iv.id
                    FROM ' . TB_PREFIX . 'invoices iv
                    LEFT JOIN (
                        SELECT invoice_id, domain_id, SUM(total) AS s
                        FROM ' . TB_PREFIX . 'invoice_items
                        GROUP BY invoice_id, domain_id
                    ) ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                    LEFT JOIN (
                        SELECT ac_inv_id, domain_id, SUM(ac_amount) AS s
                        FROM ' . TB_PREFIX . 'payment
                        GROUP BY ac_inv_id, domain_id
                    ) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
                    WHERE iv.domain_id = :domain_id
                    AND (
                        ABS(COALESCE(ii.s, 0) - iv.denorm_invoice_total) > 0.000001
                        OR ABS(COALESCE(ap.s, 0) - iv.denorm_amount_paid) > 0.000001
                        OR ABS((COALESCE(ii.s, 0) - COALESCE(ap.s, 0)) - iv.denorm_amount_owing) > 0.000001
                    )
                ) t';
        }

        $bad = (int) (dbQuery($sql, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);
        $sqlN = 'SELECT COUNT(*) AS n FROM ' . TB_PREFIX . 'invoices WHERE domain_id = :domain_id';
        $n = (int) (dbQuery($sqlN, ':domain_id', $domainId)->fetch(PDO::FETCH_ASSOC)['n'] ?? 0);

        return ['checked' => $n, 'mismatches' => $bad];
    }

    public static function rebuildDomain($domainId = null): void
    {
        $domainId = domain_id::get($domainId);
        $sth = dbQuery('SELECT id FROM ' . TB_PREFIX . 'invoices WHERE domain_id = :d ORDER BY id', ':d', $domainId);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            self::refreshForInvoice((int) $row['id'], $domainId);
        }
    }
}
