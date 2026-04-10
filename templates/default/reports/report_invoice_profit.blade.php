<div class="card">
<form name="frmpost" id="form_report_invoice_profit" action="index.php?module=reports&amp;view=report_invoice_profit" method="post">
	<div class="card-body">
<table class="table table-vcenter" align="center">
    <tr>
        <td wrap="nowrap">{{ $LANG['start_date'] ?? '' }}
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                    <input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value="{{ $start_date ?? '' }}" />
                </div>
         </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td wrap="nowrap">{{ $LANG['end_date'] ?? '' }}
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                    <input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value="{{ $end_date ?? '' }}" />
                </div>
            </td>
    </tr>
</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=reports&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['run_report'] ?? '' }}"><i class="ti ti-chart-bar me-1"></i>{{ $LANG['run_report'] ?? '' }}</button>
		</div>
	</div>
</form>

<div class="card-body border-top">

<div class="mt-4"><h4>{{ strtr($LANG['profit_per_invoice_summary'] ?? '', ['{start_date}' => $start_date ?? '', '{end_date}' => $end_date ?? '']) }}</h4></div>

<div class="table-responsive mt-3">
<table class="table table-vcenter" align="center">
    <tr>
        <td  class="details_screen">
            <b>{{ $LANG['id'] ?? '' }}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{{ $LANG['biller'] ?? '' }}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{{ $LANG['customer'] ?? '' }}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{{ $LANG['total'] ?? '' }}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{{ $LANG['cost'] ?? '' }}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{{ $LANG['profit'] ?? '' }}</b>
        </td>
	</tr>
@foreach(($invoices ?? []) as $invoice)
    @if($loop->index > 0 && (($invoices[$loop->index - 1]['preference'] ?? '') != ($invoice['preference'] ?? '')))
        <tr><td colspan="11"><br /></td></tr>
    @endif
    <tr>
        <td class="details_screen">{{ $invoice['preference'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {{ $invoice['biller'] ?? '' }}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {{ $invoice['customer'] ?? '' }}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice['invoice_total'] ?? 0) }}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice['cost'] ?? 0) }}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice['profit'] ?? 0) }}
        </td>
	</tr>
	@endforeach
 
    <tr>
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            ---
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            ---
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            ---
        </td>
	</tr>
    <tr>
        <td class="details_screen">
            {{ strtoupper($LANG['totals'] ?? '') }}:
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice_totals['sum_total'] ?? '') }}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice_totals['sum_cost'] ?? '') }}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {{ siLocal::number($invoice_totals['sum_profit'] ?? '') }}
        </td>
	</tr>

 </table>
</div>
</div>
</div>
