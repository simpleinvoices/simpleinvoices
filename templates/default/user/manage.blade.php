{{-- /*
* Script: manage.tpl
* 	Biller manage template
*
*
* License:
*	 GPL v3 or above
*/ --}}
<div class="card">
	<div class="card-body">
@if(($number_of_rows['count'] ?? 0) == 0)
		<div class="alert alert-info mb-0">{{ $LANG['no_users'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.user.manage_js')
@endif
	</div>
</div>
