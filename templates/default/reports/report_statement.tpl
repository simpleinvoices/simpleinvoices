<form name="frmpost" action="index.php?module=reports&amp;view=report_statement" method="post">

       <table align="center">

               <tr>
                      <td class="details_screen">
                               {$LANG.biller}
                       </td>
                       <td>
                           {if $billers == null }
                              <p><em>{$LANG.no_billers}</em></p>
                           {else}
                            <select name="biller_id">
                            {foreach from=$billers item=list_biller}
                            <option {if $list_biller.id == $biller} selected {/if} value="{$list_biller.id}">{$list_biller.name}</option>
                            {/foreach}
                            </select>
                            {/if}
                        </td>
                </tr>
                <tr>
                    <td class="details_screen">
                        {$LANG.customer}
                    </td>
                    <td>
                        {if $customers == null }
                        <em>{$LANG.no_customers}</em>
                        {else}
                            <select name="customer_id">
                            {foreach from=$customers item=list_customer}
                                <option {if $list_customer.id == $customer} selected {/if} value="{$list_customer.id}">{$list_customer.name}</option>
                            {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
    <tr>
        <td wrap="nowrap">Start date (YYYY-MM-DD)
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date}' />   
         </td>
	</tr>
	<tr>
        <td wrap="nowrap" >End date (YYYY-MM-DD)
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date}' />   
            </td>
    </tr>
	<tr>
	<td>
Show only unpaid invoices
	</td>
	<td>
		<input type="checkbox" name="only_unpaid_invoices"  {if $only_unpaid_invoices == "yes"} checked {/if} value="yes">
	</td>
	</tr>

<tr>
<td colspan="2"><br />
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                Run report
            </button>

        </td>
    </tr>
</table>
</td>
</tr>
</table>
</form>

<div id="top"><h3>Statement for the period {$start_date} to {$end_date}</h3></div>

<table align="center">
    <tr>
        <td  class="details_screen">
            <b>ID</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        <td  class="details_screen">
            <b>Date</b>
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
        <td  class="details_screen">
            <b>{$LANG.total}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.paid}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Owing</b>
        </td>
	</tr>
 {section name=invoice loop=$invoices}
    {if $invoices[invoice].preference != $invoices[invoice.index_prev].preference AND $smarty.section.invoice.index != 0}   
        <tr><td><br /></td></tr>
    {/if}
    <tr>
        <td class="details_screen">{$index}
            {$invoices[invoice].preference}
            {$invoices[invoice].index_id}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].date}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].biller}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].customer}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].invoice_total|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].INV_PAID|siLocal_number} 
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].owing|siLocal_number}
        </td>
	</tr>
 {/section}
 </table>
