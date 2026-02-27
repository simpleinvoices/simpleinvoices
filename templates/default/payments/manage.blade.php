{{-- /*
* Script: manage.tpl
* 	 Payments manage template
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
				<h3 class="card-title mb-0">{{ $LANG['payments'] ?? 'Payments' }}</h3>
			</div>
			<div class="col-auto">
				<a href="./index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="btn btn-primary"><i class="ti ti-plus me-1"></i>{{ $LANG['process_payment'] ?? '' }}</a>
				@if($smarty->get->id ?? null)
				<a href="./index.php?module=payments&amp;view=process&amp;id={{ urlencode($smarty->get->id ?? '') }}&amp;op=pay_selected_invoice" class="btn btn-outline-success ms-1"><i class="ti ti-cash me-1"></i>{{ $LANG['payments_filtered_invoice'] ?? '' }}</a>
				@endif
			</div>
		</div>
	</div>
	<div class="card-body">
		@if($smarty->get->id ?? null)
			@if($payments == null)
				<div class="alert alert-info mb-0">
					{{ $LANG['no_payments_invoice'] ?? '' }}
				</div>
			@else
				<div id="manageGrid"></div>
				@include('templates.default.payments.manage_js')
			@endif

		@elseif($smarty->get->c_id ?? null)
			@if($payments == null)
				<div class="alert alert-info mb-0">
					{{ $LANG['no_payments_customer'] ?? '' }}
				</div>
			@else
				<div id="manageGrid"></div>
				@include('templates.default.payments.manage_js')
			@endif

		@else
			@if($payments == null)
				<div class="alert alert-info mb-0">
					{{ $LANG['no_payments'] ?? '' }}
				</div>
			@else
				<div id="manageGrid"></div>
				@include('templates.default.payments.manage_js')
			@endif
		@endif

		<div class="mt-3">
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{{ $LANG['wheres_the_edit_button'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['wheres_the_edit_button'] ?? "Where's the Edit button?" }}</a>
		</div>
	</div>
</div>
