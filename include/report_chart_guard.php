<?php

/**
 * Shared thresholds for ApexCharts on report pages — tables stay full; charts show a capped “top N” slice when needed.
 */
if (! function_exists('si_report_chart_display_limit')) {
    /**
     * Maximum categories/bars/lines shown in a report chart when the full set is large or over thresholds.
     */
    function si_report_chart_display_limit(): int
    {
        return 10;
    }
}

if (! function_exists('si_report_chart_display_guard')) {
    /**
     * Merge threshold diagnostics from si_report_chart_allow() with display flags for Blade.
     * Overwrites 'enabled' to mean “show chart region” (true when there is at least one category to plot).
     *
     * @param  array  $threshold_allow  return value of si_report_chart_allow() before slicing
     * @param  int    $category_total    full dataset size (e.g. rows, years) used for threshold checks
     * @param  int    $category_shown    number of categories/rows actually passed to the chart after slicing
     */
    function si_report_chart_display_guard(array $threshold_allow, int $category_total, int $category_shown): array
    {
        $threshold_ok = ! empty($threshold_allow['enabled']);
        $limit        = si_report_chart_display_limit();

        return array_merge($threshold_allow, [
            'threshold_ok'             => $threshold_ok,
            'enabled'                  => $category_total > 0 && $category_shown > 0,
            'chart_truncated'          => $category_shown < $category_total,
            'chart_limit'              => $limit,
            'chart_total_categories'   => $category_total,
            'chart_shown_categories'   => $category_shown,
            'chart_threshold_blocked'  => ! $threshold_ok,
        ]);
    }
}

if (! function_exists('si_report_chart_top_rows_by_key')) {
    /**
     * Sort rows by a numeric column (descending) and return a chart-sized slice plus display guard.
     *
     * @param  array  $rows          associative rows
     * @param  string $sortKey       field used for ranking (highest first in chart)
     * @param  int    $invoiceCount  invoices in scope for si_report_chart_allow()
     * @param  int    $seriesCount   number of chart series (default 1)
     * @return array{rows: array, guard: array}
     */
    function si_report_chart_top_rows_by_key(array $rows, string $sortKey, int $invoiceCount, int $seriesCount = 1): array
    {
        $sorted = $rows;
        usort($sorted, function ($a, $b) use ($sortKey) {
            return (float) ($b[$sortKey] ?? 0) <=> (float) ($a[$sortKey] ?? 0);
        });
        $total       = count($sorted);
        $threshold   = si_report_chart_allow($invoiceCount, $total, $seriesCount);
        $limit       = si_report_chart_display_limit();
        $must_slice  = ! $threshold['enabled'] || $total > $limit;
        $shown_count = $must_slice ? min($limit, $total) : $total;
        $slice       = array_slice($sorted, 0, $shown_count);

        return [
            'rows'  => $slice,
            'guard' => si_report_chart_display_guard($threshold, $total, count($slice)),
        ];
    }
}

if (! function_exists('si_report_chart_invoice_volume_chart_max')) {
    /**
     * Active-invoice count above which some reports omit charts entirely (table still lists all rows).
     */
    function si_report_chart_invoice_volume_chart_max(): int
    {
        return 1000;
    }
}

if (! function_exists('si_report_chart_guard_omit_over_invoice_max')) {
    /**
     * @return array{omit: bool, guard: ?array} When omit is true, guard disables the chart and flags chart_omitted_invoice_cap.
     */
    function si_report_chart_guard_omit_over_invoice_max(int $invoice_count): array
    {
        $max = si_report_chart_invoice_volume_chart_max();
        if ($invoice_count <= $max) {
            return ['omit' => false, 'guard' => null];
        }

        return [
            'omit' => true,
            'guard' => [
                'enabled'                   => false,
                'chart_omitted_invoice_cap' => true,
                'chart_omitted_inv'         => $invoice_count,
                'chart_omitted_max'         => $max,
                'threshold_ok'              => false,
                'chart_truncated'           => false,
                'chart_threshold_blocked'   => false,
            ],
        ];
    }
}

if (! function_exists('si_report_active_invoice_count')) {
    /**
     * Count of invoices using an active (status = 1) preference for the domain.
     * Result is cached per request per domain id.
     */
    function si_report_active_invoice_count($domain_id): int
    {
        static $cache = [];

        $k = (string) $domain_id;
        if (! array_key_exists($k, $cache)) {
            $r = dbQuery(
                'SELECT COUNT(*) AS cnt FROM ' . TB_PREFIX . 'invoices iv
                 INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                 WHERE pr.status = \'1\' AND iv.domain_id = :domain_id',
                ':domain_id',
                $domain_id
            )->fetch();
            $cache[$k] = (int) ($r['cnt'] ?? 0);
        }

        return $cache[$k];
    }
}

if (! function_exists('si_report_chart_allow')) {
    /**
     * Whether to render a report chart. For stacked charts, pass category count (e.g. bars) and series count (segments/lines).
     *
     * @param  int  $invoiceCount  Invoices in scope: usually si_report_active_invoice_count(); report-scoped counts for date-range reports
     * @param  int  $categoryCount Number of chart categories (one axis)
     * @param  int  $seriesCount   Number of series (default 1); grouped/stacked charts use &gt; 1
     */
    function si_report_chart_allow(int $invoiceCount, int $categoryCount, int $seriesCount = 1): array
    {
        $maxInv   = 1000;
        $maxCat   = 150;
        $maxSer   = 150;
        $maxCells = 2500;

        $seriesCount = max(1, $seriesCount);
        $cells       = $categoryCount * $seriesCount;

        $skipInv   = $invoiceCount > $maxInv;
        $skipCat   = $categoryCount > $maxCat;
        $skipSer   = $seriesCount > $maxSer;
        $skipCells = $seriesCount > 1 && $cells > $maxCells;

        $enabled = ! $skipInv && ! $skipCat && ! $skipSer && ! $skipCells && $categoryCount > 0;

        return [
            'enabled'    => $enabled,
            'skip_inv'   => $skipInv,
            'skip_cat'   => $skipCat,
            'skip_ser'   => $skipSer,
            'skip_cells' => $skipCells,
            'inv'        => $invoiceCount,
            'max_inv'    => $maxInv,
            'max_cat'    => $maxCat,
            'max_ser'    => $maxSer,
            'max_cells'  => $maxCells,
            'cat_count'  => $categoryCount,
            'ser_count'  => $seriesCount,
            'cell_count' => $cells,
        ];
    }
}
