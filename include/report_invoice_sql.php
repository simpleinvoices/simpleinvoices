<?php

/**
 * Reusable SQL fragments for reports: pre-aggregated line totals and payments per invoice
 * (avoids joining raw invoice_items × payment rows before GROUP BY).
 */
if (! function_exists('si_report_sql_invoice_line_totals_inner_join')) {
    /**
     * INNER JOIN: one row per invoice that has at least one line item, with line_total = SUM(total).
     */
    function si_report_sql_invoice_line_totals_inner_join(string $invoice_alias = 'iv'): string
    {
        $t = TB_PREFIX;

        return "
            INNER JOIN (
                SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS line_total
                FROM {$t}invoice_items
                GROUP BY invoice_id, domain_id
            ) lt ON (lt.invoice_id = {$invoice_alias}.id AND lt.domain_id = {$invoice_alias}.domain_id)";
    }
}

if (! function_exists('si_report_sql_invoice_line_totals_join')) {
    /**
     * LEFT JOIN to one row per (invoice_id, domain_id) with sum_items = SUM(total).
     */
    function si_report_sql_invoice_line_totals_join(string $invoice_alias = 'iv'): string
    {
        $t = TB_PREFIX;

        return "
            LEFT JOIN (
                SELECT invoice_id, domain_id, SUM(COALESCE(total, 0)) AS sum_items
                FROM {$t}invoice_items
                GROUP BY invoice_id, domain_id
            ) ii_sum ON (ii_sum.invoice_id = {$invoice_alias}.id AND ii_sum.domain_id = {$invoice_alias}.domain_id)";
    }
}

if (! function_exists('si_report_sql_invoice_payments_join')) {
    /**
     * LEFT JOIN to one row per (ac_inv_id, domain_id) with inv_paid = SUM(ac_amount).
     *
     * @param  bool  $only_active_pref  If true, only sums payments tied to invoices whose preference has status = 1
     *                                  (matches legacy debtors-by-customer / aging-total payment subqueries).
     */
    function si_report_sql_invoice_payments_join(string $invoice_alias = 'iv', bool $only_active_pref = false): string
    {
        $t = TB_PREFIX;

        if ($only_active_pref) {
            return "
            LEFT JOIN (
                SELECT ap1.ac_inv_id, ap1.domain_id, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid
                FROM {$t}payment ap1
                LEFT JOIN {$t}invoices iv1 ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
                LEFT JOIN {$t}preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
                WHERE pr1.status = 1
                GROUP BY ap1.ac_inv_id, ap1.domain_id
            ) ap ON (ap.ac_inv_id = {$invoice_alias}.id AND ap.domain_id = {$invoice_alias}.domain_id)";
        }

        return "
            LEFT JOIN (
                SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
                FROM {$t}payment
                GROUP BY ac_inv_id, domain_id
            ) ap ON (ap.ac_inv_id = {$invoice_alias}.id AND ap.domain_id = {$invoice_alias}.domain_id)";
    }
}
