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

{{-- Hidden textarea holding raw SQL for clipboard copy --}}
@if(!empty($rawSQL))
<textarea id="sql-raw-content" class="visually-hidden" aria-hidden="true" readonly>{{ $rawSQL }}</textarea>
@endif

<div class="card">
	<div class="card-body">

		@if(!empty($backupErrors))
			<div class="alert alert-danger mb-3">
				@foreach($backupErrors as $error)
					<div>{{ $error }}</div>
				@endforeach
			</div>
		@endif

		<p class="text-secondary mb-0">{{ $LANG['backup_howto'] ?? '' }}</p>

	</div>

	@if(!empty($formattedSQL))
	<div class="accordion accordion-flush border-top" id="sql-backup-accordion">
		<div class="accordion-item">
			<h2 class="accordion-header" id="sql-backup-heading">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sql-backup-collapse" aria-expanded="true" aria-controls="sql-backup-collapse">
					<i class="ti ti-database me-2"></i>{{ $LANG['database_backup'] ?? 'Database Backup' }}
				</button>
			</h2>
			<div id="sql-backup-collapse" class="accordion-collapse collapse show" aria-labelledby="sql-backup-heading">
				<div class="accordion-body p-0">
					<div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom bg-body-tertiary">
						<button type="button" class="btn btn-sm btn-outline-secondary" id="btn-copy-sql" onclick="copySQLToClipboard(this)" title="Copy SQL to clipboard">
							<i class="ti ti-copy me-1"></i>Copy
						</button>
						<button type="submit" form="form_backup_db" class="btn btn-sm btn-outline-secondary" title="{{ $LANG['backup_database_now'] ?? 'Download SQL backup' }}">
							<i class="ti ti-download me-1"></i>{{ $LANG['backup_database_now'] ?? 'Download' }}
						</button>
					</div>
					<div class="overflow-auto p-3" style="max-height: 70vh;">
						{!! $formattedSQL !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

	<div class="card-footer">
		<div class="d-flex align-items-center">
			<a href="./index.php?module=options&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			@if(empty($formattedSQL))
			<div class="ms-auto">
				<button type="submit" form="form_backup_db" class="btn btn-primary">
					<i class="ti ti-download me-1"></i>{{ $LANG['backup_database_now'] ?? 'Download' }}
				</button>
			</div>
			@endif
		</div>
	</div>
</div>

<script>
function copySQLToClipboard(btn) {
	var textarea = document.getElementById('sql-raw-content');
	if (!textarea) return;

	var originalHTML = btn.innerHTML;

	navigator.clipboard.writeText(textarea.value).then(function() {
		btn.innerHTML = '<i class="ti ti-check me-1"></i>Copied!';
		btn.classList.remove('btn-outline-secondary');
		btn.classList.add('btn-success');
		setTimeout(function() {
			btn.innerHTML = originalHTML;
			btn.classList.remove('btn-success');
			btn.classList.add('btn-outline-secondary');
		}, 2000);
	}).catch(function() {
		// Fallback for browsers without clipboard API
		textarea.classList.remove('visually-hidden');
		textarea.select();
		try {
			document.execCommand('copy');
		} catch(e) {}
		textarea.classList.add('visually-hidden');
	});
}
</script>
