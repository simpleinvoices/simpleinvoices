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
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['inventory'] ?? 'Inventory' }}</h3>
			</div>
			<div class="col-auto">
				<a href="index.php?module=inventory&amp;view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['new_inventory_movement'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if(($number_of_rows['count'] ?? 0) == 0)
		<div class="alert alert-info mb-0">{{ $LANG['no_inventory_movements'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.inventory.manage_js')
@endif
	</div>
</div>
