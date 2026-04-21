@php
    $currencySignCurrentValue  = $currencySignCurrentValue ?? '';
    $currencyCodeFieldName     = $currencyCodeFieldName ?? null;
    $currencyCodeCurrentValue  = $currencyCodeCurrentValue ?? '';
    $matched = CurrencySignHelper::findPresetForStored($currencySignCurrentValue, $currencyCodeCurrentValue);
    $isCustom = $matched === null;
@endphp
<input type="hidden" name="{{ $currencySignFieldName }}" id="si_currency_sign_hidden" value="{{ $currencySignCurrentValue }}" />
@if($currencyCodeFieldName)
<input type="hidden" name="{{ $currencyCodeFieldName }}" id="si_currency_code_hidden" value="{{ $currencyCodeCurrentValue }}" />
@endif
<div class="mb-3">
	<label class="form-label" for="si_currency_sign_select">{{ $LANG['currency_sign'] ?? '' }}
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a>
	</label>
	<select id="si_currency_sign_select" class="form-select" autocomplete="off">
		@foreach(CurrencySignHelper::getPresetGroups() as $g)
			<optgroup label="{{ $g['label'] }}">
				@foreach($g['presets'] as $p)
					<option value="{{ $p['value'] }}" data-code="{{ $p['code'] ?? '' }}"@if($matched !== null && $matched['value'] === $p['value'] && ($matched['code'] ?? '') === ($p['code'] ?? '')) selected="selected"@endif>{{ $p['label'] }}</option>
				@endforeach
			</optgroup>
		@endforeach
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
<div class="mb-0">
	<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">{{ $LANG['currency_sign_non_dollar'] ?? '' }} <i class="ti ti-help"></i></a>
</div>
<script>
(function () {
	var sel        = document.getElementById('si_currency_sign_select');
	var signHidden = document.getElementById('si_currency_sign_hidden');
	var codeHidden = document.getElementById('si_currency_code_hidden');
	var wrap       = document.getElementById('si_currency_sign_custom_wrap');
	var customSign = document.getElementById('si_currency_sign_custom');
	var customCode = document.getElementById('si_currency_code_custom');
	if (!sel || !signHidden || !wrap || !customSign) { return; }

	function syncAll() {
		if (sel.value === '__custom__') {
			wrap.classList.remove('d-none');
			signHidden.value = customSign.value;
			if (codeHidden && customCode) { codeHidden.value = customCode.value; }
		} else {
			wrap.classList.add('d-none');
			signHidden.value = sel.value;
			if (codeHidden) {
				var opt = sel.options[sel.selectedIndex];
				codeHidden.value = opt ? (opt.getAttribute('data-code') || '') : '';
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

	sel.addEventListener('change', syncAll);
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
})();
</script>
