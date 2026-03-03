<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['statement_of_invoices'] ?? 'Statement of Invoices' }}</h3>
	</div>
	<div class="card-body">
@if($menu != false)

<form name="frmpost" action="index.php?module=statement&amp;view=index" method="post">
<div class="si_form si_form_search@if(!form_submitted()) si_form_search_null@endif">
   <table class="table table-vcenter">
	   <tr>
			  <th>
					   {{ $LANG['biller'] ?? '' }}
			   </th>
			   <td>
				   @if($billers == null )
					  <p><em>{{ $LANG['no_billers'] ?? '' }}</em></p>
				   @else
						<select name="biller_id">
						@foreach(($billers ?? []) as $list_biller)
							<option @if($list_biller['id'] == $biller_id) selected @endif value="{{ $list_biller['id'] ?? '' }}">{{ $list_biller['name'] ?? '' }}</option>
						@endforeach
						</select>
					@endif
				</td>
		</tr>
		<tr>
			<th>
				{{ $LANG['customer'] ?? '' }}
			</th>
			<td>
				@if($customers == null )
				<em>{{ $LANG['no_customers'] ?? '' }}</em>
				@else
					<select name="customer_id">
					@foreach(($customers ?? []) as $list_customer)
						<option @if($list_customer['id'] == $customer_id) selected @endif value="{{ $list_customer['id'] ?? '' }}">{{ $list_customer['name'] ?? '' }}</option>
					@endforeach
					</select>
				@endif
			</td>
		</tr>
		<tr>
			<th>
				{{ $LANG['filter_by_dates'] ?? '' }}
			</th>
			<td class="">
				<input type="checkbox" name="filter_by_date"  @if($filter_by_date == "yes") checked @endif value="yes">
			</td>
		</tr>
		<tr>
			<td wrap="nowrap" class="details_screen">
				{{ $LANG['start_date'] ?? '' }}
			</td>
			<td>
				<input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{{ $start_date ?? '' }}' />   
			 </td>
		</tr>
		<tr>
			<td wrap="nowrap" class="details_screen"  >
				{{ $LANG['end_date'] ?? '' }}
			</td>
			<td>
				<input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{{ $end_date ?? '' }}' />   
			</td>
		</tr>
		<tr>
			<th>
				{{ $LANG['show_only_unpaid_invoices'] ?? '' }}
			</th>
			<td class="">
				<input type="checkbox" name="show_only_unpaid"  @if($show_only_unpaid == "yes") checked @endif value="yes">
			</td>
		</tr>
	</table>

	<div class="card-footer">
		<button type="submit" class="btn btn-primary" name="submit" value="statement_report"><i class="ti ti-chart-bar me-1"></i>{{ $LANG['run_report'] ?? '' }}</button>
	</div>
</div>
</form>


	@if(form_submitted())
	<div class="btn-list mb-3">
			<a title="{{ urlencode($LANG['print_preview_tooltip'] ?? '' }}" href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ $start_date ?? '' }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=print" class="btn btn-outline-primary btn-sm"><i class="ti ti-printer me-1"></i>{{ $LANG['print_preview'] ?? '' }}</a>
			<a title="{{ urlencode($LANG['export_pdf_tooltip'] ?? '' }}" href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=pdf" class="btn btn-outline-secondary btn-sm"><i class="ti ti-file-type-pdf me-1"></i>{{ $LANG['export_pdf'] ?? '' }}</a>
			<a title="{{ urlencode($LANG['export_xls_tooltip'] ?? '' }}" href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file&amp;filetype=xls" class="btn btn-outline-secondary btn-sm"><i class="ti ti-file-spreadsheet me-1"></i>{{ $LANG['export_as'] ?? '' }} .xls</a>
			<a title="{{ urlencode($LANG['export_doc_tooltip'] ?? '' }}" href="index.php?module=statement&amp;view=export&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file&amp;filetype=doc" class="btn btn-outline-secondary btn-sm"><i class="ti ti-file-text me-1"></i>{{ $LANG['export_as'] ?? '' }} .doc</a>
			<a title="{{ urlencode($LANG['email'] ?? '' }}" href="index.php?module=statement&amp;view=email&amp;stage=1&amp;biller_id={{ $biller_id ?? '' }}&amp;customer_id={{ urlencode($customer_id ?? '') }}&amp;start_date={{ urlencode($start_date ?? '') }}&amp;end_date={{ urlencode($end_date ?? '') }}&amp;show_only_unpaid={{ urlencode($show_only_unpaid ?? '') }}&amp;filter_by_date={{ urlencode($filter_by_date ?? '') }}&amp;format=file" class="btn btn-outline-secondary btn-sm"><i class="ti ti-mail me-1"></i>{{ $LANG['email'] ?? '' }}</a>
	</div>
	@endif

