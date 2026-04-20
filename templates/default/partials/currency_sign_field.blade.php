@php
    $currencySignCurrentValue = $currencySignCurrentValue ?? '';
    $matched = CurrencySignHelper::findPresetForStored($currencySignCurrentValue);
    $isCustom = $matched === null;
@endphp
<input type="hidden" name="{{ $currencySignFieldName }}" id="si_currency_sign_hidden" value="{{ $currencySignCurrentValue }}" />
<div class="mb-3">
	<label class="form-label" for="si_currency_sign_select">{{ $LANG['currency_sign'] ?? '' }}
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}"><i class="ti ti-help"></i></a>
	</label>
	<select id="si_currency_sign_select" class="form-select" autocomplete="off">
		@foreach(CurrencySignHelper::getPresetGroups() as $g)
			<optgroup label="{{ $g['label'] }}">
				@foreach($g['presets'] as $p)
					<option value="{{ $p['value'] }}"@if($matched !== null && $matched['value'] === $p['value']) selected="selected"@endif>{{ $p['label'] }}</option>
				@endforeach
			</optgroup>
		@endforeach
		<option value="__custom__"@if($isCustom) selected="selected"@endif>{{ $LANG['currency_sign_custom'] ?? 'Custom…' }}</option>
	</select>
</div>
<div id="si_currency_sign_custom_wrap" class="mb-3 {{ $isCustom ? '' : 'd-none' }}">
	<label class="form-label" for="si_currency_sign_custom">{{ $LANG['currency_sign_custom'] ?? 'Custom…' }}</label>
	<input type="text" id="si_currency_sign_custom" class="form-control" value="{{ $isCustom ? $currencySignCurrentValue : '' }}" autocomplete="off" />
</div>
<div class="mb-0">
	<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{{ $LANG['currency_sign'] ?? '' }}">{{ $LANG['currency_sign_non_dollar'] ?? '' }} <i class="ti ti-help"></i></a>
</div>
<script>
(function () {
	var sel = document.getElementById('si_currency_sign_select');
	var hidden = document.getElementById('si_currency_sign_hidden');
	var wrap = document.getElementById('si_currency_sign_custom_wrap');
	var custom = document.getElementById('si_currency_sign_custom');
	if (!sel || !hidden || !wrap || !custom) {
		return;
	}
	function syncFromUi() {
		if (sel.value === '__custom__') {
			wrap.classList.remove('d-none');
			hidden.value = custom.value;
		} else {
			wrap.classList.add('d-none');
			hidden.value = sel.value;
		}
	}
	sel.addEventListener('change', syncFromUi);
	custom.addEventListener('input', function () {
		if (sel.value === '__custom__') {
			hidden.value = custom.value;
		}
	});
	custom.addEventListener('change', function () {
		if (sel.value === '__custom__') {
			hidden.value = custom.value;
		}
	});
	var form = sel.closest('form');
	if (form) {
		form.addEventListener('submit', syncFromUi);
	}
	syncFromUi();
})();
</script>
