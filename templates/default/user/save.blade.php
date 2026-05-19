{{-- /*
* View: save (Blade)
* 	User save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/ --}}

@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? 'User saved' : 'User not saved',
	'message' => outhtml(($saved == true) ? ($LANG['save_user_success'] ?? '') : (!empty($saveError ?? null) ? $saveError : ($LANG['save_user_failure'] ?? '')))
])
@if(empty($saved ?? false))
	<div class="mt-3 d-flex flex-wrap gap-2">
		<a href="javascript:history.back()" class="btn btn-outline-secondary">
			<i class="ti ti-arrow-left me-1"></i>{{ $LANG['go_back'] ?? 'Go back' }}
		</a>
		<a href="index.php?module=user&view=manage" class="btn btn-outline-primary">
			<i class="ti ti-users me-1"></i>{{ $LANG['manage_users'] ?? 'Manage users' }}
		</a>
	</div>
@endif
