@php
    // When the dashboard skips loading full entity lists (large DB), use flags from the controller.
    $hasBillers = isset($dash_has_billers) ? (bool) $dash_has_billers : (isset($billers) && is_array($billers) && count($billers) > 0);
    $hasCustomers = isset($dash_has_customers) ? (bool) $dash_has_customers : (isset($customers) && is_array($customers) && count($customers) > 0);
    $hasProducts = isset($dash_has_products) ? (bool) $dash_has_products : (isset($products) && is_array($products) && count($products) > 0);
    $hasTaxRates = isset($tax_rates) && is_array($tax_rates) && count($tax_rates) > 0;
    $hasPreferences = isset($preferences) && is_array($preferences) && count($preferences) > 0;
    $hasInvoices = isset($dash_has_invoices) ? (bool) $dash_has_invoices : (isset($latest_invoices) && is_array($latest_invoices) && count($latest_invoices) > 0);
    $wizardCurrencyPrefDone = isset($wizard_currency_pref_done) ? (bool) $wizard_currency_pref_done : false;
    $firstRun = !$hasBillers || !$hasCustomers || !$hasProducts || !$hasInvoices;
@endphp

@if($firstRun)
@php
    // Determine active wizard step - auto-advance to first incomplete step,
    // or honour an explicit ?wizard_step=N param from a redirect.
    $wizardStep = isset($_GET['wizard_step']) ? max(1, min(5, (int)$_GET['wizard_step'])) : (
        !$hasBillers ? 1 : (!$hasCustomers ? 2 : (!$hasProducts ? 3 : (!$wizardCurrencyPrefDone ? 4 : 5)))
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
                4 => ['label' => $LANG['wizard_invoice_preferences'] ?? '', 'done' => $wizardCurrencyPrefDone],
                5 => ['label' => $LANG['invoice'] ?? '',                  'done' => $hasInvoices],
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
            @if($s < 5)
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
                4 => ['icon' => 'ti-currency-dollar', 'label' => $LANG['wizard_invoice_preferences'] ?? '', 'done' => $wizardCurrencyPrefDone],
                5 => ['icon' => 'ti-file-invoice',    'label' => $LANG['wizard_create_invoice'] ?? '',  'done' => $hasInvoices],
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
                 STEP 1 - Your Details (Biller)
                 A Biller is the entity that creates invoices - you or your business.
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
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1"
                                                onclick="wizardFill('biller')">
                                            <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_biller'] ?? '' }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="wizardShuffle('biller')" title="{{ $LANG['wizard_try_another_sample'] ?? 'Try another sample' }}">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
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
                                        <input type="text" name="name" class="form-control" placeholder="{{ $wizard_sample_biller['name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? '' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="{{ $wizard_sample_biller['email'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="{{ $wizard_sample_biller['phone'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? '' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="{{ $wizard_sample_biller['street_address'] ?? '' }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">{{ $LANG['city'] ?? '' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="{{ $wizard_sample_biller['city'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">{{ $LANG['wizard_state_region'] ?? '' }}</label>
                                        <input type="text" name="state" class="form-control" placeholder="{{ $wizard_sample_biller['state'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ $LANG['postcode'] ?? '' }}</label>
                                        <input type="text" name="zip_code" class="form-control" placeholder="{{ $wizard_sample_biller['zip_code'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['country'] ?? '' }}</label>
                                        <input type="text" name="country" class="form-control" placeholder="{{ $wizard_sample_biller['country'] ?? '' }}">
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
                 STEP 2 - Add a Customer
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
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1"
                                                onclick="wizardFill('customer')">
                                            <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_customer'] ?? '' }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="wizardShuffle('customer')" title="{{ $LANG['wizard_try_another_sample'] ?? 'Try another sample' }}">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
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
                                        <input type="text" name="name" class="form-control" placeholder="{{ $wizard_sample_customer['name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['contact_person'] ?? '' }}</label>
                                        <input type="text" name="attention" class="form-control" placeholder="{{ $wizard_sample_customer['attention'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['customer_department'] ?? '' }}</label>
                                        <input type="text" name="department" class="form-control" placeholder="{{ $wizard_sample_customer['department'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['email'] ?? '' }}</label>
                                        <input type="email" name="email" class="form-control" placeholder="{{ $wizard_sample_customer['email'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['phone'] ?? '' }}</label>
                                        <input type="text" name="phone" class="form-control" placeholder="{{ $wizard_sample_customer['phone'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['street'] ?? '' }}</label>
                                        <input type="text" name="street_address" class="form-control" placeholder="{{ $wizard_sample_customer['street_address'] ?? '' }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">{{ $LANG['city'] ?? '' }}</label>
                                        <input type="text" name="city" class="form-control" placeholder="{{ $wizard_sample_customer['city'] ?? '' }}">
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
                 STEP 3 - Add a Product / Service
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
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1"
                                                onclick="wizardFill('product')">
                                            <i class="ti ti-wand me-1"></i>{{ $LANG['wizard_use_sample_product'] ?? '' }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="wizardShuffle('product')" title="{{ $LANG['wizard_try_another_sample'] ?? 'Try another sample' }}">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">{{ $LANG['wizard_sample_replace_later'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Form --}}
                        <div class="col-md-8">
                            <form method="post" action="index.php?module=products&amp;view=add">
                                <input type="hidden" name="op"          value="insert_product">
                                <input type="hidden" name="from_wizard" value="1">
                                {{-- cost/reorder_level omitted - insertProduct() treats missing keys as NULL (numeric cols) --}}
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
                                               placeholder="{{ $wizard_sample_product['description'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $LANG['unit_price'] ?? '' }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ CurrencySignHelper::forDisplay(($wizard_default_preference['pref_currency_sign'] ?? null) ?? ($preference['pref_currency_sign'] ?? '$')) }}</span>
                                            <input type="text" name="unit_price" class="form-control" placeholder="{{ $wizard_sample_product['unit_price'] ?? '' }}">
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
                 STEP 4 - Invoice preferences (currency sign for default Invoice preference)
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-4" class="tab-pane @if($wizardStep == 4) active show @endif" role="tabpanel">
                @php
                    $wizardPref = isset($wizard_default_preference) && is_array($wizard_default_preference) ? $wizard_default_preference : [];
                    $wizardPrefId = (int) ($wizardPref['pref_id'] ?? 0);
                    $wizardBiller = isset($wizard_default_biller) && is_array($wizard_default_biller) ? $wizard_default_biller : [];
                    $wizardBillerId = (int) ($wizardBiller['id'] ?? 0);
                @endphp
                @php
                    $wizardPaymentProcessors = [
                        ['label' => 'Stripe', 'provider' => 'stripe'],
                        ['label' => 'PayPal', 'provider' => 'paypal'],
                        ['label' => 'Mollie', 'provider' => 'ideal'],
                        ['label' => 'Authorize.net', 'provider' => 'authorize'],
                        ['label' => ($LANG['eway_rapid'] ?? 'eWay Rapid'), 'provider' => 'eway'],
                        ['label' => ($LANG['paymentsgateway_modern'] ?? 'Payments Gateway'), 'provider' => 'sage'],
                        ['label' => 'Ko-fi', 'provider' => 'cash-app'],
                        ['label' => ($LANG['coinbase_commerce'] ?? 'Coinbase Commerce'), 'provider' => 'bitcoin'],
                        ['label' => 'Adyen', 'provider' => 'adyen'],
                    ];
                @endphp
                @if($wizardCurrencyPrefDone)
                    <div class="d-flex align-items-center gap-3 py-2">
                        <span class="avatar avatar-lg bg-success-lt rounded"><i class="ti ti-check fs-2 text-success"></i></span>
                        <div>
                            <div class="fw-bold text-success fs-4">{{ $LANG['wizard_currency_ready'] ?? '' }}</div>
                            <p class="text-secondary mb-2">{{ $LANG['wizard_currency_ready_tagline'] ?? '' }}</p>
                            @if($wizardPrefId > 0)
                            <a href="index.php?module=preferences&amp;view=details&amp;id={{ $wizardPrefId }}&amp;action=edit" class="btn btn-sm btn-outline-success">
                                <i class="ti ti-settings me-1"></i>{{ $LANG['invoice_preferences'] ?? '' }}
                            </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="ribbon ribbon-top bg-yellow">
                                    <i class="ti ti-star"></i>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="avatar avatar-md bg-indigo-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-currency-dollar text-indigo"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['wizard_invoice_preferences'] ?? '' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_invoice_prefs_description'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mb-2 py-2 small" role="note">
                                        <div><i class="ti ti-settings me-1"></i>{{ $LANG['wizard_invoice_prefs_note'] ?? '' }}</div>
                                    </div>
                                    <div class="alert alert-secondary mb-0 py-2 small" role="note">
                                        <div><i class="ti ti-building-bank me-1"></i>{{ $LANG['wizard_bank_details_optional'] ?? '' }}</div>
                                        <div class="mt-1"><i class="ti ti-file-invoice me-1"></i>{{ $LANG['wizard_online_payment_biller_note'] ?? '' }}</div>
                                        <div class="si-wizard-payment-providers">
                                            @foreach($wizardPaymentProcessors as $processor)
                                            <div class="si-wizard-payment-provider" title="{{ $processor['label'] }}">
                                                @if(!empty($processor['provider']))
                                                <span class="payment si-payment-icon si-payment-provider-{{ $processor['provider'] }}" aria-hidden="true"></span>
                                                @else
                                                <span class="avatar avatar-sm bg-primary-lt rounded-2">
                                                    <i class="ti ti-credit-card text-primary"></i>
                                                </span>
                                                @endif
                                                <span>{{ $processor['label'] }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if($wizardPrefId < 1)
                                <div class="alert alert-warning">{{ $LANG['no_preferences'] ?? '' }}</div>
                            @else
                            <form method="post" action="index.php?module=preferences&amp;view=wizard_currency">
                                <input type="hidden" name="op" value="wizard_currency_sign">
                                <input type="hidden" name="pref_id" value="{{ $wizardPrefId }}">
                                <input type="hidden" name="from_wizard" value="1">
                                @include('templates.default.partials.currency_sign_field', [
                                    'currencySignFieldName'    => 'pref_currency_sign',
                                    'currencySignCurrentValue' => $wizardPref['pref_currency_sign'] ?? '',
                                    'currencyCodeFieldName'    => 'currency_code',
                                    'currencyCodeCurrentValue' => $wizardPref['currency_code'] ?? '',
                                ])
                                <div class="row g-3 mt-1">
                                    <div class="col-12">
                                        <label class="form-label">{{ $LANG['payment_terms'] ?? '' }}</label>
                                        <select name="payment_term_id" class="form-select">
                                            <option value="">{{ $LANG['payment_term_none'] ?? '-' }}</option>
                                            @foreach(($wizard_payment_terms ?? []) as $term)
                                                <option value="{{ $term['term_id'] ?? '' }}"
                                                    @if(($term['term_id'] ?? '') == ($wizardPref['payment_term_id'] ?? '')) selected @endif>
                                                    {{ $term['term_label'] ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-secondary">{{ $LANG['wizard_payment_terms_hint'] ?? 'Default payment terms applied to new invoices.' }}</small>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="avatar avatar-md bg-azure-lt rounded-3 flex-shrink-0">
                                            <i class="ti ti-building-bank text-azure"></i>
                                        </span>
                                        <div>
                                            <h4 class="mb-1">{{ $LANG['bank_details'] ?? 'Bank details' }}</h4>
                                            <p class="text-secondary mb-0" style="font-size:.875rem">
                                                {{ $LANG['wizard_bank_details_optional'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $LANG['bank_account_name'] ?? 'Account Name' }}</label>
                                            <input type="text" name="bank_account_name" value="{{ $wizardBiller['bank_account_name'] ?? '' }}" class="form-control" />
                                            <small class="form-hint">{{ $LANG['bank_account_name_hint'] ?? 'Legal name on the account' }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $LANG['bank_name'] ?? 'Bank Name' }}</label>
                                            <input type="text" name="bank_name" value="{{ $wizardBiller['bank_name'] ?? '' }}" class="form-control" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $LANG['bank_account_number'] ?? 'Account Number / IBAN' }}</label>
                                            <input type="text" name="bank_account_number" value="{{ $wizardBiller['bank_account_number'] ?? '' }}" class="form-control" />
                                            <small class="form-hint">{{ $LANG['bank_account_number_hint'] ?? 'IBAN (EU) or local account number' }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $LANG['bank_routing_sort_code'] ?? 'Routing / Sort Code' }}</label>
                                            <input type="text" name="bank_routing_sort_code" value="{{ $wizardBiller['bank_routing_sort_code'] ?? '' }}" class="form-control" />
                                            <small class="form-hint">{{ $LANG['bank_routing_sort_code_hint'] ?? 'BSB (AU), ABA (US), Sort Code (UK), Transit (CA) — leave blank if using IBAN' }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ $LANG['bank_swift_bic'] ?? 'SWIFT / BIC' }}</label>
                                            <input type="text" name="bank_swift_bic" value="{{ $wizardBiller['bank_swift_bic'] ?? '' }}" class="form-control" />
                                            <small class="form-hint">{{ $LANG['bank_swift_bic_hint'] ?? 'Bank identifier — universal for international transfers' }}</small>
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3 mb-0 py-2 small" role="note">
                                        <div><i class="ti ti-info-circle me-1"></i>{{ $LANG['wizard_bank_token_hint'] ?? 'Use tokens like {biller.bank_account_name}, {biller.bank_name}, {biller.bank_swift_bic}, {biller.bank_account_number}, and {biller.bank_routing_sort_code} in invoice preferences (Payment tab) and the biller invoice footer to auto-fill bank details when the invoice is rendered.' }}</div>
                                    </div>
                                    <div class="alert alert-secondary mt-3 mb-0 py-2 small" role="note">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span><i class="ti ti-credit-card me-1"></i>{{ $LANG['wizard_online_payment_biller_note'] ?? '' }}</span>
                                            @if($wizardBillerId > 0)
                                                <a href="index.php?module=billers&amp;view=details&amp;id={{ $wizardBillerId }}&amp;action=edit" class="alert-link">
                                                    <i class="ti ti-external-link me-1"></i>{{ $LANG['wizard_edit_biller_details'] ?? '' }}
                                                </a>
                                            @endif
                                        </div>
                                        <div class="si-payment-badges-compact">
                                            @foreach($wizardPaymentProcessors as $processor)
                                            <span class="si-payment-badge-compact" title="{{ $processor['label'] }}">
                                                @if(!empty($processor['provider']))
                                                <span class="payment si-payment-icon si-payment-provider-{{ $processor['provider'] }}" aria-hidden="true"></span>
                                                @else
                                                <i class="ti ti-credit-card text-secondary" style="font-size:0.75rem"></i>
                                                @endif
                                                <span>{{ $processor['label'] }}</span>
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        {{ $LANG['wizard_save_currency_continue'] ?? '' }} <i class="ti ti-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 STEP 5 - Create Your First Invoice
            ═══════════════════════════════════════════════════════════ --}}
            <div id="wizard-step-5" class="tab-pane @if($wizardStep == 5) active show @endif" role="tabpanel">
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
    var sampleArrays = {
        biller: @json($wizard_sample_billers ?? []),
        customer: @json($wizard_sample_customers ?? []),
        product: @json($wizard_sample_products ?? [])
    };

    var sampleIndex = { biller: 0, customer: 0, product: 0 };

    Object.keys(sampleIndex).forEach(function (type) {
        var arr = sampleArrays[type];
        if (arr && arr.length > 0) {
            sampleIndex[type] = Math.floor(Math.random() * arr.length);
        }
    });

    function getCurrentSample(type) {
        var arr = sampleArrays[type];
        if (!arr || arr.length === 0) return {};
        return arr[sampleIndex[type]] || {};
    }

    function fill(form, field, value) {
        var el = form.querySelector('[name="' + field + '"]');
        if (!el || el.type === 'hidden') return;
        if (el.tagName === 'SELECT') {
            for (var i = 0; i < el.options.length; i++) {
                if (el.options[i].value == value) { el.selectedIndex = i; break; }
            }
        } else {
            el.value = value || '';
        }
    }

    function highlightFields(form) {
        form.querySelectorAll('input:not([type=hidden]), select').forEach(function (el) {
            el.classList.add('is-valid');
            setTimeout(function () { el.classList.remove('is-valid'); }, 2000);
        });
    }

    window.wizardFill = function (type) {
        var s   = getCurrentSample(type);
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

        highlightFields(form);
    };

    window.wizardShuffle = function (type) {
        var arr = sampleArrays[type];
        if (!arr || arr.length <= 1) return;
        sampleIndex[type] = (sampleIndex[type] + 1) % arr.length;
        wizardFill(type);

        var paneId = type === 'biller' ? 'wizard-step-1'
                   : type === 'customer' ? 'wizard-step-2'
                   : 'wizard-step-3';
        var btn = document.querySelector('#' + paneId + ' .ti-refresh');
        if (btn) {
            btn.style.transition = 'transform 0.4s ease';
            btn.style.transform = 'rotate(360deg)';
            setTimeout(function () { btn.style.transform = ''; }, 400);
        }
    };
})();
</script>
@endif

@if(!$firstRun)
{{-- Charts row: monthly + yearly + invoices paid & invoice revenue (top right) --}}
<div class="row g-3 mb-3">
    {{-- Monthly activity chart --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header py-2 align-items-center">
                <h3 class="card-title mb-0">{{ $LANG['dash_chart_title_monthly'] ?? 'Monthly' }}</h3>
                <div class="card-options ms-auto">
                    <div id="chart-year-selector">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="chart-year-btn">
                                {{ $LANG['dash_chart_last_12_months'] ?? 'Last 12 months' }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item active" href="#" data-period="last12">{{ $LANG['dash_chart_last_12_months'] ?? 'Last 12 months' }}</a>
                                @if(count($chart_years ?? []) > 0)
                                <div class="dropdown-divider"></div>
                                @foreach(array_reverse($chart_years ?? []) as $y)
                                <a class="dropdown-item" href="#" data-year="{{ $y }}">{{ $y }}</a>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-2">
                <div id="chart-dashboard" style="min-height:200px"></div>
            </div>
        </div>
    </div>
    {{-- Annual totals bar chart --}}
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header py-2">
                <h3 class="card-title mb-0">{{ $LANG['dash_chart_title_yearly'] ?? 'Yearly' }}</h3>
            </div>
            <div class="card-body py-2">
                <div id="chart-annual" style="min-height:200px"></div>
            </div>
        </div>
    </div>
    {{-- Invoices Paid + Invoice Revenue: half-width on mobile (matches Payments / Volume cards), full-width on lg --}}
    <div class="col-lg-3">
        <div class="row g-3">
            <div class="col-6 col-lg-12">
                {{-- % Invoices Paid (success chrome matches Debtor Aging when all paid) --}}
                <div class="card h-100 d-flex flex-column @if(!empty($dash_all_invoices_paid)) border border-success border-opacity-25 @endif overflow-hidden">
                    @if(!empty($dash_all_invoices_paid))
                        <div class="card-status-top bg-success flex-shrink-0"></div>
                    @endif
                    @if(!empty($dash_all_invoices_paid))
                        <div class="card-header py-2 px-3 flex-shrink-0">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoices Paid</span>
                                <span class="badge bg-success-lt text-success border border-success border-opacity-25" title="{{ $LANG['paid'] ?? 'Paid' }}">
                                    <i class="ti ti-check"></i>
                                </span>
                                <a href="index.php?module=reports&amp;view=report_debtors_by_aging" class="ms-auto text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                                    <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body py-2 px-3 d-flex flex-column flex-grow-1 justify-content-center">
                    @else
                        <div class="card-body py-2 px-3 d-flex flex-column flex-grow-1 justify-content-center">
                            <div class="d-flex align-items-center mb-1 flex-shrink-0">
                                <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoices Paid</span>
                                <a href="index.php?module=reports&amp;view=report_debtors_by_aging" class="ms-auto text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                                    <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                                </a>
                            </div>
                    @endif
                        <div class="h1 mb-1">{{ $dash_paid_pct ?? 0 }}%</div>
                        <div class="text-secondary small mb-2">
                            {{ $dash_paid_inv_count ?? 0 }} of {{ $dash_total_inv_count ?? 0 }} invoices fully paid
                        </div>
                        <div class="progress flex-shrink-0" style="height:6px">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width:{{ $dash_paid_pct ?? 0 }}%"
                                 aria-valuenow="{{ $dash_paid_pct ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-12">
                <div class="card overflow-hidden h-100 d-flex flex-column">
                    <div class="card-body py-2 px-3 pb-1 flex-shrink-0">
                        <div class="d-flex align-items-center mb-1">
                            <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoice Revenue</span>
                            <a href="index.php?module=reports&amp;view=report_sales_total" class="ms-auto text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                                <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                            </a>
                        </div>
                        <div class="h2 mb-0">{{ ($dash_currency_sign ?? '')|si_currency_display }}{{ siLocal::number($dash_alltime_inv_total ?? 0) }}</div>
                    </div>
                    <div class="mt-auto w-100 flex-shrink-0">
                        <div id="sparkline-invoices" style="height:40px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent lists + sidebar: Payments, Debtor Aging, Volume --}}
<div class="row g-3 mb-3 align-items-lg-start">
    <div class="col-12 col-lg-9">
{{-- Recent invoices (columns / actions match Manage Invoices grid; latest 5 by id, no pager/search) --}}
<div class="card mb-3 si-dash-recent-table">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_invoices'] ?? '' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=invoices&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-window-maximize me-1"></i>{{ $LANG['view_all'] ?? '' }}
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
                    <th class="w-1"></th>
                    <th>{{ $LANG['id'] ?? '' }}</th>
                    <th class="d-none d-sm-table-cell">{{ $LANG['biller'] ?? '' }}</th>
                    <th>{{ $LANG['customer'] ?? '' }}</th>
                    <th class="d-none d-sm-table-cell text-end">{{ $LANG['date_upper'] ?? '' }}</th>
                    <th class="text-end">{{ $LANG['total'] ?? '' }}</th>
                    <th class="text-center">{{ $LANG['status'] ?? '' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($latest_invoices ?? []) as $inv)
                @php
                    $rowId = (int)($inv['id']);
                    $owing = (float)($inv['owing'] ?? 0);
                    $hasStatus = !empty($inv['status']);
                    $isPaid = $hasStatus && $owing <= 0;
                    $isDraft = !$hasStatus;
                    $aging = (string)($inv['aging'] ?? '');
                    if ($aging === '0-30') {
                        $dotColor = 'secondary';
                    } elseif ($aging === '31-60') {
                        $dotColor = 'yellow';
                    } elseif ($aging === '61-90') {
                        $dotColor = 'orange';
                    } elseif ($aging === '90+') {
                        $dotColor = 'red';
                    } else {
                        $dotColor = 'secondary';
                    }
                    $invPdfUrl = 'index.php?module=export&view=invoice&id=' . $rowId . '&format=pdf';
                    $printTitle = ($LANG['print_preview_tooltip'] ?? '') . ' ' . ($inv['index_name'] ?? '');
                @endphp
                <tr>
                    <td class="w-1">
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>{{ $LANG['actions'] ?? '' }}</span>
                                <span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="index.php?module=invoices&amp;view=quick_view&amp;id={{ $rowId }}"><i class="ti ti-eye me-2"></i>{{ $LANG['quick_view_tooltip'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                <a class="dropdown-item" href="index.php?module=invoices&amp;view=details&amp;id={{ $rowId }}&amp;action=view"><i class="ti ti-edit me-2"></i>{{ $LANG['edit_view_tooltip'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item si-preview-link" href="index.php?module=export&amp;view=invoice&amp;id={{ $rowId }}&amp;format=print" data-preview-title="{{ e($printTitle) }}" data-preview-pdf="{{ e($invPdfUrl) }}"><i class="ti ti-printer me-2"></i>{{ $LANG['print_preview_tooltip'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                <a class="dropdown-item invoice_export_dialog" href="#" rel="{{ $rowId }}"><i class="ti ti-file-export me-2"></i>{{ $LANG['export_tooltip'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                <div class="dropdown-divider"></div>
                                @if($hasStatus && $owing > 0)
                                <a class="dropdown-item" href="index.php?module=payments&amp;view=process&amp;id={{ $rowId }}&amp;op=pay_selected_invoice"><i class="ti ti-cash me-2"></i>{{ $LANG['process_payment_for'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                @elseif($hasStatus)
                                <a class="dropdown-item" href="index.php?module=payments&amp;view=details&amp;id={{ $rowId }}&amp;action=view"><i class="ti ti-receipt me-2"></i>{{ $LANG['process_payment_for'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                                @endif
                                <a class="dropdown-item" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={{ $rowId }}"><i class="ti ti-mail-forward me-2"></i>{{ $LANG['email'] ?? '' }} {{ $inv['index_name'] ?? '' }}</a>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $invName  = $inv['index_name'] ?? '';
                            $invSp    = strpos($invName, ' ');
                            $invNum   = $invSp !== false ? mb_substr($invName, $invSp + 1) : $invName;
                            $invShort = $invSp !== false
                                ? mb_substr($invName, 0, 3) . '.. ..' . mb_substr($invNum, -3)
                                : $invName;
                        @endphp
                        <span class="d-none d-sm-inline">{{ $invName }}</span>
                        <span class="d-sm-none">{{ $invShort }}</span>
                    </td>
                    <td class="d-none d-sm-table-cell">{{ $inv['biller'] ?? '' }}</td>
                    <td>
                        @php
                            $custName  = $inv['customer'] ?? '';
                            $custShort = mb_strlen($custName) > 15 ? mb_substr($custName, 0, 15) . '…' : $custName;
                        @endphp
                        <span class="d-none d-sm-inline">{{ $custName }}</span>
                        <span class="d-sm-none">{{ $custShort }}</span>
                    </td>
                    <td class="d-none d-sm-table-cell text-end text-secondary">{{ siLocal::date($inv['date'] ?? '') }}</td>
                    <td class="text-end">{{ ($inv['currency_sign'] ?? '')|si_currency_display }}{{ siLocal::number($inv['invoice_total'] ?? 0) }}</td>
                    <td class="text-center">
                        @if($isDraft)
                            <span class="d-none d-sm-inline"><span class="status status-secondary"><span class="status-dot"></span>{{ $LANG['draft'] ?? '' }}</span></span>
                            <span class="d-sm-none"><span class="status status-secondary"><span class="status-dot"></span></span></span>
                        @elseif($isPaid)
                            <span class="d-none d-sm-inline"><span class="status status-green">{{ $LANG['paid'] ?? '' }}</span></span>
                            <span class="d-sm-none"><span class="status status-green"><span class="status-dot"></span></span></span>
                        @else
                            <span class="d-none d-sm-inline"><span class="status status-{{ $dotColor }}">{{ $LANG['unpaid'] ?? 'Unpaid' }}</span></span>
                            <span class="d-sm-none"><span class="status status-{{ $dotColor }}"><span class="status-dot"></span></span></span>
                        @endif
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

{{-- Recent payments (columns / actions match Manage Payments grid; latest 5 by id, no pager/search) --}}
<div class="card mb-3 mb-lg-0 si-dash-recent-table">
    <div class="card-header">
        <h3 class="card-title">{{ $LANG['recent_payments'] ?? '' }}</h3>
        <div class="card-options ms-auto d-flex gap-2">
            <a href="index.php?module=payments&amp;view=manage" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-window-maximize me-1"></i>{{ $LANG['view_all'] ?? '' }}
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
                    <th class="w-1"></th>
                    <th>{{ $LANG['payment'] ?? '' }}</th>
                    <th class="d-none d-sm-table-cell">{{ $LANG['invoice'] ?? '' }}</th>
                    <th>{{ $LANG['customer'] ?? '' }}</th>
                    <th class="d-none d-sm-table-cell">{{ $LANG['biller'] ?? '' }}</th>
                    <th class="text-end">{{ $LANG['amount'] ?? '' }}</th>
                    <th class="text-center d-none d-sm-table-cell">{{ $LANG['date_upper'] ?? '' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($latest_payments ?? []) as $pmt)
                @php
                    $pmtId = (int)($pmt['id']);
                    $payPdfUrl = 'index.php?module=export&view=payment&id=' . $pmtId . '&format=pdf';
                    $payPrintTitle = ($LANG['print_preview_tooltip'] ?? '') . ' ' . ($pmt['index_name'] ?? '');
                @endphp
                <tr>
                    <td class="w-1">
                        <div class="dropdown">
                            <a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>{{ $LANG['actions'] ?? '' }}</span>
                                <span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="index.php?module=payments&amp;view=details&amp;id={{ $pmtId }}&amp;action=view"><i class="ti ti-eye me-2"></i>{{ $LANG['view'] ?? '' }} {{ $pmt['index_name'] ?? $pmtId }}</a>
                                <a class="dropdown-item si-preview-link" href="index.php?module=payments&amp;view=print&amp;id={{ $pmtId }}" data-preview-title="{{ e($payPrintTitle) }}" data-preview-pdf="{{ e($payPdfUrl) }}"><i class="ti ti-printer me-2"></i>{{ $LANG['print_preview_tooltip'] ?? '' }} {{ $pmt['index_name'] ?? $pmtId }}</a>
                            </div>
                        </div>
                    </td>
                    <td>{{ $pmtId }}</td>
                    <td class="d-none d-sm-table-cell">{{ $pmt['index_name'] ?? '' }}</td>
                    <td>{{ $pmt['cname'] ?? '' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $pmt['bname'] ?? '' }}</td>
                    <td class="text-end">{{ ($pmt['currency_sign'] ?? '')|si_currency_display }}{{ siLocal::number($pmt['ac_amount'] ?? 0) }}</td>
                    <td class="text-center text-secondary d-none d-sm-table-cell">{{ siLocal::date($pmt['date'] ?? '') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">{{ $LANG['no_payments_yet'] ?? '' }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="row g-3">
    {{-- Payment Revenue sparkline --}}
    <div class="col-6 col-lg-12">
        <div class="card overflow-hidden">
            <div class="card-body py-2 px-3 pb-1">
                <div class="d-flex align-items-center mb-1">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Payments</span>
                    <a href="index.php?module=reports&amp;view=report_sales_by_periods" class="ms-auto text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                        <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h2 mb-0">{{ ($dash_currency_sign ?? '')|si_currency_display }}{{ siLocal::number($dash_alltime_pmt_total ?? 0) }}</div>
            </div>
            <div id="sparkline-payments" style="height:40px"></div>
        </div>
    </div>

    {{-- Invoice & Payment Volume (count) --}}
    <div class="col-6 col-lg-12">
        <div class="card overflow-hidden">
            <div class="card-body py-2 px-3 pb-1">
                <div class="d-flex align-items-center mb-1">
                    <span class="text-uppercase text-secondary fw-semibold" style="font-size:.65rem;letter-spacing:.08em">Invoice &amp; Payment Volume</span>
                    <a href="index.php?module=reports&amp;view=report_sales_by_periods" class="ms-auto text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                        <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                    </a>
                </div>
                <div class="h2 mb-0">{{ number_format($dash_total_inv_volume ?? 0) }} <span class="fs-6 fw-normal text-secondary">inv &middot; {{ number_format($dash_total_pmt_volume ?? 0) }} pmt</span></div>
            </div>
            <div id="sparkline-volume" style="height:40px"></div>
        </div>
    </div>
    {{-- Debtor aging radial (moved from charts row; below Payments) --}}
    <div class="col-12 col-lg-12">
        <div class="card h-100 @if(!empty($dash_aging_all_clear)) border border-success border-opacity-25 @endif overflow-hidden">
            @if(!empty($dash_aging_all_clear))
                <div class="card-status-top bg-success"></div>
            @endif
            <div class="card-header py-2 align-items-center">
                <h3 class="card-title mb-0 d-flex align-items-center flex-wrap gap-2">
                    <span>{{ $LANG['dash_chart_title_debtor_aging'] ?? 'Debtor Aging' }}</span>
                    @if(!empty($dash_aging_all_clear))
                        <span class="badge bg-success-lt text-success border border-success border-opacity-25" title="{{ $LANG['paid'] ?? 'Paid' }}">
                            <i class="ti ti-check"></i>
                        </span>
                    @endif
                </h3>
                <div class="card-options ms-auto">
                    <a href="index.php?module=reports&amp;view=report_debtors_by_aging" class="text-secondary lh-1" title="{{ $LANG['run_report'] ?? 'Run report' }}">
                        <i class="ti ti-window-maximize" style="font-size:.9rem"></i>
                    </a>
                </div>
            </div>
            <div class="card-body py-2 d-flex align-items-center justify-content-center position-relative">
                @if(!empty($dash_aging_all_clear))
                    <div class="text-center px-2 w-100">
                        <span class="avatar avatar-xl bg-success-lt text-success mb-2">
                            <i class="ti ti-circle-check fs-1"></i>
                        </span>
                    </div>
                @else
                    <div id="chart-aging" style="min-height:200px;width:100%"></div>
                @endif
            </div>
        </div>
    </div>

        </div>
    </div>
</div>

<div class="modal fade" id="export_dialog" tabindex="-1" aria-labelledby="export_dialog_title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="export_dialog_title">{{ $LANG['export'] ?? '' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? '' }}"></button>
            </div>
            <div class="modal-body d-flex gap-2 flex-wrap">
                <a href="#" title="{{ ($LANG['export_tooltip'] ?? '') }} {{ ($LANG['export_pdf_tooltip'] ?? '') }}" class="btn btn-outline-danger export_pdf export_window" target="_blank" rel="noopener">
                    <i class="ti ti-file-certificate me-1"></i>{{ $LANG['export_pdf'] ?? '' }}
                </a>
                <a href="#" title="{{ ($LANG['export_tooltip'] ?? '') }} {{ ($LANG['export_xls_tooltip'] ?? '') }} .{{ $defaults['spreadsheet'] ?? 'xlsx' }}" class="btn btn-outline-success export_xls export_window">
                    <i class="ti ti-file-spreadsheet me-1"></i>{{ $LANG['export_xls'] ?? '' }}
                </a>
                <a href="#" title="{{ ($LANG['export_tooltip'] ?? '') }} {{ ($LANG['export_doc_tooltip'] ?? '') }} .{{ $defaults['wordprocessor'] ?? 'docx' }}" class="btn btn-outline-primary export_doc export_window">
                    <i class="ti ti-file-text me-1"></i>{{ $LANG['export_doc'] ?? '' }}
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $LANG['cancel'] ?? '' }}</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script>
(function () {
    var labels         = @json($chart_labels ?? []);
    var datasets       = @json($chart_data ?? []);
    var last12         = @json($chart_last12 ?? ['labels' => [], 'invoices' => [], 'payments' => []]);
    var invoiceLabel   = @json($LANG['invoices'] ?? '');
    var paymentLabel   = @json($LANG['payments'] ?? '');
    var last12Label    = @json($LANG['dash_chart_last_12_months'] ?? 'Last 12 months');
    var annualTotals   = @json($annual_totals ?? []);
    var annualYears    = @json($chart_years ?? []);
    var dashCurrSign   = @json(\CurrencySignHelper::forDisplay($dash_currency_sign ?? ''));
    var last12ByCurr   = @json($chart_last12_by_curr ?? []);
    var datasetsByCurr = @json($chart_data_by_curr ?? []);
    var annualByCurr   = @json($annual_totals_by_curr ?? []);
    var currKeys       = Object.keys(last12ByCurr);
    var multiCurr      = currKeys.length > 1;

    // Two-tone pairs per currency: invoices/payments share a hue, different currencies get different hues
    var currPalettes = [
        ['#45aaf2', '#2fb344'],
        ['#f59f00', '#fd7e14'],
        ['#7048e8', '#ae3ec9'],
        ['#e03131', '#d6336c'],
        ['#0ca678', '#099268'],
    ];

    function cssVar(name) {
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    }

    function buildOptions(period) {
        var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var primary   = cssVar('--tblr-primary')      || '#45aaf2';
        var success   = cssVar('--tblr-success')      || '#2fb344';
        var bodyColor = cssVar('--tblr-body-color')   || '#1d273b';
        var borderCol = cssVar('--tblr-border-color') || '#e6e7e9';
        var catLabels = period === 'last12' ? (last12.labels || []) : labels;
        var series = [], seriesSigns = [], colors = [];

        if (multiCurr) {
            currKeys.forEach(function (k, ci) {
                var meta   = last12ByCurr[k] || {};
                var sign   = meta.decoded_sign || '';
                var code   = meta.code || '';
                var suffix = code ? ' (' + code + ')' : '';
                var pair   = currPalettes[ci % currPalettes.length];
                var invData, payData;
                if (period === 'last12') {
                    invData = meta.invoices || [];
                    payData = meta.payments || [];
                } else {
                    var pack = ((datasetsByCurr[k] || {})[period]) || ((datasetsByCurr[k] || {})[String(period)]) || {};
                    invData = pack.invoices || [];
                    payData = pack.payments || [];
                }
                series.push({ name: invoiceLabel + suffix, data: invData });
                seriesSigns.push(sign);
                colors.push(pair[0]);
                series.push({ name: paymentLabel + suffix, data: payData });
                seriesSigns.push(sign);
                colors.push(pair[1]);
            });
        } else {
            var invData, payData;
            if (period === 'last12') {
                invData = last12.invoices || [];
                payData = last12.payments || [];
            } else {
                var pack = datasets[period] || datasets[String(period)] || {};
                invData = pack.invoices || [];
                payData = pack.payments || [];
            }
            series = [{ name: invoiceLabel, data: invData }, { name: paymentLabel, data: payData }];
            seriesSigns = [dashCurrSign, dashCurrSign];
            colors = [primary, success];
        }

        return {
            chart: {
                type: 'area',
                fontFamily: 'inherit',
                height: 200,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent'
            },
            series: series,
            xaxis: {
                categories: catLabels,
                labels: { style: { colors: bodyColor } },
                axisBorder: { color: borderCol },
                axisTicks: { color: borderCol }
            },
            yaxis: {
                labels: {
                    style: { colors: bodyColor },
                    formatter: function (v) { return (seriesSigns[0] || dashCurrSign) + v.toLocaleString(); }
                }
            },
            colors: colors,
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
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (v, opts) {
                        var sign = seriesSigns[opts && opts.seriesIndex] !== undefined
                            ? seriesSigns[opts.seriesIndex] : dashCurrSign;
                        return sign + v.toLocaleString();
                    }
                }
            }
        };
    }

    var currentPeriod = 'last12';
    var chart = new ApexCharts(document.getElementById('chart-dashboard'), buildOptions(currentPeriod));
    chart.render();

    document.querySelectorAll('#chart-year-selector .dropdown-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            var btn = document.getElementById('chart-year-btn');
            if (this.dataset.period === 'last12') {
                currentPeriod = 'last12';
                if (btn) btn.textContent = last12Label;
            } else if (this.dataset.year !== undefined && this.dataset.year !== '') {
                currentPeriod = parseInt(this.dataset.year, 10);
                if (btn) btn.textContent = String(this.dataset.year);
            }
            chart.updateOptions(buildOptions(currentPeriod), true, true);
            document.querySelectorAll('#chart-year-selector .dropdown-item').forEach(function (el) {
                el.classList.toggle('active', el === item);
            });
        });
    });

    document.documentElement.addEventListener('si-theme-changed', function () {
        chart.updateOptions(buildOptions(currentPeriod), true, true);
        annualChart.updateOptions(buildAnnualOptions(), true, true);
        if (agingChart) {
            agingChart.updateOptions(buildAgingOptions(), true, true);
        }
    });

    // Annual bar chart
    function buildAnnualOptions() {
        var isDark    = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        var primary   = cssVar('--tblr-primary')      || '#45aaf2';
        var success   = cssVar('--tblr-success')      || '#2fb344';
        var bodyColor = cssVar('--tblr-body-color')   || '#1d273b';
        var borderCol = cssVar('--tblr-border-color') || '#e6e7e9';
        var series = [], seriesSigns = [], colors = [];

        if (multiCurr) {
            Object.keys(annualByCurr).forEach(function (k, ci) {
                var entry  = annualByCurr[k] || {};
                var meta   = entry.meta || {};
                var years  = entry.years || {};
                var sign   = meta.decoded_sign || '';
                var code   = meta.code || '';
                var suffix = code ? ' (' + code + ')' : '';
                var pair   = currPalettes[ci % currPalettes.length];
                series.push({ name: invoiceLabel + suffix, data: annualYears.map(function (y) { return (years[y] || {}).invoices || 0; }) });
                seriesSigns.push(sign);
                colors.push(pair[0]);
                series.push({ name: paymentLabel + suffix, data: annualYears.map(function (y) { return (years[y] || {}).payments || 0; }) });
                seriesSigns.push(sign);
                colors.push(pair[1]);
            });
        } else {
            series = [
                { name: invoiceLabel, data: annualYears.map(function (y) { return (annualTotals[y] || {}).invoices || 0; }) },
                { name: paymentLabel, data: annualYears.map(function (y) { return (annualTotals[y] || {}).payments || 0; }) }
            ];
            seriesSigns = [dashCurrSign, dashCurrSign];
            colors = [primary, success];
        }

        return {
            chart: {
                type: 'bar',
                fontFamily: 'inherit',
                height: 200,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent'
            },
            series: series,
            xaxis: {
                categories: annualYears,
                labels: { style: { colors: bodyColor } },
                axisBorder: { color: borderCol },
                axisTicks: { color: borderCol }
            },
            yaxis: {
                labels: {
                    style: { colors: bodyColor },
                    formatter: function (v) { return (seriesSigns[0] || dashCurrSign) + v.toLocaleString(); }
                }
            },
            colors: colors,
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
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (v, opts) {
                        var sign = seriesSigns[opts && opts.seriesIndex] !== undefined
                            ? seriesSigns[opts.seriesIndex] : dashCurrSign;
                        return sign + v.toLocaleString();
                    }
                }
            }
        };
    }

    var annualChart = new ApexCharts(document.getElementById('chart-annual'), buildAnnualOptions());
    annualChart.render();

    // Debtor aging radial chart (skipped when all invoices paid and nothing owing - static success UI instead)
    var agingData = @json($aging_chart ?? []);
    var dashAgingAllClear = @json(!empty($dash_aging_all_clear));

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
                height: 200,
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
                                return amounts[idx] !== undefined ? dashCurrSign + amounts[idx].toLocaleString() : val + '%';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: bodyColor,
                            formatter: function () {
                                var t = agingData.reduce(function (s, b) { return s + b.amount; }, 0);
                                return dashCurrSign + t.toLocaleString();
                            }
                        }
                    }
                }
            },
            legend: { show: false },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val, opts) {
                        var idx = opts && opts.seriesIndex != null ? opts.seriesIndex : 0;
                        return amounts[idx] !== undefined ? dashCurrSign + amounts[idx].toLocaleString() : val + '%';
                    }
                }
            }
        };
    }

    var agingChart = null;
    if (!dashAgingAllClear) {
        var agingEl = document.getElementById('chart-aging');
        if (agingEl) {
            agingChart = new ApexCharts(agingEl, buildAgingOptions());
            agingChart.render();
        }
    }

    // ── Stat card sparklines ─────────────────────────────────────────────────
    var sparkInvAmounts  = @json($alltime_inv_monthly ?? []);
    var sparkPmtAmounts  = @json($alltime_pmt_monthly ?? []);
    var sparkInvCounts   = @json($alltime_inv_counts  ?? []);
    var sparkPmtCounts   = @json($alltime_pmt_counts  ?? []);

    function buildAreaSparkOptions(color) {
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        return {
            chart: {
                type: 'area',
                height: 60,
                toolbar: { show: false },
                animations: { enabled: false },
                background: 'transparent',
                sparkline: { enabled: true }
            },
            stroke: { width: 2, curve: 'smooth' },
            fill: { type: 'solid', opacity: 0.2 },
            colors: [color],
            dataLabels: { enabled: false },
            tooltip: { theme: isDark ? 'dark' : 'light', x: { show: false } }
        };
    }

    function buildBarSparkOptions() {
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        return {
            chart: {
                type: 'bar',
                sparkline: { enabled: true },
                height: 50,
                animations: { enabled: false },
                background: 'transparent'
            },
            colors: ['#45aaf2', '#2fb344'],
            stroke:      { width: 0 },
            fill:        { type: 'solid' },
            plotOptions: { bar: { columnWidth: '85%', borderRadius: 1 } },
            dataLabels:  { enabled: false },
            tooltip: { theme: isDark ? 'dark' : 'light', x: { show: false } }
        };
    }

    var spInv = new ApexCharts(document.getElementById('sparkline-invoices'),
        Object.assign(buildAreaSparkOptions('#45aaf2'), {
            series: [{ name: invoiceLabel, data: sparkInvAmounts }]
        }));
    spInv.render();

    var spPmt = new ApexCharts(document.getElementById('sparkline-payments'),
        Object.assign(buildAreaSparkOptions('#2fb344'), {
            series: [{ name: paymentLabel, data: sparkPmtAmounts }]
        }));
    spPmt.render();

    var spVol = new ApexCharts(document.getElementById('sparkline-volume'),
        Object.assign(buildBarSparkOptions(), {
            series: [
                { name: invoiceLabel, data: sparkInvCounts },
                { name: paymentLabel, data: sparkPmtCounts }
            ]
        }));
    spVol.render();

    document.documentElement.addEventListener('si-theme-changed', function () {
        var t = { tooltip: { theme: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light' } };
        spInv.updateOptions(t);
        spPmt.updateOptions(t);
        spVol.updateOptions(t);
    });

})();
</script>
@endif
