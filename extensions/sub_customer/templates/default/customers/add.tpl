{*
 * Script: add.tpl
 *    Customers add template
 *
 * Last edited:
 *    2016-07-27
 *
 * License:
 *   GPL v3 or above
*}
{* if customer is updated or saved.*} 

{if $smarty.post.name != null && $smarty.post.name != ""} 
  {include file="templates/default/customers/save.tpl"}
{else}
  {* if  name was inserted *} 
  {if $smarty.post.id !=null} 
  {*
    <div class="validation_alert"><img src="images/common/important.png" alt="" />
    You must enter a description for the Customer</div>
    <hr />
  *}
  {/if}  

<form name="frmpost" action="index.php?module=customers&amp;view=add" method="post"
      id="frmpost" onsubmit="return checkForm(this);">
<br />
<table class="center">
  <tr>
    <td class="details_screen">{$LANG.customer_name}
      <a class="cluetip" href="#" title="{$LANG.required_field}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" >
        <img src="{$help_image_path}required-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="name" id="name" value="{$smarty.post.name|htmlsafe}"
             size="25" class="validate[required]" tabindex="10" autofocus />
    </td>
  </tr>
  </tr>
    <td class="details_screen">{$LANG.customer_contact}
      <a href="#" class="cluetip" title="{$LANG.customer_contact}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" >
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="attention" value="{$smarty.post.attention|htmlsafe}" size="25" tabindex="20" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.street}</td>
    <td>
      <input type="text" name="street_address" value="{$smarty.post.street_address|htmlsafe}" size="25" tabindex="30" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.street2}
      <a class="cluetip" href="#" title="{$LANG.street2}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" > 
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="street_address2" value="{$smarty.post.street_address2|htmlsafe}" size="25" tabindex="40" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.city}</td>
    <td>
      <input type="text" name="city" value="{$smarty.post.city|htmlsafe}" size="25" tabindex="50" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.state}</td>
    <td>
      <input type="text" name="state" value="{$smarty.post.state|htmlsafe}" size="25" tabindex="60" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.zip}</td>
    <td>
      <input type="text" name="zip_code" value="{$smarty.post.zip_code|htmlsafe}" size="25" tabindex="70" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.country}</td>
    <td>
      <input type="text" name="country" value="{$smarty.post.country|htmlsafe}" size="50" tabindex="80" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.phone}</td>
    <td>
      <input type="text" name="phone" value="{$smarty.post.phone|htmlsafe}" size="25" tabindex="90" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.mobile_phone}</td>
    <td>
      <input type="text" name="mobile_phone" value="{$smarty.post.mobile_phone|htmlsafe}" size="25" tabindex="100" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.fax}</td>
    <td>
      <input type="text" name="fax" value="{$smarty.post.fax|htmlsafe}" size="25" tabindex="110" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.email}</td>
    <td>
      <input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="25" tabindex="120" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.credit_card_holder_name}</td>
    <td>
      <input type="text" name="credit_card_holder_name"
             value="{$smarty.post.credit_card_holder_name|htmlsafe}" size="25" tabindex="130" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.credit_card_number}</td>
    <td>
      <input type="text" name="credit_card_number"
             value="{$smarty.post.credit_card_number|htmlsafe}" size="25" tabindex="140" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.credit_card_expiry_month}</td>
    <td>
      <input type="text" name="credit_card_expiry_month"
             value="{$smarty.post.credit_card_expiry_month|htmlsafe}" size="5" tabindex="150" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.credit_card_expiry_year}</td>
    <td>
      <input type="text" name="credit_card_expiry_year"
             value="{$smarty.post.credit_card_expiry_year|htmlsafe}" size="5" tabindex="160" />
    </td>
  </tr>
  <tr>
    <td class="details_screen">
      {$LANG.parent_customer}
    </td>
    <td>
    {if $parent_customers == null }
      <em>{$LANG.no_customers}</em>
    {else}
      <select name="parent_customer_id" tabindex="170" >
        <option value=''></option>
        {foreach from=$parent_customers item=customer}
          <option {if $customer.id == $defaultCustomerID} selected {/if} value="{$customer.id|htmlsafe}">
            {$customer.name|htmlsafe}
          </option>
        {/foreach}
      </select>
    {/if}
    </td>
  </tr>
  {if !empty($customFieldLabel.customer_cf1)}
  <tr>
    <td class="details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
      <a class="cluetip" href="#" title="{$LANG.custom_fields}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" >
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="custom_field1" value="{$smarty.post.custom_field1|htmlsafe}" size="25" tabindex="180" />
    </td>
  </tr>
  {/if}
  {if !empty($customFieldLabel.customer_cf2)}
  <tr>
    <td class="details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
      <a class="cluetip" href="#" title="{$LANG.custom_fields}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="25" tabindex="190" />
    </td> 
  </tr>
  {/if}
  {if !empty($customFieldLabel.customer_cf3)}
  <tr>
    <td class="details_screen">{$customFieldLabel.customer_cf3|htmlsafe}
      <a class="cluetip" href="#" title="{$LANG.custom_fields}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="25" tabindex="200" />
    </td>
  </tr>
  {/if}
  {if !empty($customFieldLabel.customer_cf4)}
  <tr>
    <td class="details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
      <a class="cluetip" href="#" title="{$LANG.custom_fields}"
         rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" > 
        <img src="{$help_image_path}help-small.png" alt="" />
      </a>
    </td>
    <td>
      <input type="text" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="25" tabindex="210" />
    </td>
  </tr>
  {/if}
  <tr>
    <td class="details_screen">{$LANG.notes}</td>
    <td>
      <textarea name="notes" class="editor" rows="8" cols="50" tabindex="220" >
        {$smarty.post.notes|outhtml}
      </textarea>
    </td>
  </tr>
  <tr>
    <td class="details_screen">{$LANG.enabled}</td>
    <td>
      {html_options name=enabled options=$enabled selected=1 tabindex=230}
    </td>
  </tr>
</table>
<br />
<table class="center" >
  <tr>
    <td>
      <button type="submit" class="positive" name="id" value="{$LANG.save}" tabindex="240" >
        <img class="button_img" src="images/common/tick.png" alt="" /> 
        {$LANG.save}
      </button>
    </td>
    <td>
      <input type="hidden" name="op" value="insert_customer" />
      <a href="index.php?module=customers&amp;view=manage" class="negative" tabindex="250" >
        <img src="images/common/cross.png" alt="" />
        {$LANG.cancel}
      </a>
    </td>
  </tr>
</table>
</form>
{/if}
