@php
    $selectedPrefId        = $selectedPrefId ?? '';
    $currentCurrencySign   = $currentCurrencySign ?? '';
    $currentCurrencyCode   = $currentCurrencyCode ?? '';
    $selectedTermId        = $selectedTermId ?? '';
    $calcDueDate           = $calcDueDate ?? '';

    // Determine which currency preset is currently selected (sign + code match)
    $currencyMatched = CurrencySignHelper::findPresetForStored($currentCurrencySign, $currentCurrencyCode);
    $currencyIsCustom = ($currencyMatched === null) && ($currentCurrencySign !== '' || $currentCurrencyCode !== '');
@endphp

{{-- Hidden backing inputs submitted with the form --}}
<input type="hidden" name="currency_sign" id="si_invoice_currency_sign" value="{{ $currentCurrencySign }}" />
<input type="hidden" name="currency_code" id="si_invoice_currency_code" value="{{ $currentCurrencyCode }}" />

{{-- Invoice preference, currency, payment terms, due date preview (one row on large screens). Use -sm to match line-item controls on new invoice. --}}
<div class="row g-2 align-items-start si-invoice-pref-currency-terms">
	<div class="col-12 col-sm-6 col-xl-3">
		<label class="form-label mb-1" for="si_invoice_preference_id">{{ $LANG['inv_pref'] ?? '' }}</label>
		@if(($preferences ?? null) == null)
			<p class="text-muted mb-0"><em>{{ $LANG['no_preferences'] ?? '' }}</em></p>
		@else
			<select name="preference_id" id="si_invoice_preference_id" class="form-select form-select-sm">
				@foreach(($preferences ?? []) as $pref)
					<option
						@if(($pref['pref_id'] ?? '') == $selectedPrefId) selected @endif
						value="{{ $pref['pref_id'] ?? '' }}"
						data-currency-sign="{{ CurrencySignHelper::forDisplay($pref['pref_currency_sign'] ?? '') }}"
						data-currency-code="{{ $pref['currency_code'] ?? '' }}"
						data-payment-term-id="{{ $pref['payment_term_id'] ?? '' }}"
					>{{ $pref['pref_description'] ?? '' }}</option>
				@endforeach
			</select>
		@endif
	</div>
	<div class="col-12 col-sm-6 col-xl-3">
		<label class="form-label mb-1" for="si_invoice_currency_select">{{ $LANG['currency_sign'] ?? 'Currency' }}</label>
		<select id="si_invoice_currency_select" class="form-select form-select-sm" autocomplete="off">
			@foreach(CurrencySignHelper::getPresetGroups() as $g)
				<optgroup label="{{ $g['label'] }}">
					@foreach($g['presets'] as $p)
						<option
							value="{{ CurrencySignHelper::forDisplay($p['value']) }}"
							data-code="{{ $p['code'] ?? '' }}"
							@if($currencyMatched !== null && ($currencyMatched['code'] ?? '') === ($p['code'] ?? '') && CurrencySignHelper::forDisplay($currencyMatched['value']) === CurrencySignHelper::forDisplay($p['value']))
								selected
							@endif
						>{{ $p['label'] }}</option>
					@endforeach
				</optgroup>
			@endforeach
			<option value="__custom__" @if($currencyIsCustom) selected @endif>{{ $LANG['currency_sign_custom'] ?? 'Custom…' }}</option>
		</select>
		<div id="si_invoice_currency_custom_wrap" class="mt-1 {{ $currencyIsCustom ? '' : 'd-none' }}">
			<div class="row g-1">
				<div class="col">
					<input type="text" id="si_invoice_currency_sign_custom" class="form-control form-control-sm"
						placeholder="{{ $LANG['currency_sign'] ?? 'Symbol' }}"
						value="{{ $currencyIsCustom ? $currentCurrencySign : '' }}"
						autocomplete="off" />
				</div>
				<div class="col">
					<input type="text" id="si_invoice_currency_code_custom" class="form-control form-control-sm"
						placeholder="{{ $LANG['currency_code'] ?? 'Code (e.g. USD)' }}"
						value="{{ $currencyIsCustom ? $currentCurrencyCode : '' }}"
						autocomplete="off" maxlength="10" />
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-sm-6 col-xl-3">
		<label class="form-label mb-1" for="si_invoice_payment_term_id">{{ $LANG['payment_terms'] ?? 'Payment terms' }}</label>
		<select name="payment_term_id" id="si_invoice_payment_term_id" class="form-select form-select-sm">
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
	<div class="col-12 col-sm-6 col-xl-3">
		<label class="form-label mb-1" for="si_invoice_due_date_preview">{{ $LANG['due_date'] ?? 'Due date' }}</label>
		<input type="text" readonly tabindex="-1" id="si_invoice_due_date_preview" data-initial="{{ $calcDueDate }}"
			class="form-control form-control-sm fw-medium bg-body-secondary border text-body"
			value="{{ $calcDueDate !== '' ? $calcDueDate : '-' }}"
			aria-readonly="true" />
	</div>
</div>

<script>
(function () {
	var prefSel    = document.getElementById('si_invoice_preference_id');
	var curSel     = document.getElementById('si_invoice_currency_select');
	var signH      = document.getElementById('si_invoice_currency_sign');
	var codeH      = document.getElementById('si_invoice_currency_code');
	var customWrap = document.getElementById('si_invoice_currency_custom_wrap');
	var customSign = document.getElementById('si_invoice_currency_sign_custom');
	var customCode = document.getElementById('si_invoice_currency_code_custom');
	var termSel    = document.getElementById('si_invoice_payment_term_id');
	var dateEl     = document.getElementById('date1');

	if (!curSel || !signH || !codeH || !customWrap || !customSign || !customCode) { return; }

	function syncHiddenFromCurrencySelect() {
		if (curSel.value === '__custom__') {
			customWrap.classList.remove('d-none');
			signH.value = customSign.value;
			codeH.value = customCode.value;
		} else {
			customWrap.classList.add('d-none');
			var opt = curSel.options[curSel.selectedIndex];
			signH.value = opt ? (opt.value || '') : '';
			codeH.value = opt ? (opt.getAttribute('data-code') || '') : '';
		}
	}

	function syncCurrencyFromPreference() {
		if (!prefSel) { return; }
		var prefOpt = prefSel.options[prefSel.selectedIndex];
		if (!prefOpt) { return; }
		var sign = prefOpt.getAttribute('data-currency-sign') || '';
		var code = prefOpt.getAttribute('data-currency-code') || '';

		var matched = false;
		for (var i = 0; i < curSel.options.length; i++) {
			var o = curSel.options[i];
			if (o.value !== '__custom__' && o.value === sign && (o.getAttribute('data-code') || '') === code) {
				curSel.selectedIndex = i;
				matched = true;
				break;
			}
		}
		if (!matched) {
			curSel.value = '__custom__';
			customSign.value = sign;
			customCode.value = code;
		}
		syncHiddenFromCurrencySelect();
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
	customSign.addEventListener('input', function () {
		if (curSel.value === '__custom__') { signH.value = customSign.value; }
	});
	customCode.addEventListener('input', function () {
		if (curSel.value === '__custom__') { codeH.value = customCode.value; }
	});

	if (prefSel) {
		prefSel.addEventListener('change', function () {
			syncCurrencyFromPreference();
			syncPaymentTermFromPreference();
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
		if (termSel && (!termSel.value || termSel.value === '') && prefSel) {
			syncPaymentTermFromPreference();
		}
		siUpdateDueDatePreview();
	}
})();
</script>
