{{-- /*
* View: manage (Blade)
* 	 Tax Rates manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/ --}}

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0">{{ $LANG['tax_rates'] ?? 'Tax Rates' }}</h3>
        <a class="cluetip btn btn-sm btn-ghost-secondary" href="#"
           rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate"
           title="{{ $LANG['tax_rates'] ?? '' }}">
            <i class="ti ti-help me-1"></i>{{ $LANG['help'] ?? 'Help' }}
        </a>
    </div>
    <div class="card-body">
@if($taxes == null)
    <div class="alert alert-info mb-0">{{ $LANG['no_tax_rates'] ?? '' }}</div>
@else
    <div id="manageGrid"></div>
    @include('templates.default.tax_rates.manage_js')
@endif
    </div>
</div>
