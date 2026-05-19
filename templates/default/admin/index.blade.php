{{-- Admin dashboard --}}
<div class="row row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Domains</div>
                </div>
                <div class="h1 mb-3">{{ $domainCount }}</div>
                <a href="index.php?module=admin&view=domains" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-building me-1"></i>Manage Domains
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Users</div>
                </div>
                <div class="h1 mb-3">{{ $userCount }}</div>
                <a href="index.php?module=user&view=manage" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-users me-1"></i>Manage Users
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Admin Actions</h3>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php?module=admin&view=app_settings" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar bg-azure-lt"><i class="ti ti-palette"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-medium">{{ $LANG['admin_app_appearance'] ?? 'App appearance' }}</div>
                            <div class="text-secondary small">{{ $LANG['admin_app_appearance_help'] ?? 'Product name, logo, and footer links for all domains' }}</div>
                        </div>
                        <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
                    </div>
                </a>
                <a href="index.php?module=admin&view=domains" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar bg-blue-lt"><i class="ti ti-building"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-medium">Domain Management</div>
                            <div class="text-secondary small">Create, rename, or delete tenant domains</div>
                        </div>
                        <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
                    </div>
                </a>
                <a href="index.php?module=user&view=manage" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar bg-green-lt"><i class="ti ti-users"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-medium">User Management</div>
                            <div class="text-secondary small">Add, edit, or disable user accounts</div>
                        </div>
                        <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
                    </div>
                </a>
                <a href="index.php?module=options&view=index" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar bg-yellow-lt"><i class="ti ti-adjustments-horizontal"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-medium">System Options</div>
                            <div class="text-secondary small">Backup, SQL patches, and system settings</div>
                        </div>
                        <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
