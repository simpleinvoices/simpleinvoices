<form name="frmpost" action="index.php?module=payments&amp;view=save"
      method="post" onsubmit="return frmpost_Validator(this)">
  <div class="si_form">
    <table>
      {if $smarty.get.op === "pay_selected_invoice"}
      <tr>
        <th>{$invoice.preference|htmlsafe}</th>
        <td>{$invoice.index_id|htmlsafe}</td>
        <th class="details_screen">{$LANG.total}</th>
        <td>{$invoice.total|number_format:2}</td>
      </tr>
      <tr>
        <th>{$LANG.biller}</th>
        <td>{$biller.name|htmlsafe}</td>
        <th>{$LANG.paid}</th>
        <td>{$invoice.paid|number_format:2}</td>
      </tr>
      <tr>
        <th>{$LANG.customer}</th>
        <td>{$customer.name|htmlsafe}</td>
        <th>{$LANG.owing}</th>
        <td style="text-decoration: underline;">{$invoice.owing|number_format:2}</td>
      </tr>
      <tr>
        <th>{$LANG.amount}</th>
        <td colspan="5">
          <input type="text" name="ac_amount" size="25" value="{$invoice.owing|siLocal_number}" />
          <a class="cluetip" href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_process_payment_auto_amount"
             title="{$LANG.process_payment_auto_amount}">
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </td>
      </tr>
      <tr>
        <th>{$LANG.date_formatted}</th>
        <td colspan="5">
          <input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" />
        </td>
      </tr>
      {elseif $smarty.get.op === "pay_invoice"}
      <tr>
        <th>{$LANG.invoice}</th>
        <td>
          <select name="invoice_id" class="validate[required]">
            <option value=''></option>
            {foreach from=$invoice_all item=invoice}
              <option value="{$invoice.id|htmlsafe}">
                {$invoice.index_name|htmlsafe}
               ({$invoice.biller|htmlsafe},
                {$invoice.customer|htmlsafe},
                {$LANG.total} {$invoice.invoice_total|siLocal_number} :
                {$LANG.owing} {$invoice.owing|siLocal_number})
              </option>
            {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <th>{$LANG.amount}</th>
        <td colspan="5"><input type="text" name="ac_amount" size="25" /></td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.date_formatted}</th>
        <td>
          <input type="text" class="date-picker" name="ac_date" id="date1" value="{$today|htmlsafe}" />
        </td>
      </tr>
      {/if}
      <tr>
        <th>{$LANG.payment_type_method}</th>
        <td>
          {if !$paymentTypes}
            <p><em>{$LANG.no_payment_types}</em></p>
          {else}
            <select name="ac_payment_type" id="pymt_type1">
              {foreach from=$paymentTypes item=paymentType}
              <option value="{$paymentType.pt_id|htmlsafe}" {if $paymentType.pt_id==$defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlsafe}</option>
              {/foreach}
            </select>
          {/if}
        </td>
        <th>{$LANG.check_number}</th>
        <td><input type="text" name="ac_check_number" id="chk_num1" size="10" onblur="verifyCheckNumber(this,'pymt_type1');" /></td>
      </tr>
      <tr>
        <th>{$LANG.note}</th>
        <td colspan="5"><textarea class="editor" name="ac_notes" rows="5" cols="50"></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>

    <div class="si_toolbar si_toolbar_form">
      <button type="submit" class="positive" name="process_payment" value="{$LANG.save}">
        <img class="button_img" src="images/common/tick.png" alt="" />
        {$LANG.save}
      </button>
      <a href="index.php?module=payments&amp;view=manage" class="negative">
        <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </div>

    {if $smarty.get.op == 'pay_selected_invoice'}
      <input type="hidden" name="invoice_id" value="{$invoice.id|htmlsafe}" />
    {/if}
  </div>
</form>
