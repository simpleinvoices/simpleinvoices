{{-- Domain Admin: save result --}}
@if(!empty($saveError))
    <div class="alert alert-danger">
        <i class="ti ti-alert-circle me-1"></i>{{ $saveError }}
    </div>
    <a href="javascript:history.back()" class="btn btn-outline-secondary me-2">
        <i class="ti ti-arrow-left me-1"></i>Go Back
    </a>
    <a href="index.php?module=domain_admin&view=users" class="btn btn-outline-primary">
        <i class="ti ti-users me-1"></i>All Accounts
    </a>
@elseif($saved ?? false)
    {{-- Success redirects from user_save.php --}}
    <div class="alert alert-success">
        <i class="ti ti-check me-1"></i>Account saved successfully.
    </div>
    <a href="index.php?module=domain_admin&view=users" class="btn btn-outline-primary">
        <i class="ti ti-users me-1"></i>Login Accounts
    </a>
@endif
