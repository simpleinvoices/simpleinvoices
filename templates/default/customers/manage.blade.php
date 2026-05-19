{{-- /*
* View: manage (Blade)
* 	 Customer manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
@if(($number_of_customers['count'] ?? 0) == 0)
	<div class="alert alert-info mb-0">
		{{ $LANG['no_customers'] ?? '' }}
	</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.customers.manage_js')
@endif
</div>
