
<form name="frmpost" action="index.php?module=reports&amp;view=report_summary" method="post">
<table align="center">
    <tr>
        <td wrap="nowrap">Start date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date}' />   
         </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td wrap="nowrap" >End date (YYYY-MM-DD)
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date}' />   
            </td>
    </tr>
</table>
<br />
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
</form>
<div id="top"><h3>Expense account summary for the period {$start_date} to {$end_date}</h3></div>

<table align="center">
    <tr>
        <td  class="details_screen">
            <b>Account</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Amount</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Tax</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Total</b>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Status</b>
        </td>
        </td>
	</tr>
 {foreach item=account from=$accounts}
    <tr>
        <td class="details_screen">
            {$account.account}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$account.expense|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {if $account.tax != ""}
                {$account.tax|siLocal_number}
            {/if}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
        {if $account.total != ""}
            {$account.total|siLocal_number}
        {else}
            {$account.expense|siLocal_number}
        {/if}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$account.status_wording}
        </td>
	</tr>
 {/foreach}
 </table>

<div id="top"><h3>Invoice/Quote summary for the period {$start_date} to {$end_date}</h3></div>

<table align="center">
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
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Amount</b>
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
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].invoice_total|siLocal_number}
        </td>
	</tr>
 {/section}
 </table>


<div id="top"><h3>Payment summary for the period {$start_date} to {$end_date}</h3></div>

<table align="center">
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
        <td  class="details_screen">
            <b>Type</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>Amount</b>
        </td>
	</tr>
 {foreach item=payment from=$payments}
    <tr>
        <td class="details_screen">
            {$payment.id}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.bname}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.cname}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.type}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$payment.ac_amount|siLocal_number}
        </td>
	</tr>
 {/foreach}
 </table>
    
