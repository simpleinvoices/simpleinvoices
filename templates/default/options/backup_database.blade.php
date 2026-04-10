{{-- Database backup page --}}

<style>
/* SQL / JSON code viewer shared styles */
.si-code-viewer         { font-family: monospace; font-size: .82rem; line-height: 1.6; white-space: pre; background: #fff; color: #212529; }
/* JSON syntax highlighting (mirrors SQL colour palette) */
.si-code-viewer .jk     { color: #7c4dff; font-weight: 600; }   /* key      */
.si-code-viewer .js     { color: #2e7d32; }                      /* string   */
.si-code-viewer .jn     { color: #1565c0; }                      /* number   */
.si-code-viewer .jb     { color: #e65100; font-weight: 600; }   /* boolean  */
.si-code-viewer .jz     { color: #9e9e9e; }                      /* null     */
</style>


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
		<div>{{ $LANG['import_success'] ?? '' }}</div>
	</div>
</div>
@endif

{{-- ── SQL Backup card ─────────────────────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-database me-2"></i>{{ $LANG['backup_database'] ?? '' }}</h3>
	</div>

	<div class="card-body">
		<div class="row align-items-center g-3">
			<div class="col">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-md bg-primary-lt rounded-3">
						<i class="ti ti-database-export text-primary"></i>
					</span>
					<div>
						<div class="fw-semibold">{{ $LANG['download_sql_backup'] ?? '' }}</div>
						<div class="text-secondary small">
							{{ $LANG['download_sql_backup_desc'] ?? '' }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-auto d-flex gap-2">
				<button type="button" class="btn btn-outline-secondary" onclick="openSQLModal()">
					<i class="ti ti-code me-1"></i>{{ $LANG['view_sql'] ?? '' }}
				</button>
				<button type="button" class="btn btn-primary" id="btn_page_sql_download" onclick="pageDownload('sql', this)">
					<i class="ti ti-download me-1"></i>{{ $LANG['download_backup'] ?? '' }}
				</button>
			</div>
		</div>
	</div>

</div>

{{-- ── Cross-database JSON Export card ────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-file-type-json me-2"></i>{{ $LANG['json_export'] ?? '' }}</h3>
	</div>

	<div class="card-body">
		<div class="row align-items-center g-3">
			<div class="col">
				<div class="d-flex align-items-center gap-3">
					<span class="avatar avatar-md bg-green-lt rounded-3">
						<i class="ti ti-transfer text-green"></i>
					</span>
					<div>
						<div class="fw-semibold">{{ $LANG['download_json_export'] ?? '' }}</div>
						<div class="text-secondary small">
							{{ $LANG['download_json_export_desc'] ?? '' }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-auto d-flex gap-2">
				<button type="button" class="btn btn-outline-secondary" onclick="openJSONModal()">
					<i class="ti ti-braces me-1"></i>{{ $LANG['view_json'] ?? '' }}
				</button>
				<button type="button" class="btn btn-success" id="btn_page_json_download" onclick="pageDownload('json', this)">
					<i class="ti ti-download me-1"></i>{{ $LANG['download_json'] ?? '' }}
				</button>
			</div>
		</div>

		<div class="mt-3 p-3 bg-blue-lt rounded">
			<div class="d-flex gap-2">
				<i class="ti ti-info-circle text-blue flex-shrink-0 mt-1"></i>
				<div class="text-secondary small">
					<strong>{{ $LANG['how_to_migrate'] ?? '' }}</strong>
					<ol class="mb-0 mt-1 ps-3">
						<li>{{ $LANG['migrate_step1'] ?? '' }}</li>
						<li>{{ $LANG['migrate_step2'] ?? '' }}</li>
						<li>{{ $LANG['migrate_step3'] ?? '' }}</li>
						<li>{{ $LANG['migrate_step4'] ?? '' }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- ── Cross-database JSON Import card ────────────────────────────────── --}}
<div class="card mb-4">
	<div class="card-header">
		<h3 class="card-title"><i class="ti ti-file-upload me-2"></i>{{ $LANG['json_import'] ?? '' }}</h3>
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
						<strong>{{ $LANG['warning'] ?? '' }}:</strong>
						{{ $LANG['import_warning'] ?? '' }}
					</div>
				</div>
			</div>

			<div class="mb-3">
				<label class="form-label" for="json_file">
					{{ $LANG['select_json_file'] ?? '' }}
				</label>
				<input type="file"
					   class="form-control"
					   id="json_file"
					   name="json_file"
					   accept=".json,application/json"
					   required />
				<div class="form-hint">
					{{ $LANG['json_file_hint'] ?? '' }}
				</div>
			</div>
		</div>

		<div class="card-footer d-flex justify-content-end">
			<button type="submit" class="btn btn-danger">
				<i class="ti ti-database-import me-1"></i>{{ $LANG['import_data'] ?? '' }}
			</button>
		</div>
	</form>
</div>

{{-- ── SQL View Modal ───────────────────────────────────────────────────── --}}
<div class="modal fade" id="si_sql_modal" tabindex="-1" aria-labelledby="si_sql_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="si_sql_modal_label">
					<i class="ti ti-database me-2"></i>{{ $LANG['view_sql'] ?? '' }}
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? '' }}"></button>
			</div>
			<div class="modal-body p-0" style="min-height:200px;">
				<div id="si_sql_loading" class="d-flex align-items-center justify-content-center p-5">
					<div class="spinner-border text-primary me-3" role="status"></div>
					<span class="text-secondary">{{ $LANG['loading'] ?? 'Loading…' }}</span>
				</div>
				<div id="si_sql_error" class="alert alert-danger m-3 d-none"></div>
				<div id="si_sql_content" class="overflow-auto p-3 d-none" style="max-height:68vh;">
					<div id="si_sql_html" class="si-code-viewer"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal">{{ $LANG['close'] ?? '' }}</button>
				<div class="ms-auto d-flex gap-2">
					<button type="button" class="btn btn-outline-secondary d-none" id="btn_sql_copy"
							onclick="modalCopy(this,'sql')">
						<i class="ti ti-copy me-1"></i>{{ $LANG['copy_sql'] ?? 'Copy SQL' }}
					</button>
					<button type="button" class="btn btn-primary d-none" id="btn_sql_download"
							onclick="modalDownload('sql')">
						<i class="ti ti-download me-1"></i>{{ $LANG['download_backup'] ?? 'Download' }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- ── JSON View Modal ──────────────────────────────────────────────────── --}}
<div class="modal fade" id="si_json_modal" tabindex="-1" aria-labelledby="si_json_modal_label" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="si_json_modal_label">
					<i class="ti ti-file-type-json me-2"></i>{{ $LANG['view_json'] ?? '' }}
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? '' }}"></button>
			</div>
			<div class="modal-body p-0" style="min-height:200px;">
				<div id="si_json_loading" class="d-flex align-items-center justify-content-center p-5">
					<div class="spinner-border text-success me-3" role="status"></div>
					<span class="text-secondary">{{ $LANG['loading'] ?? 'Loading…' }}</span>
				</div>
				<div id="si_json_error" class="alert alert-danger m-3 d-none"></div>
				<div id="si_json_content" class="overflow-auto p-3 d-none" style="max-height:68vh;">
					<pre id="si_json_html" class="si-code-viewer mb-0"></pre>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn" data-bs-dismiss="modal">{{ $LANG['close'] ?? '' }}</button>
				<div class="ms-auto d-flex gap-2">
					<button type="button" class="btn btn-outline-secondary d-none" id="btn_json_copy"
							onclick="modalCopy(this,'json')">
						<i class="ti ti-copy me-1"></i>{{ $LANG['copy_json'] ?? 'Copy JSON' }}
					</button>
					<button type="button" class="btn btn-success d-none" id="btn_json_download"
							onclick="modalDownload('json')">
						<i class="ti ti-download me-1"></i>{{ $LANG['download_json'] ?? 'Download' }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
// ── State cache ────────────────────────────────────────────────────────────
var _siBackup = { sql: null, json: null };

var _siAjaxBase = 'index.php?module=options&view=backup_database_ajax&op=';

// ── JSON syntax highlighter ────────────────────────────────────────────────
function highlightJSON(raw) {
	var s = raw.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	return s.replace(
		/("(?:\\u[0-9a-fA-F]{4}|\\[^u]|[^\\"])*"(?:\s*:)?|true|false|null|-?\d+(?:\.\d+)?(?:[eE][+\-]?\d+)?)/g,
		function(m) {
			if (/^"/.test(m)) {
				return /:$/.test(m)
					? '<span class="jk">' + m + '</span>'
					: '<span class="js">' + m + '</span>';
			}
			if (m === 'true' || m === 'false') return '<span class="jb">' + m + '</span>';
			if (m === 'null')                  return '<span class="jz">' + m + '</span>';
			return '<span class="jn">' + m + '</span>';
		}
	);
}

// ── Page-level download (fetches data then saves as blob) ─────────────────
function pageDownload(type, btn) {
	if (_siBackup[type] !== null) {
		modalDownload(type);
		return;
	}
	var originalHTML = btn.innerHTML;
	btn.disabled = true;
	btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>' + @json($LANG['loading'] ?? 'Loading…');

	fetch(_siAjaxBase + 'view_' + type)
		.then(function(r) { return r.json(); })
		.then(function(data) {
			btn.disabled = false;
			btn.innerHTML = originalHTML;
			if (!data.ok) { alert(data.error || 'An error occurred.'); return; }
			_siBackup[type] = type === 'sql' ? data.raw : data.raw;
			modalDownload(type);
		})
		.catch(function(e) {
			btn.disabled = false;
			btn.innerHTML = originalHTML;
			alert('Download failed: ' + e.message);
		});
}

// ── SQL modal ─────────────────────────────────────────────────────────────
function openSQLModal() {
	var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('si_sql_modal'));
	modal.show();

	if (_siBackup.sql !== null) return; // already loaded

	fetch(_siAjaxBase + 'view_sql')
		.then(function(r) { return r.json(); })
		.then(function(data) {
			document.getElementById('si_sql_loading').classList.add('d-none');
			if (!data.ok) {
				var err = document.getElementById('si_sql_error');
				err.textContent = data.error || 'An error occurred.';
				err.classList.remove('d-none');
				return;
			}
			_siBackup.sql = data.raw;
			document.getElementById('si_sql_html').innerHTML = data.html;
			document.getElementById('si_sql_content').classList.remove('d-none');
			document.getElementById('btn_sql_copy').classList.remove('d-none');
			document.getElementById('btn_sql_download').classList.remove('d-none');
		})
		.catch(function(e) {
			document.getElementById('si_sql_loading').classList.add('d-none');
			var err = document.getElementById('si_sql_error');
			err.textContent = 'Request failed: ' + e.message;
			err.classList.remove('d-none');
		});
}

// ── JSON modal ────────────────────────────────────────────────────────────
function _renderJSONModal() {
	document.getElementById('si_json_loading').classList.add('d-none');
	document.getElementById('si_json_html').innerHTML = highlightJSON(_siBackup.json);
	document.getElementById('si_json_content').classList.remove('d-none');
	document.getElementById('btn_json_copy').classList.remove('d-none');
	document.getElementById('btn_json_download').classList.remove('d-none');
}

function openJSONModal() {
	var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('si_json_modal'));
	modal.show();

	if (_siBackup.json !== null) { _renderJSONModal(); return; }

	fetch(_siAjaxBase + 'view_json')
		.then(function(r) { return r.json(); })
		.then(function(data) {
			if (!data.ok) {
				document.getElementById('si_json_loading').classList.add('d-none');
				var err = document.getElementById('si_json_error');
				err.textContent = data.error || 'An error occurred.';
				err.classList.remove('d-none');
				return;
			}
			_siBackup.json = JSON.stringify(data.data, null, 2);
			_renderJSONModal();
		})
		.catch(function(e) {
			document.getElementById('si_json_loading').classList.add('d-none');
			var err = document.getElementById('si_json_error');
			err.textContent = 'Request failed: ' + e.message;
			err.classList.remove('d-none');
		});
}

// ── Copy from modal ────────────────────────────────────────────────────────
function modalCopy(btn, type) {
	var text = _siBackup[type];
	if (!text) return;
	var originalHTML = btn.innerHTML;
	var copiedLabel  = '<i class="ti ti-check me-1"></i>' + @json($LANG['copied'] ?? 'Copied');

	navigator.clipboard.writeText(text).then(function() {
		btn.innerHTML = copiedLabel;
		btn.classList.replace('btn-outline-secondary', 'btn-success');
		setTimeout(function() {
			btn.innerHTML = originalHTML;
			btn.classList.replace('btn-success', 'btn-outline-secondary');
		}, 2000);
	}).catch(function() {
		var ta = document.createElement('textarea');
		ta.value = text;
		ta.style.position = 'fixed';
		ta.style.opacity = '0';
		document.body.appendChild(ta);
		ta.select();
		try { document.execCommand('copy'); } catch(e) {}
		document.body.removeChild(ta);
	});
}

// ── Download from modal (blob, no new tab) ─────────────────────────────────
function modalDownload(type) {
	var text = _siBackup[type];
	if (!text) return;
	var today = new Date();
	var stamp = today.getFullYear()
		+ ('0'+(today.getMonth()+1)).slice(-2)
		+ ('0'+today.getDate()).slice(-2)
		+ '_'
		+ ('0'+today.getHours()).slice(-2)
		+ ('0'+today.getMinutes()).slice(-2)
		+ ('0'+today.getSeconds()).slice(-2);
	var filename = type === 'sql'
		? 'simple_invoices_backup_' + stamp + '.sql'
		: 'simple_invoices_data_'   + stamp + '.json';
	var mime = type === 'sql' ? 'application/octet-stream' : 'application/json';
	var blob = new Blob([text], { type: mime });
	var url  = URL.createObjectURL(blob);
	var a    = document.createElement('a');
	a.href     = url;
	a.download = filename;
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	URL.revokeObjectURL(url);
}

// ── Import confirmation ────────────────────────────────────────────────────
function confirmImport() {
	return confirm(@json($LANG['confirm_import'] ?? ''));
}
</script>
