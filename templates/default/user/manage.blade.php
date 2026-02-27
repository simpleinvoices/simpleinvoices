{{-- /*
* Script: manage.tpl
* 	Biller manage template
*
*
* License:
*	 GPL v3 or above
*/ --}}
<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['users'] ?? 'Users' }}</h3>
			</div>
			<div class="col-auto">
				<a href="index.php?module=user&view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['user_add'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if(($number_of_rows['count'] ?? 0) == 0)
		<div class="alert alert-info mb-0">{{ $LANG['no_users'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.user.manage_js')
@endif
	</div>
</div>
