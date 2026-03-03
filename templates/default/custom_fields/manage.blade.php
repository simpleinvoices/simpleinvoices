{{-- /*
* Script: manage.tpl
* 	 Custom fields manage template
*
* License:
*	 GPL v2 or above
*/ --}}
<div class="card">
	<div class="card-body">
@if($cfs == null)
		<div class="alert alert-info mb-0">{{ $LANG['no_invoices'] ?? '' }}.</div>
@else
		<div id="manageGrid"></div>
		@include('templates.default.custom_fields.manage_js')

		<div class="mt-3">
			<a class="cluetip btn btn-outline-secondary btn-sm" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_what_are_custom_fields" title="{{ $LANG['what_are_custom_fields'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['what_are_custom_fields'] ?? '' }}</a>
			<a class="cluetip btn btn-outline-secondary btn-sm ms-1" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_manage_custom_fields" title="{{ $LANG['whats_this_page_about'] ?? '' }}"><i class="ti ti-help me-1"></i>{{ $LANG['whats_this_page_about'] ?? '' }}</a>
		</div>
@endif
	</div>
</div>


{{-- <table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style='width:7%;' />
		<col style='width:10%;' />
		<col style='width:43%;' />
		<col style='width:40%;' />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{{ $LANG['actions'] ?? '' }}</th>
			<th class="index_table sortable">{{ $LANG['id'] ?? '' }}</th>
			<th class="index_table sortable">{{ $LANG['custom_field'] ?? '' }}</th>
			<th class="index_table sortable">{{ $LANG['custom_label'] ?? '' }}</th>
		</tr>
	</thead>
	@foreach(($cfs ?? []) as $cf)
	<tr class="index_table">
		<td class="index_table">
			<a title="{{ urlencode($LANG['view'] ?? '' }}" class="index_table" href="index.php?module=custom_fields&amp;view=details&submit={{ $cf['cf_id'] ?? '') }}&action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle" alt="" /></a>
			<a title="{{ urlencode($LANG['edit'] ?? '' }}" class="index_table" href="index.php?module=custom_fields&amp;view=details&submit={{ $cf['cf_id'] ?? '') }}&action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" alt="" /></a> </td>
		<td class="index_table">{{ $cf['cf_id'] ?? '' }}</td>
		<td class="index_table">{{ $cf['filed_name'] ?? '' }}</td>
		<td class="index_table">{{ $cf['cf_custom_label'] ?? '' }}</td>
	</tr>
	@endforeach
</table> --}}
