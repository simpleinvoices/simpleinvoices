{{-- /*
* View: quick_view (Blade)
* 	 Quick view of invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

@if(get('stage') == 1 )

	<br />
    	    @if($invoicePaid == 0)
				<div class="card">
					<div class="card-body">
						<p>{{ $LANG['confirm_delete'] ?? '' }} {{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? '' }}</p>
						<form name="frmpost" action="index.php?module=invoices&amp;view=delete&amp;stage=2&amp;id={{ urlencode(get('id')) }}" method="post">
							<div class="btn-list">
								<button type="submit" class="btn btn-danger" name="submit">
									<i class="ti ti-check me-1"></i>{{ $LANG['yes'] ?? '' }}
								</button>
								<input type="hidden" name="doDelete" value="y" />
								<a href="./index.php?module=invoices&amp;view=manage" class="btn btn-secondary">
									<i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}
								</a>
							</div>
						</form>
					</div>
				</div>
	        @endif
	
	        @if($invoicePaid != 0)
				<div class="alert alert-warning">
					{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $invoice['index_id'] ?? '' }} {{ $LANG['delete_has_payments1'] ?? '' }} {{ $preference['pref_currency_sign'] }} {{ siLocal::number($invoicePaid ?? 0) }} {{ $LANG['delete_has_payments2'] ?? '' }}
				</div>
    	    @endif

@endif

@if(get('stage') == 2 )

	<div class="alert alert-success">
		{{ $preference['pref_inv_wording'] ?? ($LANG['invoice'] ?? '') }} {{ $id ?? '' }} {{ $LANG['deleted'] ?? '' }}
	</div>

@endif
