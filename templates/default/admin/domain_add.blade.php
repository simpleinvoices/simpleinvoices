{{-- Admin: add domain --}}
@if(form_submitted(null) && !empty(post('name')))
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
                   placeholder="e.g. acme-corp" required autocomplete="off"
                   pattern="[a-zA-Z0-9_\-]+"
                   oninput="this.value=this.value.replace(/[^a-zA-Z0-9_\-]/g,'')" />
            <div class="invalid-feedback">Only letters, numbers, hyphens and underscores are allowed.</div>
            <div class="form-hint">
                Only letters, numbers, <code>-</code> and <code>_</code> — no spaces or special characters.<br>
                This name is used as the login portal identifier, e.g. the custom login page URL for this domain's users.
            </div>
        </div>
        <hr class="my-4" />
        <h6 class="text-muted mb-3">Domain administrator</h6>
        <p class="form-hint mb-3">Creates a login with the <strong>domain administrator</strong> role for this domain
            (Domain Admin menu: users, settings for the tenant).</p>
        <div class="mb-3">
            <label class="form-label">Administrator email
                <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i>
            </label>
            <input type="email" name="admin_email" value="{{ post('admin_email') }}" class="form-control"
                   placeholder="admin@example.com" required autocomplete="off" />
            <div class="invalid-feedback">A valid email is required.</div>
            <div class="form-hint">Must be unique across all accounts (login identity).</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Administrator display name</label>
            <input type="text" name="admin_name" value="{{ post('admin_name') }}" class="form-control"
                   placeholder="Optional" autocomplete="name" />
        </div>
        <div class="mb-3">
            <label class="form-label">Administrator password
                <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i>
            </label>
            <input type="password" name="admin_password" value="" class="form-control"
                   placeholder="Choose a strong password" required autocomplete="new-password" minlength="4" />
            <div class="invalid-feedback">Password is required (at least 4 characters).</div>
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
