<div class="si_center">
  <h2>Statement of Invoices</h2>
</div>
{if $menu != false}
<form name="frmpost" action="index.php?module=statement&amp;view=index"
  method="post">
  <div
    class="si_form si_form_search{if $smarty.post.submit == null} si_form_search_null{/if}">
    <table>
      <tr>
        <td class="details_screen">{$LANG.start_date}</td>
        <td>
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                 size="10" name="start_date" id="date1" value='{$start_date|htmlsafe}' />
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.end_date}</td>
        <td>
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                 size="10" name="end_date" id="date1" value='{$end_date|htmlsafe}' />
        </td>
      </tr>
      <tr>
        <th>{$LANG.biller}</th>
        <td>
        {if $billers == null }
          <p><em>{$LANG.no_billers}</em></p>
        {else}
          <select name="biller_id">
          {foreach from=$billers item=list_biller}
            <option {if $list_biller.id==$biller_id} selected {/if} value="{$list_biller.id|htmlsafe}">
              {$list_biller.name|htmlsafe}
            </option>
          {/foreach}
          </select>
        {/if}
        </td>
      </tr>
      <tr>
        <th>{$LANG.customer}</th>
        <td>
          {if $customers == null }
          <em>{$LANG.no_customers}</em>
          {else}
          <select name="customer_id">
            {foreach from=$customers item=list_customer}
            <option {if $list_customer.id==
              $customer_id} selected {/if} value="{$list_customer.id|htmlsafe}">{$list_customer.name|htmlsafe}</option>
            {/foreach}
          </select>
          {/if}
        </td>
      </tr>
      <tr>
        <th>{$LANG.filter_by_dates}</th>
        <td class="">
          <input type="checkbox" name="filter_by_date"{if $filter_by_date== "yes"} checked {/if} value="yes">
        </td>
      </tr>
      <tr>
        <th>{$LANG.show_only_unpaid_invoices}</th>
        <td class="">
          <input type="checkbox" name="show_only_unpaid" {if $show_only_unpaid== "yes"} checked {/if} value="yes">
        </td>
      </tr>
      <!-- Here to add space so calendar shows -->
    </table>
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="submit"
        value="statement_report">
        <img class="button_img" src="./images/common/tick.png" alt="" />
        {$LANG.run_report}
      </button>
    </div>
  </div>
</form>
{if $smarty.post.submit != null}
<div class="si_toolbar si_toolbar_top">
  <a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}"
     href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=print">
    <img src='images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}
  </a>
  <!-- EXPORT TO PDF -->
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_pdf_tooltip}"
     href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=pdf"><img
     src='images/common/page_white_acrobat.png' class='action' />
    &nbsp;{$LANG.export_pdf}
  </a>
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet} {$LANG.format_tooltip}"
     href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->spreadsheet}"><img
     src='images/common/page_white_excel.png' class='action' />
    &nbsp;{$LANG.export_as}.{$config->export->spreadsheet}
  </a>
  <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor} {$LANG.format_tooltip}"
     href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->wordprocessor}"><img
     src='images/common/page_white_word.png' class='action' />
    &nbsp;{$LANG.export_as}.{$config->export->wordprocessor}
  </a>
  <a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}"
    href="index.php?module=statement&amp;view=email&amp;stage=1&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;filter_by_date={$filter_by_date|urlencode}&amp;format=file"><img
    src='images/common/mail-message-new.png' class='action' />&nbsp;{$LANG.email}</a>
</div>
{/if}
{/if}
{if $smarty.post.submit != null OR $view == export}
  {if $menu == false}
  <hr />
  {/if}
  <div class="si_form" id="si_statement_info">
    <div class="si_statement_info1">
      <table>
        <tr>
          <th>{$LANG.biller}:</th>
          <td>{$biller_details.name|htmlsafe}</td>
        </tr>
        <tr>
          <th>{$LANG.customer}:</th>
          <td>{$customer_details.name|htmlsafe}</td>
        </tr>
      </table>
    </div>
    <div class="si_statement_info2">
      <table>
        <tr>
          <th>{$LANG.total}:</th>
          <td>{$statement.total|siLocal_number}</td>
        </tr>
        <tr>
        <th>{$LANG.paid}:</th>
        <td>{$statement.paid|siLocal_number}</td>
      </tr>
      <tr>
        <th>{$LANG.owing}:</th>
        <td>{$statement.owing|siLocal_number}</td>
      </tr>
    </table>
  </div>
</div>


{if $filter_by_date == "yes"}
<div>
  <strong>{$LANG.statement_for_the_period} {$start_date|htmlsafe}
    {$LANG.to_lowercase} {$end_date|htmlsafe}</strong>
</div>
<br />
{/if}

<div class="si_list">
  <table class="center" width="100%">
    <thead>
      <tr>
        <th class="si_right">{$LANG.id}</th>
        <th class="si_right">{$LANG.date_upper}</th>
        <th>{$LANG.biller}</th>
        <th>{$LANG.customer}</th>
        <th class="si_right">{$LANG.total}</th>
        <th class="si_right">{$LANG.paid}</th>
        <th class="si_right">{$LANG.owing}</th>
      </tr>
    </thead>
    <tbody>
      {section name=invoice loop=$invoices}
        {if $invoices[invoice].preference != $invoices[invoice.index_prev].preference AND $smarty.section.invoice.index != 0}
        <tr>
          <td><br /></td>
        </tr>
        {/if}
      <tr>
        <td class="si_right">
          {$index|htmlsafe}
          {$invoices[invoice].preference|htmlsafe}
          {$invoices[invoice].index_id|htmlsafe}
        </td>
        <td class="si_right">{$invoices[invoice].date|siLocal_date}</td>
        <td>{$invoices[invoice].biller|htmlsafe}</td>
        <td>{$invoices[invoice].customer|htmlsafe}</td>
        {if $invoices[invoice].status > 0}
          <td class="si_right">{$invoices[invoice].invoice_total|siLocal_number}</td>
          <td class="si_right">{$invoices[invoice].INV_PAID|siLocal_number}</td>
          <td class="si_right">{$invoices[invoice].owing|siLocal_number}</td>
        {else}
          <td class="si_right"><i>{$invoices[invoice].invoice_total|siLocal_number}</i></td>
          <td colspan="2">&nbsp;</td>
        {/if}
      </tr>
      {/section}
    </tbody>
    <tfoot>
      <tr>
        <td colspan=3></td>
        <th></th>
        <td class="si_right">{$statement.total|siLocal_number}</td>
        <td class="si_right">{$statement.paid|siLocal_number}</td>
        <td class="si_right">{$statement.owing|siLocal_number}</td>
      </tr>
    </tfoot>
  </table>
</div>
{/if}
