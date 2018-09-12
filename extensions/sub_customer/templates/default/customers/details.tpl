{*
 * Script: details.tpl
 *      Customer details template
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}

{if $smarty.get.action == 'view' }
<br />
<table class="center">
  <tr>
    <td colspan="7"> </td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="align_center"><i>{$LANG.customer_details}</i></td>
    <td width="10%"></td>
    <td colspan="2" align="center" class="align_center"><i>{$LANG.summary_of_accounts}</i></td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.customer_name}</td>
    <td colspan="2">{$customer.name}</td>
    <td colspan="2"></td>
    <td class="details_screen">{$LANG.total_invoices}</td>
    <td style="text-align:right">{$stuff.total|siLocal_number}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.attention_short}
      <a href="#" class="cluetip" title="{$LANG.customer_contact}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" >
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td colspan="2">{$customer.attention|htmlsafe}</td>
    <td colspan="2"></td>
    <td class="details_screen"><a href="index.php?module=payments&view=manage&c_id={$customer.id|urlencode}">{$LANG.total_paid}</a></td>
    <td style="text-align:right">{$stuff.paid|siLocal_number}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.street}</td>
    <td colspan="2">{$customer.street_address|htmlsafe}</td>
    <td colspan="2"></td>
    <td class="details_screen">{$LANG.total_owing}</td>
    <td style="text-align:right"><u>{$stuff.owing|siLocal_number}</u></td>
  </tr>
  <tr>
    <td class="details_screen"	>{$LANG.street2}
      <a class="cluetip" href="#" title="{$LANG.street2}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" >
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>{$customer.street_address2|htmlsafe}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.city}</td>
    <td>{$customer.city|htmlsafe}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.state}</td>
    <td>{$customer.state|htmlsafe}</td>
    <td class="details_screen">{$LANG.phone}</td>
    <td>{$customer.phone|htmlsafe}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.zip}</td>
    <td>{$customer.zip_code|htmlsafe}</td>
    <td class="details_screen">{$LANG.mobile_phone}</td>
    <td>{$customer.mobile_phone|htmlsafe}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.country}</td>
    <td>{$customer.country|htmlsafe}</td>
    <td class="details_screen">{$LANG.fax}</td>
    <td>{$customer.fax|htmlsafe}</td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.enabled}</td>
    <td>{$customer.wording_for_enabled|htmlsafe}</td>
    <td class="details_screen">{$LANG.email}</td>
    <td>{$customer.email|htmlsafe}</td>
  </tr>
