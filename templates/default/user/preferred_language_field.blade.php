{{-- Shared: preferred UI language (blank = organisation / system default) --}}
@php
	$prefVal = $userPreferredValue ?? '';
@endphp
<div class="mb-3">
	<label class="form-label">{{ $LANG['language'] ?? '' }}</label>
	<select name="preferred_language" class="form-select">
		<option value="" @if($prefVal === '') selected @endif>{{ $LANG['ui_language_domain_default'] ?? '' }}</option>
		@foreach(($userUiLanguageList ?? []) as $lng)
		<option value="{{ $lng->shortname }}" @if($prefVal === (string) $lng->shortname) selected @endif>{{ $lng->name }} ({{ $lng->shortname }})</option>
		@endforeach
	</select>
	<div class="form-hint small">{{ $LANG['ui_language_account_hint'] ?? '' }}</div>
</div>
