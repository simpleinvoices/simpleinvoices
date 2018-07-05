{if isset($saved)}
  {if $saved == 'true' }
  <meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
    <div class="si_message_ok">{$LANG.save_cron_success}</div>
  {else if $saved == 'false' }
  <meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
    <div class="si_message_error">{$LANG.save_cron_failure}</div>
    {if $smarty.post.op == 'add' AND $smarty.post.invoice_id == ''}
      <div class="validation_alert">
        <img src="images/common/important.png" alt="" />
        You must select an invoice
      </div>
      <hr />
    {/if}
  {/if}
{/if}
{if !isset($saved) || $saved == 'false'}
  <form name="frmpost" action="index.php?module=cron&view=add" method="POST" id="frmpost">
    <table class="center">
      <tr>
        <td class="details_screen">{$LANG.invoice}</td>
        <td>
          <select name="invoice_id" class="validate[required]">
            <option value=''></option>
            {foreach from=$invoice_all item=invoice}
            <option value="{$invoice.id|htmlsafe}">
              {$invoice.index_name|htmlsafe}
              ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe},
               {$invoice.invoice_total|siLocal_number})
            </option>
            {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.start_date}</td>
        <td>
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                 size="10" name="start_date" id="date" value='{"+1 days"|date_format:"%Y-%m-%d"}' />
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.end_date}</td>
        <td>
          <input type="text" class="date-picker" size="10" name="end_date" id="date" value='' />
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.recur_each}</td>
        <td>
          <input name="recurrence" size="10" class="validate[required]" />
          <select name="recurrence_type" class="validate[required]">
            <option value="day">{$LANG.days}</option>
            <option value="week">{$LANG.weeks}</option>
            <option value="month">{$LANG.months}</option>
            <option value="year">{$LANG.years}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.email_biller_after_cron}</td>
        <td>
          <select name="email_biller" class="validate[required]">
            <option value="1">{$LANG.yes}</option>
            <option value="0">{$LANG.no}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.email_customer_after_cron}</td>
        <td>
          <select name="email_customer" class="validate[required]">
            <option value="1">{$LANG.yes}</option>
            <option value="0">{$LANG.no}</option>
          </select>
        </td>
      </tr>
    </table>
    <br />
    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="id" value="{$LANG.save}">
        <img class="button_img" src="images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="index.php?module=cron&view=manage" class="negative">
        <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>
    <input type="hidden" name="domain_id" value={$domain_id} />
    <input type="hidden" name="op" value="add" />
  </form>
{/if}
