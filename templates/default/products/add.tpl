{* if customer is updated or saved.*} 

{if $smarty.post.prod_description != "" && $smarty.post.submit != null } 
{$refresh_total}

<br />
<br>
{$display_block} 
<br />
<br />

{else}
{* if  name was inserted *} 
	{if $smarty.post.submit !=null} 
		<div class="validation_alert"><img src="./images/common/important.png"</img>
		You must enter a description for the product</div>
		<hr></hr>
	{/if}
<FORM name="frmpost" ACTION="index.php?module=products&view=add" METHOD="POST">

<div id="top"><b>&nbsp;{$LANG.product_to_add}&nbsp;</b></div>
 <hr></hr>

<table align=center>
	<tr>
		<td class="details_screen">{$LANG.product_description} <a href=""><img src="./images/common/important.png"></img></a></td>
		<td><input type=text name="prod_description" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_unit_price}</td>
		<td><input type=text name="prod_unit_price" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.1} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field1" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.2} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field2" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.3} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field3" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.4} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field4" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type=text name='prod_notes' rows=8 cols=50>{$prod_notesField}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.product_enabled}</td>
		<td>
			<select name="prod_enabled">
			<option value="1" selected>{$LANG.enabled}</option>
			<option value="0">{$LANG.disabled}</option>
			</select>
		</td>
	</tr>
</table>
<!-- </div> -->
<hr></hr>
	<input type=submit name="submit" value="{$LANG.insert_product}">
	<input type=hidden name="op" value="insert_product">
</FORM>
	{/if}
