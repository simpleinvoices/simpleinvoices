{{-- /*
* Script: save.tpl
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

<div class="card">
	<div class="card-body">
		@if($saved == true)
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_user_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_user_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
@if(post('cancel') == null)
	<meta http-equiv="refresh" content="2;URL=index.php?module=user&view=manage" />
@else
	<meta http-equiv="refresh" content="0;URL=index.php?module=user&view=manage" />
@endif
