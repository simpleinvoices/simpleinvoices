{*
/*
* Script: /simple/extensions/invoice_add_display_no/templates/default/invoices/itemised.tpl
* 	 Itemised invoice template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">

	<div id="gmail_loading" class="gmailLoader si_hide" style="float:right;"><img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." /> {$LANG.loading} ...</div>


{if $first_run_wizard == true}

		<div class="si_message">
            {$LANG.before_starting}
		</div>
 
		<table class="buttons" align="center">

    {if $billers == null}
			<tr>
				<th>{$LANG.setup_as_biller}</th>
                <td>
                    <a href="./index.php?module=billers&amp;view=add" class="positive"><img src="./images/common/user_add.png" alt="add" />{$LANG.add_new_biller}</a>
                </td>
        </tr>
    {/if}

    {if $customers == null}
			<tr>
				<th>{$LANG.setup_add_customer}</th>
                <td>
                    <a href="./index.php?module=customers&amp;view=add" class="positive"><img src="./images/common/vcard_add.png" alt="vcard" />{$LANG.customer_add}</a>
                </td>
            </tr>
    {/if}

    {if $products == null}
			<tr>
				<th>{$LANG.setup_add_products}</th>
                <td>
                    <a href="./index.php?module=products&amp;view=add" class="positive"><img src="./images/common/cart_add.png" alt="cart" />{$LANG.add_new_product}</a>
                </td>
            </tr>

    {/if}

    {if $taxes == null}
			<tr>
				<th>{$LANG.setup_add_taxrate}</th>
                <td>
                    <a href="index.php?module=tax_rates&amp;view=add" class="positive"><img src="./images/common/money_delete.png" alt="delete" />{$LANG.add_new_tax_rate}</a>
                </td>
            </tr>

    {/if}

    {if $preferences == null}
            <tr>
				<th>{$LANG.setup_add_inv_pref}</th>
                <td>
                    </a>
                    <a href="./index.php?module=preferences&amp;view=add" class="positive"><img src="./images/common/page_white_edit.png" alt="edit" />{$LANG.add_new_preference}</a>
                </td>
            </tr>
    {/if}
		</table>

{else}

<div class="si_toolbar" style="float: right;">
	<div class="si_toolbar_form">
		<button type="submit" class="invoice_save" name="submit" value="{$LANG.save}"><img class="button_img" src="./images/common/tick.png" alt="tick" />{$LANG.save}</button><br />
	</div>
{if $defaults.use_modal}
	<div class="si_toolbar_inform">
		<br />{$LANG.Modal}:<br /><br />
		<a rel="superbox[iframe][1075x600]" href="index.php?module=customers&view=add" class="show-details modal customer_add" title="{$LANG.add_customer}">
			<img class="button_img" src="./images/common/add.png" alt="add" />{$LANG.add_customer}</a><br /><br />
{*		<a rel="superbox[iframe][1200x750]" href="extensions/matts_luxury_pack/modules/iframe_customers/add.php" class="show-details modal customer_add" title="{$LANG.add_customer}">
			<img class="button_img" src="./images/common/add.png" alt="add" />{$LANG.add_customer}</a><br /><br />*}
{*		<a rel="superbox[ajax][extensions/matts_luxury_pack/modules/iframe_customers/add.php][1000x1100]" href="#" class="show-details modal customer_add" id="modal_customer_add" title="{$LANG.add_customer}"><!--javascript:void(false)-->
			<img class="button_img" src="./images/common/add.png" alt="add" />{$LANG.add_customer}</a><br /><br />*}
		<a rel="superbox[iframe][1075x600]" href="index.php?module=products&view=add" class="show-details modal product_add" title="{$LANG.add_product}">
			<img class="button_img" src="./images/common/add.png" alt="add" />{$LANG.add_product}</a>
{*		<a rel="superbox[ajax][index.php?module=products&view=add][1075x600]" href="#" class="show-details modal product_add" id="modal_product_add" title="{$LANG.add_product}">
			<img class="button_img" src="./images/common/add.png" alt="add" />{$LANG.add_product}</a>*}
	</div>
{/if}
</div>

<div class="si_invoice_form">

	{include file="$path/header.tpl"}

	<table id="itemtable" class="si_invoice_items">
		<thead>
			<tr>
				<td class=""></td>
				<td class="">{$LANG.quantity}</td>
				<td class="">{$LANG.item}</td>
			{section name=tax_header loop=$defaults.tax_per_line_item }
				<td class="">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if} </td>
			{/section}
				<td class="">{$LANG.unit_price}</td>
			</tr>
		</thead>

		{section name=line start=0 loop=$dynamic_line_items step=1}
		<tbody class="line_item" id="row{$smarty.section.line.index|htmlsafe}">
			{assign var="lineNumber" value=$smarty.section.line.index}
			<tr>
				<td>
					{if $smarty.section.line.index == "0"}
					<a href="#" class="trash_link" id="trash_link{$smarty.section.line.index|htmlsafe}" title="{$LANG.cannot_delete_first_row|htmlsafe}" >
						<img id="trash_image{$smarty.section.line.index|htmlsafe}" src="./images/common/blank.gif" height="16px" width="16px" title="{$LANG.cannot_delete_first_row}" alt="cant" />
					</a>
					{/if}

					{if $smarty.section.line.index != 0}
					{* can't delete line 0 *}
					<!-- onclick="delete_row({$smarty.section.line.index|htmlsafe});" --> 
					<a 
						id="trash_link{$smarty.section.line.index|htmlsafe}"
						class="trash_link modal"
						title="{$LANG.delete_row}" 
						rel="{$smarty.section.line.index|htmlsafe}"
						href="#" 
						style="display: inline;"
					>
						<img src="./images/common/delete_item.png" alt="delete" />
					</a>
					{/if}
				</td>
				<td>
					<input type="text" 
						class="si_right{if $smarty.section.line.index == "0"} validate[required]{/if}" 
						name="quantity{$smarty.section.line.index|htmlsafe}" 
						id="quantity{$smarty.section.line.index|htmlsafe}" size="5" 
						{if $smarty.get.quantity.$lineNumber}
							value="{$smarty.get.quantity.$lineNumber}"
						{/if}
						/>
				</td>
				<td>
								
			{if $products == null }
				<p><em>{$LANG.no_products}</em></p>
			{else}
				<select 
					id="products{$smarty.section.line.index|htmlsafe}"
					name="products{$smarty.section.line.index|htmlsafe}"
					rel="{$smarty.section.line.index|htmlsafe}"
					class="{if $smarty.section.line.index == "0"}validate[required] {/if}modal{*changeProduct*}{*product__change*}"
					{**}onchange="changeProductSelection(this)"{**}
				>
					<option value=""></option>
				{foreach from=$products item=product}
					<option 
						{if $product.id == $smarty.get.product.$lineNumber}
							value="{$smarty.get.product.$lineNumber}"
							selected
						{else}
							value="{$product.id|htmlsafe}"
						{/if}
					>
						{$product.description|htmlsafe}
					</option>
				{/foreach}
				</select>
			{/if}
				</td>
				{section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
							{ assign var="taxNumber" value=$smarty.section.tax.index } 
				<td>				                				                
					<select 
						id="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
						name="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
					>
					<option value=""></option>
					{foreach from=$taxes item=tax}
						<option 
							{if $tax.tax_id == $smarty.get.tax.$lineNumber.$taxNumber}
							value="{$smarty.get.tax.$lineNumber.$taxNumber}"
							selected
							{else}
							   value="{$tax.tax_id|htmlsafe}"
							{/if}
						>
							{$tax.tax_description|htmlsafe}
						</option>
					{/foreach}
				</select>
				</td>
				{/section}

				<td>
					<input id="unit_price{$smarty.section.line.index|htmlsafe}" 
						name="unit_price{$smarty.section.line.index|htmlsafe}" 
						size="7"
						{if $smarty.get.unit_price.$lineNumber}
							value="{$smarty.get.unit_price.$lineNumber}"
						{else}
							value=""
						{/if}
						class="si_right{if $smarty.section.line.index == "0"} validate[required]{/if}" 
					/>
				</td>	

			</tr>
					
			<tr class="details si_hide">
				<td></td>
				<td colspan="4">
					<textarea input type="text" class="detail" name="description{$smarty.section.line.index|htmlsafe}" id="description{$smarty.section.line.index|htmlsafe}" rows="3" cols=3 WRAP=nowrap></textarea>
				</td>
			</tr>
		</tbody>
		{/section}
	</table>

	<div class="si_toolbar si_toolbar_inform">
		<a href="#" class="add_line_item"><img src="./images/common/add.png" alt="add" />{$LANG.add_new_row}</a>
		<a href='#' class="show-details" onclick="javascript: $('.details').addClass('si_show').removeClass('si_hide');$('.show-details').addClass('si_hide').removeClass('si_show');"><img src="./images/common/page_white_add.png" title="{$LANG.show_details}" alt="details" />{$LANG.show_details}</a>
		<a href='#' class="details si_hide" onclick="javascript: $('.details').removeClass('si_show').addClass('si_hide');$('.show-details').addClass('si_show').removeClass('si_hide');" ><img src="./images/common/page_white_delete.png" title="{$LANG.hide_details}" alt="hide" />{$LANG.hide_details}</a>
	</div>

	<table class="si_invoice_bot">

		{$show_custom_field.1}
		{$show_custom_field.2}
		{$show_custom_field.3}
		{$show_custom_field.4}
		{*
			{showCustomFields categorieId="4" itemId=""}
		*}

		<tr>
			<td class='si_invoice_notes' colspan="2">
				<H5>{$LANG.notes}</H5>
				<textarea input type="text" class="editor" name="note" rows="5" cols="50" wrap="nowrap">
						{$smarty.get.note}
				</textarea>
			</td>
		</tr>
			
		<tr>
			<th>
				{$LANG.inv_pref}
			</th>
			<td>
			{if $preferences == null }
				<em>{$LANG.no_preferences}</em>
			{else}
				<select name="preference_id">
				{foreach from=$preferences item=preference}
					<option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe}</option>
				{/foreach}
				</select>
			{/if}
			</td>
		</tr>	
	</table>
 
	<input type="hidden" id="max_items" name="max_items" value="{$smarty.section.line.index|htmlsafe}" />
	<input type="hidden" name="type" value="2" />

	<div class="si_toolbar si_toolbar_form">
		<button type="submit" class="invoice_save" name="submit" value="{$LANG.save}"><img class="button_img" src="./images/common/tick.png" alt="tick" />{$LANG.save}</button>
    	<a href="./index.php?module=invoices&amp;view=manage" class="negative"><img src="./images/common/cross.png" alt="cross" />{$LANG.cancel}</a>
	</div>

	<div class="si_help_div">
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{$LANG.want_more_fields}"><img src="./images/common/help-small.png" alt="help" /> {$LANG.want_more_fields}</a>
	</div>

</div>

</form>

{/if}

<script type="text/javascript">
<!--
{literal}
/*
function init() {
	var el = getElementsByClassName("changeProduct");
	alert('el.length='+ el.length);
	for (var i=0; i<el.length; i++) {
	alert('el['+ i+ ']='+ el[i]);
		if (el[i].addEventListener){
			el[i].addEventListener("change", function() { changeProductSelection(this); }, false);
		} else if (el[i].attachEvent){
			el[i].attachEvent("onchange", function() { changeProductSelection(this); });
		}
	}
};

if (window.addEventListener) {
	window.addEventListener("load", init, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", init);
} else {
	document.addEventListener("load", init, false);
}
*/
{/literal}
//-->
</script>
