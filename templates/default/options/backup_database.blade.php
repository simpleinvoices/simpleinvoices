{{-- Database backup page --}}

<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_backup_db">
	<input type="hidden" name="op" value="backup_db" />
	<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
</form>

<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_export_json">
	<input type="hidden" name="op" value="export_json" />
	<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
</form>

{{-- Hidden textarea holding raw SQL for clipboard copy --}}
@if(!empty($rawSQL))
<textarea id="sql-raw-content" class="visually-hidden" aria-hidden="true" readonly>{{ $rawSQL }}</textarea>
@endif

{{-- ── Alerts ─────────────────────────────────────────────────────────── --}}
@if(!empty($backupErrors))
<div class="alert alert-danger mb-4">
	<div class="d-flex">
		<i class="ti ti-alert-circle me-2 fs-4 flex-shrink-0"></i>
		<div>
			@foreach($backupErrors as $error)
				<div>{{ $error }}</div>
			@endforeach
		</div>
	</div>
</div>
@endif

@if(!empty($importSuccess))
<div class="alert alert-success mb-4">
	<div class="d-flex">
		<i class="ti ti-circle-check me-2 fs-4 flex-shrink-0"></i>
		<div>{{ $LANG['import_success'] ?? 'Data imported successfully. All tables have been restored from the uploaded JSON file.' }}</div>
	</div>
</div>
@endif

