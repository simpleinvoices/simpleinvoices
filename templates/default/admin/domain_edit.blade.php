{{-- Admin: edit domain --}}
@if(post('name') != null && form_submitted())
    @include('templates.default.admin.domain_save')
@else

@php $d = $domain ?? []; @endphp
<form method="post" action="index.php?module=admin&view=domain_edit&id={{ urlencode($d['id'] ?? '') }}"
      class="needs-validation" novalidate>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Domain ID</label>
            <input type="text" class="form-control" value="{{ $d['id'] ?? '' }}" readonly disabled />
        </div>
        <div class="mb-3">
            <label class="form-label">Domain Name
                <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i>
            </label>
            <input type="text" name="name" value="{{ $d['name'] ?? '' }}" class="form-control"
                   required autocomplete="off" />
            <div class="invalid-feedback">Domain name is required.</div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex">
            <a href="index.php?module=admin&view=domains" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary ms-auto">
                <i class="ti ti-check me-1"></i>Save Changes
            </button>
        </div>
    </div>
</div>
<input type="hidden" name="op" value="update_domain" />
<input type="hidden" name="id" value="{{ $d['id'] ?? '' }}" />
<input type="hidden" name="csrfprotectionbysr" value="{{ $domainSaveCsrfToken ?? '' }}" />
</form>

@endif
