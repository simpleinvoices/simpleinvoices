{{-- Database backup page --}}

<form method="post" action="index.php?module=options&amp;view=backup_database" id="form_backup_db">
	<input type="hidden" name="op" value="backup_db" />
	<input type="hidden" name="csrfprotectionbysr" value="{{ $backupActionToken ?? '' }}" />
</form>

{{-- Hidden textarea holding raw SQL for clipboard copy --}}
@if(!empty($rawSQL))
<textarea id="sql-raw-content" class="visually-hidden" aria-hidden="true" readonly>{{ $rawSQL }}</textarea>
@endif

<div class="card">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-database me-2"></i>{{ $LANG['backup_database'] ?? 'Database Backup' }}</h3>
	</div>

	<div class="card-body">

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

		<div class="row align-items-center g-3">
			<div class="col">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-md bg-primary-lt rounded-3">
						<i class="ti ti-database-export text-primary"></i>
					</span>
					<div>
						<div class="fw-semibold">{{ $LANG['download_sql_backup'] ?? 'Download SQL backup' }}</div>
						<div class="text-secondary small">
							{{ $LANG['download_sql_backup_desc'] ?? 'Exports your full database as a .sql file you can use to restore or migrate.' }}
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
</script>
