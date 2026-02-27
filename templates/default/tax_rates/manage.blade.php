{{-- /*
* Script: manage.tpl
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
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['tax_rates'] ?? 'Tax Rates' }}</h3>
			</div>
			<div class="col-auto">
				<a href="index.php?module=tax_rates&view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['add_new_tax_rate'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if($taxes == null)
		<div class="alert alert-info mb-0">{{ $LANG['no_tax_rates'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.tax_rates.manage_js')
@endif
	</div>
</div>
