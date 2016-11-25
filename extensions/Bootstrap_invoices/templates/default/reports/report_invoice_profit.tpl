<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.profit_per_invoice}</h1>

<form name="frmpost" action="index.php?module=reports&amp;view=report_invoice_profit" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="start_date" class="col-sm-3 control-label details_screen">Start date (YYYY-MM-DD) </label>
                <div class="col-sm-6"><input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date|htmlsafe}' /></div>
    </div>
    <div class="form-group">
        <label for="start_date" class="col-sm-3 control-label details_screen">End date (YYYY-MM-DD) </label>
                 <div class="col-sm-6"><input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date|htmlsafe}' /></div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-3 col-sm-6">
        <button type="submit" class="btn btn-default positive" name="submit" value="{$LANG.insert_biller}">
             <span class="glyphicon glyphicon-ok"></span> 
            Run report
        </button>
    </div>
    </div>
</form>

<h3>Profit per Invoice based on average product cost summary for the period {$start_date|htmlsafe} to {$end_date|htmlsafe}</h3>

<div class="table-responsive">
<table class="table table-striped table-hover">
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
 {section name=invoice loop=$invoices}
    {if $invoices[invoice].preference != $invoices[invoice.index_prev].preference AND $smarty.section.invoice.index != 0}   
        <tr><td><br /></td></tr>
    {/if}
    <tr>
        <td class="details_screen">{$index|htmlsafe}
            {$invoices[invoice].preference|htmlsafe}
            {$invoices[invoice].index_id|htmlsafe}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].biller|htmlsafe}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].customer|htmlsafe}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].invoice_total|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].cost|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].profit|siLocal_number}
        </td>
	</tr>
 {/section}
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
            {$invoice_totals.sum_total|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoice_totals.sum_cost|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoice_totals.sum_profit|siLocal_number}
        </td>
	</tr>

 </table>
    </div>
