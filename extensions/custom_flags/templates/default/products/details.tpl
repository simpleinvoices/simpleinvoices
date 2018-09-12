<form name="frmpost" action="index.php?module=products&view=save&id={$smarty.get.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">

{if $smarty.get.action== 'view' }
<div class="si_form si_form_view">
  <table>
    <tr>
      <th>{$LANG.product_description}</th>
      <td>{$product.description|htmlsafe}</td>
    </tr>
    <tr>
      <th>{$LANG.product_unit_price}</th>
      <td>{$product.unit_price|siLocal_number_trim}</td>
    </tr>
    {if $defaults.inventory == '1'}
    <tr>
      <th>{$LANG.cost}</th>
      <td>{$product.cost|siLocal_number}</td>
    </tr>
    <tr>
      <th>{$LANG.reorder_level}</th>
      <td>{$product.reorder_level}</td>
    </tr>
    {/if}
    <tr>
      <th>{$LANG.default_tax}</th>
      <td>{$tax_selected.tax_description|htmlsafe} {$tax_selected.type|htmlsafe}</td>
    </tr>
    {if !empty($customFieldLabel.product_cf1)}
    <tr>
      <th>{$customFieldLabel.product_cf1|htmlsafe}</th>
      <td>{$product.custom_field1|htmlsafe}</td>
    </tr>
    {/if}
    {if !empty($customFieldLabel.product_cf2)}
    <tr>
      <th>{$customFieldLabel.product_cf2|htmlsafe}</th>
      <td>{$product.custom_field2|htmlsafe}</td>
    </tr>
    {/if}
    {if !empty($customFieldLabel.product_cf3)}
    <tr>
      <th>{$customFieldLabel.product_cf3|htmlsafe}</th>
      <td>{$product.custom_field3|htmlsafe}</td>
    </tr>
    {/if}
    {if !empty($customFieldLabel.product_cf4)}
    <tr>
      <th>{$customFieldLabel.product_cf4|htmlsafe}</th>
      <td>{$product.custom_field4|htmlsafe}</td>
    </tr>
    {/if}
    {foreach from=$cflgs item=cflg}
      {assign var="i" value=$cflg.flg_id-1}
      <tr>
        <th>{$cflg.field_label|trim|htmlsafe}</th>
        <td><input type="checkbox" disabled="disabled" name="custom_flags_{$cflg.flg_id}"
            {if substr($product.custom_flags,$i,1) == '1'} checked {/if} value="1"/>
        </td>
      </tr>
    {/foreach}
    {if $defaults.product_attributes}
    <tr>
      <th class="details_screen">{$LANG.product_attributes}</th>
      <td></td>
    </tr>
    {foreach from=$attributes item=attribute}
      {assign var="i" value=$attribute.id}
      {if $attribute.enabled == '1' OR $product.attribute_decode[$i] == 'true'}
      <tr>
        <td></td>
        <th class="details_screen product_attribute"> 
          <input type="checkbox" disabled="disabled" name="attribute{$i}" {if $product.attribute_decode[$i] == 'true'} checked {/if} value="true"/>
          {$attribute.name}
        </th>
      </tr>
      {/if}
    {/foreach}
    {/if}
    <tr>
      <th>{$LANG.notes}</th>
      <td>{$product.notes|unescape}</td>
    </tr>
    <tr>
      <th class="details_screen">{$LANG.note_attributes}</th>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <th class="details_screen product_attribute">
        <input type="checkbox"  disabled="disabled" name="notes_as_description" {if $product.notes_as_description== 'Y'} checked {/if} value='true'/>
        {$LANG.note_as_description}
      </th>
    </tr>
    <tr>
      <td></td>
      <th class="details_screen product_attribute">
        <input type="checkbox" disabled="disabled" name="show_description"  {if $product.show_description == 'Y'} checked {/if} value='true'/>
        {$LANG.note_expand}
      </th>
    </tr>
    <tr>
      <th>{$LANG.product_enabled}</th>
      <td>{$product.wording_for_enabled|htmlsafe}</td>
    </tr>
  </table>
</div>
<div class="si_toolbar si_toolbar_form">
  <a href="index.php?module=products&view=details&id={$product.id|htmlsafe}&action=edit" class="positive">
    <img src="images/famfam/add.png" alt=""/>
    {$LANG.edit}
  </a>
