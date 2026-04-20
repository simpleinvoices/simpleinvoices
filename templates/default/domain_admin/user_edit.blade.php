{{-- Domain Admin: edit customer or biller login account --}}
@php
    $isPost   = form_submitted(null);
    $u        = $domainUser ?? [];
    $uid      = $isPost ? (int) post('id')       : (int) ($u['id']        ?? 0);
    $curRole  = $isPost ? (post('role_key') ?: 'customer') : ($u['role_name'] ?? 'customer');
    $curId    = $isPost ? (int) post('linked_id') : (int) ($u['user_id']   ?? 0);
    $curName  = $isPost ? post('name')            : ($u['name']            ?? '');
    $curEmail = $isPost ? post('email')           : ($u['email']           ?? '');
    $curEnabledPost = post('enabled');
    $curEnabled = $isPost
        ? (int) $curEnabledPost
        : (int) ($u['enabled'] ?? 1);
    $curPrefLang = $isPost
        ? trim((string) ($_POST['preferred_language'] ?? ''))
        : trim((string) ($u['preferred_language'] ?? ''));
@endphp

@if($saved ?? false)
<meta http-equiv="refresh" content="2;URL=index.php?module=domain_admin&amp;view=users" />
<div class="alert alert-success">
    <i class="ti ti-check me-1"></i>Account saved successfully.
</div>
<a href="index.php?module=domain_admin&view=users" class="btn btn-outline-primary">
    <i class="ti ti-users me-1"></i>Login Accounts
</a>
@else

@if(!empty($saveError))
<div class="alert alert-danger mb-3">
    <i class="ti ti-alert-circle me-1"></i>{{ $saveError }}
</div>
@endif

<form method="post" action="index.php?module=domain_admin&view=user_edit&id={{ urlencode((string) $uid) }}"
      class="needs-validation" novalidate id="domainUserForm">
<div class="card">
    <div class="card-body">

        {{-- Role selector --}}
        <div class="mb-3">
            <label class="form-label fw-medium">Account Type</label>
            <div class="d-flex gap-3">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_key" value="customer"
                           @if($curRole === 'customer') checked @endif
                           onchange="toggleLinkedDropdown(this.value)">
                    <span class="form-check-label">
                        <i class="ti ti-address-book me-1"></i>Customer
                    </span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_key" value="biller"
                           @if($curRole === 'biller') checked @endif
                           onchange="toggleLinkedDropdown(this.value)">
                    <span class="form-check-label">
                        <i class="ti ti-building-store me-1"></i>Biller
                    </span>
                </label>
            </div>
        </div>

        {{-- Hidden field that carries the selected entity id --}}
        <input type="hidden" name="linked_id" id="linked_id_hidden" value="{{ $curId ?: '' }}" />

        {{-- Link to customer --}}
        <div class="mb-3" id="customerDropdown" @if($curRole === 'biller') style="display:none" @endif>
            <label class="form-label">Link to Customer</label>
            <select id="linked_id_customer" class="form-select" onchange="syncLinkedId()">
                <option value="">— select customer —</option>
                @foreach(($customers ?? []) as $c)
                    <option value="{{ $c['id'] }}"
                            @if($curRole === 'customer' && (int)$c['id'] === $curId) selected @endif>
                        {{ $c['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Link to biller --}}
        <div class="mb-3" id="billerDropdown" @if($curRole !== 'biller') style="display:none" @endif>
            <label class="form-label">Link to Biller</label>
            <select id="linked_id_biller" class="form-select" onchange="syncLinkedId()">
                <option value="">— select biller —</option>
                @foreach(($billers ?? []) as $b)
                    <option value="{{ $b['id'] }}"
                            @if($curRole === 'biller' && (int)$b['id'] === $curId) selected @endif>
                        {{ $b['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr class="my-3">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ $curName }}" class="form-control" autocomplete="off" />
        </div>
        <div class="mb-3">
            <label class="form-label">Email <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <input type="email" name="email" value="{{ $curEmail }}" class="form-control"
                   required autocomplete="off" />
            <div class="invalid-feedback">A valid email is required.</div>
            <div class="form-hint small">
                For <strong>customer</strong> accounts, the email must be unique among customer logins in this organisation; it may match a customer login in another organisation or a staff/biller account.
                For <strong>biller</strong> accounts, the email must be unique on the shared staff login (system-wide).
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password
                <span class="text-secondary small">(leave blank to keep current)</span>
            </label>
            <input type="password" name="password_field" class="form-control" autocomplete="new-password"
                   minlength="4" />
            <div class="invalid-feedback">New password must be at least 4 characters.</div>
        </div>
        @include('user.preferred_language_field', ['userPreferredValue' => $curPrefLang])
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="enabled" class="form-select">
                <option value="1" @if($curEnabled) selected @endif>Enabled</option>
                <option value="0" @if(!$curEnabled) selected @endif>Disabled</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex">
            <a href="index.php?module=domain_admin&view=users" class="btn btn-link">Cancel</a>
            <button type="submit" name="submit" class="btn btn-primary ms-auto">
                <i class="ti ti-check me-1"></i>Save Changes
            </button>
        </div>
    </div>
</div>
<input type="hidden" name="op" value="update_domain_user" />
<input type="hidden" name="id" value="{{ $uid }}" />
<input type="hidden" name="csrfprotectionbysr" value="{{ $domainUserSaveCsrfToken ?? '' }}" />
</form>

<script>
function syncLinkedId() {
    var role = document.querySelector('input[name="role_key"]:checked').value;
    var src  = role === 'biller'
               ? document.getElementById('linked_id_biller')
               : document.getElementById('linked_id_customer');
    document.getElementById('linked_id_hidden').value = src ? src.value : '';
}

function toggleLinkedDropdown(role) {
    var customerDiv = document.getElementById('customerDropdown');
    var billerDiv   = document.getElementById('billerDropdown');
    if (role === 'biller') {
        customerDiv.style.display = 'none';
        billerDiv.style.display   = '';
    } else {
        customerDiv.style.display = '';
        billerDiv.style.display   = 'none';
    }
    syncLinkedId();
}

document.addEventListener('DOMContentLoaded', syncLinkedId);
</script>

@endif
