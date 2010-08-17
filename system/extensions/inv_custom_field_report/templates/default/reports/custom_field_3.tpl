<center>
<h2>Sales Report</h2>
</center>
{if $menu != false}

<div class="welcome">
<form name="frmpost" action="index.php?module=reports&amp;view=custom_field_3" method="post">

       <table align="center">

               <tr>
                      <td class="details_screen">
                               Sales Rep
                       </td>
                       <td>
                           {if $cf3 == null }
                              <p><em>No sales reps</em></p>
                           {else}
                            <select name="custom_field3">
                            {foreach from=$cf3 item=list_biller}
                            <option {if $list_biller.id == $biller_id} selected {/if} value="{$list_biller.custom_field3}">{$list_biller.custom_field3}</option>
                            {/foreach}
                            </select>
                            {/if}
                        </td>
                </tr>
	<tr>
	<td class="details_screen">
		Filter by date
	</td>
	<td class="">
		<input type="checkbox" name="filter_by_date"  {if $filter_by_date == "yes"} checked {/if} value="yes">
	</td>
	</tr>
    <tr>
        <td wrap="nowrap" class="details_screen">
		Start date
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date}' />   
         </td>
	</tr>
	<tr>
        <td wrap="nowrap" class="details_screen"  >
		End date
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date}' />   
            </td>
    </tr>

<tr>
<td colspan="2"><br />
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="statement_report">
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
</div>
<br />
<br />

{/if}
{if $smarty.post.submit != null OR $view == export}

{if $menu == false}
<hr />
<br />
{/if}
<table width="100%">
<tr>
	<td width="75%" align="left">
		<strong>Sales Rep:</strong> {$custom_field3} <br />
		<br />
	</td>
	<td width="25%">
		<strong>Sales summary:</strong><br />
		<strong>{$LANG.total}:</strong> {$statement.total|siLocal_number} <br />
	</td>
</tr>
</table>
<br />
<br />
{if $filter_by_date == "yes"} 
<div class="align_left"><strong>{$LANG.statement_for_the_period} {$start_date} {$LANG.to_lowercase} {$end_date}</strong></div>
<br />
{/if}

<table align="center" width="100%">
    <tr>
        <td  class="details_screen">
            <b>{$LANG.id}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        <td  class="details_screen">
            <b>{$LANG.date}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.biller}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.customer}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.total}</b>
        </td>
	</tr>
 {section name=invoice loop=$invoices}
    {if $invoices[invoice].preference != $invoices[invoice.index_prev].preference AND $smarty.section.invoice.index != 0}   
        <tr><td><br /></td></tr>
    {/if}
    <tr>
        <td class="details_screen">{$index}
            {$invoices[invoice].id}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {* TODO - JK edit this back in {$invoices[invoice].date|siLocal_date} *}
            {* $invoices[invoice].date|date_format:"%e %h %Y" *}
            {$invoices[invoice].date|siLocal_date}
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
        <td class="details_screen">
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
	    -----<br />
            {$statement.total|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
	</tr>
 </table>
{/if}
