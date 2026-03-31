<div class="card">
	<div class="card-body">
		@if($saved)
			<div class="alert alert-success" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_defaults_success'] ?? '') !!}
			</div>
		@else
			<div class="alert alert-warning" role="alert">
				<div class="alert-icon"></div>
				{!! outhtml($LANG['save_defaults_failure'] ?? '') !!}
			</div>
		@endif
	</div>
</div>
<meta http-equiv="refresh" content="2;URL=index.php?module=system_defaults&amp;view=manage" />
