{{-- /*
* View: manage (Blade)
*   Currencies manage template
*
* License:
*   GPL v3 or above
*/ --}}

<div class="card">
	@if($currencies == null || count($currencies) == 0)
		<div class="alert alert-info mb-0">{{ $LANG['no_items'] ?? 'No items found.' }}</div>
	@else
		<div id="manageGrid"></div>
		@include('templates.default.currencies.manage_js')
	@endif
</div>
