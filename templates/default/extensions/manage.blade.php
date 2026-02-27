{{-- /*
* Script: manage.tpl
* 	 Extensions manage template
*
* Authors:
*	 Justin Kelly, Ben Brown, Marcel van Dorp
*
* Last edited:
* 	 2009-02-12
*
* License:
*	 GPL v2 or above
*/ --}}
<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="card-title mb-0">{{ $LANG['extensions'] ?? 'Extensions' }}</h3>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="alert alert-info mb-3">
			Note: Manage extensions is still a work-in-progress
		</div>

@if($exts == null)
		<p class="text-muted mb-0"><em>No extensions registered</em></p>
@else
		<div id="manageGrid"></div>
		@include('templates.default.extensions.manage_js')
@endif
	</div>
</div>
