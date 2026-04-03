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

<div class="card">
	<div class="card-body">
		@if(!empty($backupErrors))
			<div class="alert alert-danger">
				@foreach($backupErrors as $error)
					<div>{{ $error }}</div>
				@endforeach
			</div>
		@endif

		@if(!empty($backupMessages))
			<div class="alert alert-success">
				@foreach($backupMessages as $message)
					<div>{{ $message }}</div>
				@endforeach
			</div>
		@endif

		<div class="mb-4">
			<p>{{ $LANG['backup_howto'] ?? '' }}</p>
		</div>

			<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_backup_db">
				<input type="hidden" name="op" value="backup_db" />
				<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
			</form>

		<div class="mb-3">
			<a class="cluetip btn btn-outline-secondary me-2" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="{{ $LANG['database_backup'] ?? 'Database backup' }}"><i class="ti ti-info-circle me-1"></i>{{ $LANG['more_info'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=options&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" form="form_backup_db" class="btn btn-primary ms-auto">
				<i class="ti ti-download me-1"></i>{{ $LANG['backup_database_now'] ?? '' }}
			</button>
		</div>
	</div>
</div>
