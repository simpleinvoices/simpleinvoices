{{-- /*
* View: manage (Blade)
* 	 Products manage template
*
*
* License:
*	 GPL v3 or above
*/ --}}
<div class="card">
@if(($number_of_rows['count'] ?? 0) == 0 )
	<div class="alert alert-info mb-0">{{ $LANG['no_products'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.products.manage_js')
@endif
</div>
