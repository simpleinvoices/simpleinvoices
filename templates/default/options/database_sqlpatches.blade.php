{{-- database_sqlpatches.blade.php — Tabler.io styled DB patch wizard --}}

@php $mode = $page['mode'] ?? 'list'; @endphp

{{-- ═══════════════════════════════════════════════════════════════════════
     MODE: done — all patches already applied, redirect to dashboard
     ═══════════════════════════════════════════════════════════════════════ --}}
@if($mode === 'done')

<div class="container-tight py-6">
    <div class="text-center mb-5">
        <span class="avatar avatar-xl bg-success-lt mb-3" style="width:5rem;height:5rem;font-size:2.5rem;">
            <i class="ti ti-circle-check text-success"></i>
        </span>
        <h1 class="mt-3 mb-1">Database is up to date</h1>
        <p class="text-secondary">All database patches have already been applied. No action is needed.</p>
    </div>

    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <div class="progress mb-2" style="height:6px;">
                    <div class="progress-bar bg-success progress-bar-animated" role="progressbar"
                         style="width:100%" id="redirect-bar"></div>
                </div>
                <p class="text-secondary small mb-0" id="redirect-msg">
                    Redirecting to dashboard in <strong id="redirect-count">{{ $page['refresh'] ?? 3 }}</strong> second(s)…
                </p>
            </div>
            <a href="index.php" class="btn btn-success">
                <i class="ti ti-home me-1"></i>Go to Dashboard now
            </a>
        </div>
    </div>
</div>

@if(!empty($page['refresh']))
<meta http-equiv="refresh" content="{{ $page['refresh'] }};url=index.php">
@endif

<script>
(function () {
    var n = {{ (int)($page['refresh'] ?? 3) }};
    var el = document.getElementById('redirect-count');
    var bar = document.getElementById('redirect-bar');
    var total = n;
    var t = setInterval(function () {
        n--;
        if (el) el.textContent = n;
        if (bar) bar.style.width = Math.round((total - n) / total * 100) + '%';
        if (n <= 0) clearInterval(t);
    }, 1000);
}());
</script>

{{-- ═══════════════════════════════════════════════════════════════════════
     MODE: list — show pending patches, prompt user to apply
     ═══════════════════════════════════════════════════════════════════════ --}}
@elseif($mode === 'list')

@php $count = (int)($page['pending_count'] ?? 0); @endphp

<div class="container-tight py-4">

    {{-- Step indicator --}}
    <div class="text-center mb-5">
        <span class="avatar avatar-xl bg-warning-lt mb-3" style="width:5rem;height:5rem;font-size:2.5rem;">
            <i class="ti ti-database-import text-warning"></i>
        </span>
        <h1 class="mt-3 mb-1">Database Upgrade Required</h1>
        <p class="text-secondary">
            @if($count === 1)
                There is <strong>1 patch</strong> that needs to be applied to your database.
            @else
                There are <strong>{{ $count }} patches</strong> that need to be applied to your database.
            @endif
        </p>
    </div>

    {{-- Step pills --}}
    <ul class="steps steps-counter mb-5">
        <li class="step-item active">Review patches</li>
        <li class="step-item">Backup database</li>
        <li class="step-item">Apply updates</li>
    </ul>

    {{-- Patches table --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ti ti-list-check me-2"></i>Pending Patches
            </h3>
            <div class="card-options">
                <span class="badge bg-warning-lt text-warning border border-warning">
                    {{ $count }} pending
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead>
                    <tr>
                        <th style="width:80px">#</th>
                        <th>Description</th>
                        <th style="width:120px" class="text-end">Release</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($page['rows'] ?? []) as $row)
                    <tr>
                        <td>
                            <span class="badge bg-secondary-lt text-secondary">{{ $row['id'] }}</span>
                        </td>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-end text-secondary small">{{ $row['date'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-secondary py-4">No pending patches.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <a href="index.php?case=backup" class="btn btn-primary">
                Next: Backup Database <i class="ti ti-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════════════════
     MODE: backup — step 2, download backup then continue to apply
     ═══════════════════════════════════════════════════════════════════════ --}}
@elseif($mode === 'backup')

@php $count = (int)($page['pending_count'] ?? 0); @endphp

