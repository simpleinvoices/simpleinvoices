{{-- /*
* View: manage (Blade)
* 	 Invoice Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/ --}}
<div class="card">
@if(($preferences ?? null) == null)
	<div class="alert alert-info mb-0">{{ $LANG['no_preferences'] ?? '' }}.</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.product_attribute.manage_js')
@endif
</div>
