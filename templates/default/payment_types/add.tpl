{* if customer is updated or saved.*}

{if $smarty.post.pt_description != "" && $smarty.post.submit != null }
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
        You must enter a description for the payment type</div>
        <hr></hr>
    {/if}


<form name="frmpost" action="index.php?module=payment_types&view=add" method="post">
	<b>Payment type to add</b>
	<hr></hr>
	<table align=center>
		<tr>
			<td class="details_screen">Payment type description <a href="./modules/documentation/info_pages/required_field.html" rel="gb_page_center[350, 150]"><img src="./images/common/required-small.png"></img></a></td>
			<td><input type=text name="pt_description" size=50></td>
		</tr>
		<tr>
			<td class="details_screen">{$LANG.enabled}</td>
			<td>
				<select name="pt_enabled">
					<option value="1" selected>{$LANG.enabled}</option>
					<option value="0">{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
	</table>
	<hr></hr>
	<input type=submit name="submit" value="{$LANG.insert_payment_type}">
	<input type=hidden name="op" value="insert_payment_type">
</form>
    {/if}
