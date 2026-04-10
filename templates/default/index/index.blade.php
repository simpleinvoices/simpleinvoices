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
    .page-header .container-xl {
        display: none;
    }

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
        <h3 class="card-title"><i class="ti ti-rocket me-2"></i>{{ $LANG['getting_started'] ?? '' }}</h3>
    </div>

    {{-- Step progress indicator --}}
    <div class="card-body pb-0">
        <p class="text-secondary mb-3">{{ $LANG['first_run_intro'] ?? '' }}</p>
        {{-- Step progress indicator --}}
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            @foreach([
                1 => ['label' => $LANG['wizard_your_details'] ?? '', 'done' => $hasBillers],
                2 => ['label' => $LANG['customer'] ?? '',                'done' => $hasCustomers],
                3 => ['label' => $LANG['product'] ?? '',                  'done' => $hasProducts],
                4 => ['label' => $LANG['invoice'] ?? '',                  'done' => $hasInvoices],
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
                1 => ['icon' => 'ti-building-store', 'label' => $LANG['wizard_your_details'] ?? '',      'done' => $hasBillers],
                2 => ['icon' => 'ti-users',           'label' => $LANG['customer'] ?? '',                    'done' => $hasCustomers],
                3 => ['icon' => 'ti-package',         'label' => $LANG['product'] ?? '',                      'done' => $hasProducts],
                4 => ['icon' => 'ti-file-invoice',    'label' => $LANG['wizard_create_invoice'] ?? '',  'done' => $hasInvoices],
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
                        <span class="badge bg-success-lt text-success ms-1 d-none d-sm-inline" style="font-size:.7rem">{{ $LANG['wizard_done_badge'] ?? '' }}</span>
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
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_biller_ready'] ?? '' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_biller_ready_tagline'] ?? '' }}</p>
                            <a href="index.php?module=billers&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_billers'] ?? '' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="ribbon ribbon-top bg-yellow">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="avatar avatar-md bg-primary-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-building-store text-primary"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['wizard_add_your_details'] ?? '' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_biller_description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? '' }}</p>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                            onclick="wizardFill('biller')">
                                        <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_biller'] ?? '' }}
                                    </button>
                                    <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_prefills_form'] ?? '' }}</p>
                                </div>
                            </div>
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
                                            {{ $LANG['wizard_business_name'] ?? '' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="name" class="form-control" placeholder="{{ $LANG['placeholder_biller_name_example'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? '' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="{{ $LANG['placeholder_email_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="{{ $LANG['placeholder_phone_example'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? '' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="{{ $LANG['placeholder_street_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">{{ $LANG['city'] ?? '' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="{{ $LANG['placeholder_city_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $LANG['wizard_state_region'] ?? '' }}</label>
                                        <input type="text" name="state" class="form-control" placeholder="{{ $LANG['placeholder_state_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ $LANG['postcode'] ?? '' }}</label>
                                        <input type="text" name="zip_code" class="form-control" placeholder="{{ $LANG['placeholder_zip_example'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['country'] ?? '' }}</label>
                                        <input type="text" name="country" class="form-control" placeholder="{{ $LANG['placeholder_country_example'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <a href="index.php?module=billers&amp;view=add" class="text-secondary small">
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_biller_form'] ?? '' }}
                                    </a>
                                    <button type="submit" name="submit" value="save" class="btn btn-primary">
                                        {{ $LANG['wizard_save_details'] ?? '' }} <i class="ti ti-arrow-right ms-1"></i>
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
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_customer_ready'] ?? '' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_customer_ready_tagline'] ?? '' }}</p>
                            <a href="index.php?module=customers&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_customers'] ?? '' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="ribbon ribbon-top bg-yellow">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="avatar avatar-md bg-blue-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-users text-blue"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['wizard_add_a_customer'] ?? '' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_customer_description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? '' }}</p>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                            onclick="wizardFill('customer')">
                                        <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_customer'] ?? '' }}
                                    </button>
                                    <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_sample_replace_later'] ?? '' }}</p>
                                </div>
                            </div>
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
                                            {{ $LANG['customer_name'] ?? '' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="name" class="form-control" placeholder="{{ $LANG['placeholder_customer_name_example'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['contact_person'] ?? '' }}</label>
                                        <input type="text" name="attention" class="form-control" placeholder="{{ $LANG['placeholder_contact_person_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['customer_department'] ?? '' }}</label>
                                        <input type="text" name="department" class="form-control" placeholder="{{ $LANG['placeholder_department_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? '' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="{{ $LANG['placeholder_customer_email_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="{{ $LANG['placeholder_phone_example'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? '' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="{{ $LANG['placeholder_street_business_example'] ?? '' }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">{{ $LANG['city'] ?? '' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="{{ $LANG['placeholder_city_example'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between align-items-center">
                                    <a href="index.php?module=customers&amp;view=add" class="text-secondary small">
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_customer_form'] ?? '' }}
                                    </a>
                                    <button type="submit" name="submit" value="save" class="btn btn-primary">
                                        {{ $LANG['save_customer'] ?? '' }} <i class="ti ti-arrow-right ms-1"></i>
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
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_product_ready'] ?? '' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_product_ready_tagline'] ?? '' }}</p>
                            <a href="index.php?module=products&amp;view=manage" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['manage_products'] ?? '' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        {{-- Description --}}
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="ribbon ribbon-top bg-yellow">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="avatar avatar-md bg-orange-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-package text-orange"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['wizard_add_a_product'] ?? '' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_product_description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <p class="text-secondary small mb-2">{{ $LANG['wizard_try_sample'] ?? '' }}</p>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                            onclick="wizardFill('product')">
                                        <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_product'] ?? '' }}
                                    </button>
                                    <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_sample_replace_later'] ?? '' }}</p>
                                </div>
                            </div>
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
                                            {{ $LANG['description'] ?? '' }} <i class="ti ti-asterisk text-danger" style="font-size:.7rem"></i>
                                        </label>
                                        <input type="text" name="description" class="form-control"
                                               placeholder="{{ $LANG['placeholder_product_description_example'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['unit_price'] ?? '' }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $preference['pref_currency_sign'] ?? '$' }}</span>
                                            <input type="text" name="unit_price" class="form-control" placeholder="{{ $LANG['placeholder_unit_price_example'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['default_tax'] ?? '' }}</label>
                                        <select name="default_tax_id" class="form-select">
                                            <option value="">{{ $LANG['tax_rate_none'] ?? '' }}</option>
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
                                        <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_full_product_form'] ?? '' }}
                                    </a>
                                    <button type="submit" name="id" value="save" class="btn btn-primary">
                                        {{ $LANG['save_product'] ?? '' }} <i class="ti ti-arrow-right ms-1"></i>
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
                            <p class="text-secondary mb-2">{{ $LANG['wizard_all_set_tagline'] ?? '' }}</p>
                            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-sm btn-outline-success me-2">
                                <i class="ti ti-list me-1"></i>{{ $LANG['wizard_all_invoices'] ?? '' }}
                            </a>
                            <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus me-1"></i>{{ $LANG['new_invoice'] ?? '' }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-4 align-items-center">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="ribbon ribbon-top bg-yellow">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="avatar avatar-md bg-green-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-file-invoice text-green"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['wizard_create_first_invoice'] ?? '' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_invoice_description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if(!$hasBillers || !$hasCustomers || !$hasProducts)
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-circle me-2"></i>
                                    {{ $LANG['wizard_prereqs_intro'] ?? '' }}
                                    <ul class="mb-0 mt-1">
                                        @if(!$hasBillers)<li>{{ $LANG['wizard_prereqs_biller'] ?? '' }}</li>@endif
                                        @if(!$hasCustomers)<li>{{ $LANG['wizard_prereqs_customer'] ?? '' }}</li>@endif
                                        @if(!$hasProducts)<li>{{ $LANG['wizard_prereqs_product'] ?? '' }}</li>@endif
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
                                        <h3 class="mb-2">{{ $LANG['wizard_ready_to_go'] ?? '' }}</h3>
                                        <p class="text-secondary mb-4">
                                            {{ $LANG['wizard_ready_click_below'] ?? '' }}<br>
                                            {{ $LANG['wizard_ready_tagline'] ?? '' }}
                                        </p>
					<a href="index.php?module=invoices&amp;view=itemised" class="btn btn-primary btn-lg">
                                            <i class="ti ti-plus me-2"></i>{{ $LANG['wizard_create_first_invoice'] ?? '' }}
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

@if(!$firstRun)
{{-- Charts row: monthly activity + annual totals + aging radial --}}
<div class="row g-3 mb-3">
    {{-- Monthly activity chart --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div>
                    <h3 class="card-title">{{ $LANG['invoices'] ?? '' }} &amp; {{ $LANG['payments'] ?? '' }}</h3>
                    <div class="card-subtitle">{{ $LANG['monthly_activity'] ?? '' }}</div>
                </div>
                <div class="card-options ms-auto">
                    @if(count($chart_years ?? []) > 5)
                    <div id="chart-year-selector">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="chart-year-btn">
                                {{ $chart_current_year ?? '' }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @foreach(array_reverse($chart_years ?? []) as $y)
                                <a class="dropdown-item{{ $y === ($chart_current_year ?? 0) ? ' active' : '' }}"
                                   href="#" data-year="{{ $y }}">{{ $y }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="segmented-control" id="chart-year-selector">
                        @foreach(($chart_years ?? []) as $y)
                        <label class="segmented-control-item">
                            <input type="radio" class="segmented-control-input" name="chart_year" value="{{ $y }}"{{ $y === ($chart_current_year ?? 0) ? ' checked' : '' }}>
                            <span class="segmented-control-label">{{ $y }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div id="chart-dashboard" style="min-height:288px"></div>
            </div>
        </div>
    </div>
    {{-- Annual totals bar chart --}}
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header">
                <div>
                    <h3 class="card-title">{{ $LANG['invoices'] ?? '' }} &amp; {{ $LANG['payments'] ?? '' }}</h3>
                    <div class="card-subtitle">Annual totals</div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart-annual" style="min-height:288px"></div>
            </div>
        </div>
    </div>
    {{-- Debtor aging radial chart --}}
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Debtor Aging</h3>
                    <div class="card-subtitle">Outstanding by age &mdash; total {{ siLocal::number($aging_total ?? 0) }}</div>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div id="chart-aging" style="min-height:288px;width:100%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Dashboard stat cards --}}
<div class="row g-3 mb-3">

    {{-- Card 1: % Invoices Paid --}}
    <div class="col-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoices Paid</span>
                    <a href="index.php?module=reports&amp;view=report_debtors_by_aging" target="_blank" rel="noopener" class="ms-auto text-secondary lh-1" title="Open report">
                        <i class="ti ti-external-link" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h1 mb-1">{{ $dash_paid_pct ?? 0 }}%</div>
                <div class="text-secondary small mb-3">
                    {{ $dash_paid_inv_count ?? 0 }} of {{ $dash_total_inv_count ?? 0 }} invoices fully paid
                </div>
                <div class="progress" style="height:6px">
                    <div class="progress-bar bg-primary" role="progressbar"
                         style="width:{{ $dash_paid_pct ?? 0 }}%"
                         aria-valuenow="{{ $dash_paid_pct ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Invoice Revenue sparkline --}}
    <div class="col-6 col-lg-3">
        <div class="card h-100 overflow-hidden">
            <div class="card-body pb-1">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoice Revenue</span>
                    <a href="index.php?module=reports&amp;view=report_sales_by_periods" target="_blank" rel="noopener" class="ms-auto text-secondary lh-1" title="Open report">
                        <i class="ti ti-external-link" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h1 mb-1">{{ siLocal::number($dash_alltime_inv_total ?? 0) }}</div>
                <div class="text-secondary small mb-2">All time</div>
            </div>
            <div id="sparkline-invoices" style="height:70px"></div>
        </div>
    </div>

    {{-- Card 3: Payment Revenue sparkline --}}
    <div class="col-6 col-lg-3">
        <div class="card h-100 overflow-hidden">
            <div class="card-body pb-1">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Payment Revenue</span>
                    <a href="index.php?module=reports&amp;view=report_sales_by_periods" target="_blank" rel="noopener" class="ms-auto text-secondary lh-1" title="Open report">
                        <i class="ti ti-external-link" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h1 mb-1">{{ siLocal::number($dash_alltime_pmt_total ?? 0) }}</div>
                <div class="text-secondary small mb-2">All time</div>
            </div>
            <div id="sparkline-payments" style="height:70px"></div>
        </div>
    </div>

    {{-- Card 4: Invoice & Payment Volume (count) --}}
    <div class="col-6 col-lg-3">
        <div class="card h-100 overflow-hidden">
            <div class="card-body pb-1">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoice &amp; Payment Volume</span>
                    <a href="index.php?module=reports&amp;view=report_sales_by_periods" target="_blank" rel="noopener" class="ms-auto text-secondary lh-1" title="Open report">
                        <i class="ti ti-external-link" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h1 mb-0">
                    {{ number_format($dash_total_inv_volume ?? 0) }}
                    <span class="fs-4 fw-normal text-secondary ms-1">inv</span>
                </div>
                <div class="text-secondary small mb-2">
                    {{ number_format($dash_total_pmt_volume ?? 0) }} payments &nbsp;&middot;&nbsp; All time
                </div>
            </div>
            <div id="sparkline-volume" style="height:70px"></div>
        </div>
    </div>

</div>

{{-- Recent invoices --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_invoices'] ?? '' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                {{ $LANG['view_all'] ?? '' }}
            </a>
            <a href="index.php?module=invoices&amp;view=itemised" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_invoice'] ?? '' }}
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
            <thead>
                <tr>
                    <th>{{ $LANG['id'] ?? '' }}</th>
                    <th>{{ $LANG['customer'] ?? '' }}</th>
                    <th>{{ $LANG['biller'] ?? '' }}</th>
                    <th>{{ $LANG['date_upper'] ?? '' }}</th>
                    <th class="text-end">{{ $LANG['total'] ?? '' }}</th>
                    <th>{{ $LANG['status'] ?? '' }}</th>
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
                            <span class="status status-secondary"><span class="status-dot"></span>{{ $LANG['draft'] ?? '' }}</span>
                        @elseif($isPaid)
                            <span class="status status-green">{{ $LANG['paid'] ?? '' }}</span>
                        @else
                            <span class="status status-orange">{{ $LANG['due'] ?? '' }}</span>
                        @endif
                    </td>
                    <td class="w-1">
                        <div class="btn-group">
                            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($inv['id']) }}" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['quick_view'] ?? '' }}">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="index.php?module=export&amp;view=invoice&amp;id={{ urlencode($inv['id']) }}&amp;format=pdf" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['export_pdf'] ?? '' }}" target="_blank" rel="noopener">
                                <i class="ti ti-file-type-pdf"></i>
                            </a>
                            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['manage_invoices'] ?? '' }}">
                                <i class="ti ti-list"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">{{ $LANG['no_invoices_yet'] ?? '' }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Recent payments --}}
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_payments'] ?? '' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                {{ $LANG['view_all'] ?? '' }}
            </a>
            <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>{{ $LANG['add_new_payment'] ?? '' }}
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter table-hover card-table">
            <thead>
                <tr>
                    <th>{{ $LANG['invoice'] ?? '' }}</th>
                    <th>{{ $LANG['customer'] ?? '' }}</th>
                    <th>{{ $LANG['biller'] ?? '' }}</th>
                    <th>{{ $LANG['date_upper'] ?? '' }}</th>
                    <th class="text-end">{{ $LANG['amount'] ?? '' }}</th>
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
                            <a href="index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($pmt['ac_inv_id']) }}" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['quick_view'] ?? '' }}">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm" title="{{ $LANG['manage_payments'] ?? '' }}">
                                <i class="ti ti-list"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary">{{ $LANG['no_payments_yet'] ?? '' }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script>
(function () {
    var labels       = @json($chart_labels ?? []);
    var datasets     = @json($chart_data ?? []);
    var invoiceLabel = @json($LANG['invoices'] ?? '');
    var paymentLabel = @json($LANG['payments'] ?? '');
    var annualTotals = @json($annual_totals ?? []);
    var annualYears  = @json($chart_years ?? []);

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

    // Year selector — works for both segmented control and dropdown
    var currentYear = {{ $chart_current_year ?? 'null' }};

    document.querySelectorAll('#chart-year-selector input[name="chart_year"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            currentYear = this.value;
            chart.updateOptions(buildOptions(currentYear), true, true);
        });
    });

    document.querySelectorAll('#chart-year-selector .dropdown-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            currentYear = this.dataset.year;
            chart.updateOptions(buildOptions(currentYear), true, true);
            // Update button label and active state
            var btn = document.getElementById('chart-year-btn');
            if (btn) btn.textContent = currentYear;
            document.querySelectorAll('#chart-year-selector .dropdown-item').forEach(function (el) {
                el.classList.toggle('active', el === item);
            });
        });
    });

    document.documentElement.addEventListener('si-theme-changed', function () {
        chart.updateOptions(buildOptions(currentYear), true, true);
        annualChart.updateOptions(buildAnnualOptions(), true, true);
        agingChart.updateOptions(buildAgingOptions(), true, true);
    });

    // Annual bar chart
    function buildAnnualOptions() {
        var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var primary   = cssVar('--tblr-primary')      || '#45aaf2';
        var success   = cssVar('--tblr-success')      || '#2fb344';
        var bodyColor = cssVar('--tblr-body-color')   || '#1d273b';
        var borderCol = cssVar('--tblr-border-color') || '#e6e7e9';

        var invData = annualYears.map(function (y) { return (annualTotals[y] || {}).invoices || 0; });
        var pmtData = annualYears.map(function (y) { return (annualTotals[y] || {}).payments || 0; });

        return {
            chart: {
                type: 'bar',
                fontFamily: 'inherit',
                height: 288,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent'
            },
            series: [
                { name: invoiceLabel, data: invData },
                { name: paymentLabel, data: pmtData }
            ],
            xaxis: {
                categories: annualYears,
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
            plotOptions: {
                bar: { columnWidth: '60%', borderRadius: 3 }
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

    var annualChart = new ApexCharts(document.getElementById('chart-annual'), buildAnnualOptions());
    annualChart.render();

    // Debtor aging radial chart
    var agingData = @json($aging_chart ?? []);

    function buildAgingOptions() {
        var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var bodyColor = cssVar('--tblr-body-color')   || '#1d273b';
        var borderCol = cssVar('--tblr-border-color') || '#e6e7e9';

        var series  = agingData.map(function (b) { return b.percent; });
        var labels  = agingData.map(function (b) { return b.label; });
        var amounts = agingData.map(function (b) { return b.amount; });

        return {
            chart: {
                type: 'radialBar',
                fontFamily: 'inherit',
                height: 288,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent'
            },
            series: series,
            labels: labels,
            colors: ['#2fb344', '#f59f00', '#f76707', '#e67700', '#e03131'],
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: 0,
                    endAngle: 270,
                    hollow: { margin: 5, size: '30%' },
                    track: { background: isDark ? '#2d3748' : '#f0f0f0' },
                    dataLabels: {
                        name: { fontSize: '11px', color: bodyColor },
                        value: {
                            fontSize: '12px',
                            color: bodyColor,
                            formatter: function (val, opts) {
                                var idx = opts && opts.seriesIndex != null ? opts.seriesIndex : 0;
                                return amounts[idx] !== undefined ? amounts[idx].toLocaleString() : val + '%';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: bodyColor,
                            formatter: function () {
                                var t = agingData.reduce(function (s, b) { return s + b.amount; }, 0);
                                return t.toLocaleString();
                            }
                        }
                    }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                labels: { colors: bodyColor },
                fontSize: '11px'
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val, opts) {
                        var idx = opts && opts.seriesIndex != null ? opts.seriesIndex : 0;
                        return amounts[idx] !== undefined ? amounts[idx].toLocaleString() : val + '%';
                    }
                }
            }
        };
    }

    var agingChart = new ApexCharts(document.getElementById('chart-aging'), buildAgingOptions());
    agingChart.render();

    // ── Stat card sparklines ─────────────────────────────────────────────────
    var sparkInvAmounts  = @json($alltime_inv_monthly ?? []);
    var sparkPmtAmounts  = @json($alltime_pmt_monthly ?? []);
    var sparkInvCounts   = @json($alltime_inv_counts  ?? []);
    var sparkPmtCounts   = @json($alltime_pmt_counts  ?? []);

    function buildSparkOptions(type, colors) {
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var base = {
            chart: {
                type: type,
                sparkline: { enabled: true },
                height: 70,
                animations: { enabled: false },
                background: 'transparent'
            },
            colors: colors,
            tooltip: { theme: isDark ? 'dark' : 'light', x: { show: false } }
        };
        if (type === 'area') {
            base.stroke = { width: 2, curve: 'smooth' };
            base.fill   = { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.0 } };
        } else {
            base.stroke      = { width: 0 };
            base.fill        = { type: 'solid' };
            base.plotOptions = { bar: { columnWidth: '85%', borderRadius: 1 } };
            base.dataLabels  = { enabled: false };
        }
        return base;
    }

    var spInv = new ApexCharts(document.getElementById('sparkline-invoices'),
        Object.assign(buildSparkOptions('area', ['#45aaf2']), {
            series: [{ name: invoiceLabel, data: sparkInvAmounts }]
        }));
    spInv.render();

    var spPmt = new ApexCharts(document.getElementById('sparkline-payments'),
        Object.assign(buildSparkOptions('area', ['#2fb344']), {
            series: [{ name: paymentLabel, data: sparkPmtAmounts }]
        }));
    spPmt.render();

    var spVol = new ApexCharts(document.getElementById('sparkline-volume'),
        Object.assign(buildSparkOptions('bar', ['#45aaf2', '#2fb344']), {
            series: [
                { name: invoiceLabel, data: sparkInvCounts },
                { name: paymentLabel, data: sparkPmtCounts }
            ]
        }));
    spVol.render();

    document.documentElement.addEventListener('si-theme-changed', function () {
        [spInv, spPmt, spVol].forEach(function (c) { c.updateOptions({ tooltip: { theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light' } }); });
    });

})();
</script>
@endif
