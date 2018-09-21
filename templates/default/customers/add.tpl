{*
 * Script: add.tpl
 *      Customers add template
 *
 * Last edited:
 *      2016-07-27
 *
 * License:
 *      GPL v3 or above
 *}
{* if customer is updated or saved.*} 
{if $smarty.post.name != null && $smarty.post.name != "" } 
  {include file="templates/default/customers/save.tpl"}
{else}
  {* if  name was inserted *} 
  {if $smarty.post.id !=null} 
  {*
    <div class="validation_alert"><img src="images/common/important.png" alt="" />
      You must enter a description for the Customer
    </div>
    <hr />
  *}
  {/if} 
  <form name="frmpost" action="index.php?module=customers&amp;view=add"
        method="post" id="frmpost" onsubmit="return checkForm(this);">
    <div class="si_form">
      <table>
        <tr>
          <th>{$LANG.customer_name}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
               title="{$LANG.required_field}">
              <img src="{$help_image_path}required-small.png" alt="" />
            </a>
          </th>
          <td>
            <input type="text" name="name" id="name" value="{$smarty.post.name|htmlsafe}" size="25"
                   class="validate[required]" tabindex="10" autofocus />
          </td>
        </tr>
        <tr>
          <th>{$LANG.customer_contact}
            <a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
               href="#" class="cluetip" title="{$LANG.customer_contact}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td>
            <input type="text" name="attention" value="{$smarty.post.attention|htmlsafe}" size="25" tabindex="20" />
          </td>
        </tr>
        <tr>
          <th>{$LANG.street}</th>
          <td>
            <input type="text" name="street_address"
                   value="{$smarty.post.street_address|htmlsafe}" size="25" tabindex="30" />
          </td>
        </tr>
        <tr>
          <th>{$LANG.street2}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
               title="{$LANG.street2}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" name="street_address2"
                     value="{$smarty.post.street_address2|htmlsafe}" size="25" tabindex="40" /></td>
        </tr>
        <tr>
          <th>{$LANG.city}</th>
          <td><input type="text" name="city" value="{$smarty.post.city|htmlsafe}" size="25" tabindex="50" /></td>
        </tr>
        <tr>
          <th>{$LANG.state}</th>
          <td><input type="text" name="state" value="{$smarty.post.state|htmlsafe}" size="25" tabindex="60" /></td>
        </tr>
        <tr>
          <th>{$LANG.zip}</th>
          <td><input type="text" name="zip_code" value="{$smarty.post.zip_code|htmlsafe}" size="25" tabindex="70" /></td>
        </tr>
        <tr>
          <th>{$LANG.country}</th>
          <td><input type="text" name="country" value="{$smarty.post.country|htmlsafe}" size="25" tabindex="80" /></td>
        </tr>
        <tr>
          <th>{$LANG.phone}</th>
          <td><input type="text" name="phone" value="{$smarty.post.phone|htmlsafe}" size="25" tabindex="90" /></td>
        </tr>
        <tr>
          <th>{$LANG.mobile_phone}</th>
          <td><input type="text" name="mobile_phone" value="{$smarty.post.mobile_phone|htmlsafe}" size="25" tabindex="100" /></td>
        </tr>
        <tr>
          <th>{$LANG.fax}</th>
          <td><input type="text" name="fax" value="{$smarty.post.fax|htmlsafe}" size="25" tabindex="110" /></td>
        </tr>
        <tr>
          <th>{$LANG.email}</th>
          <td><input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="25" tabindex="120" /></td>
        </tr>
        <tr>
          <th>{$LANG.credit_card_holder_name}</th>
          <td><input type="text" name="credit_card_holder_name"
                     value="{$smarty.post.credit_card_holder_name|htmlsafe}" size="25" tabindex="130" /></td>
        </tr>
        <tr>
          <th>{$LANG.credit_card_number}</th>
          <td><input type="text" name="credit_card_number"
                     value="{$smarty.post.credit_card_number|htmlsafe}" size="25" tabindex="140" /></td>
        </tr>
        <tr>
          <th>{$LANG.credit_card_expiry_month}</th>
          <td><input type="text" name="credit_card_expiry_month"
                     value="{$smarty.post.credit_card_expiry_month|htmlsafe}" size="5" tabindex="150" /></td>
        </tr>
        <tr>
          <th>{$LANG.credit_card_expiry_year}</th>
          <td><input type="text" name="credit_card_expiry_year"
                     value="{$smarty.post.credit_card_expiry_year|htmlsafe}" size="5" tabindex="160" /></td>
        </tr>
        {if !empty($customFieldLabel.customer_cf1)}
        <tr>
          <th>{$customFieldLabel.customer_cf1|htmlsafe}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
               title="{$LANG.custom_fields}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" name="custom_field1" value="{$smarty.post.custom_field1|htmlsafe}" size="25" tabindex="160" /></td>
        </tr>
        {/if}
        {if !empty($customFieldLabel.customer_cf2)}
        <tr>
          <th>{$customFieldLabel.customer_cf2|htmlsafe}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
               title="{$LANG.custom_fields}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="25" tabindex="170" /></td>
        </tr>
        {/if}
        {if !empty($customFieldLabel.customer_cf3)}
        <tr>
          <th>{$customFieldLabel.customer_cf3|htmlsafe}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
               title="{$LANG.custom_fields}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="25" tabindex="180" /></td>
        </tr>
        {/if}
        {if !empty($customFieldLabel.customer_cf4)}
        <tr>
          <th>{$customFieldLabel.customer_cf4|htmlsafe}
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
               title="{$LANG.custom_fields}">
              <img src="{$help_image_path}help-small.png" alt="" />
            </a>
          </th>
          <td><input type="text" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="25" tabindex="190" /></td>
        </tr>
        {/if}
        <tr>
          <th>{$LANG.notes}</th>
          <td>
            <textarea name="notes" class="editor" rows="8" cols="50" tabindex="200" >
              {$smarty.post.notes|outhtml}
            </textarea>
          </td>
        </tr>
        <tr>
          <th>{$LANG.enabled}</th>
          <td>{html_options name=enabled options=$enabled selected=1 tabindex=210}</td>
        </tr>
        {* {showCustomFields categorieId="2"} *}
      </table>
      <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="id" value="{$LANG.save}" tabindex="220">
          <img class="button_img" src="images/common/tick.png" alt="" />
          {$LANG.save}
        </button>
        <a href="index.php?module=customers&amp;view=manage" class="negative" tabindex="230">
          <img src="images/common/cross.png" alt="" />
          {$LANG.cancel}
        </a>
      </div>
    </div>
    <input type="hidden" name="op" value="insert_customer" />
    <input type="hidden" name="domain_id" value="{$domain_id}"/>
  </form>
{/if}
