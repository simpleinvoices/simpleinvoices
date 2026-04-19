{{-- Domain Admin dashboard --}}
<div class="alert alert-info mb-3" role="status">
    <div class="fw-medium mb-1">Customer portal</div>
    <p class="small text-secondary mb-2">Give this link to customers so they can sign in for <strong>this organisation only</strong> (not staff or billers).</p>
    <code class="d-block small user-select-all text-break">{{ $customerPortalUrl ?? '' }}</code>
</div>

<div class="row row-cards mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Customer Accounts</div>
                <div class="h1 mb-3">{{ $customerUserCount }}</div>
                <a href="index.php?module=domain_admin&view=users&filter=customer" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-users me-1"></i>View
                </a>
                <a href="index.php?module=domain_admin&view=user_add&role=customer" class="btn btn-sm btn-primary ms-1">
                    <i class="ti ti-plus me-1"></i>Add
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Biller Accounts</div>
                <div class="h1 mb-3">{{ $billerUserCount }}</div>
                <a href="index.php?module=domain_admin&view=users&filter=biller" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-users me-1"></i>View
                </a>
                <a href="index.php?module=domain_admin&view=user_add&role=biller" class="btn btn-sm btn-primary ms-1">
                    <i class="ti ti-plus me-1"></i>Add
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Customers without Login</div>
                <div class="h1 mb-3 @if($unlinkedCustomers > 0) text-warning @endif">{{ $unlinkedCustomers }}</div>
                @if($unlinkedCustomers > 0)
                <a href="index.php?module=domain_admin&view=user_add&role=customer" class="btn btn-sm btn-warning">
                    <i class="ti ti-plus me-1"></i>Create Logins
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Billers without Login</div>
                <div class="h1 mb-3 @if($unlinkedBillers > 0) text-warning @endif">{{ $unlinkedBillers }}</div>
                @if($unlinkedBillers > 0)
                <a href="index.php?module=domain_admin&view=user_add&role=biller" class="btn btn-sm btn-warning">
                    <i class="ti ti-plus me-1"></i>Create Logins
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Domain Admin Actions</h3>
    </div>
    <div class="list-group list-group-flush">
        <a href="index.php?module=domain_admin&view=users" class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-auto"><span class="avatar bg-blue-lt"><i class="ti ti-users"></i></span></div>
                <div class="col">
                    <div class="fw-medium">Manage Login Accounts</div>
                    <div class="text-secondary small">View, edit, or disable customer and biller login accounts</div>
                </div>
                <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
            </div>
        </a>
        <a href="index.php?module=domain_admin&view=user_add" class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-auto"><span class="avatar bg-green-lt"><i class="ti ti-user-plus"></i></span></div>
                <div class="col">
                    <div class="fw-medium">Add Login Account</div>
                    <div class="text-secondary small">Create a login for an existing customer or biller</div>
                </div>
                <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
            </div>
        </a>
        <a href="index.php?module=customers&view=manage" class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-auto"><span class="avatar bg-purple-lt"><i class="ti ti-address-book"></i></span></div>
                <div class="col">
                    <div class="fw-medium">Manage Customers</div>
                    <div class="text-secondary small">Add or edit customer records in this domain</div>
                </div>
                <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
            </div>
        </a>
        <a href="index.php?module=billers&view=manage" class="list-group-item list-group-item-action">
            <div class="row align-items-center">
                <div class="col-auto"><span class="avatar bg-orange-lt"><i class="ti ti-building-store"></i></span></div>
                <div class="col">
                    <div class="fw-medium">Manage Billers</div>
                    <div class="text-secondary small">Add or edit biller records in this domain</div>
                </div>
                <div class="col-auto text-secondary"><i class="ti ti-chevron-right"></i></div>
            </div>
        </a>
    </div>
</div>
