<center>
<h2>Statement of Invoices</h2>
</center>
{if $menu != false}

{if $smarty.post.submit == null}
<div class="welcome">
{else}
<div class="">
{/if}
<form name="frmpost" action="index.php?module=statement&amp;view=index" method="post">

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
                            <option {if $list_biller.id == $biller_id} selected {/if} value="{$list_biller.id|htmlsafe}">{$list_biller.name|htmlsafe}</option>
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
                                <option {if $list_customer.id == $customer_id} selected {/if} value="{$list_customer.id|htmlsafe}">{$list_customer.name|htmlsafe}</option>
                            {/foreach}
                            </select>
                        {/if}
                    </td>
                </tr>
	<tr>
	<td class="details_screen">
		{$LANG.filter_by_dates}
	</td>
	<td class="">
		<input type="checkbox" name="filter_by_date"  {if $filter_by_date == "yes"} checked {/if} value="yes">
	</td>
	</tr>
    <tr>
        <td wrap="nowrap" class="details_screen">
		{$LANG.start_date}
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date1" value='{$start_date|htmlsafe}' />   
         </td>
	</tr>
	<tr>
        <td wrap="nowrap" class="details_screen"  >
		{$LANG.end_date}
	</td><td>
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="end_date" id="date1" value='{$end_date|htmlsafe}' />   
            </td>
    </tr>
	<tr>
	<td class="details_screen">
		{$LANG.show_only_unpaid_invoices}
	</td>
	<td class="">
		<input type="checkbox" name="show_only_unpaid"  {if $show_only_unpaid == "yes"} checked {/if} value="yes">
	</td>
	</tr>

<tr>
<td colspan="2"><br />
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="statement_report">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.run_report}
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
	{if $smarty.post.submit != null}
	<span class="welcome">
			<a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=print"><img src='images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}</a>
			 &nbsp;&nbsp; 
			 <!-- EXPORT TO PDF -->
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_pdf_tooltip}" href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=pdf"><img src='images/common/page_white_acrobat.png' class='action' />&nbsp;{$LANG.export_pdf}</a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet} {$LANG.format_tooltip}" href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->spreadsheet}"><img src='images/common/page_white_excel.png' class='action' />&nbsp;{$LANG.export_as} .{$config->export->spreadsheet}</a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor} {$LANG.format_tooltip}" href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->wordprocessor}"><img src='images/common/page_white_word.png' class='action' />&nbsp;{$LANG.export_as} .{$config->export->wordprocessor} </a>
			 &nbsp;&nbsp; 
			<a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}" href="index.php?module=statement&amp;view=email&amp;stage=1&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file"><img src='images/common/mail-message-new.png' class='action' />&nbsp;{$LANG.email}</a>
	</span>
	<br />
	<br />
	<br />
	{/if}

{/if}
{if $smarty.post.submit != null OR $view == export}

{if $menu == false}
<hr />
<br />
{/if}
<table width="100%">
<tr>
	<td width="75%" align="left">
		<strong>{$LANG.biller}:</strong> {$biller_details.name|htmlsafe} <br />
		<strong>{$LANG.customer}:</strong> {$customer_details.name|htmlsafe} <br />
		<br />	
		<br />	
	</td>
	<td width="25%">
		<strong>{$LANG.statement_summary}:</strong><br />
		<strong>{$LANG.total}:</strong> {$statement.total|siLocal_number} <br />
		<strong>{$LANG.owing}:</strong> {$statement.owing|siLocal_number} <br />
		<strong>{$LANG.paid}:</strong> {$statement.paid|siLocal_number} <br />
	</td>
</tr>
</table>
<br />
<br />
{if $filter_by_date == "yes"} 
<div class="align_left"><strong>{$LANG.statement_for_the_period} {$start_date|htmlsafe} {$LANG.to_lowercase} {$end_date|htmlsafe}</strong></div>
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
        </td>
        <td class="details_screen" align='right'>
            <b>{$LANG.owing}</b>
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
            {* TODO - JK edit this back in {$invoices[invoice].date|siLocal_date} *}
            {$invoices[invoice].date|date_format:"%e %h %Y"}
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
        </td>
        <td class="details_screen" align='right'>
            {$invoices[invoice].owing|siLocal_number}
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
        <td class="details_screen">
	    -----<br />
            {$statement.paid|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen" align='right'>
	    -----<br />
            {$statement.owing|siLocal_number}
        </td>
	</tr>
 </table>
{/if}
