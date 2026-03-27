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
			<p class="mb-3">{{ $LANG['note'] ?? '' }}: {{ $LANG['backup_note_to_file'] ?? '' }}</p>

			@if(!($backupDirectoryWritable ?? false))
				<div class="alert alert-warning mb-3">
					{{ $LANG['fwrite_error'] ?? 'Backup directory is not writable.' }}
				</div>
			@endif

			<form method="post" action="index.php?module=options&amp;view=backup_database" class="d-inline">
				<input type="hidden" name="op" value="backup_db" />
				<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
				<button type="submit" class="btn btn-primary" @if(!($backupDirectoryWritable ?? false)) disabled @endif>
					<i class="ti ti-database-export me-1"></i>{{ $LANG['backup_database_now'] ?? '' }}
				</button>
			</form>
		</div>

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

		<div class="si_help_div">
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="{{ $LANG['database_backup'] ?? '' }}">
				<img src="./images/common/important.png" alt="" />{{ $LANG['more_info'] ?? '' }}
			</a>
			<a class="cluetip ms-3" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite" title="{{ $LANG['fwrite_error'] ?? '' }}">
				<img src="./images/common/help-small.png" alt="" />{{ $LANG['fwrite_error'] ?? '' }}
			</a>
		</div>
	</div>
</div>
