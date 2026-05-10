@php
    $currencySignCurrentValue  = $currencySignCurrentValue ?? '';
    $currencyCodeFieldName     = $currencyCodeFieldName ?? null;
    $currencyCodeCurrentValue  = $currencyCodeCurrentValue ?? '';
    $currencyIdFieldName       = $currencyIdFieldName ?? null;
    $currencyIdCurrentValue    = $currencyIdCurrentValue ?? '';

    // Use DB currencies if available, otherwise fall back to CurrencySignHelper presets
    $dbCurrencies = $currencies ?? [];
    $useDbCurrencies = !empty($dbCurrencies);

    if ($useDbCurrencies) {
        // Find matched currency in DB list by sign+code
        $matchedCurrency = null;
        foreach ($dbCurrencies as $c) {
            $dbSign = CurrencySignHelper::forDisplay($c['currency_sign'] ?? '');
            $dbCode = $c['currency_code'] ?? '';
            $currentSign = CurrencySignHelper::forDisplay($currencySignCurrentValue);
            if ($dbSign === $currentSign && ($currencyCodeCurrentValue === '' || $dbCode === $currencyCodeCurrentValue)) {
                $matchedCurrency = $c;
                break;
            }
        }
        $isCustom = ($matchedCurrency === null) && ($currencySignCurrentValue !== '' || $currencyCodeCurrentValue !== '');
    } else {
        $matched = CurrencySignHelper::findPresetForStored($currencySignCurrentValue, $currencyCodeCurrentValue);
        $isCustom = $matched === null;
    }
@endphp
<input type="hidden" name="{{ $currencySignFieldName }}" id="si_currency_sign_hidden" value="{{ $currencySignCurrentValue }}" />
@if($currencyCodeFieldName)
<input type="hidden" name="{{ $currencyCodeFieldName }}" id="si_currency_code_hidden" value="{{ $currencyCodeCurrentValue }}" />
@endif
@if($currencyIdFieldName)
<input type="hidden" name="{{ $currencyIdFieldName }}" id="si_currency_id_hidden" value="{{ $currencyIdCurrentValue }}" />
@endif
<div class="mb-3">
	<label class="form-label" for="si_currency_sign_select">{{ $LANG['currency_sign'] ?? '' }}
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a>
	</label>
	<select id="si_currency_sign_select" class="form-select" autocomplete="off">
		@if($useDbCurrencies)
			@foreach($dbCurrencies as $c)
				<option value="{{ CurrencySignHelper::forDisplay($c['currency_sign'] ?? '') }}"
					data-code="{{ $c['currency_code'] ?? '' }}"
					data-position="{{ $c['currency_position'] ?? 'left' }}"
					data-id="{{ $c['id'] ?? '' }}"
					@if($matchedCurrency !== null && $matchedCurrency['id'] == ($c['id'] ?? '')) selected="selected"@endif
				>{{ $c['currency_code'] ?? '' }} - {{ CurrencySignHelper::forDisplay($c['currency_sign'] ?? '') }}</option>
			@endforeach
		@else
			@foreach(CurrencySignHelper::getPresetGroups() as $g)
				<optgroup label="{{ $g['label'] }}">
					@foreach($g['presets'] as $p)
						<option value="{{ $p['value'] }}" data-code="{{ $p['code'] ?? '' }}" data-position="{{ $p['position'] ?? 'left' }}"@if($matched !== null && $matched['value'] === $p['value'] && ($matched['code'] ?? '') === ($p['code'] ?? '')) selected="selected"@endif>{{ $p['label'] }}</option>
					@endforeach
				</optgroup>
			@endforeach
		@endif
		<option value="__custom__"@if($isCustom) selected="selected"@endif>{{ $LANG['currency_sign_custom'] ?? 'Custom…' }}</option>
	</select>
</div>
<div id="si_currency_sign_custom_wrap" class="mb-3 {{ $isCustom ? '' : 'd-none' }}">
	<div class="row g-2">
		<div class="col">
			<label class="form-label" for="si_currency_sign_custom">{{ $LANG['currency_sign_custom'] ?? 'Custom symbol' }}</label>
			<input type="text" id="si_currency_sign_custom" class="form-control" value="{{ $isCustom ? $currencySignCurrentValue : '' }}" autocomplete="off" placeholder="e.g. Fr." />
		</div>
		@if($currencyCodeFieldName)
		<div class="col">
			<label class="form-label" for="si_currency_code_custom">{{ $LANG['currency_code'] ?? 'Currency code' }}</label>
			<input type="text" id="si_currency_code_custom" class="form-control" value="{{ $isCustom ? $currencyCodeCurrentValue : '' }}" autocomplete="off" placeholder="e.g. XYZ" maxlength="10" />
		</div>
		@endif
	</div>
</div>
<script>
(function () {
	var sel        = document.getElementById('si_currency_sign_select');
	var signHidden = document.getElementById('si_currency_sign_hidden');
	var codeHidden = document.getElementById('si_currency_code_hidden');
	var idHidden   = document.getElementById('si_currency_id_hidden');
	var wrap       = document.getElementById('si_currency_sign_custom_wrap');
	var customSign = document.getElementById('si_currency_sign_custom');
	var customCode = document.getElementById('si_currency_code_custom');
	if (!sel || !signHidden || !wrap || !customSign) { return; }

	function syncAll() {
		if (sel.value === '__custom__') {
			wrap.classList.remove('d-none');
			signHidden.value = customSign.value;
			if (codeHidden && customCode) { codeHidden.value = customCode.value; }
			if (idHidden) { idHidden.value = ''; }
		} else {
			wrap.classList.add('d-none');
			signHidden.value = sel.value;
			if (codeHidden) {
				var opt = sel.options[sel.selectedIndex];
				codeHidden.value = opt ? (opt.getAttribute('data-code') || '') : '';
			}
			if (idHidden) {
				var opt2 = sel.options[sel.selectedIndex];
				idHidden.value = opt2 ? (opt2.getAttribute('data-id') || '') : '';
			}
		}
	}

	function syncSignOnly() {
		if (sel.value === '__custom__') {
			wrap.classList.remove('d-none');
			signHidden.value = customSign.value;
		} else {
			wrap.classList.add('d-none');
			signHidden.value = sel.value;
		}
	}

	sel.addEventListener('change', function () {
		syncAll();
	});
	customSign.addEventListener('input', function () {
		if (sel.value === '__custom__') { signHidden.value = customSign.value; }
	});
	customSign.addEventListener('change', function () {
		if (sel.value === '__custom__') { signHidden.value = customSign.value; }
	});
	if (customCode && codeHidden) {
		customCode.addEventListener('input', function () {
			if (sel.value === '__custom__') { codeHidden.value = customCode.value; }
		});
		customCode.addEventListener('change', function () {
			if (sel.value === '__custom__') { codeHidden.value = customCode.value; }
		});
	}
	var form = sel.closest('form');
	if (form) { form.addEventListener('submit', syncAll); }

	syncSignOnly();
	// Populate code from preset only when the stored code is empty (first-time setup)
	if (codeHidden && !codeHidden.value && sel.value !== '__custom__') {
		var opt = sel.options[sel.selectedIndex];
		if (opt) { codeHidden.value = opt.getAttribute('data-code') || ''; }
	}
	// Populate id from preset when empty
	if (idHidden && !idHidden.value && sel.value !== '__custom__') {
		var opt3 = sel.options[sel.selectedIndex];
		if (opt3) { idHidden.value = opt3.getAttribute('data-id') || ''; }
	}
})();
</script>