</table>
<br />
<div id="tabs_customer">
  <ul class="anchors">
    {if !empty($customFieldLabel.customer_cf1) || !empty($customFieldLabel.customer_cf2) ||
        !empty($customFieldLabel.customer_cf3) || !empty($customFieldLabel.customer_cf4)}
    <li><a href="#section-1" target="_top">{$LANG.custom_fields}</a></li>
    {/if}
    <li><a href="#section-2" target="_top">{$LANG.credit_card_details}</a></li>
    <li><a href="#section-3" target="_top">{$LANG.customer} {$LANG.invoice_listings}</a></li>
    <li><a href="#section-4" target="_top">{$LANG.notes}</a></li>
    <li><a href="#section-5" target="_top">{$LANG.sub_customers}</a></li>
  </ul>
  {if !empty($customFieldLabel.customer_cf1) || !empty($customFieldLabel.customer_cf2) ||
      !empty($customFieldLabel.customer_cf3) || !empty($customFieldLabel.customer_cf4)}
  <div id="section-1" class="fragment">
    <table>
      <tr>
        {if !empty($customFieldLabel.customer_cf1)}
        <td class="details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
          <a class="cluetip" href="#" title="{$LANG.custom_fields}"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </td>
        <td>{$customer.custom_field1|htmlsafe}</td>
        {else}
        <td colspan="2"></td>
        {/if}
      </tr>
      <tr>
        {if !empty($customFieldLabel.customer_cf2)}
        <td class="details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
          <a class="cluetip" href="#" title="{$LANG.custom_fields}"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </td>
        <td>{$customer.custom_field2|htmlsafe}</td>
        {else}
        <td colspan="2"></td>
        {/if}
      </tr>
      <tr>
        {if !empty($customFieldLabel.customer_cf3)}
        <td class="details_screen">{$customFieldLabel.customer_cf3|htmlsafe}
          <a class="cluetip" href="#" title="{$LANG.custom_fields}"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </td>
        <td>{$customer.custom_field3|htmlsafe}</td>
        {else}
        <td colspan="2"></td>
        {/if}
      </tr>
      <tr>
        {if !empty($customFieldLabel.customer_cf4)}
        <td class="details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
          <a class="cluetip" href="#" title="{$LANG.custom_fields}"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </td>
        <td>{$customer.custom_field4|htmlsafe}</td>
        {else}
        <td colspan="2"></td>
        {/if}
      </tr>
    </table>
  </div>
  {/if}
  <div id="section-2" class="fragment">
    <table>
      <tr>
        <td class="details_screen">{$LANG.credit_card_holder_name}</td>
        <td>{$customer.credit_card_holder_name|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.credit_card_number}</td>
        <td>{$customer.credit_card_number_masked|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.credit_card_expiry_month}</td>
        <td>{$customer.credit_card_expiry_month|htmlsafe}</td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.credit_card_expiry_year}</td>
        <td>{$customer.credit_card_expiry_year|htmlsafe}</td>
      </tr>
    </table>
  </div>
  <div id="section-3" class="fragment">
    <table width="100%" class="center">
      <tr class="sortHeader">
        <th class="sortable">{$LANG.id}</th>
        <th class="sortable_rt">{$LANG.total}</th>
        <th class="sortable_rt">{$LANG.paid}</th>
        <th class="sortable_rt">{$LANG.owing}</th>
        <th class="sortable_rt">{$LANG.date_created}</th>
      </tr>
      {foreach from=$invoices item=invoice}
      <tr class="index_table">
        <td class="details_screen">
          <a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.id|htmlsafe}</a>
        </td>
        <td style="text-align:right" class="details_screen">{$invoice.total|siLocal_number}</td>
        <td style="text-align:right" class="details_screen">{$invoice.paid|siLocal_number}</td>
        <td style="text-align:right" class="details_screen">{$invoice.owing|siLocal_number}</td>
        <td style="text-align:right" class="details_screen">{$invoice.date|htmlsafe}</td>
      </tr>
      {/foreach}
    </table>  
  </div>
  <div id="section-4" class="fragment">
    <div id="left">
      {$customer.notes|outhtml}
    </div>
  </div>
  <div id="section-5" class="fragment">
    <table width="100%" class="center">
      <tr class="sortHeader">
        <th class="sortable"></th>
        <th class="sortable">{$LANG.name}</th>
      </tr>
      {foreach from=$sub_customers item=sc}
      <tr class="index_table">
        <td class="details_screen">
          <a href="index.php?module=customers&amp;view=details&action=edit&id={$sc.id|urlencode}">{$LANG.edit|htmlsafe}</a>
          :: 
          <a href="index.php?module=customers&amp;view=details&action=view&id={$sc.id|urlencode}">{$LANG.view|htmlsafe}</a>
        </td>
        <td style="text-align:left" class="details_screen">{$sc.name|htmlsafe}</td>
      </tr>
      {/foreach}
    </table>  
  </div>
</div>
<br />
<table class="center" >
  <tr>
    <td>
      <a href="index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="positive">
        <img src="images/common/tick.png" alt="" />
        {$LANG.edit}
      </a>
    </td>
  </tr>