{{-- ── SQL Backup card ─────────────────────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-database me-2"></i>{{ $LANG['backup_database'] ?? 'Database Backup' }}</h3>
	</div>

	<div class="card-body">
		<div class="row align-items-center g-3">
			<div class="col">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-md bg-primary-lt rounded-3">
						<i class="ti ti-database-export text-primary"></i>
					</span>
					<div>
						<div class="fw-semibold">{{ $LANG['download_sql_backup'] ?? 'Download SQL backup' }}</div>
						<div class="text-secondary small">
							{{ $LANG['download_sql_backup_desc'] ?? 'Exports your full database as a .sql file for the current database type (MySQL, PostgreSQL, or SQLite).' }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-auto d-flex gap-2">
				@if(!empty($formattedSQL))
				<button type="button" class="btn btn-outline-secondary" id="btn-copy-sql"
						onclick="copySQLToClipboard(this)" title="Copy SQL to clipboard">
					<i class="ti ti-copy me-1"></i>{{ $LANG['copy_sql'] ?? 'Copy SQL' }}
				</button>
				@endif
				<button type="submit" form="form_backup_db" class="btn btn-primary">
					<i class="ti ti-download me-1"></i>{{ $LANG['download_backup'] ?? 'Download Backup' }}
				</button>
			</div>
		</div>
	</div>

	@if(!empty($formattedSQL))
	<div class="accordion accordion-flush border-top" id="sql-backup-accordion">
		<div class="accordion-item">
			<h2 class="accordion-header" id="sql-backup-heading">
				<button class="accordion-button collapsed" type="button"
						data-bs-toggle="collapse" data-bs-target="#sql-backup-collapse"
						aria-expanded="false" aria-controls="sql-backup-collapse">
					<i class="ti ti-code me-2 text-secondary"></i>
					{{ $LANG['view_sql'] ?? 'View SQL' }}
				</button>
			</h2>
			<div id="sql-backup-collapse" class="accordion-collapse collapse"
				 aria-labelledby="sql-backup-heading" data-bs-parent="#sql-backup-accordion">
				<div class="accordion-body p-0">
					<div class="overflow-auto p-3" style="max-height:65vh;">
						{!! $formattedSQL !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	<div class="card-footer">
		<a href="./index.php?module=options&amp;view=index" class="btn btn-link">
			<i class="ti ti-arrow-left me-1"></i>{{ $LANG['cancel'] ?? 'Back' }}
		</a>
	</div>
</div>

{{-- ── Cross-database JSON Export card ────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-file-type-json me-2"></i>{{ $LANG['json_export'] ?? 'Cross-Database Export (JSON)' }}</h3>
	</div>

	<div class="card-body">
		<div class="row align-items-center g-3">
			<div class="col">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-md bg-green-lt rounded-3">
						<i class="ti ti-transfer text-green"></i>
					</span>
					<div>
						<div class="fw-semibold">{{ $LANG['download_json_export'] ?? 'Download JSON data export' }}</div>
						<div class="text-secondary small">
							{{ $LANG['download_json_export_desc'] ?? 'Exports all data as a database-independent JSON file. Use this to migrate data between MySQL, PostgreSQL, and SQLite.' }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-auto">
				<button type="submit" form="form_export_json" class="btn btn-success">
					<i class="ti ti-download me-1"></i>{{ $LANG['download_json'] ?? 'Download JSON' }}
				</button>
			</div>
		</div>

		<div class="mt-3 p-3 bg-blue-lt rounded">
			<div class="d-flex gap-2">
				<i class="ti ti-info-circle text-blue flex-shrink-0 mt-1"></i>
				<div class="text-secondary small">
					<strong>{{ $LANG['how_to_migrate'] ?? 'How to migrate to a different database:' }}</strong>
					<ol class="mb-0 mt-1 ps-3">
						<li>{{ $LANG['migrate_step1'] ?? 'Export this JSON file from your current database.' }}</li>
						<li>{{ $LANG['migrate_step2'] ?? 'Configure the new database connection in config/config.php.' }}</li>
						<li>{{ $LANG['migrate_step3'] ?? 'Run the installer to create the schema on the new database.' }}</li>
						<li>{{ $LANG['migrate_step4'] ?? 'Use the Import section below to load your data into the new database.' }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- ── Cross-database JSON Import card ────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-file-upload me-2"></i>{{ $LANG['json_import'] ?? 'Cross-Database Import (JSON)' }}</h3>
	</div>

	<form method="post" action="index.php?module=options&amp;view=backup_database"
		  enctype="multipart/form-data" id="form_import_json"
		  onsubmit="return confirmImport()">
		<input type="hidden" name="op" value="import_json" />
		<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />

		<div class="card-body">
			<div class="alert alert-warning mb-4">
				<div class="d-flex gap-2">
					<i class="ti ti-alert-triangle flex-shrink-0 mt-1"></i>
					<div>
						<strong>{{ $LANG['warning'] ?? 'Warning' }}:</strong>
						{{ $LANG['import_warning'] ?? 'Importing will permanently replace ALL existing data in this database with the contents of the uploaded file. This cannot be undone. Take a backup first.' }}
					</div>
				</div>
			</div>

			<div class="mb-3">
				<label class="form-label" for="json_file">
					{{ $LANG['select_json_file'] ?? 'Select JSON export file' }}
				</label>
				<input type="file"
					   class="form-control"
					   id="json_file"
					   name="json_file"
					   accept=".json,application/json"
					   required />
				<div class="form-hint">
					{{ $LANG['json_file_hint'] ?? 'Select a .json file previously exported from Simple Invoices using the export above.' }}
				</div>
			</div>
		</div>

		<div class="card-footer d-flex justify-content-between align-items-center">
			<a href="./index.php?module=options&amp;view=index" class="btn btn-link">
				<i class="ti ti-arrow-left me-1"></i>{{ $LANG['cancel'] ?? 'Back' }}
			</a>
			<button type="submit" class="btn btn-danger">
				<i class="ti ti-database-import me-1"></i>{{ $LANG['import_data'] ?? 'Import Data' }}
			</button>
		</div>
	</form>
</div>

<script>
function copySQLToClipboard(btn) {
	var textarea = document.getElementById('sql-raw-content');
	if (!textarea) return;

	var originalHTML = btn.innerHTML;

	navigator.clipboard.writeText(textarea.value).then(function () {
		btn.innerHTML = '<i class="ti ti-check me-1"></i>' + @json($LANG['copied'] ?? 'Copied!');
		btn.classList.replace('btn-outline-secondary', 'btn-success');
		setTimeout(function () {
			btn.innerHTML = originalHTML;
			btn.classList.replace('btn-success', 'btn-outline-secondary');
		}, 2000);
	}).catch(function () {
		textarea.classList.remove('visually-hidden');
		textarea.select();
		try { document.execCommand('copy'); } catch (e) {}
		textarea.classList.add('visually-hidden');
	});
}

function confirmImport() {
	return confirm(
		@json($LANG['confirm_import'] ?? 'This will permanently replace ALL data in the current database with the uploaded file.\n\nAre you sure you want to continue?')
	);
}
</script>
