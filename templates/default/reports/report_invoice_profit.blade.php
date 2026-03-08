<div class="card">
	<div class="card-body">
<form name="frmpost" action="index.php?module=reports&amp;view=report_invoice_profit" method="post">
<table class="table table-vcenter" align="center">
    <tr>
        <td wrap="nowrap">Start date (YYYY-MM-DD)
                <input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value="{{ $start_date ?? '' }}" />
         </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td wrap="nowrap">End date (YYYY-MM-DD)
                <input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value="{{ $end_date ?? '' }}" />
            </td>
    </tr>
</table>
<br />
<div class="mb-3">
            <button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['insert_biller'] ?? '' }}"><i class="ti ti-chart-bar me-1"></i>Run report</button>
</div>
</form>

<div class="mt-4"><h4>Profit per Invoice based on average product cost summary for the period {{ $start_date ?? '' }} to {{ $end_date ?? '' }}</h4></div>

<div class="table-responsive mt-3">
<table class="table table-vcenter" align="center">
    <tr>
        <td  class="details_screen">
            <b>ID</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>Biller</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>Customer</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Total</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Cost</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Profit</b>
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
            TOTALS:
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
