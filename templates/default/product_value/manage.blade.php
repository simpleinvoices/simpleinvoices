{{-- /*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* License:
*	 GPL v2 or above
*/ --}}
<div class="card">
	<div class="card-body">
@if(($preferences ?? null) == null)
		<div class="alert alert-info mb-0">{{ $LANG['no_preferences'] ?? '' }}.</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.product_value.manage_js')
@endif
	</div>
</div>
