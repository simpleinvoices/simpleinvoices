{{-- Domain Admin: list customer and biller login accounts --}}
@php
    $filter = get('filter') ?? '';
    $filtered = array_values(array_filter($domainUsers ?? [], function($u) use ($filter) {
        return !$filter || $u['role_name'] === $filter;
    }));
@endphp

<div class="card">
    {{-- Filter tabs --}}
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link @if(!$filter) active @endif"
                   href="index.php?module=domain_admin&view=users">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($filter === 'customer') active @endif"
                   href="index.php?module=domain_admin&view=users&filter=customer">
                    <i class="ti ti-address-book me-1"></i>Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($filter === 'biller') active @endif"
                   href="index.php?module=domain_admin&view=users&filter=biller">
                    <i class="ti ti-building-store me-1"></i>Billers
                </a>
            </li>
        </ul>
    </div>

    @if(empty($filtered))
        <div class="card-body">
            <div class="empty">
                <div class="empty-icon"><i class="ti ti-users-group" style="font-size:3rem;"></i></div>
                <p class="empty-title">No login accounts yet</p>
                <p class="empty-subtitle text-secondary">
                    Create a login to give a customer or biller portal access.
                </p>
                <div class="empty-action">
                    <a href="index.php?module=domain_admin&view=user_add" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Add Login Account
                    </a>
                </div>
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
                        $linkedName = $u['role_name'] === 'customer'
                            ? ($u['customer_name'] ?? '<span class="text-danger">unlinked</span>')
                            : ($u['biller_name']   ?? '<span class="text-danger">unlinked</span>');
                        $roleIcon = $u['role_name'] === 'customer' ? 'ti-address-book' : 'ti-building-store';
                        $roleBadge = $u['role_name'] === 'customer' ? 'bg-blue-lt' : 'bg-orange-lt';
                    @endphp
                    <tr>
                        <td class="fw-medium">{{ $u['name'] ?: '—' }}</td>
                        <td class="text-secondary">{{ $u['email'] }}</td>
                        <td>
                            <span class="badge {{ $roleBadge }}">
                                <i class="ti {{ $roleIcon }} me-1"></i>{{ ucfirst($u['role_name']) }}
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
                            <a href="index.php?module=domain_admin&view=user_edit&id={{ urlencode($u['id']) }}"
                               class="btn btn-sm btn-outline-secondary">
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
