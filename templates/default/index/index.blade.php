@php
    $hasBillers = isset($billers) && is_array($billers) && count($billers) > 0;
    $hasCustomers = isset($customers) && is_array($customers) && count($customers) > 0;
    $hasProducts = isset($products) && is_array($products) && count($products) > 0;
    $hasTaxRates = isset($tax_rates) && is_array($tax_rates) && count($tax_rates) > 0;
    $hasPreferences = isset($preferences) && is_array($preferences) && count($preferences) > 0;
    $hasInvoices = isset($latest_invoices) && is_array($latest_invoices) && count($latest_invoices) > 0;
    $firstRun = !$hasBillers || !$hasCustomers || !$hasProducts || !$hasInvoices;
@endphp

@if($firstRun)
<div class="card mb-3">
    <div class="card-status-top bg-primary"></div>
    <div class="card-header">
        <h3 class="card-title"><i class="ti ti-rocket me-2"></i>{{ $LANG['getting_started'] ?? 'Getting Started' }}</h3>
    </div>
    <div class="card-body">
        <p class="text-secondary">{{ $LANG['first_run_intro'] ?? 'Welcome! Complete these steps to start invoicing:' }}</p>
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($hasBillers) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($hasBillers) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-building-store"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['biller'] ?? 'Biller' }}</div>
                            @if($hasBillers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=billers&amp;view=add" class="text-primary">{{ $LANG['add_new_biller'] ?? 'Add Biller' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($hasCustomers) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($hasCustomers) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-users"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['customer'] ?? 'Customer' }}</div>
                            @if($hasCustomers)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=customers&amp;view=add" class="text-primary">{{ $LANG['customer_add'] ?? 'Add Customer' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($hasProducts) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($hasProducts) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-package"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['product'] ?? 'Product' }}</div>
                            @if($hasProducts)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=products&amp;view=add" class="text-primary">{{ $LANG['add_new_product'] ?? 'Add Product' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card @if($hasInvoices) border-success @endif">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="avatar @if($hasInvoices) bg-success-lt @else bg-primary-lt @endif rounded">
                            <i class="ti ti-file-invoice"></i>
                        </span>
                        <div>
                            <div class="fw-bold">{{ $LANG['invoice'] ?? 'Invoice' }}</div>
                            @if($hasInvoices)
                                <span class="text-success"><i class="ti ti-check"></i> {{ $LANG['done'] ?? 'Done' }}</span>
                            @else
                                <a href="index.php?module=invoices&amp;view=itemised" class="text-primary">{{ $LANG['create_invoice'] ?? 'Create Invoice' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Activity chart --}}
