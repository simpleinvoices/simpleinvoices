{if $saved == 'true' }
  <meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
  <div class="si_message_ok">{$LANG.save_inventory_success}</div>
{else if $saved == 'false' }
  <meta http-equiv="refresh" content="2;URL=index.php?module=inventory&amp;view=manage" />
  <div class="si_message_error">{$LANG.save_inventory_failure}</div>
  {if $smarty.post.op == 'add' AND $smarty.post.product_id == ''} 
  <div class="validation_alert">
    <img src="./images/common/important.png" alt="" />
    You must select an product
  </div>
  <hr />
  {/if}
  {* is this still needed ?*}
  <div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
    <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." />
    {$LANG.loading} ...
  </div>
  <form name="frmpost" action="index.php?module=inventory&view=add" method="POST" id="frmpost">
    <div class="si_form">
      <table>
        <tr>
          <th>{$LANG.product}</th>
          <td>
            <select name="product_id" class="validate[required] product_inventory_change">
              <option value=''></option>
              {foreach from=$product_all item=product}
              <option value="{$product.id|htmlsafe}">{$product.description|htmlsafe}</option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <th>{$LANG.quantity}</th>
          <td>
            <input class="validate[required]" name="quantity" size="10" >
          </td>
        </tr>
        <tr>
          <th>{$LANG.date_upper}</th>
          <td>
            <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                   size="10" name="date" id="date" value='{"now"|date_format:"%Y-%m-%d"}' />  
          </td>
        </tr>
        <tr>
          <th>{$LANG.cost}</th>
          <td>
            <input class="validate[required]" name="cost" id="cost" size="10" >
          </td>
        </tr>
        <tr>
          <th>{$LANG.notes}</th>
          <td>
            <textarea  name="note" class="editor" rows="8" cols="50">
              {$customer.notes|outhtml}
            </textarea>
          </td>
        </tr>
      </table>
      <div class="si_toolbar si_toolbar_form">  
        <button type="submit" class="positive" name="id" value="{$LANG.save}">
          <img class="button_img" src="./images/common/tick.png" alt="" /> 
          {$LANG.save}
        </button>
        <a href="./index.php?module=cron&view=manage" class="negative">
          <img src="./images/common/cross.png" alt="" />
          {$LANG.cancel}
        </a>
      </div>
    </div>
    <input type="hidden" name="op" value="add" />
    <input type="hidden" name="domain_id" value="{$domain_id}" />
  </form>
{/if}
