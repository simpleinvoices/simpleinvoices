{*
* Script: add.tpl
*   Biller add template
*
* Last edited:
*    2016-01-16 by Rich Rowley to add signature field.
*    2008-08-25
*
* License:
*  GPL v3 or above
*}

{if !empty($smarty.post.name) && isset($smarty.post.submit) } 
  {* The new biller was just added *}
  {include file="extensions/signature_field/templates/default/billers/save.tpl"}
{else}
  {* Adding a new biller *}
  <form name="frmpost" action="index.php?module=billers&amp;view=add" method="post" id="frmpost">
    <div class="si_form">
      <table>
      <tr>
        <th>{$LANG.biller_name} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
             title="{$LANG.required_field}" >
            <img src="{$help_image_path}required-small.png" alt="" />
          </a>  
        </th>
        <td>
          <input type="text" name="name" value="{$smarty.post.name|htmlsafe}"
                 size="25" id="name" class="validate[required]">
        </td>
      </tr>
      <tr> 
        <th>{$LANG.street}</th>
        <td><input type="text" name="street_address" value="{$smarty.post.street_address|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.street2} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
             title="{$LANG.street2}" > 
            <img src="{$help_image_path}help-small.png" alt="" /> 
          </a>
        </th>
        <td><input type="text" name="street_address2" value="{$smarty.post.street_address2|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.city}</th>
        <td><input type="text" name="city" value="{$smarty.post.city|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.state}</th>
        <td><input type="text" name="state" value="{$smarty.post.state|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.zip}</th>
        <td><input type="text" name="zip_code" value="{$smarty.post.zip_code|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.country}</th>
        <td><input type="text" name="country" value="{$smarty.post.country|htmlsafe}" size="50" /></td>
      </tr>
      <tr>
        <th>{$LANG.phone}</th>
        <td><input type="text" name="phone" value="{$smarty.post.phone|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.mobile_phone}</th>
        <td><input type="text" name="mobile_phone" value="{$smarty.post.mobile_phone|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.fax}</th>
        <td><input type="text" name="fax" value="{$smarty.post.fax|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.email}</th>
        <td><input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.signature}</th>
        <td><textarea  name="signature" class="editor" rows="3" cols="30">{$smarty.post.signature|htmlsafe}</textarea></td>
      </tr>
      <tr>
        <th>{$LANG.paypal_business_name}</th>
        <td><input type="text" name="paypal_business_name" value="{$smarty.post.paypal_business_name|htmlsafe}" size="25" /></td>
      </tr>
      <tr>
        <th>{$LANG.paypal_notify_url}</th>
        <td><input type="text" name="paypal_notify_url" value="{$smarty.post.paypal_notify_url|htmlsafe}" size="50" /></td>
      </tr>
      <tr>
        <th>{$LANG.paypal_return_url}</th>
        <td><input type="text" name="paypal_return_url" value="{$smarty.post.paypal_return_url|htmlsafe}" size="50" /></td>
      </tr>
      <tr>
        <th>{$LANG.eway_customer_id}</th>
        <td><input type="text" name="eway_customer_id" value="{$smarty.post.eway_customer_id|htmlsafe}" size="50" /></td>
      </tr>
      <tr>
        <th>{$LANG.paymentsgateway_api_id}</th>
        <td><input type="text" name="paymentsgateway_api_id" value="{$smarty.post.paymentsgateway_api_id|htmlsafe}" size="50" /></td>
      </tr>
      {if !empty($customFieldLabel.biller_cf1)}
      <tr>
        <th>{$customFieldLabel.biller_cf1|htmlsafe}
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
             title="{$LANG.custom_fields}" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td><input type="text" name="custom_field1" value="{$smarty.post.custom_field1}" size="25" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.biller_cf2)}
      <tr>
        <th>{$customFieldLabel.biller_cf2} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
             title="{$LANG.custom_fields}" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td><input type="text" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="25" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.biller_cf3)}
      <tr>
        <th>{$customFieldLabel.biller_cf3|htmlsafe} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
             title="{$LANG.custom_fields}" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td><input type="text" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="25" /></td>
      </tr>
      {/if}
      {if !empty($customFieldLabel.biller_cf4)}
      <tr>
        <th>{$customFieldLabel.biller_cf4|htmlsafe} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
             title="{$LANG.custom_fields}" >
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td><input type="text" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="25" /></td>
      </tr>
      {/if}
      <tr>
        <th>{$LANG.logo_file} 
          <a class="cluetip"
             href="#"
             rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text"
             title="{$LANG.logo_file}" > 
            <img src="{$help_image_path}help-small.png" alt="" />
          </a>
        </th>
        <td>
          {html_options name=logo output=$files values=$files selected=$files[0] }
        </td>
      </tr>
      <tr>
        <th>{$LANG.invoice_footer}</th>
        <td>
          <textarea class="editor" name="footer" rows="4" cols="50">
                    {$smarty.post.footer|htmlsafe}
          </textarea>
       </td>
      </tr>
      <tr>
        <th>{$LANG.notes}</th>
        <td>
          <textarea class="editor" name="notes" rows="8" cols="50">
                    {$smarty.post.notes|htmlsafe}
          </textarea>
        </td>
      </tr>
      <tr>
        <th>{$LANG.enabled}</th>
        <td>
          {html_options name=enabled options=$enabled selected=1}
        </td>
      </tr>
      {* 
        {showCustomFields categorieId="1" itemId=""}
      *}
    
      </table>
    
      <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
          <img class="button_img" src="images/common/tick.png" alt="" /> 
          {$LANG.save}
        </button>
            
        <a href="index.php?module=billers&amp;view=manage" class="negative">
          <img src="images/common/cross.png" alt="" />
          {$LANG.cancel}
        </a>
      </div>
    </div>
    
    <input type="hidden" name="op" value="insert_biller" />
    <input type="hidden" name="domain_id" value="{$domain_id}" />
  </form>
{/if}
