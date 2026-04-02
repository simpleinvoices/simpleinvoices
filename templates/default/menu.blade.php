@stack('hook_tabmenu_start')
@php
    $appName = $config->app?->name ?? $LANG['simple_invoices'] ?? 'Simple Invoices';
    $appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
    $appWebsiteLabel = $config->app?->website_label ?? 'Website';
    $authEnabled = ((int) ($config->authentication->enabled ?? 0)) === 1;
    $currentUserEmail = $authEnabled ? ($_SESSION['SI_Auth']['email'] ?? '') : '';
    $currentUserName  = $authEnabled ? (($_SESSION['SI_Auth']['name'] ?? '') ?: $currentUserEmail) : '';
    $currentUserId    = $authEnabled ? ($_SESSION['SI_Auth']['id']    ?? '') : '';
@endphp

{{-- Row 1: Top bar — logo + user controls --}}
<header class="navbar navbar-expand-md d-print-none" data-bs-theme="dark">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="index.php?module=index&view=index" class="text-decoration-none d-flex align-items-center">
                @if(!empty($config->app?->logo))
                    <img src="{{ $config->app->logo }}" alt="{{ $appName }}" class="me-2" style="max-height: 28px;" />
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><line x1="9" y1="7" x2="10" y2="7"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="13" y1="17" x2="15" y2="17"/></svg>
                @endif
                <span>{{ $appName }}</span>
            </a>
        </h1>
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex me-3">
                <a href="{{ $appWebsite }}" target="_blank" rel="noopener" class="btn btn-outline-secondary border-0">
                    <i class="ti ti-external-link me-1"></i>{{ $appWebsiteLabel }}
                </a>
            </div>
            <div class="nav-item me-2">
                <a href="#" class="nav-link px-0 hide-theme-dark" title="Switch to dark mode" onclick="siToggleTheme(event)">
                    <i class="ti ti-moon fs-4"></i>
                </a>
                <a href="#" class="nav-link px-0 hide-theme-light" title="Switch to light mode" onclick="siToggleTheme(event)">
                    <i class="ti ti-sun fs-4"></i>
                </a>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="User menu" aria-expanded="false">
                    <span class="avatar avatar-sm rounded-circle bg-primary-lt">
                        <i class="ti ti-user" style="font-size: 1.2rem;"></i>
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        @if($authEnabled && $currentUserName)
                            <div class="fw-medium">{{ $currentUserName }}</div>
                            <div class="mt-1 small text-secondary">{{ $currentUserEmail }}</div>
                        @else
                            <div class="fw-medium">{{ $LANG['users'] ?? 'Admin' }}</div>
                            <div class="mt-1 small text-secondary">{{ $appName }}</div>
                        @endif
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    @if($authEnabled && $currentUserId)
                        <a href="index.php?module=user&view=details&action=edit&id={{ urlencode($currentUserId) }}" class="dropdown-item">
                            <i class="ti ti-user-edit me-2"></i>{{ $LANG['edit_profile'] ?? 'Edit Profile' }}
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <a href="index.php?module=auth&view=logout" class="dropdown-item text-danger">
                        <i class="ti ti-logout me-2"></i>{{ $LANG['logout'] ?? 'Logout' }}
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
                        <a class="nav-link" href="index.php?module=index&view=index">
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
                            <a class="dropdown-item @if(($module ?? '') == 'invoices' && ($view ?? '') == 'manage') active @endif" href="index.php?module=invoices&view=manage">
                                <i class="ti ti-list me-2 text-secondary"></i>{{ $LANG['manage_invoices'] ?? 'All Invoices' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'invoices' && ($view ?? '') == 'itemised') active @endif" href="index.php?module=invoices&view=itemised">
                                <i class="ti ti-file-plus me-2 text-secondary"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'cron') active @endif" href="index.php?module=cron&view=manage">
                                <i class="ti ti-repeat me-2 text-secondary"></i>{{ $LANG['recurrence'] ?? 'Recurrence' }}
                            </a>
                        </div>
                    </li>

                    {{-- Payments --}}
                    <li class="nav-item @if(($module ?? '') == 'payments') active @endif">
                        <a class="nav-link" href="index.php?module=payments&view=manage">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-cash"></i></span>
                            <span class="nav-link-title">{{ $LANG['payments'] ?? 'Payments' }}</span>
                        </a>
                    </li>

                    {{-- People --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['customers','billers','user'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                            <span class="nav-link-title">{{ $LANG['people'] ?? 'People' }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item @if(($module ?? '') == 'customers') active @endif" href="index.php?module=customers&view=manage">
                                <i class="ti ti-address-book me-2 text-secondary"></i>{{ $LANG['customers'] ?? 'Customers' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'billers') active @endif" href="index.php?module=billers&view=manage">
                                <i class="ti ti-building-store me-2 text-secondary"></i>{{ $LANG['billers'] ?? 'Billers' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'user') active @endif" href="index.php?module=user&view=manage">
                                <i class="ti ti-users me-2 text-secondary"></i>{{ $LANG['manage_accounts'] ?? 'Manage Users' }}
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
                            <a class="dropdown-item @if(($module ?? '') == 'products' && ($view ?? '') == 'manage') active @endif" href="index.php?module=products&view=manage">
                                <i class="ti ti-packages me-2 text-secondary"></i>{{ $LANG['manage_products'] ?? 'Manage Products' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'products' && ($view ?? '') == 'add') active @endif" href="index.php?module=products&view=add">
                                <i class="ti ti-package-export me-2 text-secondary"></i>{{ $LANG['add_product'] ?? 'Add Product' }}
                            </a>
                            @if(isset($defaults->inventory) && $defaults->inventory == "1")
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'inventory') active @endif" href="index.php?module=inventory&view=manage">
                                <i class="ti ti-stack-2 me-2 text-secondary"></i>{{ $LANG['inventory'] ?? 'Inventory' }}
                            </a>
                            @endif
                            @if(isset($defaults->product_attributes) && $defaults->product_attributes)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'product_attribute') active @endif" href="index.php?module=product_attribute&view=manage">
                                <i class="ti ti-tag me-2 text-secondary"></i>{{ $LANG['product_attributes'] ?? 'Product Attributes' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'product_value') active @endif" href="index.php?module=product_value&view=manage">
                                <i class="ti ti-tags me-2 text-secondary"></i>{{ $LANG['product_values'] ?? 'Product Values' }}
                            </a>
                            @endif
                        </div>
                    </li>

                    {{-- Reports --}}
                    <li class="nav-item @if(($module ?? '') == 'reports') active @endif">
                        <a class="nav-link" href="index.php?module=reports&view=index">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-bar"></i></span>
                            <span class="nav-link-title">{{ $LANG['all_reports'] ?? 'Reports' }}</span>
                        </a>
                    </li>

                </ul>
                <ul class="navbar-nav ms-auto">
                    {{-- Settings --}}
                    <li class="nav-item dropdown @if(in_array($module ?? '', ['options','system_defaults','custom_fields','tax_rates','preferences','payment_types'])) active @endif">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-adjustments-horizontal"></i></span>
                            <span class="nav-link-title">{{ $LANG['settings'] ?? 'Settings' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg-end">
                            <a class="dropdown-item @if(($module ?? '') == 'system_defaults') active @endif" href="index.php?module=system_defaults&view=manage">
                                <i class="ti ti-adjustments me-2 text-secondary"></i>{{ $LANG['system_preferences'] ?? 'System Preferences' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'custom_fields') active @endif" href="index.php?module=custom_fields&view=manage">
                                <i class="ti ti-forms me-2 text-secondary"></i>{{ $LANG['custom_fields_upper'] ?? 'Custom Fields' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'tax_rates') active @endif" href="index.php?module=tax_rates&view=manage">
                                <i class="ti ti-receipt-tax me-2 text-secondary"></i>{{ $LANG['tax_rates'] ?? 'Tax Rates' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'preferences') active @endif" href="index.php?module=preferences&view=manage">
                                <i class="ti ti-file-settings me-2 text-secondary"></i>{{ $LANG['invoice_preferences'] ?? 'Invoice Preferences' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'payment_types') active @endif" href="index.php?module=payment_types&view=manage">
                                <i class="ti ti-credit-card me-2 text-secondary"></i>{{ $LANG['payment_types'] ?? 'Payment Types' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item @if(($module ?? '') == 'options' && ($view ?? '') == 'index') active @endif" href="index.php?module=options&view=index">
                                <i class="ti ti-adjustments-horizontal me-2 text-secondary"></i>{{ $LANG['settings'] ?? 'Settings' }}
                            </a>
                            <a class="dropdown-item @if(($module ?? '') == 'options' && ($view ?? '') == 'backup_database') active @endif" href="index.php?module=options&view=backup_database">
                                <i class="ti ti-database-export me-2 text-secondary"></i>{{ $LANG['backup_database'] ?? 'Backup Database' }}
                            </a>
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
        $page_title_lang_keys = [
            'index_index' => 'title_view_index',
            'reports_index' => 'all_reports',
            'reports_report_invoice_profit' => 'profit_per_invoice',
            'invoices_manage' => 'manage_invoices',
            'invoices_itemised' => 'new_invoice_itemised',
            'invoices_total' => 'new_invoice_total',
            'invoices_consulting' => 'new_invoice_consulting',
            'invoices_details' => 'invoice',
            'invoices_add_invoice_item' => 'add_invoice_item',
            'invoices_manage_js' => 'manage_invoices',
            'cron_manage' => 'recurrence',
            'cron_add' => 'new_recurrence',
            'cron_edit' => 'recurrence',
            'payments_manage' => 'manage_payments',
            'payments_process' => 'process_payment',
            'payments_eway' => 'process_payment_via_eway',
            'customers_manage' => 'manage_customers',
            'customers_add' => 'customer_add',
            'customers_details' => 'customer_details',
            'billers_manage' => 'manage_billers',
            'billers_add' => 'add_new_biller',
            'billers_details' => 'biller_details',
            'products_manage' => 'manage_products',
            'products_add' => 'add_new_product',
            'products_details' => 'product_edit',
            'user_manage' => 'users',
            'user_details' => 'details',
            'inventory_manage' => 'inventory',
            'inventory_add' => 'new_inventory_movement',
            'inventory_edit' => 'inventory',
            'product_attribute_manage' => 'manage_product_attributes',
            'product_attribute_add' => 'add_product_attribute',
            'product_attribute_details' => 'product_attribute',
            'product_value_manage' => 'manage_product_values',
            'product_value_add' => 'add_product_value',
            'product_value_details' => 'product_value',
            'system_defaults_manage' => 'system_preferences',
            'system_defaults_edit' => 'system_preferences',
            'custom_fields_manage' => 'manage_custom_fields',
            'custom_fields_details' => 'custom_field',
            'tax_rates_manage' => 'manage_tax_rates',
            'tax_rates_add' => 'add_tax_rate',
            'tax_rates_details' => 'tax_rate_details',
            'preferences_manage' => 'manage_invoice_preferences',
            'preferences_add' => 'add_new_preference',
            'preferences_details' => 'invoice_preferences',
            'payment_types_manage' => 'manage_payment_types',
            'payment_types_add' => 'add_payment_type',
            'payment_types_details' => 'payment_type_details',
            'options_index' => 'options',
            'options_backup_database' => 'backup_database',
            'statement_index' => 'statement_of_invoices',
            'statement_email' => 'email_statement_as_pdf',
        ];
        $page_title_key = ($module ?? '') . '_' . ($view ?? '');
        $page_title = isset($page_title_lang_keys[$page_title_key]) && isset($LANG[$page_title_lang_keys[$page_title_key]])
            ? $LANG[$page_title_lang_keys[$page_title_key]]
            : ($tmp_lang_view . ' ' . $tmp_lang_module);
    @endphp
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">{{ $page_title }}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                    @if(($module ?? '') == 'index' && ($view ?? '') == 'index')
                        <a href="index.php?module=invoices&view=itemised" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                        </a>
                    @elseif(($module ?? '') == 'invoices' && ($view ?? '') == 'manage')
                        <a href="index.php?module=invoices&view=itemised" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                        </a>
                    @elseif(($module ?? '') == 'customers' && ($view ?? '') == 'manage')
                        <a href="index.php?module=customers&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['customer_add'] ?? 'Add Customer' }}
                        </a>
                    @elseif(($module ?? '') == 'billers' && ($view ?? '') == 'manage')
                        <a href="index.php?module=billers&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_biller'] ?? 'Add Biller' }}
                        </a>
                    @elseif(($module ?? '') == 'products' && ($view ?? '') == 'manage')
                        <a href="index.php?module=products&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_product'] ?? 'Add Product' }}
                        </a>
                    @elseif(($module ?? '') == 'payments' && ($view ?? '') == 'manage')
                        @if(get('id'))
                        <a href="index.php?module=payments&view=process&id={{ urlencode(get('id')) }}&op=pay_selected_invoice" class="btn btn-outline-success">
                            <i class="ti ti-cash me-1"></i>{{ $LANG['payments_filtered_invoice'] ?? 'Pay this invoice' }}
                        </a>
                        @endif
                        <a href="index.php?module=payments&view=process&op=pay_invoice" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['process_payment'] ?? 'Process Payment' }}
                        </a>
                    @elseif(($module ?? '') == 'cron' && ($view ?? '') == 'manage')
                        <a href="index.php?module=cron&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['new_recurrence'] ?? 'New Recurrence' }}
                        </a>
                    @elseif(($module ?? '') == 'inventory' && ($view ?? '') == 'manage')
                        <a href="index.php?module=inventory&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['new_inventory_movement'] ?? 'New Movement' }}
                        </a>
                    @elseif(($module ?? '') == 'product_attribute' && ($view ?? '') == 'manage')
                        <a href="index.php?module=product_attribute&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_product_attribute'] ?? 'Add Attribute' }}
                        </a>
                    @elseif(($module ?? '') == 'product_value' && ($view ?? '') == 'manage')
                        <a href="index.php?module=product_value&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_product_value'] ?? 'Add Value' }}
                        </a>
                    @elseif(($module ?? '') == 'tax_rates' && ($view ?? '') == 'manage')
                        <a href="index.php?module=tax_rates&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_tax_rate'] ?? 'Add Tax Rate' }}
                        </a>
                    @elseif(($module ?? '') == 'payment_types' && ($view ?? '') == 'manage')
                        <a href="index.php?module=payment_types&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_payment_type'] ?? 'Add Payment Type' }}
                        </a>
                    @elseif(($module ?? '') == 'preferences' && ($view ?? '') == 'manage')
                        <a href="index.php?module=preferences&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_preference'] ?? 'Add Preference' }}
                        </a>
                    @elseif(($module ?? '') == 'user' && ($view ?? '') == 'manage')
                        <a href="index.php?module=user&view=add" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>{{ $LANG['user_add'] ?? 'Add User' }}
                        </a>
                    @elseif(($module ?? '') == 'reports')
                        <a href="index.php?module=reports&view=index" class="btn btn-outline-secondary">
                            {{ $LANG['all_reports'] ?? 'All Reports' }}
                        </a>
                    @else
                        @php
                            $back_views = ['add', 'edit', 'details', 'view', 'quick_view', 'itemised', 'total', 'process', 'save'];
                            $show_back = in_array($view ?? '', $back_views);
                            if ($show_back) {
                                $back_config = [
                                    'invoices' => ['url' => 'index.php?module=invoices&view=manage', 'label' => $LANG['manage_invoices'] ?? 'Manage Invoices'],
                                    'customers' => ['url' => 'index.php?module=customers&view=manage', 'label' => $LANG['manage_customers'] ?? 'Manage Customers'],
                                    'billers' => ['url' => 'index.php?module=billers&view=manage', 'label' => $LANG['manage_billers'] ?? 'Manage Billers'],
                                    'products' => ['url' => 'index.php?module=products&view=manage', 'label' => $LANG['manage_products'] ?? 'Manage Products'],
                                    'payments' => ['url' => 'index.php?module=payments&view=manage', 'label' => $LANG['manage_payments'] ?? 'Manage Payments'],
                                    'cron' => ['url' => 'index.php?module=cron&view=manage', 'label' => $LANG['recurrence'] ?? 'Recurrence'],
                                    'inventory' => ['url' => 'index.php?module=inventory&view=manage', 'label' => $LANG['inventory'] ?? 'Inventory'],
                                    'product_attribute' => ['url' => 'index.php?module=product_attribute&view=manage', 'label' => $LANG['product_attributes'] ?? 'Product Attributes'],
                                    'product_value' => ['url' => 'index.php?module=product_value&view=manage', 'label' => $LANG['product_values'] ?? 'Product Values'],
                                    'tax_rates' => ['url' => 'index.php?module=tax_rates&view=manage', 'label' => $LANG['tax_rates'] ?? 'Tax Rates'],
                                    'payment_types' => ['url' => 'index.php?module=payment_types&view=manage', 'label' => $LANG['payment_types'] ?? 'Payment Types'],
                                    'custom_fields' => ['url' => 'index.php?module=custom_fields&view=manage', 'label' => $LANG['custom_fields_upper'] ?? 'Custom Fields'],
                                    'system_defaults' => ['url' => 'index.php?module=system_defaults&view=manage', 'label' => $LANG['system_preferences'] ?? 'System Preferences'],
                                    'preferences' => ['url' => 'index.php?module=preferences&view=manage', 'label' => $LANG['invoice_preferences'] ?? 'Invoice Preferences'],
                                    'user' => ['url' => 'index.php?module=user&view=manage', 'label' => $LANG['manage_accounts'] ?? 'Manage Users'],
                                ];
                                $back = $back_config[$module ?? ''] ?? null;
                            }
                        @endphp
                        @if(!empty($back))
                            <a href="{{ $back['url'] }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i>{{ $back['label'] }}
                            </a>
                        @endif
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
