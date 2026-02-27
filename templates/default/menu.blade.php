@stack('hook_tabmenu_start')

{{-- Row 1: Top bar — logo + user controls --}}
<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="index.php?module=index&amp;view=index" class="text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><line x1="9" y1="7" x2="10" y2="7"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="13" y1="17" x2="15" y2="17"/></svg>
                <span>Simple Invoices</span>
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex me-3">
                <a href="http://www.simpleinvoices.org" target="_blank" class="btn btn-outline-secondary btn-sm border-0">
                    <i class="ti ti-external-link me-1"></i>Website
                </a>
            </div>
            <div class="nav-item d-none d-md-flex me-3">
                <a href="index.php?module=options&amp;view=backup_database" class="btn btn-ghost-secondary btn-icon" title="{{ $LANG['backup_database'] ?? 'Backup Database' }}">
                    <i class="ti ti-database-export"></i>
                </a>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="User menu" aria-expanded="false">
                    <span class="avatar avatar-sm rounded-circle bg-primary-lt">
                        <i class="ti ti-user" style="font-size: 1.2rem;"></i>
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        <div class="fw-medium">{{ $LANG['users'] ?? 'Admin' }}</div>
                        <div class="mt-1 small text-secondary">{{ $LANG['simple_invoices'] ?? 'Simple Invoices' }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="index.php?module=user&amp;view=manage" class="dropdown-item">
                        <i class="ti ti-users me-2"></i>{{ $LANG['manage_accounts'] ?? 'Manage Users' }}
                    </a>
                    <a href="index.php?module=options&amp;view=index" class="dropdown-item">
                        <i class="ti ti-settings me-2"></i>{{ $LANG['settings'] ?? 'Settings' }}
                    </a>
                    <a href="index.php?module=system_defaults&amp;view=manage" class="dropdown-item">
                        <i class="ti ti-adjustments me-2"></i>{{ $LANG['system_preferences'] ?? 'System Preferences' }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="index.php?module=options&amp;view=backup_database" class="dropdown-item">
                        <i class="ti ti-database-export me-2"></i>{{ $LANG['backup_database'] ?? 'Backup Database' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Row 2: Navigation bar --}}
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    {{-- Home --}}
                    <li class="nav-item @if(($module ?? '') == 'index') active @endif">
                        <a class="nav-link" href="index.php?module=index&amp;view=index">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home"></i></span>
                            <span class="nav-link-title">{{ $LANG['home'] ?? 'Home' }}</span>
                        </a>
                    </li>

                    {{-- Invoices --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['invoices','cron'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-file-invoice"></i></span>
                            <span class="nav-link-title">{{ $LANG['invoices'] ?? 'Invoices' }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item @if(($module ?? '') == 'invoices' && ($view ?? '') == 'manage') active @endif" href="index.php?module=invoices&amp;view=manage">
                                {{ $LANG['manage_invoices'] ?? 'All Invoices' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'invoices' && ($view ?? '') == 'itemised') active @endif" href="index.php?module=invoices&amp;view=itemised">
                                {{ $LANG['new_invoice'] ?? 'New Invoice' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'cron') active @endif" href="index.php?module=cron&amp;view=manage">
                                {{ $LANG['recurrence'] ?? 'Recurrence' }}
                            </a>
                        </div>
                    </li>

                    {{-- Payments --}}
                    <li class="nav-item @if(($module ?? '') == 'payments') active @endif">
                        <a class="nav-link" href="index.php?module=payments&amp;view=manage">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-cash"></i></span>
                            <span class="nav-link-title">{{ $LANG['payments'] ?? 'Payments' }}</span>
                        </a>
                    </li>

                    {{-- People --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['customers','billers'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                            <span class="nav-link-title">{{ $LANG['people'] ?? 'People' }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item @if(($module ?? '') == 'customers') active @endif" href="index.php?module=customers&amp;view=manage">
                                {{ $LANG['customers'] ?? 'Customers' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'billers') active @endif" href="index.php?module=billers&amp;view=manage">
                                {{ $LANG['billers'] ?? 'Billers' }}
                            </a>
                        </div>
                    </li>

                    {{-- Products --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['products','inventory','product_attribute','product_value'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-package"></i></span>
                            <span class="nav-link-title">{{ $LANG['products'] ?? 'Products' }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item @if(($module ?? '') == 'products' && ($view ?? '') == 'manage') active @endif" href="index.php?module=products&amp;view=manage">
                                {{ $LANG['manage_products'] ?? 'Manage Products' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'products' && ($view ?? '') == 'add') active @endif" href="index.php?module=products&amp;view=add">
                                {{ $LANG['add_product'] ?? 'Add Product' }}
                            </a>
                            @if(isset($defaults->inventory) && $defaults->inventory == "1")
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'inventory') active @endif" href="index.php?module=inventory&amp;view=manage">
                                {{ $LANG['inventory'] ?? 'Inventory' }}
                            </a>
                            @endif
                            @if(isset($defaults->product_attributes) && $defaults->product_attributes)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'product_attribute') active @endif" href="index.php?module=product_attribute&amp;view=manage">
                                {{ $LANG['product_attributes'] ?? 'Product Attributes' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'product_value') active @endif" href="index.php?module=product_value&amp;view=manage">
                                {{ $LANG['product_values'] ?? 'Product Values' }}
                            </a>
                            @endif
                        </div>
                    </li>

                    {{-- Reports --}}
                    <li class="nav-item @if(($module ?? '') == 'reports') active @endif">
                        <a class="nav-link" href="index.php?module=reports&amp;view=index">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-bar"></i></span>
                            <span class="nav-link-title">{{ $LANG['all_reports'] ?? 'Reports' }}</span>
                        </a>
                    </li>

                    {{-- Settings --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['options','system_defaults','custom_fields','tax_rates','preferences','payment_types','extensions'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-settings"></i></span>
                            <span class="nav-link-title">{{ $LANG['settings'] ?? 'Settings' }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="index.php?module=system_defaults&amp;view=manage">{{ $LANG['system_preferences'] ?? 'System Preferences' }}</a>
                            <a class="dropdown-item" href="index.php?module=custom_fields&amp;view=manage">{{ $LANG['custom_fields_upper'] ?? 'Custom Fields' }}</a>
                            <a class="dropdown-item" href="index.php?module=tax_rates&amp;view=manage">{{ $LANG['tax_rates'] ?? 'Tax Rates' }}</a>
                            <a class="dropdown-item" href="index.php?module=preferences&amp;view=manage">{{ $LANG['invoice_preferences'] ?? 'Invoice Preferences' }}</a>
                            <a class="dropdown-item" href="index.php?module=payment_types&amp;view=manage">{{ $LANG['payment_types'] ?? 'Payment Types' }}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php?module=extensions&amp;view=manage">{{ $LANG['extensions'] ?? 'Extensions' }}</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

@stack('hook_tabmenu_end')

{{-- Page wrapper with header + body --}}
<div class="page-wrapper">
    @php
        $tmp_lang_module = $LANG['title_module_'.$module] ?? $LANG[$module] ?? ucfirst($module ?? '');
        $tmp_lang_view = $LANG['title_view_'.$view] ?? $LANG[$view] ?? ucfirst($view ?? '');
    @endphp
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">{{ $tmp_lang_module }}</div>
                    <h2 class="page-title">{{ $tmp_lang_view }}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                    @if(($module ?? '') == 'invoices' && ($view ?? '') == 'manage')
                        <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary">
                            {{ $LANG['manage_invoices'] ?? 'All Invoices' }}
                        </a>
                        <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                        </a>
                    @elseif(($module ?? '') == 'customers' && ($view ?? '') == 'manage')
                        <a href="index.php?module=customers&amp;view=manage" class="btn btn-outline-secondary">
                            {{ $LANG['manage_customers'] ?? 'All Customers' }}
                        </a>
                        <a href="index.php?module=customers&amp;view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['customer_add'] ?? 'Add Customer' }}
                        </a>
                    @elseif(($module ?? '') == 'billers' && ($view ?? '') == 'manage')
                        <a href="index.php?module=billers&amp;view=manage" class="btn btn-outline-secondary">
                            {{ $LANG['manage_billers'] ?? 'All Billers' }}
                        </a>
                        <a href="index.php?module=billers&amp;view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_biller'] ?? 'Add Biller' }}
                        </a>
                    @elseif(($module ?? '') == 'products' && ($view ?? '') == 'manage')
                        <a href="index.php?module=products&amp;view=manage" class="btn btn-outline-secondary">
                            {{ $LANG['manage_products'] ?? 'All Products' }}
                        </a>
                        <a href="index.php?module=products&amp;view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_product'] ?? 'Add Product' }}
                        </a>
                    @elseif(($module ?? '') == 'payments' && ($view ?? '') == 'manage')
                        <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary">
                            {{ $LANG['manage_payments'] ?? 'All Payments' }}
                        </a>
                        <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['process_payment'] ?? 'Process Payment' }}
                        </a>
                    @elseif(($module ?? '') == 'reports')
                        <a href="index.php?module=reports&amp;view=index" class="btn btn-outline-secondary">
                            {{ $LANG['all_reports'] ?? 'All Reports' }}
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
