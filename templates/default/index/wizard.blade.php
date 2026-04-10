<div class="card">
    <div class="card-body">
        @if($wizard_success)
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="ti ti-circle-check me-2 fs-3"></i>
                {{ $LANG['wizard_sample_added'] ?? '' }}
            </div>
            <meta http-equiv="refresh" content="1;URL=index.php?wizard_step={{ $wizard_next_step }}" />
        @else
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-circle me-2 fs-3"></i>
                {{ $LANG['wizard_sample_failed'] ?? '' }}
            </div>
            <meta http-equiv="refresh" content="3;URL=index.php" />
        @endif
    </div>
</div>
