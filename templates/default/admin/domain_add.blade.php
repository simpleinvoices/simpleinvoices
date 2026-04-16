{{-- Admin: add domain --}}
@if(post('name') != null && form_submitted())
    @include('templates.default.admin.domain_save')
@else

<form method="post" action="index.php?module=admin&view=domain_add" class="needs-validation" novalidate>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Domain Name
                <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i>
            </label>
            <input type="text" name="name" value="{{ post('name') }}" class="form-control"
                   placeholder="e.g. acme-corp" required autocomplete="off" />
            <div class="invalid-feedback">Domain name is required.</div>
            <div class="form-hint">Must be unique. Used to isolate tenant data.</div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex">
            <a href="index.php?module=admin&view=domains" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary ms-auto">
                <i class="ti ti-check me-1"></i>Create Domain
            </button>
        </div>
    </div>
</div>
<input type="hidden" name="op" value="insert_domain" />
<input type="hidden" name="csrfprotectionbysr" value="{{ $domainSaveCsrfToken ?? '' }}" />
</form>

@endif
