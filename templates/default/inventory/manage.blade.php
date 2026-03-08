{{-- /*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
@if(($number_of_rows['count'] ?? 0) == 0)
	<div class="alert alert-info mb-0">{{ $LANG['no_inventory_movements'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.inventory.manage_js')
@endif
</div>