<div class="card mb-3">
    <div class="card-header">
        <div>
            <h3 class="card-title">{{ $LANG['invoices'] ?? 'Invoices' }} &amp; {{ $LANG['payments'] ?? 'Payments' }}</h3>
            <div class="card-subtitle">{{ $LANG['monthly_activity'] ?? 'Monthly activity' }}</div>
        </div>
        <div class="card-options ms-auto">
            <div class="segmented-control" id="chart-year-selector">
                @foreach(($chart_years ?? []) as $y)
                <label class="segmented-control-item">
                    <input type="radio" class="segmented-control-input" name="chart_year" value="{{ $y }}"{{ $y === ($chart_current_year ?? 0) ? ' checked' : '' }}>
                    <span class="segmented-control-label">{{ $y }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart-dashboard" style="min-height:288px"></div>
    </div>
</div>

{{-- Recent invoices --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_invoices'] ?? 'Recent Invoices' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=invoices&amp;view=add" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_invoice'] ?? 'Add Invoice' }}
            </a>
            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                {{ $LANG['manage_invoices'] ?? 'View all' }}
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
            <thead>
                <tr>
                    <th>{{ $LANG['id'] ?? '#' }}</th>
                    <th>{{ $LANG['customer'] ?? 'Customer' }}</th>
                    <th>{{ $LANG['biller'] ?? 'Biller' }}</th>
                    <th>{{ $LANG['date_upper'] ?? 'Date' }}</th>
                    <th class="text-end">{{ $LANG['total'] ?? 'Total' }}</th>
                    <th>{{ $LANG['status'] ?? 'Status' }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse(($latest_invoices ?? []) as $inv)
                @php
                    $isPaid  = ($inv['status'] ?? 0) && ($inv['owing'] ?? 0) <= 0;
                    $isDraft = !($inv['status'] ?? 0);
                    $currency = $preference['pref_currency_sign'] ?? '';
                @endphp
                <tr>
                    <td>
                        <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($inv['id']) }}">
                            {{ ($inv['preference'] ?? $inv['pref_description'] ?? '') }} {{ $inv['index_id'] ?? '' }}
                        </a>
                    </td>
                    <td>{{ $inv['customer'] ?? '' }}</td>
                    <td class="text-secondary">{{ $inv['biller'] ?? '' }}</td>
                    <td class="text-secondary">{{ $inv['date'] ?? '' }}</td>
                    <td class="text-end">{{ siLocal::number($inv['invoice_total'] ?? 0) }}</td>
                    <td>
                        @if($isDraft)
                            <span class="status status-secondary"><span class="status-dot"></span>{{ $LANG['draft'] ?? 'Draft' }}</span>
                        @elseif($isPaid)
                            <span class="status status-green">{{ $LANG['paid'] ?? 'Paid' }}</span>
                        @else
                            <span class="status status-orange">{{ $LANG['due'] ?? 'Due' }}</span>
                        @endif
                    </td>
                    <td class="w-1">
                        <div class="btn-group">
                            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($inv['id']) }}" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['quick_view'] ?? 'Quick View' }}">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($inv['id']) }}&amp;format=pdf" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['export_pdf'] ?? 'PDF' }}">
                                <i class="ti ti-file-type-pdf"></i>
                            </a>
                            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['manage_invoices'] ?? 'Manage' }}">
                                <i class="ti ti-list"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">{{ $LANG['no_invoices'] ?? 'No invoices yet' }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent payments --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_payments'] ?? 'Recent Payments' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_payment'] ?? 'Add Payment' }}
            </a>
            <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                {{ $LANG['manage_payments'] ?? 'View all' }}
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
            <thead>
                <tr>
                    <th>{{ $LANG['invoice'] ?? 'Invoice' }}</th>
                    <th>{{ $LANG['customer'] ?? 'Customer' }}</th>
                    <th>{{ $LANG['biller'] ?? 'Biller' }}</th>
                    <th>{{ $LANG['date_upper'] ?? 'Date' }}</th>
                    <th class="text-end">{{ $LANG['amount'] ?? 'Amount' }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse(($latest_payments ?? []) as $pmt)
                <tr>
                    <td>
                        <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($pmt['ac_inv_id']) }}">
                            {{ ($pmt['preference'] ?? $pmt['pref_description'] ?? '') }} {{ $pmt['index_id'] ?? '' }}
                        </a>
                    </td>
                    <td>{{ $pmt['customer'] ?? '' }}</td>
                    <td class="text-secondary">{{ $pmt['biller'] ?? '' }}</td>
                    <td class="text-secondary">{{ $pmt['ac_date'] ?? '' }}</td>
                    <td class="text-end">{{ siLocal::number($pmt['ac_amount'] ?? 0) }}</td>
                    <td class="w-1">
                        <div class="btn-group">
                            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($pmt['ac_inv_id']) }}" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['quick_view'] ?? 'Quick View' }}">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['manage_payments'] ?? 'Manage' }}">
                                <i class="ti ti-list"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary">{{ $LANG['no_payments'] ?? 'No payments yet' }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script>
(function () {
    var labels   = @json($chart_labels ?? []);
    var datasets = @json($chart_data ?? []);
    var invoiceLabel = @json($LANG['invoices'] ?? 'Invoices');
    var paymentLabel = @json($LANG['payments'] ?? 'Payments');

    function cssVar(name) {
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    }

    function buildOptions(year) {
        var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var primary   = cssVar('--tblr-primary')      || '#45aaf2';
        var success   = cssVar('--tblr-success')      || '#2fb344';
        var bodyColor = cssVar('--tblr-body-color')   || '#1d273b';
        var borderCol = cssVar('--tblr-border-color') || '#e6e7e9';
        return {
            chart: {
                type: 'area',
                fontFamily: 'inherit',
                height: 288,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent'
            },
            series: [
                { name: invoiceLabel, data: (datasets[year] || {invoices: [], payments: []}).invoices },
                { name: paymentLabel, data: (datasets[year] || {invoices: [], payments: []}).payments }
            ],
            xaxis: {
                categories: labels,
                labels: { style: { colors: bodyColor } },
                axisBorder: { color: borderCol },
                axisTicks: { color: borderCol }
            },
            yaxis: {
                labels: {
                    style: { colors: bodyColor },
                    formatter: function (v) { return v.toLocaleString(); }
                }
            },
            colors: [primary, success],
            stroke: { width: 2, curve: 'smooth' },
            fill: {
                type: 'gradient',
                gradient: { opacityFrom: 0.15, opacityTo: 0.01 }
            },
            legend: {
                show: true,
                position: 'bottom',
                labels: { colors: bodyColor }
            },
            dataLabels: { enabled: false },
            grid: { borderColor: borderCol, strokeDashArray: 4 },
            tooltip: { theme: isDark ? 'dark' : 'light' }
        };
    }

    var chart = new ApexCharts(document.getElementById('chart-dashboard'), buildOptions({{ $chart_current_year ?? 'null' }}));
    chart.render();

    document.querySelectorAll('#chart-year-selector input[name="chart_year"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            chart.updateOptions(buildOptions(this.value), true, true);
        });
    });

    document.documentElement.addEventListener('si-theme-changed', function () {
        var active = document.querySelector('#chart-year-selector input[name="chart_year"]:checked');
        chart.updateOptions(buildOptions(active ? active.value : {{ $chart_current_year ?? 'null' }}), true, true);
    });
})();
</script>
