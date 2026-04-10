{{-- /*
* View: manage (Blade)
* 	 Tax Rates manage template
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
@if($taxes == null)
	<div class="alert alert-info mb-0">{{ $LANG['no_tax_rates'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.tax_rates.manage_js')
@endif
</div>
