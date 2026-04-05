<div class="card">
    <div class="card-body">
        @if($wizard_success)
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="ti ti-circle-check me-2 fs-3"></i>
                Sample data added — taking you to the next step&hellip;
            </div>
            <meta http-equiv="refresh" content="1;URL=index.php?wizard_step={{ $wizard_next_step }}" />
        @else
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="ti ti-alert-circle me-2 fs-3"></i>
                Could not add sample data. Please try again or fill in the form manually.
            </div>
            <meta http-equiv="refresh" content="3;URL=index.php" />
        @endif
    </div>
</div>
