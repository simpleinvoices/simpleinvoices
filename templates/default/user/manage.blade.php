{{-- /*
* View: manage (Blade)
* 	Biller manage template
*
*
* License:
*	 GPL v3 or above
*/ --}}
@if(!empty($userSavedOp))
	<div class="alert alert-success mb-3">
		<i class="ti ti-check me-1"></i>
		@if($userSavedOp === 'insert_user')
			User was added successfully.
		@else
			User was updated successfully.
		@endif
	</div>
@endif
<div class="card">
@if(($number_of_rows['count'] ?? 0) == 0)
	<div class="alert alert-info mb-0">{{ $LANG['no_users'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.user.manage_js')
@endif
</div>
