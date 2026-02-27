{{-- /*
* Script: manage.tpl
* 	 Manage payment types template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
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
				<h3 class="card-title mb-0">{{ $LANG['payment_types'] ?? 'Payment Types' }}</h3>
			</div>
			<div class="col-auto">
				<a href="./index.php?module=payment_types&amp;view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['add_new_payment_type'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if($paymentTypes==null )
		<div class="alert alert-info mb-0">{{ $LANG['no_payment_types'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.payment_types.manage_js')
@endif
	</div>
</div>
