<h1 style="position: relative; margin: 0 auto; text-align: center;">Net Income Report</h1>
<hr />
<form name="frmpost"
      action="index.php?module=reports&amp;view=net_income_report"
      method="post">
  <table class="center" >
    <tr>
      <td colspan="2"
        style="font-weight: bold; font-size: 1.5em; text-align: center; text-decoration: underline;">
        Report Period</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr style="margin: 0 auto; width: 100%;">
      <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
        Start date:
      </td>
      <td>
        <input type="text"
               class="validate[required,custom[date],length[0,10]] date-picker"
               size="10" name="start_date" id="date1" value='{$start_date}' />
      </td>
    </tr>
    <tr style="margin: 0, auto; width: 100%">
      <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
        End date:
      </td>
      <td>
        <input type="text"
               class="validate[required,custom[date],length[0,10]] date-picker"
               size="10" name="end_date" id="date2" value='{$end_date}' />
      </td>
    </tr>
    {if $custom_flags_enabled == '1'}
    <tr>
      <td style="text-align: right; padding-right: 10px; white-space: nowrap; width: 47%;">
        Exclude Custom Flag #:
      </td>
      <td>
        <select name="custom_flag">
          <option value="0">None</option>
          {foreach from=$custom_flag_labels key=ndx item=label}
            {if $label != ''}
              <option value="{$ndx+1}" {if $custom_flag - 1 == $ndx} selected {/if}>
                      {$ndx+1}&nbsp;-&nbsp;{$label}
              </option>
            {/if}
          {/foreach}
        </select>
      </td>
    </tr>
    {/if}
    <tr>
      <td colspan="2">
        <br />
        <table class="center">
          <tr>
            <td>
              <button type="submit" class="positive" name="submit" value="statement_report">
                <img class="button_img" src="./images/common/tick.png" alt="" />
                Run Report
              </button>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
  {if $smarty.post.submit == null}
  <br />
  <br />
  <br />
  <br />
  <br />
  {/if}
</form>
{if $smarty.post.submit != null OR $view == export}
<div style="text-align: center;">
  <strong>
    {$LANG.total_income}&nbsp;{$LANG.for_the_period_upper}:&nbsp;&#36;{$tot_income|siLocal_number}
  </strong>
</div>
<br />
<table class="center" style="width:90%" >
  <thead>
    <tr style="font-weight: bold;">
      <th class="details_screen" width="8%" style="text-align:right;">{$LANG.invoice}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="12%" style="text-align:center;">{$LANG.date_upper}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" style="text-align:center;">{$LANG.customer}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="13%" style="text-align:right;">{$LANG.invoice_total}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="13%" style="text-align:right;">{$LANG.total_paid}</th>
      <th class="details_screen" width="2%"></th>
      <th class="details_screen" width="13%" style="text-align:right;">{$LANG.total_paid_this_period}</th>
    </tr>
  </thead>
  <tbody>
    {section name=idx loop=$invoices}
    <tr>
      <td class="details_screen" style="text-align:right;">{$invoices[idx]->number}</td>
      <td>&nbsp;</td>
      <td class="details_screen" style="text-align:center;">{$invoices[idx]->date|date_format:"%m/%d/%Y"}</td>
      <td>&nbsp;</td>
      <td class="details_screen">{$invoices[idx]->customer}</td>
      <td>&nbsp;</td>
      <td class="details_screen" style="text-align:right;">
        {$invoices[idx]->total_amount|siLocal_number}
      </td>
      <td>&nbsp;</td>
      <td class="details_screen" style="text-align:right;">
        {$invoices[idx]->total_payments|siLocal_number}
      </td>
      <td>&nbsp;</td>
      <td class="details_screen"
          style="text-align:right;{if $smarty.section.idx.last}text-decoration:underline;{/if}">
        {$invoices[idx]->total_period_payments|siLocal_number}
      </td>
    <tr>
    {/section}

    <tr>
      <td colspan="10">&nbsp;</td>
      <td class="details_screen" style="text-align:right;">&#36;{$tot_income|siLocal_number}</td>
    </tr>
    
  </tbody>
</table>
{/if}
