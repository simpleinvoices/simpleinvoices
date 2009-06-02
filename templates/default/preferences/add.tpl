{if $smarty.post.p_description == "" AND $smarty.post.submit != null } 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a description for the preference</div>
		<hr />
{/if}
<form name="frmpost" action="index.php?module=preferences&amp;view=save" method="post">

<br />
<table align="center">
<tr>
	<td class="details_screen">{$LANG.description} 
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
				title="{$LANG.Required_Field}"
		>
		<img src="./images/common/required-small.png" alt="" />
		</a>	
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description" title="{$LANG.description}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td>
	<input type="text" class="validate[required]" name="p_description" value="{$smarty.post.p_description}" size="25" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.currency_sign} 
	<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign" title="{$LANG.currency_sign}"><img src="./images/common/help-small.png" alt="" /> </a>
	</td>
	<td>
	<input type="text" name="p_currency_sign"  value="{$smarty.post.p_currency_sign}" size="25" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_heading} 
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading" title="{$LANG.invoice_heading}"><img src="./images/common/help-small.png" alt="" /> </a>
	</td>
	<td>
	<input type="text" name="p_inv_heading"  value="{$smarty.post.p_inv_heading}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_wording}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording" title="{$LANG.invoice_wording}"><img src="./images/common/help-small.png" alt="" /> </a>
	</td>
	<td>
	<input type="text" name="p_inv_wording"  value="{$smarty.post.p_inv_wording}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_detail_heading}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading" title="{$LANG.invoice_detail_heading}"><img src="./images/common/help-small.png" alt="" /> </a>
	</td>
	<td>
	<input type="text" name="p_inv_detail_heading"  value="{$smarty.post.p_inv_detail_heading}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_detail_line}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}"><img src="./images/common/help-small.png" alt="" /></a></td>
	</td>
	<td>
	<input type="text" name="p_inv_detail_line"  value="{$smarty.post.p_inv_detail_line}" size="75" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_method}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method" title="{$LANG.invoice_payment_method}"><img src="./images/common/help-small.png" alt="" /></a></td>
	</td>
	<td>
	<input type="text" name="p_inv_payment_method"  value="{$smarty.post.p_inv_payment_method}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_1_name}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name" title="{$LANG.invoice_payment_line_1_name}"><img src="./images/common/help-small.png" alt="" /></a></td>
	</td>
	<td>
	<input type="text" name="p_inv_payment_line1_name"  value="{$smarty.post.p_inv_payment_line1_name}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_1_value}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value" title="{$LANG.invoice_payment_line_1_value}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td>
	<input type="text" name="p_inv_payment_line1_value"  value="{$smarty.post.p_inv_payment_line1_value}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_2_name}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name" title="{$LANG.invoice_payment_line_2_name}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td>
	<input type="text" name="p_inv_payment_line2_name"  value="{$smarty.post.p_inv_payment_line2_name}" size="50" />
	</td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_2_value}
	<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value" title="{$LANG.invoice_payment_line_2_value}"><img src="./images/common/help-small.png" alt="" /></a>
	</td>
	<td>
	<input type="text" name="p_inv_payment_line2_value"  value="{$smarty.post.p_inv_payment_line2_value}" size="50" />
	</td>
</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}
			<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled" title="{$LANG.enabled}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td>
			<select name="pref_enabled">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
</table>
<!-- </div> -->
<br />
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="insert_preference" />
        
            <a href="./index.php?module=preferences&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
 </table>
</form>
