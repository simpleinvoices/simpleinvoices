{{-- manage_sqlpatches.blade.php — applied SQL patches list --}}

@php $total = count($patches ?? []); @endphp

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="ti ti-database-check me-2"></i>Applied Database Patches
        </div>
        <div class="card-options">
            <span class="badge bg-success-lt text-success border border-success">
                {{ $total }} applied
            </span>
        </div>
    </div>

    @if($total === 0)
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-database-off fs-1 mb-3 d-block"></i>
        No patches have been recorded yet.
    </div>
    @else

    <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover" id="live-grid">
            <thead>
                <tr>
                    <th style="width:80px">#</th>
                    <th>Description</th>
                    <th style="width:120px" class="text-end">Release</th>
                    <th style="width:60px" class="text-center">
                        <i class="ti ti-check"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach(($patches ?? []) as $patch)
                <tr>
                    <td>
                        <span class="badge bg-secondary-lt text-secondary">
                            {{ $patch['sql_patch_ref'] ?? '' }}
                        </span>
                    </td>
                    <td>{{ $patch['sql_patch'] ?? '' }}</td>
                    <td class="text-end text-secondary small">{{ $patch['sql_release'] ?? '' }}</td>
                    <td class="text-center">
                        <span class="avatar avatar-xs bg-success-lt rounded-circle">
                            <i class="ti ti-check text-success" style="font-size:0.7rem;"></i>
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span class="text-secondary small">
            Showing {{ $total }} applied patch(es), most recent first.
        </span>
        <a href="index.php?module=options&view=backup_database" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-database-export me-1"></i>Backup Database
        </a>
    </div>

    @endif
</div>
