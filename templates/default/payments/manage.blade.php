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
	@if(get('id'))
		@if($payments == null)
			<div class="alert alert-info mb-0">
				{{ $LANG['no_payments_invoice'] ?? '' }}
			</div>
		@else
			<div id="manageGrid"></div>
			@include('templates.default.payments.manage_js')
			<div class="mt-2 text-start">
				<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{{ $LANG['wheres_the_edit_button'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['wheres_the_edit_button'] ?? "Where's the Edit button?" }}</a>
			</div>
		@endif

	@elseif(get('c_id'))
		@if($payments == null)
			<div class="alert alert-info mb-0">
				{{ $LANG['no_payments_customer'] ?? '' }}
			</div>
		@else
			<div id="manageGrid"></div>
			@include('templates.default.payments.manage_js')
			<div class="mt-2 text-start">
				<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{{ $LANG['wheres_the_edit_button'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['wheres_the_edit_button'] ?? "Where's the Edit button?" }}</a>
			</div>
		@endif

	@else
		@if($payments == null)
			<div class="alert alert-info mb-0">
				{{ $LANG['no_payments'] ?? '' }}
			</div>
		@else
			<div id="manageGrid"></div>
			@include('templates.default.payments.manage_js')
			<div class="mt-2 text-start">
				<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{{ $LANG['wheres_the_edit_button'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['wheres_the_edit_button'] ?? "Where's the Edit button?" }}</a>
			</div>
		@endif
	@endif
</div>
