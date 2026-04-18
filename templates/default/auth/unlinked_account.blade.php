{{-- Shown when a customer or biller login account has no linked entity --}}
<div class="container-xl py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-link-off text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="card-title">Account Not Linked</h3>
                    <p class="text-secondary mb-4">
                        Your login account has not been linked to a
                        @if(($unlinkedRole ?? '') === 'biller')
                            biller
                        @else
                            customer
                        @endif
                        record yet. Please contact your administrator to have your account set up.
                    </p>
                    <a href="index.php?module=auth&view=logout" class="btn btn-outline-secondary">
                        <i class="ti ti-logout me-1"></i>Log out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
