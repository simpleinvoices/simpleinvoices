{{-- /*
* View: manage (Blade)
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
@if(($number_of_crons['count'] ?? 0) == 0)
	<div class="alert alert-info mb-0">
		{{ $LANG['no_crons'] ?? '' }}
	</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.cron.manage_js')
@endif
</div>
