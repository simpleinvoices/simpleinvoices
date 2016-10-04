<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost" action="index.php?module=preferences&amp;view=save&amp;id={$smarty.get.id}" method="post">


{if $smarty.get.action== 'view' }

<div class="si_form si_form_view">
	<table>
		<tr>	
			<th>Description 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{$LANG.description}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>{$preference.pref_description}</td>
		</tr>
		<tr>
			<th>Currency sign 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{$LANG.currency_sign}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>{$preference.pref_currency_sign}</td>
		</tr>
		<tr>
			<th>{$LANG.currency_code}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{$LANG.currency_code}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>{$preference.currency_code|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{$LANG.invoice_heading}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a> 
			</th>
			<td>{$preference.pref_inv_heading|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice wording 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{$LANG.invoice_wording}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>{$preference.pref_inv_wording|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice detail heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{$LANG.invoice_detail_heading}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>{$preference.pref_inv_detail_heading|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.include_online_payment}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
				<input type=checkbox name=include_online_payment[] {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if} value='paypal' DISABLED>{$LANG.paypal} 
				<input type=checkbox name=include_online_payment[] {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if} value='eway_merchant_xml' DISABLED>{$LANG.eway_merchant_xml} 
				<input type=checkbox name=include_online_payment[] {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if} value='paymentsgateway' DISABLED>{$LANG.paymentsgateway} 
			</td>
		</tr>
		<tr>
			<th>Invoice payment method 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{$LANG.invoice_payment_method}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.pref_inv_payment_method|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice payment line1 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{$LANG.invoice_payment_line_1_name}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.pref_inv_payment_line1_name|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice payment line1 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{$LANG.invoice_payment_line_1_value}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.pref_inv_payment_line1_value|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice payment line2 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{$LANG.invoice_payment_line_2_name}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.pref_inv_payment_line2_name|htmlsafe}</td>
		</tr>
		<tr>
			<th>Invoice payment line2 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{$LANG.invoice_payment_line_2_value}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.pref_inv_payment_line2_value|htmlsafe}</td>
		</tr>
		
        <tr>
        	<th>{$LANG.enabled} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{$LANG.enabled}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.enabled}</td>
		</tr>	
        <tr>
        	<th>{$LANG.status} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{$LANG.status}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.status_wording}</td>
		</tr>	
        <tr>
        	<th>{$LANG.invoice_numbering_group} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{$LANG.invoice_numbering_group}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$index_group.pref_description}</td>
		</tr>
		<tr>
			<th>{$LANG.language} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{$LANG.language}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.language}</td>
		</tr>
		<tr>
			<th>{$LANG.locale} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{$LANG.locale}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>{$preference.locale}</td>
		</tr>

	</table>
</div>

	<div class="si_toolbar si_toolbar_form">
				<a href="./index.php?module=preferences&amp;view=details&amp;id={$preference.pref_id}&amp;action=edit" class="positive">
				<img src="./images/famfam/report_edit.png" alt="" />{$LANG.edit}</a>

            <a href="./index.php?module=preferences&amp;view=manage" class="negative">
				<img src="./images/common/cross.png" alt="" />{$LANG.cancel}</a>
	</div>

	<div class="si_help_div">
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{$LANG.whats_all_this_inv_pref}">
			<img src="{$help_image_path}help-small.png" alt="" /> Whats all this "Invoice Preference" stuff about? </a>
	</div>
{/if}





{if $smarty.get.action== 'edit' }

