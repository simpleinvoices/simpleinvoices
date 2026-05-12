@php
    $selectedPrefId        = $selectedPrefId ?? '';
    $currentCurrencySign   = $currentCurrencySign ?? '';
    $currentCurrencyCode   = $currentCurrencyCode ?? '';
    $currentCurrencyId     = $currentCurrencyId ?? '';
    $selectedTermId        = $selectedTermId ?? '';
    $calcDueDate           = $calcDueDate ?? '';
    $nextInvoiceId         = $nextInvoiceId ?? '';
    $showInvoiceIdPreview  = $showInvoiceIdPreview ?? false;
    $isNewInvoice          = $isNewInvoice ?? false;

    $currencyMatched = CurrencySignHelper::findPresetForStored($currentCurrencySign, $currentCurrencyCode);

    // Build code → DB id map for data-id attributes on currency <option> tags
    $currencyCodeToId = [];
    try {
        $allCurrencies = \siCurrencies::getForDomain(\domain_id::get());
        foreach ($allCurrencies as $c) {
            if (!empty($c['currency_code'])) {
                $currencyCodeToId[$c['currency_code']] = (int) $c['id'];
            }
        }
    } catch (\Throwable $e) {
        $currencyCodeToId = [];
    }
@endphp

{{-- Hidden backing inputs submitted with the form --}}
<input type="hidden" name="currency_sign" id="si_invoice_currency_sign" value="{{ $currentCurrencySign }}" />
<input type="hidden" name="currency_code" id="si_invoice_currency_code" value="{{ $currentCurrencyCode }}" />
<input type="hidden" name="currency_id" id="si_invoice_currency_id" value="{{ $currentCurrencyId }}" />

