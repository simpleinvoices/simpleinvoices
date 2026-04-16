{{-- Admin: domain save result (included by domain_add/domain_edit on form submission) --}}
@if(!empty($saveError))
    <div class="alert alert-danger">
        <i class="ti ti-alert-circle me-1"></i>{{ $saveError }}
    </div>
    <a href="javascript:history.back()" class="btn btn-outline-secondary me-2">
        <i class="ti ti-arrow-left me-1"></i>Go Back
    </a>
    <a href="index.php?module=admin&view=domains" class="btn btn-outline-primary">
        <i class="ti ti-building me-1"></i>All Domains
    </a>
@elseif($saved ?? false)
    <div class="alert alert-success">
        <i class="ti ti-check me-1"></i>Domain saved successfully.
    </div>
    <meta http-equiv="refresh" content="1;URL=index.php?module=admin&view=domains" />
@endif
