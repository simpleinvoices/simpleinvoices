{{-- Admin: list all domain_administrator users across all domains --}}
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
    @if(empty($domainAdminUsers))
        <div class="card-body">
            <div class="empty">
                <div class="empty-icon"><i class="ti ti-shield-lock" style="font-size:3rem;"></i></div>
                <p class="empty-title">No domain admin accounts found</p>
                <p class="empty-subtitle text-secondary">
                    Domain admin accounts are created when a new domain is added.
                </p>
                <div class="empty-action">
                    <a href="index.php?module=admin&view=domain_add" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Add Domain
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Domain</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domainAdminUsers as $u)
                    <tr>
                        <td>
                            <span class="badge bg-purple-lt">
                                <i class="ti ti-building me-1"></i>{{ $u['domain_name'] }}
                            </span>
                        </td>
                        <td class="fw-medium">{{ $u['name'] ?: '-' }}</td>
                        <td class="text-secondary">{{ $u['email'] }}</td>
                        <td>
                            @if($u['enabled'])
                                <span class="badge bg-success-lt">Enabled</span>
                            @else
                                <span class="badge bg-danger-lt">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <a href="index.php?module=user&view=details&amp;action=edit&amp;id={{ urlencode($u['id']) }}&amp;return_module=admin&amp;return_view=domain_admin_users"
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
