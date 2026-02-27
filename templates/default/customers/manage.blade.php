{{-- /*
* Script: manage.tpl
* 	 Customer manage template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['customers'] ?? 'Customers' }}</h3>
			</div>
			<div class="col-auto">
				<a href="./index.php?module=customers&amp;view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['customer_add'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if(($number_of_customers['count'] ?? 0) == 0)
		<div class="alert alert-info mb-0">
			{{ $LANG['no_customers'] ?? '' }}
		</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.customers.manage_js')
@endif
	</div>
</div>
