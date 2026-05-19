{{-- Admin: domain list --}}
@if(!empty($domainSavedOp))
    <div class="alert alert-success mb-3">
        <i class="ti ti-check me-1"></i>
        @if($domainSavedOp === 'insert_domain')
            Domain and domain administrator account were created successfully.
        @else
            Domain saved successfully.
        @endif
    </div>
@endif
<div class="card">
    @if(empty($domains))
        <div class="alert alert-info mb-0">No domains found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Users</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domains as $d)
                    <tr>
                        <td class="text-secondary">{{ $d['id'] }}</td>
                        <td>
                            <span class="fw-medium">{{ $d['name'] }}</span>
                            @if((int)$d['id'] === 1)
                                <span class="badge bg-blue-lt ms-1">default</span>
                            @endif
                        </td>
                        <td>{{ $d['user_count'] }}</td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="index.php?module=admin&view=domain_edit&id={{ urlencode($d['id']) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="ti ti-edit me-1"></i>Edit
                                </a>
                                @if((int)$d['id'] !== 1 && (int)$d['user_count'] === 0)
                                <form method="post" action="index.php?module=admin&view=domain_edit&id={{ urlencode($d['id']) }}"
                                      onsubmit="return confirm('Delete domain \'{{ addslashes($d['name']) }}\'? This cannot be undone.');">
                                    <input type="hidden" name="op" value="delete_domain" />
                                    <input type="hidden" name="id" value="{{ $d['id'] }}" />
                                    <input type="hidden" name="csrfprotectionbysr" value="{{ siNonce('domain_save') }}" />
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="ti ti-trash me-1"></i>Delete
                                    </button>
                                </form>
                                @elseif((int)$d['id'] !== 1)
                                <button class="btn btn-sm btn-outline-danger" disabled
                                        title="Cannot delete - domain has users assigned">
                                    <i class="ti ti-trash me-1"></i>Delete
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