{{-- Invoice preference, currency, payment terms, due date preview (one row on large screens). Use -sm to match line-item controls on new invoice. --}}
<div class="row g-2 align-items-start si-invoice-pref-currency-terms">
	<div class="col-12 col-sm-6 col-xl-4">
		<label class="form-label mb-1" for="si_invoice_preference_id">{{ $LANG['inv_pref'] ?? '' }}</label>
		@if(($preferences ?? null) == null)
			<p class="text-muted mb-0"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
		@else
			<select name="preference_id" id="si_invoice_preference_id" class="form-select">
				@foreach(($preferences ?? []) as $pref)
				<option
					@if(($pref['pref_id'] ?? '') == $selectedPrefId) selected @endif
					value="{{ $pref['pref_id'] ?? '' }}"
					data-currency-sign="{{ CurrencySignHelper::forDisplay($pref['currency_sign'] ?? '') }}"
					data-currency-code="{{ $pref['currency_code'] ?? '' }}"
					data-currency-id="{{ $pref['currency_id'] ?? '' }}"
					data-payment-term-id="{{ $pref['payment_term_id'] ?? '' }}"
					data-index-group="{{ $pref['index_group'] ?? '' }}"
				>{{ $pref['pref_description'] ?? '' }}</option>
				@endforeach
			</select>
		@endif
	</div>
	<div class="col-12 col-sm-6 col-xl-4">
		<label class="form-label mb-1" for="si_invoice_currency_select">{{ $LANG['currency_sign'] ?? 'Currency' }}</label>
		<select id="si_invoice_currency_select" class="form-select" autocomplete="off">
			@foreach(CurrencySignHelper::getPresetGroups() as $g)
				<optgroup label="{{ $g['label'] }}">
					@foreach($g['presets'] as $p)
						<option
							value="{{ CurrencySignHelper::forDisplay($p['value']) }}"
							data-code="{{ $p['code'] ?? '' }}"
							data-id="{{ $currencyCodeToId[$p['code']] ?? '' }}"
							@if($currencyMatched !== null && ($currencyMatched['code'] ?? '') === ($p['code'] ?? '') && CurrencySignHelper::forDisplay($currencyMatched['value']) === CurrencySignHelper::forDisplay($p['value']))
								selected
							@endif
						>{{ $p['label'] }}</option>
					@endforeach
				</optgroup>
			@endforeach
		</select>
	</div>
	<div class="col-12 col-sm-6 col-xl-4">
		<label class="form-label mb-1" for="si_invoice_payment_term_id">{{ $LANG['payment_terms'] ?? 'Payment terms' }}</label>
		<select name="payment_term_id" id="si_invoice_payment_term_id" class="form-select">
			<option value="">{{ $LANG['payment_term_none'] ?? '-' }}</option>
			@foreach(($paymentTerms ?? []) as $pt)
				<option
					value="{{ $pt['term_id'] ?? '' }}"
					data-calc-kind="{{ $pt['calc_kind'] ?? '' }}"
					data-param="{{ $pt['param_int'] ?? '' }}"
					@if((string)($pt['term_id'] ?? '') === (string)$selectedTermId) selected @endif
				>{{ $pt['term_label'] ?? '' }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-12 col-sm-6 col-xl-4" id="si_invoice_due_date_container">
		<label class="form-label mb-1" for="si_invoice_due_date_preview">{{ $LANG['due_date'] ?? 'Due date' }}</label>
		<input type="text" readonly tabindex="-1" id="si_invoice_due_date_preview" data-initial="{{ $calcDueDate }}"
			class="form-control fw-medium bg-body-secondary border text-body"
			value="{{ $calcDueDate !== '' ? $calcDueDate : '-' }}"
			aria-readonly="true" />
	</div>
	@if($showInvoiceIdPreview)
	<div class="col-12 col-sm-6 col-xl-4">
		<label class="form-label mb-1" for="si_invoice_id_preview">{{ $LANG['invoice_id'] ?? 'Invoice ID' }}</label>
		<input type="text" readonly tabindex="-1" id="si_invoice_id_preview" data-initial="{{ $nextInvoiceId }}"
			class="form-control fw-medium bg-body-secondary border text-body"
			value="{{ $nextInvoiceId !== '' ? $nextInvoiceId : '-' }}"
			aria-readonly="true" />
	</div>
	@endif
</div>

<script>
(function () {
	var prefSel    = document.getElementById('si_invoice_preference_id');
	var curSel     = document.getElementById('si_invoice_currency_select');
	var signH      = document.getElementById('si_invoice_currency_sign');
	var codeH      = document.getElementById('si_invoice_currency_code');
		var idH        = document.getElementById('si_invoice_currency_id');
		var termSel    = document.getElementById('si_invoice_payment_term_id');
		var dateEl     = document.getElementById('date1');
		var isNewInvoice = {{ $isNewInvoice ? 'true' : 'false' }};

	if (!curSel || !signH || !codeH) { return; }

	function syncHiddenFromCurrencySelect() {
		var opt = curSel.options[curSel.selectedIndex];
		signH.value = opt ? (opt.value || '') : '';
		codeH.value = opt ? (opt.getAttribute('data-code') || '') : '';
		if (idH) { idH.value = opt ? (opt.getAttribute('data-id') || '') : ''; }
	}

	function syncCurrencyFromPreference() {
		if (!prefSel) { return; }
		var prefOpt = prefSel.options[prefSel.selectedIndex];
		if (!prefOpt) { return; }
		var sign = prefOpt.getAttribute('data-currency-sign') || '';
		var code = prefOpt.getAttribute('data-currency-code') || '';
		var cid = prefOpt.getAttribute('data-currency-id') || '';

		var matched = false;
		for (var i = 0; i < curSel.options.length; i++) {
			var o = curSel.options[i];
			if (o.value === sign && (o.getAttribute('data-code') || '') === code) {
				curSel.selectedIndex = i;
				matched = true;
				break;
			}
		}
		syncHiddenFromCurrencySelect();
		if (idH) { idH.value = cid; }
	}

	function siPad2(n) { return (n < 10 ? '0' : '') + n; }
	function siFormatYmd(d) {
		return d.getFullYear() + '-' + siPad2(d.getMonth() + 1) + '-' + siPad2(d.getDate());
	}
	function siParseYmd(s) {
		if (!s || !/^\d{4}-\d{2}-\d{2}$/.test(s)) return null;
		var p = s.split('-');
		return new Date(parseInt(p[0], 10), parseInt(p[1], 10) - 1, parseInt(p[2], 10));
	}
	function siLastDayOfMonth(dt) {
		return new Date(dt.getFullYear(), dt.getMonth() + 1, 0);
	}
	function siDueDateFromTerm(ymd, kind, paramStr) {
		var dt = siParseYmd(ymd);
		if (!dt || isNaN(dt.getTime())) return '';
		var param = paramStr === '' || paramStr === null ? null : parseInt(paramStr, 10);
		if (kind === 'NET_DAYS') {
			var n = param || 0;
			var d = new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
			d.setDate(d.getDate() + n);
			return siFormatYmd(d);
		}
		if (kind === 'EOM') {
			return siFormatYmd(siLastDayOfMonth(dt));
		}
		if (kind === 'EOM_PLUS_DAYS') {
			var n2 = param || 0;
			var eom = siLastDayOfMonth(dt);
			eom.setDate(eom.getDate() + n2);
			return siFormatYmd(eom);
		}
		if (kind === 'MFI_DAY') {
			var dayWant = Math.max(1, Math.min(31, param || 1));
			var firstNext = new Date(dt.getFullYear(), dt.getMonth() + 1, 1);
			var dim = new Date(firstNext.getFullYear(), firstNext.getMonth() + 1, 0).getDate();
			var use = Math.min(dayWant, dim);
			var due = new Date(firstNext.getFullYear(), firstNext.getMonth(), use);
			return siFormatYmd(due);
		}
		return '';
	}
	function siUpdateDueDatePreview() {
		var out = document.getElementById('si_invoice_due_date_preview');
		if (!out) return;
		if (!dateEl || !termSel) {
			out.value = '-';
			return;
		}
		var opt = termSel.options[termSel.selectedIndex];
		if (!opt || !opt.value) {
			out.value = '-';
			return;
		}
		var kind = opt.getAttribute('data-calc-kind') || '';
		var param = opt.getAttribute('data-param') || '';
		var ymd = siDueDateFromTerm(dateEl.value, kind, param);
		out.value = ymd || '-';
	}
	function siUpdateInvoiceIdPreview() {
		var out = document.getElementById('si_invoice_id_preview');
		if (!out || !prefSel) return;
		var prefOpt = prefSel.options[prefSel.selectedIndex];
		if (!prefOpt) return;
		var indexGroup = prefOpt.getAttribute('data-index-group') || '';
		if (indexGroup === '') {
			out.value = '-';
			return;
		}
		var xhr = new XMLHttpRequest();
		xhr.onload = function () {
			try {
				var data = JSON.parse(xhr.responseText);
				out.value = (data.next !== undefined) ? String(data.next) : '-';
			} catch (e) {
				out.value = '-';
			}
		};
		xhr.onerror = function () {
			out.value = '-';
		};
		xhr.open('GET', 'index.php?module=preferences&view=index_lookup_ajax&index_group=' + encodeURIComponent(indexGroup), true);
		xhr.send();
	}
	function syncPaymentTermFromPreference() {
		if (!prefSel || !termSel) return;
		var prefOpt = prefSel.options[prefSel.selectedIndex];
		if (!prefOpt) return;
		var tid = prefOpt.getAttribute('data-payment-term-id') || '';
		if (tid === '') return;
		for (var i = 0; i < termSel.options.length; i++) {
			if (termSel.options[i].value === tid) {
				termSel.selectedIndex = i;
				break;
			}
		}
		siUpdateDueDatePreview();
	}

	curSel.addEventListener('change', syncHiddenFromCurrencySelect);

	if (prefSel) {
		prefSel.addEventListener('change', function () {
			syncCurrencyFromPreference();
			syncPaymentTermFromPreference();
			siUpdateInvoiceIdPreview();
		});
	}

	if (!signH.value && !codeH.value && prefSel) {
		syncCurrencyFromPreference();
	} else {
		syncHiddenFromCurrencySelect();
	}

	if (termSel) {
		termSel.addEventListener('change', siUpdateDueDatePreview);
	}
	if (dateEl) {
		dateEl.addEventListener('change', siUpdateDueDatePreview);
		dateEl.addEventListener('blur', siUpdateDueDatePreview);
	}

	var out0 = document.getElementById('si_invoice_due_date_preview');
	if (out0 && out0.getAttribute('data-initial')) {
		out0.value = out0.getAttribute('data-initial');
	} else {
		if (termSel && (!termSel.value || termSel.value === '') && prefSel && isNewInvoice) {
			syncPaymentTermFromPreference();
		}
		siUpdateDueDatePreview();
	}
	var idOut0 = document.getElementById('si_invoice_id_preview');
	if (idOut0 && idOut0.getAttribute('data-initial')) {
		idOut0.value = idOut0.getAttribute('data-initial');
	}
	siUpdateInvoiceIdPreview();
})();
</script>
