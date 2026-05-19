{{-- Domain Admin: list all login accounts in this domain --}}
@php
    $filter = get('filter') ?? '';
    $filtered = array_values(array_filter($allDomainUsers ?? [], function($u) use ($filter) {
        return !$filter || $u['role_name'] === $filter;
    }));
    $roleConfig = [
        'customer'             => ['icon' => 'ti-address-book',  'badge' => 'bg-blue-lt',   'label' => 'Customer'],
        'biller'               => ['icon' => 'ti-building-store', 'badge' => 'bg-orange-lt', 'label' => 'Biller'],
        'domain_administrator' => ['icon' => 'ti-shield-lock',   'badge' => 'bg-purple-lt', 'label' => 'Domain Admin'],
        'administrator'        => ['icon' => 'ti-shield-check',  'badge' => 'bg-red-lt',    'label' => 'Admin'],
    ];
@endphp

@if(!empty($userSavedOp))
    <div class="alert alert-success mb-3">
        <i class="ti ti-check me-1"></i>
        @if($userSavedOp === 'insert_user')
            User was added successfully.
        @else
            User was updated successfully.
        @endif
    </div>
@endif

<div class="card">
    {{-- Filter tabs --}}
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link @if(!$filter) active @endif"
                   href="index.php?module=domain_admin&view=all_users">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($filter === 'customer') active @endif"
                   href="index.php?module=domain_admin&view=all_users&filter=customer">
                    <i class="ti ti-address-book me-1"></i>Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($filter === 'biller') active @endif"
                   href="index.php?module=domain_admin&view=all_users&filter=biller">
                    <i class="ti ti-building-store me-1"></i>Billers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($filter === 'domain_administrator') active @endif"
                   href="index.php?module=domain_admin&view=all_users&filter=domain_administrator">
                    <i class="ti ti-shield-lock me-1"></i>Domain Admins
                </a>
            </li>
        </ul>
    </div>

    @if(empty($filtered))
        <div class="card-body">
            <div class="empty">
                <div class="empty-icon"><i class="ti ti-users-group" style="font-size:3rem;"></i></div>
                <p class="empty-title">No users found</p>
                @if($filter)
                    <p class="empty-subtitle text-secondary">No {{ $filter }} accounts in this domain.</p>
                    <div class="empty-action">
                        <a href="index.php?module=domain_admin&view=all_users" class="btn btn-outline-secondary">
                            <i class="ti ti-x me-1"></i>Clear filter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Linked To</th>
                        <th>Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filtered as $u)
                    @php
                        $rc = $roleConfig[$u['role_name']] ?? ['icon' => 'ti-user', 'badge' => 'bg-secondary-lt', 'label' => ucfirst($u['role_name'])];
                        $linkedName = match($u['role_name']) {
                            'customer' => $u['customer_name'] ?? '<span class="text-danger">unlinked</span>',
                            'biller'   => $u['biller_name']   ?? '<span class="text-danger">unlinked</span>',
                            default    => '-',
                        };
                        $editUrl = in_array($u['role_name'], ['customer', 'biller'])
                            ? 'index.php?module=domain_admin&view=user_edit&id=' . urlencode($u['id'])
                            : 'index.php?module=user&view=details&action=edit&id=' . urlencode($u['id'])
                                . '&return_module=domain_admin&return_view=all_users';
                    @endphp
                    <tr>
                        <td class="fw-medium">{{ $u['name'] ?: '-' }}</td>
                        <td class="text-secondary">{{ $u['email'] }}</td>
                        <td>
                            <span class="badge {{ $rc['badge'] }}">
                                <i class="ti {{ $rc['icon'] }} me-1"></i>{{ $rc['label'] }}
                            </span>
                        </td>
                        <td>{!! $linkedName !!}</td>
                        <td>
                            @if($u['enabled'])
                                <span class="badge bg-success-lt">Enabled</span>
                            @else
                                <span class="badge bg-danger-lt">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ $editUrl }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-edit me-1"></i>Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