<div class="container-tight py-4">

    <div class="text-center mb-5">
        <span class="avatar avatar-xl bg-warning-lt mb-3" style="width:5rem;height:5rem;font-size:2.5rem;">
            <i class="ti ti-database-export text-warning"></i>
        </span>
        <h1 class="mt-3 mb-1">Backup Your Database</h1>
        <p class="text-secondary">Download a backup before applying patches so you can restore if anything goes wrong.</p>
    </div>

    {{-- Step pills --}}
    <ul class="steps steps-counter mb-5">
        <li class="step-item">Review patches</li>
        <li class="step-item active">Backup database</li>
        <li class="step-item">Apply updates</li>
    </ul>

    {{-- Format explainer --}}
    <div class="alert alert-info mb-4">
        <div class="d-flex gap-2">
            <i class="ti ti-info-circle flex-shrink-0 mt-1"></i>
            <div class="small">
                <strong>SQL</strong> — a full dump of your database (structure + data).
                Restore it with any MySQL client if something goes wrong. Best for disaster recovery.<br>
                <strong>JSON</strong> — your data only, in a portable format.
                Use it to migrate to a different database engine (MySQL → PostgreSQL, etc.) or import into a fresh install.
                It does not contain table structure, so it cannot replace a SQL backup for recovery.
            </div>
        </div>
    </div>

    {{-- SQL download --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <span class="avatar avatar-md bg-primary-lt rounded-3">
                    <i class="ti ti-database-export text-primary"></i>
                </span>
                <div class="flex-fill">
                    <div class="fw-semibold">SQL Backup <span class="badge bg-primary-lt text-primary ms-1">Recommended</span></div>
                    <div class="text-secondary small">Full database dump — structure and data. Use this to restore if patches fail.</div>
                </div>
                <button type="button" class="btn btn-primary" id="si_patch_sql_btn" onclick="siPatchDownload('sql', this)">
                    <i class="ti ti-download me-1"></i>Download SQL
                </button>
            </div>
        </div>
    </div>

    {{-- JSON download --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <span class="avatar avatar-md bg-teal-lt rounded-3">
                    <i class="ti ti-file-type-json text-teal"></i>
                </span>
                <div class="flex-fill">
                    <div class="fw-semibold">JSON Export</div>
                    <div class="text-secondary small">Data only, no table structure. Useful for migration between database engines.</div>
                </div>
                <button type="button" class="btn btn-outline-teal" id="si_patch_json_btn" onclick="siPatchDownload('json', this)">
                    <i class="ti ti-download me-1"></i>Download JSON
                </button>
            </div>
        </div>
    </div>

    {{-- Download confirmation --}}
    <div id="si_patch_backup_status" class="alert alert-success d-flex gap-2 mb-4 d-none">
        <i class="ti ti-circle-check flex-shrink-0 mt-1"></i>
        <div>Backup downloaded. You can now safely apply the database patches.</div>
    </div>

    {{-- Nav --}}
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-ghost-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back
        </a>
        <a href="index.php?case=run" class="btn btn-primary">
            <i class="ti ti-player-play me-1"></i>Apply {{ $count }} Patch{{ $count === 1 ? '' : 'es' }}
        </a>
    </div>

</div>

<script>
function siPatchDownload(type, btn) {
    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Preparing\u2026';

    var op  = type === 'sql' ? 'view_sql' : 'view_json';
    var url = 'index.php?module=options&view=backup_database_ajax&op=' + op;

    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.ok) {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
                alert(data.error || 'Download failed.');
                return;
            }

            var today = new Date();
            var stamp = today.getFullYear()
                + ('0'+(today.getMonth()+1)).slice(-2)
                + ('0'+today.getDate()).slice(-2);

            var raw, filename, mime;
            if (type === 'sql') {
                raw      = data.raw;
                filename = 'simple_invoices_backup_' + stamp + '.sql';
                mime     = 'application/octet-stream';
            } else {
                raw      = JSON.stringify(data.data, null, 2);
                filename = 'simple_invoices_data_' + stamp + '.json';
                mime     = 'application/json';
            }

            var blob = new Blob([raw], { type: mime });
            var a    = document.createElement('a');
            a.href     = URL.createObjectURL(blob);
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);

            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-check me-1"></i>Downloaded';
            btn.classList.add(type === 'sql' ? 'btn-success' : 'btn-outline-success');
            if (type === 'sql') btn.classList.remove('btn-primary');

            document.getElementById('si_patch_backup_status').classList.remove('d-none');
        })
        .catch(function(e) {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            alert('Download failed: ' + e.message);
        });
}
</script>

{{-- ═══════════════════════════════════════════════════════════════════════
     MODE: run — patches applied, show results and redirect
     ═══════════════════════════════════════════════════════════════════════ --}}
@elseif($mode === 'run')

@php $applied = (int)($page['applied_count'] ?? count($page['rows'] ?? [])); @endphp