@endif


@if(form_submitted() OR $view == export)

@if($menu == false)
<hr />

@endif

<div class="si_form" id="si_statement_info">
	<div class="si_statement_info1">
		<table>
			<tr>
				<th>{{ $LANG['biller'] ?? '' }}:</th>	<td>{{ $biller_details['name'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['customer'] ?? '' }}:</th>	<td>{{ $customer_details['name'] ?? '' }}</td>
			</tr>
		</table>
	</div>
	<div class="si_statement_info2">
		<table>
			<tr>
				<th>{{ $LANG['total'] ?? '' }}:</th>	<td>{{ siLocal::number($statement['total'] ?? 0) }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['paid'] ?? '' }}:</th>	<td>{{ siLocal::number($statement['paid'] ?? 0) }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['owing'] ?? '' }}:</th>	<td>{{ siLocal::number($statement['owing'] ?? 0) }}</td>
			</tr>
		</table>
	</div>
</div>


@if($filter_by_date == "yes") 
	<div><strong>{{ $LANG['statement_for_the_period'] ?? '' }} {{ $start_date ?? '' }} {{ $LANG['to_lowercase'] ?? '' }} {{ $end_date ?? '' }}</strong></div>
<br />
@endif

<div class="table-responsive">
	<table class="table table-vcenter table-striped" align="center" width="100%">
		<thead>
			<tr>
				<th class="si_right">{{ $LANG['id'] ?? '' }}</th>
				<th class="si_right">{{ $LANG['date_upper'] ?? '' }}</th>
				<th>{{ $LANG['biller'] ?? '' }}</th>
				<th>{{ $LANG['customer'] ?? '' }}</th>
				<th class="si_right">{{ $LANG['total'] ?? '' }}</th>
				<th class="si_right">{{ $LANG['paid'] ?? '' }}</th>
				<th class="si_right">{{ $LANG['owing'] ?? '' }}</th>
			</tr>
		</thead>
		<tbody>
		@foreach(($invoices ?? []) as $invoice)
			@if($loop->index > 0 && (($invoices[$loop->index - 1]['preference'] ?? '') != ($invoice['preference'] ?? '')))
				<tr><td colspan="7"><br /></td></tr>
			@endif
			<tr>
				<td class="si_right">{{ $invoice['preference'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
				<td class="si_right">{{ siLocal::date($invoice['date'] ?? '') }}</td>
				<td>{{ $invoice['biller'] ?? '' }}</td>
				<td>{{ $invoice['customer'] ?? '' }}</td>
			@if(($invoice['status'] ?? 0) > 0)
				<td class="si_right">{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</td>
				<td class="si_right">{{ siLocal::number($invoice['inv_paid'] ?? $invoice['INV_PAID'] ?? 0) }}</td>
				<td class="si_right">{{ siLocal::number($invoice['owing'] ?? 0) }}</td>
			@else
				<td class="si_right"><i>{{ siLocal::number($invoice['invoice_total'] ?? 0) }}</i></td>
				<td colspan="2">&nbsp;</td>
			@endif
			</tr>
		@endforeach
			</tbody>
			<tfoot>		
				<tr>
					<td colspan=3></td>
					<th></th>
					<td class="si_right">
						{{ siLocal::number($statement['total'] ?? 0) }}
					</td>
					<td class="si_right">
						{{ siLocal::number($statement['paid'] ?? 0) }}
					</td>
					<td class="si_right">
						{{ siLocal::number($statement['owing'] ?? 0) }}
					</td>
				</tr>
			</tfoot>
	 </table>
</div>
@endif
	</div>
</div>
