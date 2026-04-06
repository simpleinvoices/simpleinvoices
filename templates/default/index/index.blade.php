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
@php
    // Determine active wizard step — auto-advance to first incomplete step,
    // or honour an explicit ?wizard_step=N param from a redirect.
    $wizardStep = isset($_GET['wizard_step']) ? max(1, min(4, (int)$_GET['wizard_step'])) : (
        !$hasBillers ? 1 : (!$hasCustomers ? 2 : (!$hasProducts ? 3 : 4))
    );
@endphp
<style>
    @media (max-width: 575.98px) {
        .wizard-tabs {
            flex-wrap: nowrap;
        }

        .wizard-tabs .nav-item {
            flex: 1 1 0;
            min-width: 0;
        }

        .wizard-tabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: .65rem .35rem;
            white-space: nowrap;
        }

        .wizard-tabs .nav-link .ti {
            margin-right: 0 !important;
        }
    }
</style>
<div class="card mb-3">
    <div class="card-status-top bg-primary"></div>

    {{-- Header --}}
    <div class="card-header">
        <h3 class="card-title"><i class="ti ti-rocket me-2"></i>{{ $LANG['getting_started'] ?? 'Getting Started' }}</h3>
    </div>

    {{-- Step progress indicator --}}
    <div class="card-body pb-0">
        <p class="text-secondary mb-3">{{ $LANG['first_run_intro'] ?? 'Welcome! Complete these steps to start invoicing:' }}</p>
        {{-- Step progress indicator --}}
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            @foreach([
                1 => ['label' => $LANG['wizard_your_details'] ?? 'Your Details', 'done' => $hasBillers],
                2 => ['label' => $LANG['customer'] ?? 'Customer',                'done' => $hasCustomers],
                3 => ['label' => $LANG['product'] ?? 'Product',                  'done' => $hasProducts],
                4 => ['label' => $LANG['invoice'] ?? 'Invoice',                  'done' => $hasInvoices],
            ] as $s => $sc)
            @php
                $sDone   = $sc['done'];
                $sActive = ($s == $wizardStep);
            @endphp
            <div class="d-flex align-items-center gap-2">
                <span class="avatar avatar-sm rounded-circle
                    @if($sDone) bg-success @elseif($sActive) bg-primary @else bg-secondary-lt @endif">
                    @if($sDone)
                        <i class="ti ti-check text-white" style="font-size:.85rem"></i>
                    @else
                        <span class="@if($sActive) text-white @else text-secondary @endif" style="font-size:.8rem;font-weight:600">{{ $s }}</span>
                    @endif
                </span>
                <span class="@if($sDone) text-success @elseif($sActive) text-primary fw-semibold @else text-secondary @endif
                             d-none d-sm-inline">
                    {{ $sc['label'] }}
                </span>
            </div>
            @if($s < 4)
                <div class="flex-grow-0 border-top @if($sDone) border-success @else border-secondary-subtle @endif"
                     style="width:30px;opacity:.5"></div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Wizard tab navigation --}}
    <div class="card-header" style="border-top:1px solid var(--tblr-border-color);">
        <ul class="nav nav-tabs card-header-tabs wizard-tabs" role="tablist">
            @foreach([
                1 => ['icon' => 'ti-building-store', 'label' => $LANG['wizard_your_details'] ?? 'Your Details',      'done' => $hasBillers],
                2 => ['icon' => 'ti-users',           'label' => $LANG['customer'] ?? 'Customer',                    'done' => $hasCustomers],
                3 => ['icon' => 'ti-package',         'label' => $LANG['product'] ?? 'Product',                      'done' => $hasProducts],
                4 => ['icon' => 'ti-file-invoice',    'label' => $LANG['wizard_create_invoice'] ?? 'Create Invoice',  'done' => $hasInvoices],
            ] as $step => $cfg)
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($wizardStep == $step) active @endif"
                   href="#wizard-step-{{ $step }}" data-bs-toggle="tab" role="tab">
                    @if($cfg['done'])
                        <i class="ti ti-check text-success me-1"></i>
                    @else
                        <i class="ti {{ $cfg['icon'] }} me-1"></i>
                    @endif
                    <span class="d-none d-sm-inline">{{ $cfg['label'] }}</span>
                    @if($cfg['done'])
                        <span class="badge bg-success-lt text-success ms-1 d-none d-sm-inline" style="font-size:.7rem">{{ $LANG['wizard_done_badge'] ?? 'Done' }}</span>
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Wizard tab content --}}
    <div class="card-body">
        <div class="tab-content">

            {{-- ═══════════════════════════════════════════════════════════
                 STEP 1 — Your Details (Biller)
                 A Biller is the entity that creates invoices — you or your business.
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-1" class="tab-pane @if($wizardStep == 1) active show @endif" role="tabpanel">
                @if($hasBillers)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-lg bg-success-lt rounded"><i class="ti ti-check fs-2 text-success"></i></span>
                        <div>
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_biller_ready'] ?? 'Your billing details are set up!' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_biller_ready_tagline'] ?? 'Your biller is ready. You can update your details any time.' }}</p>
                            <a href="index.php?module=billers&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_billers'] ?? 'Manage Billers' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="avatar avatar-md bg-primary-lt rounded-3 flex-shrink-0">
                                    <i class="ti ti-building-store text-primary"></i>
                                </span>
                                <div>
                                    <h4 class="mb-1">{{ $LANG['wizard_add_your_details'] ?? 'Add Your Details' }}</h4>
                                    <p class="text-secondary mb-0" style="font-size:.875rem">
                                        {{ $LANG['wizard_biller_description'] ?? 'A Biller represents you or your business — the entity that creates and sends invoices. Your name, address, and contact info will appear on every invoice you generate.' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? 'Just want to try it out first?' }}</p>
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    onclick="wizardFill('biller')">
                                <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_biller'] ?? 'Use sample biller data' }}
                            </button>
                            <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_prefills_form'] ?? 'Pre-fills the form — edit before saving.' }}</p>
                        </div>
                        {{-- Form --}}
                        <div class="col-md-8">
                            <form method="post" action="index.php?module=billers&amp;view=add">
                                <input type="hidden" name="op"          value="insert_biller">
                                <input type="hidden" name="from_wizard" value="1">
                                <input type="hidden" name="street_address2"        value="">
                                <input type="hidden" name="mobile_phone"           value="">
                                <input type="hidden" name="fax"                    value="">
                                <input type="hidden" name="logo"                   value="">
                                <input type="hidden" name="footer"                 value="">
                                <input type="hidden" name="paypal_business_name"   value="">
                                <input type="hidden" name="paypal_notify_url"      value="">
                                <input type="hidden" name="paypal_return_url"      value="">
                                <input type="hidden" name="eway_customer_id"       value="">
                                <input type="hidden" name="paymentsgateway_api_id" value="">
                                <input type="hidden" name="notes"                  value="">
                                <input type="hidden" name="custom_field1"          value="">
                                <input type="hidden" name="custom_field2"          value="">
                                <input type="hidden" name="custom_field3"          value="">
                                <input type="hidden" name="custom_field4"          value="">
                                <input type="hidden" name="enabled"                value="1">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">
                                            {{ $LANG['wizard_business_name'] ?? 'Business / Your Name' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. Acme Consulting" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? 'Email' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="you@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? 'Phone' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="+1 555 000 0000">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? 'Street Address' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="123 Main Street">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">{{ $LANG['city'] ?? 'City' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="City">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $LANG['wizard_state_region'] ?? 'State / Region' }}</label>
                                        <input type="text" name="state" class="form-control" placeholder="State">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ $LANG['postcode'] ?? 'Postcode' }}</label>
                                        <input type="text" name="zip_code" class="form-control" placeholder="ZIP / Postcode">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['country'] ?? 'Country' }}</label>
                                        <input type="text" name="country" class="form-control" placeholder="Country">
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <a href="index.php?module=billers&amp;view=add" class="text-secondary small">
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_biller_form'] ?? 'Full biller form (more options)' }}
                                    </a>
                                    <button type="submit" name="submit" value="save" class="btn btn-primary">
                                        {{ $LANG['wizard_save_details'] ?? 'Save Your Details' }} <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 STEP 2 — Add a Customer
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-2" class="tab-pane @if($wizardStep == 2) active show @endif" role="tabpanel">
                @if($hasCustomers)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-lg bg-success-lt rounded"><i class="ti ti-check fs-2 text-success"></i></span>
                        <div>
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_customer_ready'] ?? 'At least one customer is set up!' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_customer_ready_tagline'] ?? 'You can add more customers any time.' }}</p>
                            <a href="index.php?module=customers&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_customers'] ?? 'Manage Customers' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="avatar avatar-md bg-blue-lt rounded-3 flex-shrink-0">
                                    <i class="ti ti-users text-blue"></i>
                                </span>
                                <div>
                                    <h4 class="mb-1">{{ $LANG['wizard_add_a_customer'] ?? 'Add a Customer' }}</h4>
                                    <p class="text-secondary mb-0" style="font-size:.875rem">
                                        {{ $LANG['wizard_customer_description'] ?? 'Customers are the people or businesses you invoice. Add at least one so you can assign them to invoices. You can add as many as you need.' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? 'Just want to try it out first?' }}</p>
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    onclick="wizardFill('customer')">
                                <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_customer'] ?? 'Use sample customer data' }}
                            </button>
                            <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_sample_replace_later'] ?? 'You can edit or replace sample data later.' }}</p>
                        </div>
                        {{-- Form --}}
                        <div class="col-md-8">
                            <form method="post" action="index.php?module=customers&amp;view=add">
                                <input type="hidden" name="op"          value="insert_customer">
                                <input type="hidden" name="from_wizard" value="1">
                                {{-- Hidden fields not shown in wizard --}}
                                <input type="hidden" name="street_address2" value="">
                                <input type="hidden" name="state"           value="">
                                <input type="hidden" name="zip_code"        value="">
                                <input type="hidden" name="country"         value="">
                                <input type="hidden" name="mobile_phone"    value="">
                                <input type="hidden" name="fax"             value="">
                                <input type="hidden" name="notes"           value="">
                                <input type="hidden" name="custom_field1"   value="">
                                <input type="hidden" name="custom_field2"   value="">
                                <input type="hidden" name="custom_field3"   value="">
                                <input type="hidden" name="custom_field4"   value="">
                                <input type="hidden" name="enabled"         value="1">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">
                                            {{ $LANG['customer_name'] ?? 'Customer Name' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. Acme Corp" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['contact_person'] ?? 'Contact Person' }}</label>
                                        <input type="text" name="attention" class="form-control" placeholder="e.g. Jane Smith">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['customer_department'] ?? 'Department' }}</label>
                                        <input type="text" name="department" class="form-control" placeholder="e.g. Accounts">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? 'Email' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="accounts@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? 'Phone' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="+1 555 000 0000">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? 'Street Address' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="123 Business Rd">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">{{ $LANG['city'] ?? 'City' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="City">
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <a href="index.php?module=customers&amp;view=add" class="text-secondary small">
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_customer_form'] ?? 'Full customer form (more options)' }}
                                    </a>
                                    <button type="submit" name="submit" value="save" class="btn btn-primary">
                                        {{ $LANG['save_customer'] ?? 'Save Customer' }} <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 STEP 3 — Add a Product / Service
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-3" class="tab-pane @if($wizardStep == 3) active show @endif" role="tabpanel">
                @if($hasProducts)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-lg bg-success-lt rounded"><i class="ti ti-check fs-2 text-success"></i></span>
                        <div>
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_product_ready'] ?? 'At least one product / service is set up!' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_product_ready_tagline'] ?? 'You can add more products or services any time.' }}</p>
                            <a href="index.php?module=products&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_products'] ?? 'Manage Products' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <span class="avatar avatar-md bg-orange-lt rounded-3 flex-shrink-0">
                                    <i class="ti ti-package text-orange"></i>
                                </span>
                                <div>
                                    <h4 class="mb-1">{{ $LANG['wizard_add_a_product'] ?? 'Add a Product or Service' }}</h4>
                                    <p class="text-secondary mb-0" style="font-size:.875rem">
                                        {{ $LANG['wizard_product_description'] ?? 'Products (or services) are what you invoice for — an hourly rate, a fixed fee, or a physical item. Add at least one so it can appear on your invoices.' }}
                                    </p>
                                </div>
                            </div>
                            <hr class="my-3">
                            <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? 'Just want to try it out first?' }}</p>
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                    onclick="wizardFill('product')">
                                <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_product'] ?? 'Use sample product data' }}
                            </button>
                            <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_sample_replace_later'] ?? 'You can edit or replace sample data later.' }}</p>
                        </div>
                        {{-- Form --}}
                        <div class="col-md-8">
                            <form method="post" action="index.php?module=products&amp;view=add">
                                <input type="hidden" name="op"          value="insert_product">
                                <input type="hidden" name="from_wizard" value="1">
                                {{-- cost/reorder_level omitted — insertProduct() treats missing keys as NULL (numeric cols) --}}
                                <input type="hidden" name="default_tax_id"       value="">
                                <input type="hidden" name="custom_field1"        value="">
                                <input type="hidden" name="custom_field2"        value="">
                                <input type="hidden" name="custom_field3"        value="">
                                <input type="hidden" name="custom_field4"        value="">
                                <input type="hidden" name="notes"                value="">
                                <input type="hidden" name="notes_as_description" value="">
                                <input type="hidden" name="show_description"     value="">
                                <input type="hidden" name="enabled"              value="1">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">
                                            {{ $LANG['description'] ?? 'Description' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="description" class="form-control"
                                               placeholder="e.g. Hourly consulting rate" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['unit_price'] ?? 'Unit Price' }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $preference['pref_currency_sign'] ?? '$' }}</span>
                                            <input type="text" name="unit_price" class="form-control" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['default_tax'] ?? 'Default Tax' }}</label>
                                        <select name="default_tax_id" class="form-select">
                                            <option value="">— none —</option>
                                            @foreach(($taxes ?? []) as $tax)
                                                <option value="{{ $tax['tax_id'] ?? '' }}">
                                                    {{ $tax['tax_description'] ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <a href="index.php?module=products&amp;view=add" class="text-secondary small">
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_product_form'] ?? 'Full product form (more options)' }}
                                    </a>
                                    <button type="submit" name="id" value="save" class="btn btn-primary">
                                        {{ $LANG['save_product'] ?? 'Save Product' }} <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 STEP 4 — Create Your First Invoice
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-4" class="tab-pane @if($wizardStep == 4) active show @endif" role="tabpanel">
                @if($hasInvoices)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-lg bg-success-lt rounded"><i class="ti ti-check fs-2 text-success"></i></span>
                        <div>
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_all_set'] ?? "You're all set up and invoicing!" }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_all_set_tagline'] ?? 'Head to the invoice list to see your invoices, or create another one.' }}</p>
                            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-sm btn-outline-success me-2">
                                <i class="ti ti-list me-1"></i>{{ $LANG['wizard_all_invoices'] ?? 'All Invoices' }}
                            </a>
                            <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? 'New Invoice' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4 align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start gap-3">
                                <span class="avatar avatar-md bg-green-lt rounded-3 flex-shrink-0">
                                    <i class="ti ti-file-invoice text-green"></i>
                                </span>
                                <div>
                                    <h4 class="mb-1">{{ $LANG['wizard_create_first_invoice'] ?? 'Create Your First Invoice' }}</h4>
                                    <p class="text-secondary mb-0" style="font-size:.875rem">
                                        {{ $LANG['wizard_invoice_description'] ?? 'You have your billing details, a customer, and a product ready. Pick a biller, a customer, add your line items, and send it off.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(!$hasBillers || !$hasCustomers || !$hasProducts)
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-circle me-2"></i>
                                    {{ $LANG['wizard_prereqs_intro'] ?? 'Before creating an invoice you need to complete the earlier steps:' }}
                                    <ul class="mb-0 mt-1">
                                        @if(!$hasBillers)<li>{{ $LANG['wizard_prereqs_biller'] ?? 'Add your billing details (Step 1)' }}</li>@endif
                                        @if(!$hasCustomers)<li>{{ $LANG['wizard_prereqs_customer'] ?? 'Add a customer (Step 2)' }}</li>@endif
                                        @if(!$hasProducts)<li>{{ $LANG['wizard_prereqs_product'] ?? 'Add a product or service (Step 3)' }}</li>@endif
                                    </ul>
                                </div>
                            @else
                                <div class="card card-md border-2 border-primary shadow-sm text-center">
                                    <div class="card-body py-5">
                                        <div class="mb-3">
                                            <span class="avatar avatar-xl bg-primary-lt rounded-3">
                                                <i class="ti ti-file-invoice fs-1 text-primary"></i>
                                            </span>
                                        </div>
                                        <h3 class="mb-2">{{ $LANG['wizard_ready_to_go'] ?? 'Ready to go!' }}</h3>
                                        <p class="text-secondary mb-4">
                                            {{ $LANG['wizard_ready_click_below'] ?? 'Click the button below to open the new invoice form.' }}<br>
                                            {{ $LANG['wizard_ready_tagline'] ?? 'Your biller, customer, and products are all set.' }}
                                        </p>
					<a href="index.php?module=invoices&amp;view=itemised" class="btn btn-primary btn-lg">
                                            <i class="ti ti-plus me-2"></i>{{ $LANG['wizard_create_first_invoice'] ?? 'Create Your First Invoice' }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>{{-- /.tab-content --}}
    </div>{{-- /.card-body --}}
</div>

{{-- Wizard sample-data prefill --}}
<script>
(function () {
    var samples = {
        biller: @json($wizard_sample_biller ?? []),
        customer: @json($wizard_sample_customer ?? []),
        product: @json($wizard_sample_product ?? [])
    };

    function fill(form, field, value) {
        var el = form.querySelector('[name="' + field + '"]');
        if (!el || el.type === 'hidden') return;
        if (el.tagName === 'SELECT') {
            // try to select by value; fall back silently
            for (var i = 0; i < el.options.length; i++) {
                if (el.options[i].value == value) { el.selectedIndex = i; break; }
            }
        } else {
            el.value = value || '';
        }
    }

    window.wizardFill = function (type) {
        var s   = samples[type] || {};
        var paneId = type === 'biller' ? 'wizard-step-1'
                   : type === 'customer' ? 'wizard-step-2'
                   : 'wizard-step-3';
        var form = document.querySelector('#' + paneId + ' form');
        if (!form) return;

        if (type === 'biller') {
            fill(form, 'name',           s.name);
            fill(form, 'email',          s.email);
            fill(form, 'phone',          s.phone);
            fill(form, 'street_address', s.street_address);
            fill(form, 'city',           s.city);
            fill(form, 'state',          s.state);
            fill(form, 'zip_code',       s.zip_code);
            fill(form, 'country',        s.country);
        } else if (type === 'customer') {
            fill(form, 'name',           s.name);
            fill(form, 'attention',      s.attention);
            fill(form, 'department',     s.department);
            fill(form, 'email',          s.email);
            fill(form, 'phone',          s.phone);
            fill(form, 'street_address', s.street_address);
            fill(form, 'city',           s.city);
        } else if (type === 'product') {
            fill(form, 'description',    s.description);
            fill(form, 'unit_price',     s.unit_price);
            fill(form, 'default_tax_id', s.default_tax_id);
        }

        // Highlight filled fields briefly so user notices the prefill
        form.querySelectorAll('input:not([type=hidden]), select').forEach(function (el) {
            el.classList.add('is-valid');
            setTimeout(function () { el.classList.remove('is-valid'); }, 2000);
        });
    };
})();
</script>
@endif

<div class="accordion mb-3" id="dashboard-accordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="dashboard-heading-activity">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse-activity" aria-expanded="true" aria-controls="dashboard-collapse-activity">
                <span>
                    <span class="fw-semibold">{{ $LANG['invoices'] ?? 'Invoices' }} &amp; {{ $LANG['payments'] ?? 'Payments' }}</span>
                    <span class="text-secondary d-block small">{{ $LANG['monthly_activity'] ?? 'Monthly activity' }}</span>
                </span>
            </button>
        </h2>
        <div id="dashboard-collapse-activity" class="accordion-collapse collapse show" aria-labelledby="dashboard-heading-activity" data-bs-parent="#dashboard-accordion">
            <div class="accordion-body">
                <div class="d-flex justify-content-end mb-3">
                    <div class="segmented-control" id="chart-year-selector">
                        @foreach(($chart_years ?? []) as $y)
                        <label class="segmented-control-item">
                            <input type="radio" class="segmented-control-input" name="chart_year" value="{{ $y }}"{{ $y === ($chart_current_year ?? 0) ? ' checked' : '' }}>
                            <span class="segmented-control-label">{{ $y }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div id="chart-dashboard" style="min-height:288px"></div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="dashboard-heading-invoices">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse-invoices" aria-expanded="false" aria-controls="dashboard-collapse-invoices">
                <span class="fw-semibold">{{ $LANG['recent_invoices'] ?? 'Recent Invoices' }}</span>
            </button>
        </h2>
        <div id="dashboard-collapse-invoices" class="accordion-collapse collapse" aria-labelledby="dashboard-heading-invoices" data-bs-parent="#dashboard-accordion">
            <div class="accordion-body p-0">
                <div class="d-flex flex-wrap gap-2 justify-content-end p-3 border-bottom">
                    <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                        {{ $LANG['manage_invoices'] ?? 'View all' }}
                    </a>
                    <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_invoice'] ?? 'Add Invoice' }}
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover card-table mb-0">
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
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="dashboard-heading-payments">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse-payments" aria-expanded="false" aria-controls="dashboard-collapse-payments">
                <span class="fw-semibold">{{ $LANG['recent_payments'] ?? 'Recent Payments' }}</span>
            </button>
        </h2>
        <div id="dashboard-collapse-payments" class="accordion-collapse collapse" aria-labelledby="dashboard-heading-payments" data-bs-parent="#dashboard-accordion">
            <div class="accordion-body p-0">
                <div class="d-flex flex-wrap gap-2 justify-content-end p-3 border-bottom">
                    <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                        {{ $LANG['manage_payments'] ?? 'View all' }}
                    </a>
                    <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_payment'] ?? 'Add Payment' }}
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover card-table mb-0">
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
        </div>
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

    document.querySelectorAll('#dashboard-accordion .accordion-collapse').forEach(function (el) {
        el.addEventListener('shown.bs.collapse', function () {
            if (this.id === 'dashboard-collapse-activity') {
                var active = document.querySelector('#chart-year-selector input[name="chart_year"]:checked');
                setTimeout(function () {
                    chart.updateOptions(buildOptions(active ? active.value : {{ $chart_current_year ?? 'null' }}), true, true);
                }, 50);
            }
        });
    });
})();
</script>