<div class="container-tight py-4">

    {{-- Step indicator --}}
    <div class="text-center mb-5">
        <span class="avatar avatar-xl bg-success-lt mb-3" style="width:5rem;height:5rem;font-size:2.5rem;">
            <i class="ti ti-circle-check text-success"></i>
        </span>
        <h1 class="mt-3 mb-1">Patches Applied Successfully</h1>
        <p class="text-secondary">
            @if($applied === 1)
                <strong>1 patch</strong> was applied to your database.
            @elseif($applied > 0)
                <strong>{{ $applied }} patches</strong> were applied to your database.
            @else
                Your database patches are now up to date.
            @endif
        </p>
    </div>

    {{-- Step pills --}}
    <ul class="steps steps-counter mb-5">
        <li class="step-item">Review patches</li>
        <li class="step-item">Backup database</li>
        <li class="step-item active">Apply updates</li>
    </ul>

    {{-- Applied patches --}}
    @if(!empty($page['rows']))
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ti ti-checks me-2"></i>Applied Patches
            </h3>
            <div class="card-options">
                <span class="badge bg-success-lt text-success border border-success">
                    {{ $applied }} applied
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th style="width:60px"></th>
                        <th style="width:80px">#</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($page['rows'] ?? []) as $row)
                    <tr>
                        <td>
                            <span class="avatar avatar-sm bg-success-lt rounded-circle">
                                <i class="ti ti-check text-success" style="font-size:0.85rem;"></i>
                            </span>
                        </td>
                        <td class="text-secondary small">
                            <span class="badge bg-secondary-lt text-secondary">{{ $row['id'] ?? '' }}</span>
                        </td>
                        <td>{{ $row['name'] ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Redirect card --}}
    <div class="card">
        <div class="card-body text-center py-5">
            <p class="text-secondary mb-3">
                Redirecting to dashboard in <strong id="redirect-count">{{ $page['refresh'] ?? 5 }}</strong> second(s)…
            </p>
            <div class="progress mb-4" style="height:6px;max-width:300px;margin:0 auto;">
                <div class="progress-bar bg-success progress-bar-animated" role="progressbar"
                     style="width:0%" id="redirect-bar"></div>
            </div>
            <a href="index.php" class="btn btn-success">
                <i class="ti ti-home me-1"></i>Go to Dashboard now
            </a>
        </div>
    </div>

</div>

@if(!empty($page['refresh']))
<meta http-equiv="refresh" content="{{ $page['refresh'] }};url=index.php">
@endif

<script>
(function () {
    var n = {{ (int)($page['refresh'] ?? 5) }};
    var el = document.getElementById('redirect-count');
    var bar = document.getElementById('redirect-bar');
    var total = n;
    var t = setInterval(function () {
        n--;
        if (el) el.textContent = n;
        if (bar) bar.style.width = Math.round((total - n) / total * 100) + '%';
        if (n <= 0) clearInterval(t);
    }, 1000);
}());
</script>

{{-- ═══════════════════════════════════════════════════════════════════════
     MODE: init — first-time patch table initialisation
     ═══════════════════════════════════════════════════════════════════════ --}}
@elseif($mode === 'init')

<div class="container-tight py-4">
    <div class="text-center mb-5">
        <span class="avatar avatar-xl bg-blue-lt mb-3" style="width:5rem;height:5rem;font-size:2.5rem;">
            <i class="ti ti-database-plus text-blue"></i>
        </span>
        <h1 class="mt-3 mb-1">Initialising Database Upgrade System</h1>
        <p class="text-secondary">The patch manager table is being created for the first time.</p>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="ti ti-terminal me-2"></i>Setup Log</h3>
        </div>
        <div class="card-body">
            <div class="text-secondary small font-monospace">{!! $page['init_log'] ?? '' !!}</div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <a href="index.php?module=options&view=database_sqlpatches" class="btn btn-primary">
                <i class="ti ti-arrow-right me-1"></i>Continue to Apply Patches
            </a>
        </div>
    </div>
</div>

@else

{{-- Fallback for legacy/unknown mode --}}
<div class="card">
    <div class="card-body">
        @if(!empty($page['message']))
        <div class="alert alert-info mb-3">{{ $page['message'] }}</div>
        @endif
        @if(!empty($page['html']))
        {!! $page['html'] !!}
        @endif
        @if(!empty($page['rows']))
        <ul class="list-group list-group-flush mt-3">
            @foreach(($page['rows']) as $row)
            <li class="list-group-item">{!! $row['text'] ?? '' !!}</li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@if(!empty($page['refresh']))
<meta http-equiv="refresh" content="{{ $page['refresh'] }};url=index.php">
@endif

@endif
