<div class="si_center"><h2>Statement of Invoices</h2></div>
{if $menu}
  <form name="frmpost" action="index.php?module=statement&amp;view=index" method="post">
    <div class="si_form si_form_search{if $smarty.post.submit == null} si_form_search_null{/if}">
      <table>
        <tr style="margin: 0 auto; width: 100%;">
          <td style="text-align: left; padding-right: 10px; white-space: nowrap; width: 47%;">
            {$LANG.start_date}
          </td>
          <td>
            <input type="text" tabindex="10"
                   class="validate[required,custom[date],length[0,10]] date-picker"
                   size="10" name="start_date" id="date1" value='{$start_date|htmlsafe}' />
          </td>
        </tr>
        <tr style="margin: 0 auto; width: 100%;">
          <td style="text-align: left; padding-right: 10px; white-space: nowrap; width: 47%;">
            {$LANG.end_date}
          </td>
          <td>
            <input type="text" tabindex="20"
                   class="validate[required,custom[date],length[0,10]] date-picker"
                   size="10" name="end_date" id="date1" value='{$end_date|htmlsafe}' />
          </td>
        </tr>
        <tr>
          <th>{$LANG.biller}</th>
          <td>
            <select name="biller_id" tabindex="30" >
              {if $biller_count != 1}
              <option value=""></option>
              {/if}
              {foreach from=$billers item=list_biller}
              <option {if $list_biller.id==$biller_id} selected {/if} value="{$list_biller.id|htmlsafe}">
                {$list_biller.name|htmlsafe}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <th>{$LANG.customer}</th>
          <td>
            <select name="customer_id" tabindex="40" >
              {if $customer_count != 1}
              <option value=""></option>
              {/if}
              {foreach from=$customers item=list_customer}
              <option {if $list_customer.id== $customer_id} selected {/if} value="{$list_customer.id|htmlsafe}">
                {$list_customer.name|htmlsafe}
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <th>Do not {$LANG.filter_by_dates}</th>
          <td>
            <input type="checkbox" name="do_not_filter_by_date" tabindex="50"
                   {if $do_not_filter_by_date== "yes"} checked {/if} value="yes">
          </td>
        </tr>
        <tr>
          <th>{$LANG.show_only_unpaid_invoices}</th>
          <td>
            <input type="checkbox" name="show_only_unpaid" tabindex="60"
                   {if $show_only_unpaid== "yes"} checked {/if} value="yes">
          </td>
        </tr>
      </table>
      <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="statement_report">
          <img class="button_img" src="images/common/tick.png" alt="" />
          {$LANG.run_report}
        </button>
      </div>
      <br/><!-- Here to add space so calendar shows -->
    </div>
  </form>
  {if isset($smarty.post.submit)}
  <div class="si_toolbar si_toolbar_top">
    <a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}"
       href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=print">
      <img src='images/common/printer.png' class='action' />&nbsp;{$LANG.print_preview}
    </a>
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_pdf_tooltip}"
       href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=pdf">
      <img src='images/common/page_white_acrobat.png' class='action' />&nbsp;{$LANG.export_pdf}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet} {$LANG.format_tooltip}"
       href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->spreadsheet}">
       <img src='images/common/page_white_excel.png' class='action' />&nbsp;{$LANG.export_as}.{$config->export->spreadsheet}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor} {$LANG.format_tooltip}"
       href="index.php?module=statement&amp;view=export&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file&amp;filetype={$config->export->wordprocessor}">
       <img src='images/common/page_white_word.png' class='action' />&nbsp;{$LANG.export_as}.{$config->export->wordprocessor}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.id|htmlsafe}"
       href="index.php?module=statement&amp;view=email&amp;stage=1&amp;biller_id={$biller_id|urlencode}&amp;customer_id={$customer_id|urlencode}&amp;start_date={$start_date|urlencode}&amp;end_date={$end_date|urlencode}&amp;show_only_unpaid={$show_only_unpaid|urlencode}&amp;do_not_filter_by_date={$do_not_filter_by_date|urlencode}&amp;format=file">
       <img src='images/common/mail-message-new.png' class='action' />&nbsp;{$LANG.email}
    </a>
  </div>
  {/if}
{/if}
{if $smarty.post.submit != null || $view == export}
  {if !$menu}
  <hr />
  {/if}
  <div class="si_form" id="si_statement_info">
    <div class="si_statement_info1">
      <table>
        <tr>
          <th>{$LANG.biller}:</th>
          <td>{if empty($biller_details.name)}All{else}{$biller_details.name|htmlsafe}{/if}</td>
        </tr>
        <tr>
          <th>{$LANG.customer}:</th>
          <td>{if empty($customer_details.name)}All{else}{$customer_details.name|htmlsafe}{/if}</td>
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
  {if $do_not_filter_by_date != "yes"}
  <div>
    <strong>{$LANG.statement_for_the_period} {$start_date|htmlsafe} {$LANG.to_lowercase} {$end_date|htmlsafe}</strong>
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
      {assign var=i value=-1}
      {section name=invoice loop=$invoices}
{* Don't know why this doesn't work.
        {if $invoices[invoice].preference != $invoices[invoice.index_prev].preference && $smarty.section.invoice.index != 0}
 *}
        {if $invoices[invoice].preference != $invoices[$i].preference && $smarty.section.invoice.index != 0}
        <tr><td><br /></td></tr>
        {/if}
        {assign var=i value=$i+1}
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
