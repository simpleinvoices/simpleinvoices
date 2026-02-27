{{-- /*
* Script: manage.tpl
* 	 Invoice Preferences manage template
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
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['invoice_preferences'] ?? 'Invoice Preferences' }}</h3>
			</div>
			<div class="col-auto">
				<a href="./index.php?module=preferences&amp;view=add" class="btn btn-primary">
					<i class="ti ti-plus me-1"></i>{{ $LANG['add_new_preference'] ?? '' }}
				</a>
			</div>
		</div>
	</div>
	<div class="card-body">
@if($preferences == null)
		<div class="alert alert-info mb-0">{{ $LANG['no_preferences'] ?? '' }}</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.preferences.manage_js')
@endif

		<div class="mt-3">
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
		</div>
	</div>
</div>
