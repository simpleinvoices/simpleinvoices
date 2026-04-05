{{-- /*
* Script: backup_database.tpl
* 	 Database backup template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_backup_db">
	<input type="hidden" name="op" value="backup_db" />
	<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
</form>

<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_view_backup">
	<input type="hidden" name="op" value="view_backup" />
	<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
</form>

<div class="card">
	<div class="card-body">

		@if(!empty($backupErrors))
			<div class="alert alert-danger mb-3">
				@foreach($backupErrors as $error)
					<div>{{ $error }}</div>
				@endforeach
			</div>
		@endif

		<p class="text-secondary">{{ $LANG['backup_howto'] ?? '' }}</p>

		<div>
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="{{ $LANG['database_backup'] ?? 'Database backup' }}"><i class="ti ti-info-circle me-1"></i>{{ $LANG['more_info'] ?? '' }}</a>
		</div>

	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=options&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<div class="ms-auto d-flex gap-2">
				@if(!empty($formattedSQL))
				<button type="submit" form="form_backup_db" class="btn btn-outline-secondary">
					<i class="ti ti-download me-1"></i>{{ $LANG['backup_database_now'] ?? 'Download' }}
				</button>
				@endif
				<button type="submit" form="form_view_backup" class="btn btn-outline-secondary">
					<i class="ti ti-eye me-1"></i>{{ $LANG['view_in_browser'] ?? 'View in Browser' }}
				</button>
				<button type="submit" form="form_backup_db" class="btn btn-primary">
					<i class="ti ti-download me-1"></i>{{ $LANG['backup_database_now'] ?? 'Download' }}
				</button>
			</div>
		</div>
	</div>

	@if(!empty($formattedSQL))
	<div class="card-body border-top overflow-auto" style="max-height: 70vh;">
		{!! $formattedSQL !!}
	</div>
	@endif
</div>
