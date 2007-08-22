<form name="frmpost"
	action="index.php?module=preferences&view=save&submit={$smarty.get.submit}"
	method="post">


{if $smarty.get.action== 'view' }
	<b>Preference :: <a href='index.php?module=preferences&view=details&submit={$preference.pref_id}&action=edit'>Edit</a></b>
	<hr></hr>

	
	<table align=center>
		<tr>
  			<td class='details_screen'>Preference ID</td><td>{$preference.pref_id}</td>
                </tr>
		<tr>	
			<td class='details_screen'>Description <a href="docs.php?t=help&p=inv_pref_description" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_description}</td>
                </tr>
                <tr>
			<td class='details_screen'>Currency sign <a href="docs.php?t=help&p=inv_pref_currency_sign" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_currency_sign}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice heading <a href="docs.php?t=help&p=inv_pref_invoice_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_heading}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice wording <a href="docs.php?t=help&p=inv_pref_invoice_wording" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_wording}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail heading <a href="docs.php?t=help&p=inv_pref_invoice_detail_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_detail_heading}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail line <a href="docs.php?t=help&p=inv_pref_invoice_detail_line" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_detail_line}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment method <a href="docs.php?t=help&p=inv_pref_invoice_payment_method" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_payment_method}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 name <a href="docs.php?t=help&p=inv_pref_payment_line1_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_payment_line1_name}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 value <a href="docs.php?t=help&p=inv_pref_payment_line1_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_payment_line1_value}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 name <a href="docs.php?t=help&p=inv_pref_payment_line2_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_payment_line2_name}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 value <a href="docs.php?t=help&p=inv_pref_payment_line2_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.pref_inv_payment_line2_value}</td>
		</tr>
	        <tr>
        	        <td class='details_screen'>{$LANG.enabled} <a href="docs.php?t=help&p=inv_pref_invoice_enabled" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$preference.enabled}</td>
	        </tr>	
		<tr>
			<td colspan=2 align=center></td>
		</tr>
		<tr>
			<td colspan=2 align=center class="align_center"><a href="docs.php?t=help&p=inv_pref_what_the" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
		</tr>
		</table>
		<hr></hr>

<a href='index.php?module=preferences&view=details&submit=$pref_idField&action=edit'>Edit</a>

{/if}

{if $smarty.get.action== 'edit' }

<b>Preferences</b>
	<hr></hr>

        <table align=center>
                <tr>
                        <td class='details_screen'>Preference ID</td><td>{$preference.pref_id}</td>
                </tr>
                <tr>
                        <td class='details_screen'>Description <a href="docs.php?t=help&p=inv_pref_description" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_description' value='{$preference.pref_description}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Currenc sign <a href="docs.php?t=help&p=inv_pref_currency_sign" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_currency_sign' value='{$preference.pref_currency_sign}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice heading <a href="docs.php?t=help&p=inv_pref_invoice_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a><td><input type=text name='pref_inv_heading' value='{$preference.pref_inv_heading}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice wording <a href="docs.php?t=help&p=inv_pref_invoice_wording" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_wording' value='{$preference.pref_inv_wording}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail heading <a href="docs.php?t=help&p=inv_pref_invoice_detail_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_heading' value='{$preference.pref_inv_detail_heading}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail line <a href="docs.php?t=help&p=inv_pref_invoice_detail_line" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_line' value='{$preference.pref_inv_detail_line}' size=75></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment method <a href="docs.php?t=help&p=inv_pref_invoice_payment_method" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_method' value='{$preference.pref_inv_payment_method}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 name <a href="docs.php?t=help&p=inv_pref_payment_line1_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_name' value='{$preference.pref_inv_payment_line1_name}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 value <a href="docs.php?t=help&p=inv_pref_payment_line1_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_value' value='{$preference.pref_inv_payment_line1_value}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 name <a href="docs.php?t=help&p=inv_pref_payment_line2_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_name' value='{$preference.pref_inv_payment_line2_name}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 value <a href="docs.php?t=help&p=inv_pref_payment_line2_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_value' value='{$preference.pref_inv_payment_line2_value}' size=50></td>
                </tr>
	<tr>
    	<td class='details_screen'>{$LANG.enabled} <a href="docs.php?t=help&p=inv_pref_invoice_enabled" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
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
                        <td colspan=2 align=center class="align_center"><a href="docs.php?t=help&p=inv_pref_what_the" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
                </tr>

                </table>
		<hr></hr>

<input type=submit name='action' value='{$LANG.cancel}'>
<input type=submit name='save_preference' value='{$LANG.save}'>
<input type=hidden name='op' value='edit_preference'>
{/if}
</form>
