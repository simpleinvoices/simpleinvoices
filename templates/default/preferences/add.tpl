
{* if customer is updated or saved.*} 

{if $smarty.post.p_description != "" && $smarty.post.submit != null } 
{$refresh_total}

<br />
<br />
{$display_block} 
<br />
<br />

{else}
{* if  name was inserted *} 
	{if $smarty.post.submit !=null} 
		<div class="validation_alert"><img src="./images/common/important.png"</img>
		You must enter a description for the preference</div>
		<hr />
	{/if}
<form name="frmpost" ACTION="index.php?module=preferences&view=add" METHOD="POST">

<h3>{$LANG.invoice_preference_to_add}</h3>

<hr />


<table align=center>
<tr>
	<td class="details_screen">{$LANG.description} <a href="docs.php?t=help&p=inv_pref_description" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_description"  value="{$smarty.post.p_description}" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.currency_sign} <a href="docs.php?t=help&p=inv_pref_currency_sign" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_currency_sign"  value="{$smarty.post.p_currency_sign}" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_heading} <a href="docs.php?t=help&p=inv_pref_invoice_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_heading"  value="{$smarty.post.p_inv_heading}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_wording}
	<a href="docs.php?t=help&p=inv_pref_invoice_wording" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_wording"  value="{$smarty.post.p_inv_wording}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_detail_heading}
	<a href="docs.php?t=help&p=inv_pref_invoice_detail_heading" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_heading"  value="{$smarty.post.p_inv_detail_heading}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_detail_line}
	<a href="docs.php?t=help&p=inv_pref_invoice_detail_line" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_line"  value="{$smarty.post.p_inv_detail_line}" size=75></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_method}
	<a href="docs.php?t=help&p=inv_pref_invoice_payment_method" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_method"  value="{$smarty.post.p_inv_payment_method}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_1_name}
	<a href="docs.php?t=help&p=inv_pref_payment_line1_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_name"  value="{$smarty.post.p_inv_payment_line1_name}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_1_value}
	<a href="docs.php?t=help&p=inv_pref_payment_line1_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_value"  value="{$smarty.post.p_inv_payment_line1_value}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_2_name}
	<a href="docs.php?t=help&p=inv_pref_payment_line2_name" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_name"  value="{$smarty.post.p_inv_payment_line2_name}" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG.invoice_payment_line_2_value}
	<a href="docs.php?t=help&p=inv_pref_payment_line2_value" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_value"  value="{$smarty.post.p_inv_payment_line2_value}" size=50></td>
</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			<select name="pref_enabled">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
</table>
<!-- </div> -->
<hr />
<div style="text-align:center;">
	<input type=submit name="submit" value="{$LANG.insert_preference}">
	<input type=hidden name="op" value="insert_preference">
</div>
</form>
	
{/if}