</table>
{elseif $smarty.get.action == 'edit' }
<form name="frmpost"
      action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}"
      method="post" id="frmpost" onsubmit="return checkForm(this);">
  <br />
  <input type="hidden" name="op" value="edit_customer" />
  <input type="hidden" name="domain_id" value="{$customer.domain_id}" />
  <table class="center">
    <tr>
      <td class="details_screen">{$LANG.customer_name}
	    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
           title="{$LANG.required_field}" >
          <img src="{$help_image_path}required-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="name" value="{$customer.name|htmlsafe}" size="50"
               id="name" class="validate[required]" tabindex="10" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.attention_short}
        <a href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
           class="cluetip" title="{$LANG.customer_contact}" >
          <img src="{$help_image_path}help-small.png" alt="" /></a>
      </td>
      <td>
        <input type="text" name="attention" value="{$customer.attention|htmlsafe}" size="50" tabindex="20" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.street}</td>
      <td>
        <input type="text" name="street_address" value="{$customer.street_address|htmlsafe}" size="50" tabindex="30" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.street2}
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
           title="{$LANG.street2}" > 
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="street_address2" value="{$customer.street_address2|htmlsafe}" size="50" tabindex="40" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.city}</td>
      <td>
        <input type="text" name="city" value="{$customer.city|htmlsafe}" size="50" tabindex="50" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.zip}</td>
      <td>
        <input type="text" name="zip_code" value="{$customer.zip_code|htmlsafe}" size="50" tabindex="50" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.state}</td>
      <td>
        <input type="text" name="state" value="{$customer.state|htmlsafe}" size="50" tabindex="60" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.country}</td>
      <td>
        <input type="text" name="country" value="{$customer.country|htmlsafe}" size="50" tabindex="70" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.phone}</td>
      <td>
        <input type="text" name="phone" value="{$customer.phone|htmlsafe}" size="50" tabindex="80" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.mobile_phone}</td>
      <td>
        <input type="text" name="mobile_phone" value="{$customer.mobile_phone|htmlsafe}" size="50" tabindex="90" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.fax}</td>
      <td>
        <input type="text" name="fax" value="{$customer.fax|htmlsafe}" size="50" tabindex="100" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.email}</td>
      <td>
        <input type="text" name="email" value="{$customer.email|htmlsafe}" size="50" tabindex="110" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.credit_card_holder_name}</td>
      <td>
        <input type="text" name="credit_card_holder_name" value="{$customer.credit_card_holder_name|htmlsafe}"
               size="25" tabindex="120" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.credit_card_number}</td>
      <td>{$customer.credit_card_number_masked}</td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.credit_card_number_new}</td>
      <td>
        {* Note that no value is put in this field and the name is the actual database name *}
        <input type="text" name="credit_card_number" size="25" tabindex="130" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.credit_card_expiry_month}</td>
      <td>
        <input type="text" name="credit_card_expiry_month" value="{$customer.credit_card_expiry_month|htmlsafe}"
               size="5" tabindex="140" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.credit_card_expiry_year}</td>
      <td>
        <input type="text" name="credit_card_expiry_year" value="{$customer.credit_card_expiry_year|htmlsafe}"
               size="5" tabindex="150" />
      </td>
    </tr>
    <tr>
      <td class="details_screen">{$LANG.parent_customer}</td>
      <td>
      {if $parent_customers == null }
        <em>{$LANG.no_customers}</em>
      {else}
        <select name="parent_customer_id" tabindex="160">
          <option value=''></option>
          {foreach from=$parent_customers item=c}
            <option {if $c.id == $customer.parent_customer_id}selected{/if} value="{$c.id|htmlsafe}">
              {$c.name|htmlsafe}
            </option>
          {/foreach}
        </select>
      {/if}
      </td>
    </tr>
    {if !empty($customFieldLabel.customer_cf1)}
    <tr>
      <td class="details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
           title="{$LANG.custom_fields}" > 
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="custom_field1" value="{$customer.custom_field1|htmlsafe}" size="50" tabindex="170" />
      </td>
    </tr>
    {/if}
    {if !empty($customFieldLabel.customer_cf2)}
    <tr>
      <td class="details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
           title="{$LANG.custom_fields}" > 
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="custom_field2" value="{$customer.custom_field2|htmlsafe}" size="50" tabindex="180" />
      </td>
    </tr>
    {/if}
    {if !empty($customFieldLabel.customer_cf3)}
    <tr>
      <td class="details_screen">{$customFieldLabel.customer_cf3|htmlsafe} 
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
           title="{$LANG.custom_fields}" > 
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="custom_field3" value="{$customer.custom_field3|htmlsafe}" size="50" tabindex="190" />
      </td >
    </tr>
    {/if}
    {if !empty($customFieldLabel.customer_cf4)}
    <tr>
      <td class="details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
           title="{$LANG.custom_fields}" > 
          <img src="{$help_image_path}help-small.png" alt="" />
        </a>
      </td>
      <td>
        <input type="text" name="custom_field4" value="{$customer.custom_field4|htmlsafe}" size="50" tabindex="200" />
      </td>
    </tr>
    {/if}
    <tr>
      <td class="details_screen">{$LANG.notes}</td>
      <td>
        <textarea  name="notes"  class="editor" rows="8" cols="50" tabindex="210" >
          {$customer.notes|outhtml}
        </textarea>
      </td>
    </tr>
    {* {showCustomFields categorieId="2" itemId=$smarty.get.customer } *}
    <tr>
      <td class="details_screen">{$LANG.enabled}</td>
      <td>
        {html_options name=enabled options=$enabled selected=$customer.enabled tabindex=220}
      </td>
    </tr>
  </table>
  <br />
  <table class="center" >
    <tr>
      <td>
        <button type="submit" class="positive" name="save_customer" value="{$LANG.save_customer}" tabindex="230" >
          <img class="button_img" src="images/common/tick.png" alt="" /> 
          {$LANG.save}
        </button>
        <a href="index.php?module=customers&amp;view=manage" class="negative">
          <img src="images/common/cross.png" alt="" />
          {$LANG.cancel}
        </a>
      </td>
    </tr>
  </table>
</form>
{/if}
