{{-- /*
* Script: manage.tpl
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
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['manage_product_attributes'] ?? '' }}</h3>
			</div>
			<div class="col-auto">
				<a href="index.php?module=product_attribute&view=add" class="btn btn-primary"><i class="ti ti-plus me-1"></i>{{ $LANG['add_product_attribute'] ?? '' }}</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if(($preferences ?? null) == null)
		<div class="alert alert-info mb-0">{{ $LANG['no_preferences'] ?? '' }}.</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.product_attribute.manage_js')
@endif
	</div>
</div>
