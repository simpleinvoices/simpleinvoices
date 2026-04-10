{{-- /*
* View: manage (Blade)
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
@if($preferences == null)
	<div class="alert alert-info mb-0">{{ $LANG['no_preferences'] ?? '' }}</div>
@else
	<div id="manageGrid"></div>
	@include('templates.default.preferences.manage_js')
@endif

	<div class="mt-3">
		<a class="cluetip btn btn-outline-secondary" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{{ $LANG['whats_all_this_inv_pref'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_all_this_inv_pref'] ?? '' }}</a>
	</div>
</div>
