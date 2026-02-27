@php
    $hasBillers = isset($billers) && is_array($billers) && count($billers) > 0;
    $hasCustomers = isset($customers) && is_array($customers) && count($customers) > 0;
    $hasProducts = isset($products) && is_array($products) && count($products) > 0;
    $hasTaxRates = isset($tax_rates) && is_array($tax_rates) && count($tax_rates) > 0;
    $hasPreferences = isset($preferences) && is_array($preferences) && count($preferences) > 0;
    $firstRun = !$hasBillers || !$hasCustomers || !$hasProducts || !$hasTaxRates || !$hasPreferences;
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
            <div class="col-sm-6 col-lg-4">
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
            <div class="col-sm-6 col-lg-4">
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
            <div class="col-sm-6 col-lg-4">
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
        </div>
    </div>
</div>
@endif

<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar bg-primary-lt rounded me-3"><i class="ti ti-file-invoice"></i></span>
                    <div>
                        <div class="text-secondary fs-5">{{ $LANG['invoices'] ?? 'Invoices' }}</div>
                    </div>
                </div>
                <a href="index.php?module=invoices&amp;view=manage" class="btn btn-primary w-100">
                    <i class="ti ti-list me-1"></i>{{ $LANG['manage_invoices'] ?? 'Manage Invoices' }}
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar bg-success-lt rounded me-3"><i class="ti ti-plus"></i></span>
                    <div>
                        <div class="text-secondary fs-5">{{ $LANG['new_invoice'] ?? 'New Invoice' }}</div>
                    </div>
                </div>
                <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-success w-100">
                    <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar bg-warning-lt rounded me-3"><i class="ti ti-users"></i></span>
                    <div>
                        <div class="text-secondary fs-5">{{ $LANG['customers'] ?? 'Customers' }}</div>
                    </div>
                </div>
                <a href="index.php?module=customers&amp;view=manage" class="btn btn-outline-warning w-100">
                    <i class="ti ti-users me-1"></i>{{ $LANG['customers'] ?? 'Customers' }}
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <span class="avatar bg-info-lt rounded me-3"><i class="ti ti-chart-bar"></i></span>
                    <div>
                        <div class="text-secondary fs-5">{{ $LANG['all_reports'] ?? 'Reports' }}</div>
                    </div>
                </div>
                <a href="index.php?module=reports&amp;view=index" class="btn btn-outline-info w-100">
                    <i class="ti ti-chart-bar me-1"></i>{{ $LANG['all_reports'] ?? 'Reports' }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-cash me-2"></i>{{ $LANG['money'] ?? 'Money' }}</h3>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php?module=invoices&amp;view=manage" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-file-invoice me-2 text-primary"></i>{{ $LANG['manage_invoices'] ?? 'Manage Invoices' }}
                </a>
                <a href="index.php?module=invoices&amp;view=itemised" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-plus me-2 text-success"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                </a>
                <a href="index.php?module=payments&amp;view=manage" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-cash me-2 text-warning"></i>{{ $LANG['payments'] ?? 'Payments' }}
                </a>
                <a href="index.php?module=reports&amp;view=report_sales_total" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-chart-line me-2 text-info"></i>{{ $LANG['sales_report'] ?? 'Sales Report' }}
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-users me-2"></i>{{ $LANG['people'] ?? 'People' }} &amp; {{ $LANG['products'] ?? 'Products' }}</h3>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php?module=customers&amp;view=manage" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-users me-2 text-primary"></i>{{ $LANG['customers'] ?? 'Customers' }}
                </a>
                <a href="index.php?module=billers&amp;view=manage" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-building-store me-2 text-primary"></i>{{ $LANG['billers'] ?? 'Billers' }}
                </a>
                <a href="index.php?module=products&amp;view=manage" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-package me-2 text-primary"></i>{{ $LANG['products'] ?? 'Products' }}
                </a>
                <a href="index.php?module=options&amp;view=index" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="ti ti-settings me-2 text-secondary"></i>{{ $LANG['settings'] ?? 'Settings' }}
                </a>
            </div>
        </div>
    </div>
</div>
