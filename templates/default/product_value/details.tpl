<form name="frmpost"
      action="index.php?module=product_value&amp;view=save&amp;id={$smarty.get.id}"
      method="post">
{if $smarty.get.action== 'view' }
  <b>Product Values ::
    <a href="index.php?module=product_value&amp;view=details&amp;id={$product_value.id}&amp;action=edit">{$LANG.edit}</a>
  </b>
  <hr />
  <table class="center">
    <tr>
      <th style="text-align:left;">{$LANG.id}:&nbsp;</th>
      <td>{$product_value.id}</td>
    </tr>
    <tr>  
      <th style="text-align:left;">{$LANG.attribute}:&nbsp;</th>
      <td>{$product_attribute}</td>
    </tr>
    <tr>  
      <th style="text-align:left;">{$LANG.value}:&nbsp;</th>
      <td>{$product_value.value}</td>
    </tr>
    <tr>
      <th style="text-align:left;">{$LANG.status}:&nbsp;</th>
      <td>{if $product_value.enabled == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
    </tr>
  </table>
  <hr />
{/if}
{if $smarty.get.action== 'edit' }
  <b>{$LANG.product_value}</b>
  <hr />
  <table class="center">
    <tr>
      <th style="text-align:left;">{$LANG.id}:&nbsp;</th>
      <td>{$product_value.id}</td>
    </tr>
    <tr>
      <th style="text-align:left;">{$LANG.attribute}:&nbsp;</th>
      <td>
        <select name="attribute_id">
        {foreach $product_attributes as $product_attribute}
          <option {if $product_attributes == $product_value.attribute_id}selected{/if}
                  value="{$product_attribute.id}">{$product_attribute.name}</option>
        {/foreach}
        </select>
      </td>
    <tr>
      <th style="text-align:left;">{$LANG.value}:&nbsp;</th>
      <td><input type="text" name="value" value="{$product_value.value}" size="50" /></td>
    </tr>
    <th style="text-align:left;">{$LANG.status}:&nbsp;</th>
    <td>{html_options name=enabled options=$enabled selected=$product_value.enabled}</td>
      </tr>
  </table>
  <hr />
  <div style="text-align:center;">
    <input type="submit" name="save_product_value" value="{$LANG.save}" />
    <input type="hidden" name="op" value="edit_product_value" />
  </div>
  {/if}
</form>
