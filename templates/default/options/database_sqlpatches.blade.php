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

    {{-- Backup warning --}}
    <div class="alert alert-warning mb-4" role="alert">
        <div class="d-flex gap-3">
            <i class="ti ti-alert-triangle flex-shrink-0 fs-3"></i>
            <div>
                <strong>Please back up your database before proceeding.</strong><br>
                While patches are applied safely, a backup lets you restore if anything goes wrong.
                <div class="mt-2">
                    <a href="index.php?module=options&view=backup_database" class="btn btn-sm btn-outline-warning">
                        <i class="ti ti-database-export me-1"></i>Go to Database Backup
                    </a>
                </div>
            </div>
        </div>
    </div>

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
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-secondary small">Showing {{ $count }} patch(es) to apply</span>
            <a href="index.php?case=run" class="btn btn-primary">
                <i class="ti ti-player-play me-1"></i>Apply Database Updates
            </a>
        </div>
    </div>

</div>

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
