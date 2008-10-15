<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<form name="frmpost"
	action="index.php?module=preferences&view=save&id={$smarty.get.id}"
	method="post">


{if $smarty.get.action== 'view' }
	<b>Preference :: <a href='index.php?module=preferences&view=details&id={$preference.pref_id}&action=edit'>Edit</a></b>
	<hr></hr>

	
	<table align=center>
		<tr>
  			<td class='details_screen'>Preference ID</td><td>{$preference.pref_id}</td>
                </tr>
		<tr>	
			<td class='details_screen'>Description 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_description" title="{$LANG.description}"><img src="./images/common/help-small.png"></img></a>
			</td>
			<td>
				{$preference.pref_description|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Currency sign 
                 <a class="cluetip" href="#" rel="docs.php?t=help&p=inv_pref_currency_sign" title="{$LANG.currency_sign}"><img src="./images/common/help-small.png"></img> </a>
			</td>
			<td>
				{$preference.pref_currency_sign|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice heading 
            	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_heading" title="{$LANG.invoice_heading}"><img src="./images/common/help-small.png"></img> </a> 
			</td>
			<td>
				{$preference.pref_inv_heading|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice wording 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_wording" title="{$LANG.invoice_wording}"><img src="./images/common/help-small.png"></img> </a>
			</td>
			<td>
				{$preference.pref_inv_wording|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice detail heading 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_detail_heading" title="{$LANG.invoice_detail_heading}"><img src="./images/common/help-small.png"></img> </a>
			</td>
			<td>
				{$preference.pref_inv_detail_heading|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice detail line 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}"><img src="./images/common/help-small.png"></img></a></td>
			<td>
				{$preference.pref_inv_detail_line|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice payment method 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_payment_method" title="{$LANG.invoice_payment_method}"><img src="./images/common/help-small.png"></img></a></td>
			<td>
				{$preference.pref_inv_payment_method|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice payment line1 name 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line1_name" title="{$LANG.invoice_payment_line_1_name}"><img src="./images/common/help-small.png"></img></a></td>
			<td>
				{$preference.pref_inv_payment_line1_name|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice payment line1 value 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line1_value" title="{$LANG.invoice_payment_line_1_value}"><img src="./images/common/help-small.png"></img></a>
			</td>
			<td>
				{$preference.pref_inv_payment_line1_value|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice payment line2 name 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line2_name" title="{$LANG.invoice_payment_line_2_name}"><img src="./images/common/help-small.png"></img></a>
			</td>
			<td>
				{$preference.pref_inv_payment_line2_name|regex_replace:"/[\\\]/":""}
			</td>
        </tr>
        <tr>
			<td class='details_screen'>Invoice payment line2 value 
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line2_value" title="{$LANG.invoice_payment_line_2_value}"><img src="./images/common/help-small.png"></img></a>
			</td>
			<td>
				{$preference.pref_inv_payment_line2_value|regex_replace:"/[\\\]/":""}
			</td>
		</tr>
	    <tr>
        	<td class='details_screen'>{$LANG.enabled} 
        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_enabled" title="{$LANG.enabled}"><img src="./images/common/help-small.png"></img></a>
        </td>
        	<td>
        		{$preference.enabled}
        	</td>
	    </tr>	
		<tr>
			<td colspan=2 align=center>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center class="align_center">
				<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_what_the" title="{$LANG.whats_all_this_inv_pref}"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about? </a>
			</td>
		</tr>
		</table>
		<hr></hr>

<a href='index.php?module=preferences&view=details&id={$preference.pref_id}&action=edit'>Edit</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>Preferences</b>
	<hr></hr>

        <table align=center>
                <tr>
                        <td class='details_screen'>Preference ID</td>
                        <td>{$preference.pref_id}</td>
                </tr>
                <tr>
                        <td class='details_screen'>Description 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_description" title="{$LANG.description}"><img src="./images/common/help-small.png"></img></a>
                       	</td>
                       	<td>
                        	<input type=text name='pref_description' value="{$preference.pref_description|regex_replace:"/[\\\]/":""}" size=50>
                       	</td>
                </tr>
                <tr>
                        <td class='details_screen'>Currency sign 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_currency_sign" title="{$LANG.currency_sign}"><img src="./images/common/help-small.png"></img> </a>
                        </td>
                        <td>
                        	<input type=text name='pref_currency_sign' value="{$preference.pref_currency_sign}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice heading 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_heading" title="{$LANG.invoice_heading}"><img src="./images/common/help-small.png"></img> </a> 
                        <td>
                        	<input type=text name='pref_inv_heading' value="{$preference.pref_inv_heading|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice wording 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_wording" title="{$LANG.invoice_wording}"><img src="./images/common/help-small.png"></img> </a> 
                        </td>
                        <td>
                        	<input type=text name='pref_inv_wording' value="{$preference.pref_inv_wording|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail heading 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_detail_heading" title="{$LANG.invoice_detail_heading}"><img src="./images/common/help-small.png"></img> </a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_detail_heading' value="{$preference.pref_inv_detail_heading|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail line 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_detail_line" title="{$LANG.invoice_detail_line}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_detail_line' value="{$preference.pref_inv_detail_line|regex_replace:"/[\\\]/":""}" size=75>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment method 
	                        <a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_payment_method" title="{$LANG.invoice_payment_method}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_payment_method' value="{$preference.pref_inv_payment_method|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 name 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line1_name" title="{$LANG.invoice_payment_line_1_name}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_payment_line1_name' value="{$preference.pref_inv_payment_line1_name|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 value 
	                        <a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line1_value" title="{$LANG.invoice_payment_line_1_value}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_payment_line1_value' value="{$preference.pref_inv_payment_line1_value|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 name 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line2_name" title="{$LANG.invoice_payment_line_2_name}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_payment_line2_name' value="{$preference.pref_inv_payment_line2_name|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 value 
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_payment_line2_value" title="{$LANG.invoice_payment_line_2_value}"><img src="./images/common/help-small.png"></img></a>
                        </td>
                        <td>
                        	<input type=text name='pref_inv_payment_line2_value' value="{$preference.pref_inv_payment_line2_value|regex_replace:"/[\\\]/":""}" size=50>
                        </td>
                </tr>
	<tr>
    	<td class='details_screen'>{$LANG.enabled} 
    		<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_invoice_enabled" title="{$LANG.enabled}"><img src="./images/common/help-small.png"></img></a>
    	</td>
		<td>
		{* enabled block *}
		<select name="pref_enabled">
			<option value="{$preference.pref_enabled}" selected
				style="font-weight: bold;">{$preference.enabled}</option>
			<option value="1">{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
		</select>
		{* /enabled block*}
		</td>
	</tr>
                <tr>
                        <td colspan=2 align=center></td>
                </tr>
                <tr>
                        <td colspan=2 align=center class="align_center">
                        	<a class="cluetip" href="#"	rel="docs.php?t=help&p=inv_pref_what_the" title="{$LANG.whats_all_this_inv_pref}"><img src="./images/common/help-small.png"></img> {$LANG.whats_all_this_inv_pref} </a>
                        </td>
                </tr>

                </table>
		<hr></hr>
<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_preference" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt=""/> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_preference">
        
            <a href="./index.php?module=preferences&view=manage" class="negative">
                <img src="./images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
 </table>
{/if}
</form>
