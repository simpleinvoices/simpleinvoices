{{-- Domain Admin: edit customer or biller login account --}}
@if(!empty($_POST['email']) && form_submitted())
    @include('templates.default.domain_admin.user_save')
@else

@php
    $u       = $domainUser ?? [];
    $curRole = $u['role_name'] ?? 'customer';
    $curId   = (int) ($u['user_id'] ?? 0);
@endphp

<form method="post" action="index.php?module=domain_admin&view=user_edit&id={{ urlencode($u['id'] ?? '') }}"
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

        {{-- Link to customer --}}
        <div class="mb-3" id="customerDropdown" @if($curRole === 'biller') style="display:none" @endif>
            <label class="form-label">Link to Customer</label>
            <select name="linked_id" id="linked_id_customer" class="form-select">
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
            <select name="linked_id_biller" id="linked_id_biller" class="form-select">
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
            <input type="text" name="name" value="{{ $u['name'] ?? '' }}" class="form-control" autocomplete="off" />
        </div>
        <div class="mb-3">
            <label class="form-label">Email <i class="ti ti-asterisk text-danger" style="font-size:.7rem;"></i></label>
            <input type="email" name="email" value="{{ $u['email'] ?? '' }}" class="form-control"
                   required autocomplete="off" />
            <div class="invalid-feedback">A valid email is required.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password
                <span class="text-secondary small">(leave blank to keep current)</span>
            </label>
            <input type="password" name="password_field" class="form-control" autocomplete="new-password" />
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="enabled" class="form-select">
                <option value="1" @if(($u['enabled'] ?? 1)) selected @endif>Enabled</option>
                <option value="0" @if(!($u['enabled'] ?? 1)) selected @endif>Disabled</option>
            </select>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex">
            <a href="index.php?module=domain_admin&view=users" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary ms-auto">
                <i class="ti ti-check me-1"></i>Save Changes
            </button>
        </div>
    </div>
</div>
<input type="hidden" name="op" value="update_domain_user" />
<input type="hidden" name="id" value="{{ $u['id'] ?? '' }}" />
<input type="hidden" name="csrfprotectionbysr" value="{{ $domainUserSaveCsrfToken ?? '' }}" />
</form>

<script>
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
}

document.getElementById('domainUserForm').addEventListener('submit', function() {
    var role = document.querySelector('input[name="role_key"]:checked').value;
    var src  = role === 'biller'
               ? document.getElementById('linked_id_biller')
               : document.getElementById('linked_id_customer');
    src.name = 'linked_id';
});
</script>

@endif
