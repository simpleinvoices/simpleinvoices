{{-- Domain Admin: add customer or biller login account --}}
@php $presetRole = post('role_key') ?: get('role') ?: 'customer'; @endphp

@if($saved ?? false)
<meta http-equiv="refresh" content="2;URL=index.php?module=domain_admin&amp;view=users" />
<div class="alert alert-success">
    <i class="ti ti-check me-1"></i>Account created successfully.
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

<form method="post" action="index.php?module=domain_admin&view=user_add"
      class="needs-validation" novalidate id="domainUserForm">
<div class="card">
    <div class="card-body">

        {{-- Role selector --}}
        <div class="mb-3">
            <label class="form-label fw-medium">Account Type <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <div class="d-flex gap-3">
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_key" value="customer"
                           @if($presetRole !== 'biller') checked @endif
                           onchange="toggleLinkedDropdown(this.value)">
                    <span class="form-check-label">
                        <i class="ti ti-address-book me-1"></i>Customer
                    </span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role_key" value="biller"
                           @if($presetRole === 'biller') checked @endif
                           onchange="toggleLinkedDropdown(this.value)">
                    <span class="form-check-label">
                        <i class="ti ti-building-store me-1"></i>Biller
                    </span>
                </label>
            </div>
        </div>

        {{-- Hidden field that carries the selected entity id --}}
        @php $presetLinkedId = (int) post('linked_id'); @endphp
        <input type="hidden" name="linked_id" id="linked_id_hidden" value="{{ $presetLinkedId ?: '' }}" />

        {{-- Link to customer --}}
        <div class="mb-3" id="customerDropdown" @if($presetRole === 'biller') style="display:none" @endif>
            <label class="form-label">Link to Customer <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <select id="linked_id_customer" class="form-select"
                    onchange="syncLinkedId()"
                    @if($presetRole !== 'biller') required @endif>
                <option value="">- select customer -</option>
                @foreach(($customers ?? []) as $c)
                    <option value="{{ $c['id'] }}"
                            @if($presetRole === 'customer' && (int)$c['id'] === $presetLinkedId) selected @endif>
                        {{ $c['name'] }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">Please select a customer to link.</div>
            @if(empty($customers))
                <div class="form-hint text-warning">No enabled customers found in this domain.</div>
            @endif
        </div>

        {{-- Link to biller --}}
        <div class="mb-3" id="billerDropdown" @if($presetRole !== 'biller') style="display:none" @endif>
            <label class="form-label">Link to Biller <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <select id="linked_id_biller" class="form-select"
                    onchange="syncLinkedId()"
                    @if($presetRole === 'biller') required @endif>
                <option value="">- select biller -</option>
                @foreach(($billers ?? []) as $b)
                    <option value="{{ $b['id'] }}"
                            @if($presetRole === 'biller' && (int)$b['id'] === $presetLinkedId) selected @endif>
                        {{ $b['name'] }}
                    </option>
                @endforeach
            </select>
            <div class="invalid-feedback">Please select a biller to link.</div>
            @if(empty($billers))
                <div class="form-hint text-warning">No enabled billers found in this domain.</div>
            @endif
        </div>

        <hr class="my-3">

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ post('name') }}" class="form-control" autocomplete="off" />
        </div>
        <div class="mb-3">
            <label class="form-label">Email <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <input type="email" name="email" value="{{ post('email') }}" class="form-control"
                   required autocomplete="off" />
            <div class="invalid-feedback">A valid email is required.</div>
            <div class="form-hint small">
                For <strong>customer</strong> accounts, the email must be unique among customer logins in this organisation; it may match a customer login in another organisation or a staff/biller account.
                For <strong>biller</strong> accounts, the email must be unique on the shared staff login (system-wide).
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <input type="password" name="password_field" class="form-control"
                   required autocomplete="new-password" minlength="4" />
            <div class="invalid-feedback">Password is required (at least 4 characters).</div>
        </div>
        @include('user.preferred_language_field', ['userPreferredValue' => $userPreferredValue ?? ''])
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="enabled" class="form-select">
                <option value="1" @if(post('enabled', '1') !== '0') selected @endif>Enabled</option>
                <option value="0" @if(post('enabled') === '0') selected @endif>Disabled</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex">
            <a href="index.php?module=domain_admin&view=users" class="btn btn-link">Cancel</a>
            <button type="submit" name="submit" class="btn btn-primary ms-auto">
                <i class="ti ti-check me-1"></i>Create Account
            </button>
        </div>
    </div>
</div>
<input type="hidden" name="op" value="insert_domain_user" />
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
    var customerDiv    = document.getElementById('customerDropdown');
    var billerDiv      = document.getElementById('billerDropdown');
    var customerSelect = document.getElementById('linked_id_customer');
    var billerSelect   = document.getElementById('linked_id_biller');
    if (role === 'biller') {
        customerDiv.style.display    = 'none';
        billerDiv.style.display      = '';
        customerSelect.required      = false;
        billerSelect.required        = true;
    } else {
        customerDiv.style.display    = '';
        billerDiv.style.display      = 'none';
        customerSelect.required      = true;
        billerSelect.required        = false;
    }
    syncLinkedId();
}

// Sync on page load so the hidden field has a value from the start
document.addEventListener('DOMContentLoaded', syncLinkedId);
</script>

@endif
