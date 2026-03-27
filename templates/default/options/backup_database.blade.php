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
			<p class="mb-0">{{ $LANG['note'] ?? '' }}: {{ $LANG['backup_note_to_file'] ?? '' }}</p>
		</div>

			@if(!($backupDirectoryWritable ?? false))
				<div class="alert alert-warning mb-3">
					{{ $LANG['fwrite_error'] ?? 'Backup directory is not writable.' }}
				</div>
			@endif

			<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_backup_db">
				<input type="hidden" name="op" value="backup_db" />
				<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
			</form>

		@if(!empty($backupResults))
			<div class="card border mb-4">
				<div class="card-body">
					<h5 class="mb-3">{{ $LANG['database_backup'] ?? 'Database backup' }}</h5>
					@if(!empty($backupFile))
						<p><strong>File:</strong> {{ $backupFile }}</p>
					@endif
					<div class="table-responsive">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>Table</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($backupResults as $tableName)
									<tr>
										<td>{{ $tableName }}</td>
										<td>Backed up successfully</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endif

		<div class="mb-3">
			<a class="cluetip btn btn-outline-secondary btn-sm me-2" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="{{ $LANG['database_backup'] ?? '' }}"><i class="ti ti-info-circle me-1"></i>{{ $LANG['more_info'] ?? '' }}</a>
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite" title="{{ $LANG['fwrite_error'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['fwrite_error'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=options&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" form="form_backup_db" class="btn btn-primary ms-auto" @if(!($backupDirectoryWritable ?? false)) disabled @endif>
				<i class="ti ti-database-export me-1"></i>{{ $LANG['backup_database_now'] ?? '' }}
			</button>
		</div>
	</div>
</div>