</div>
{elseif $smarty.get.action== 'edit' }
<div class="si_form">
  <div id="tabs_customer">
    <ul class="anchors">
      <li><a href="#section-1" target="_top">{$LANG.details}</a></li>
      <li><a href="#section-2" target="_top">{$LANG.custom_upper}&nbsp;{$LANG.fields_upper}&nbsp;&amp;&nbsp;{$LANG.flags_upper}</a></li>
      <li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
    </ul>
  </div>
  <div id="section-1" class="fragment">
    <table class="center">
      <tr>
        <th>{$LANG.product_description}</th>
        <td><input type="text" name="description" size="50" value="{$product.description|htmlsafe}" id="description"  class="validate[required]" /></td>
      </tr>
      <tr>
        <th>{$LANG.product_unit_price}</th>
        <td><input type="text" name="unit_price" size="25" value="{$product.unit_price|siLocal_number_trim}" /></td>
      </tr>
      {if $defaults.inventory == '1'}
        <tr>
          <th>
            {$LANG.cost}
            <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{$LANG.cost}">
               <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" class="edit" name="cost" value="{$product.cost|siLocal_number}"  size="25" /></td>
        </tr>
        <tr>
          <th>{$LANG.reorder_level}</th>
          <td><input type="text" class="edit" name="reorder_level" value="{$product.reorder_level|htmlsafe}"  size="25" /></td>
        </tr>
      {/if}
      <tr>
        <th>{$LANG.default_tax}</th>
        <td>
          <select name="default_tax_id">
            {foreach from=$taxes item=tax}
              <option value="{$tax.tax_id|htmlsafe}" {if $tax.tax_id == $product.default_tax_id}selected{/if}>{$tax.tax_description|htmlsafe}</option>
            {/foreach}
          </select>
        </td>
      </tr>
    </table>
  </div>
  <div id="section-2" class="fragment">
    <table>
      {if !empty($customFieldLabel.product_cf1)}
      <tr>
        <th>{$customFieldLabel.product_cf1|htmlsafe} 
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$help_image_path}help-small.png" alt="" /></a>
        </th>
        <td><input type="text" name="custom_field1" size="50" value="{$product.custom_field1|htmlsafe}" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.product_cf2)}
      <tr>
        <th>{$customFieldLabel.product_cf2|htmlsafe} 
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$help_image_path}help-small.png" alt="" /></a>
        </th>
        <td><input type="text" name="custom_field2" size="50" value="{$product.custom_field2|htmlsafe}" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.product_cf3)}
      <tr>
        <th>{$customFieldLabel.product_cf3|htmlsafe} 
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$help_image_path}help-small.png" alt="" /></a>
        </th>
        <td><input type="text" name="custom_field3" size="50" value="{$product.custom_field3|htmlsafe}" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.product_cf4)}
      <tr>
        <th>{$customFieldLabel.product_cf4|htmlsafe} 
          <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$help_image_path}help-small.png" alt="" /></a>
        </th>
        <td><input type="text" name="custom_field4" size="50" value="{$product.custom_field4|htmlsafe}" /></td>
      </tr>
      {/if}
      {foreach from=$cflgs item=cflg}
        {assign var="i" value=$cflg.flg_id-1}
        <tr>
          <th>
            {$cflg.field_label|trim|htmlsafe}
            {if strlen($cflg.field_help) > 0}
              <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}" title="{$cflg.field_label}"><img src="{$help_image_path}help-small.png" alt="" /></a>
            {/if}
          </th>
          <td style="float:left;margin-left:auto;width:10px;"><input type="checkbox" name="custom_flags_{$cflg.flg_id}" {if substr($product.custom_flags,$i,1) == '1'} checked {/if} value="1" /></td>
        </tr>
      {/foreach}
      {if $defaults.product_attributes}
        <tr>
          <th class="details_screen">{$LANG.product_attributes}</th>
          <td></td>
        </tr>
        {foreach from=$attributes item=attribute}
          {assign var="i" value=$attribute.id}
          {if $attribute.enabled == '1' OR $product.attribute_decode[$i] == 'true'}
            <tr>
              <td></td>
              <th class="details_screen product_attribute">
                <input type="checkbox" name="attribute{$i}" {if $product.attribute_decode[$i] } == 'true'} checked{/if} value="true"/>
                {$attribute.name}
              </th>
            </tr>
          {/if}
        {/foreach}
      {/if}
    </table>
  </div>
  <div id="section-3" class="fragment">
    <table>
      <tr>
        <th>{$LANG.notes}</th>
        <td><textarea name="notes" class="editor" rows="8" cols="50">{$product.notes|unescape}</textarea></td>
      </tr>
      <tr>
        <tr>
          <th class="details_screen">{$LANG.note_attributes}</th>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <th class="details_screen product_attribute">
            <input type="checkbox" name="notes_as_description" {if $product.notes_as_description== 'Y'} checked {/if} value='true'/>
            {$LANG.note_as_description}
          </th>
        </tr>
        <tr>
          <td></td>
          <th class="details_screen product_attribute">
            <input type="checkbox" name="show_description"  {if $product.show_description == 'Y'} checked {/if} value='true'/>
            {$LANG.note_expand}
          </th>
        </tr>
        <th>{$LANG.product_enabled}</th>
        <td>{html_options name=enabled options=$enabled selected=$product.enabled}</td>
      </tr>
    </table>
  </div>
  <div class="si_toolbar si_toolbar_form">
    <button type="submit" class="positive" name="save_product" value="{$LANG.save}">
      <img class="button_img" src="images/common/tick.png" alt="" /> 
      {$LANG.save}
    </button> 
    <a href="index.php?module=products&view=manage" class="negative">
      <img src="images/common/cross.png" alt="" />
      {$LANG.cancel}
    </a>
  </div>
</div>
<input type="hidden" name="op" value="edit_product">  
{/if}
</form>