<div class="si_form">
	<table>
		<tr>
			<th>Description 
				<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
					title="{$LANG.required_field}"
				>
					<img src="{$help_image_path}required-small.png" alt="" />
				</a>	
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{$LANG.description}">
					<img src="{$help_image_path}help-small.png" alt="" />
				</a>
			</th>
			<td><input type="text" class="validate[required]" name='pref_description' value="{$preference.pref_description|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Currency sign 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{$LANG.currency_sign}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>
                <input type="text" name='pref_currency_sign' value="{$preference.pref_currency_sign}" size="15" />
                <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{$LANG.currency_sign}">
                   {$LANG.currency_sign_non_dollar}
                    <img src="{$help_image_path}help-small.png" alt="" /> 
                </a>
            </td>
		</tr>
		<tr>
			<th>{$LANG.currency_code} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code" title="{$LANG.currency_code}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td>
                <input type="text" name='currency_code' value="{$preference.currency_code}" size="15" />
            </td>
		</tr>
		<tr>
			<th>Invoice heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{$LANG.invoice_heading}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td><input type="text" name='pref_inv_heading' value="{$preference.pref_inv_heading|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice wording 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{$LANG.invoice_wording}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a> 
			</th>
			<td><input type="text" name='pref_inv_wording' value="{$preference.pref_inv_wording|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice detail heading 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{$LANG.invoice_detail_heading}">
				<img src="{$help_image_path}help-small.png" alt="" /> </a>
			</th>
			<td><input type="text" name='pref_inv_detail_heading' value="{$preference.pref_inv_detail_heading|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice detail line 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_detail_line' value="{$preference.pref_inv_detail_line|htmlsafe}" size="75" /></td>
		</tr>
		<tr>
			<th>{$LANG.include_online_payment}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
				<input type=checkbox name=include_online_payment[] {if in_array("paypal",explode(",", $preference.include_online_payment)) }checked{/if} value='paypal'>{$LANG.paypal}
				<input type=checkbox name=include_online_payment[] {if in_array("eway_merchant_xml",explode(",", $preference.include_online_payment)) }checked{/if} value='eway_merchant_xml'>{$LANG.eway_merchant_xml}
				<input type=checkbox name=include_online_payment[] {if in_array("paymentsgateway",explode(",", $preference.include_online_payment)) }checked{/if} value='paymentsgateway'>{$LANG.paymentsgateway}
			</td>
		</tr>
		<tr>
			<th>Invoice payment method 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{$LANG.invoice_payment_method}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_payment_method' value="{$preference.pref_inv_payment_method|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line1 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{$LANG.invoice_payment_line_1_name}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line1_name' value="{$preference.pref_inv_payment_line1_name|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line1 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{$LANG.invoice_payment_line_1_value}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line1_value' value="{$preference.pref_inv_payment_line1_value|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line2 name 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{$LANG.invoice_payment_line_2_name}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line2_name' value="{$preference.pref_inv_payment_line2_name|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>Invoice payment line2 value 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{$LANG.invoice_payment_line_2_value}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td><input type="text" name='pref_inv_payment_line2_value' value="{$preference.pref_inv_payment_line2_value|htmlsafe}" size="50" /></td>
		</tr>
		<tr>
			<th>{$LANG.status} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status" title="{$LANG.status}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
				<select name="status">
                {foreach from=$status item=s}
                    <option {if $s.id == $preference.status} selected {/if} value="{$s.id}">{$s.status}</option>
                {/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_numbering_group} 
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group" title="{$LANG.invoice_numbering_group}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
            <td class="details_screen">
            {if $preferences == null }
                <p><em>{$LANG.no_preferences}</em></p>
            {else}
                <select name="index_group">
                {foreach from=$preferences item=p}
                    <option {if $p.pref_id == $preference.index_group} selected {/if} value="{$p.pref_id|htmlsafe}">{$p.pref_description|htmlsafe}</option>
                {/foreach}
                </select>
            {/if}
            
            </td>
    	</tr>	
		<tr>
			<th>{$LANG.enabled}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{$LANG.enabled}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
				<select name="pref_enabled">
				<option value="{$preference.pref_enabled|htmlsafe}" selected
				style="font-weight: bold;">{$preference.enabled|htmlsafe}</option>
				<option value="1">{$LANG.enabled}</option>
				<option value="0">{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.language}  
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_language" title="{$LANG.language}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
                <select name="language">
                {foreach from=$localelist key=language item=value}
                    <option {if $language == $preference.language} selected {/if} value="{$language|htmlsafe}">{$language|htmlsafe}</option>
                {/foreach}
                </select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.locale}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale" title="{$LANG.locale}">
				<img src="{$help_image_path}help-small.png" alt="" /></a>
			</th>
			<td>
                <select name="locale">
                {foreach from=$localelist key=locale item=value}
                    <option {if $locale == $preference.locale} selected {/if} value="{$locale|htmlsafe}">{$locale|htmlsafe}</option>
                {/foreach}
                </select>
			</td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
				<button type="submit" class="positive" name="save_preference" value="{$LANG.save}">
					<img class="button_img" src="./images/common/tick.png" alt="" /> 
					{$LANG.save}
				</button>
        
				<a href="./index.php?module=preferences&amp;view=manage" class="negative">
					<img src="./images/common/cross.png" alt="" />
					{$LANG.cancel}
	            </a>
	</div>

</div>

	<div class="si_help_div">
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the" title="{$LANG.whats_all_this_inv_pref}">
		<img src="{$help_image_path}help-small.png" alt="" /> {$LANG.whats_all_this_inv_pref} </a>
	</div>


<input type="hidden" name="op" value="edit_preference" />
{/if}
</form>
