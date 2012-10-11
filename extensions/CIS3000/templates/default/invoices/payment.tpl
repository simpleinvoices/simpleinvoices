{*
/*
* Script: itemised.tpl
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

<div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
        	<img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." /> {$LANG.loading} ...
</div>


{if $first_run_wizard == true}

        <br />
        <br />
        <span class="welcome">
            {$LANG.before_starting}
        </span>
        <br />
        <br />
        <br />
        <table class="buttons" align="center">
    {if $billers == null}
        <tr>
                <td>
                     {$LANG.setup_as_biller}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=billers&amp;view=add" class="positive">
                        <img src="./images/common/user_add.png" alt="" />
                        {$LANG.add_new_biller}
                    </a>
                </td>
        </tr>
    {/if}
    {if $customers == null}
            <tr>
                <td>
                     {$LANG.setup_add_customer}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=customers&amp;view=add" class="positive">
                        <img src="./images/common/vcard_add.png" alt="" />
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
    {/if}
    {if $products == null}
            <tr>
                <td>
                     {$LANG.setup_add_products}&nbsp;  
                </td>
                <td>
                    <a href="./index.php?module=products&amp;view=add" class="positive">
                        <img src="./images/common/cart_add.png" alt="" />
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>

    {/if}
    {if $taxes == null}
            <tr>
                <td>
                     {$LANG.setup_add_taxrate}&nbsp;  
                </td>
                <td>
                    <a href="index.php?module=tax_rates&amp;view=add" class="positive">
                        <img src="./images/common/money_delete.png" alt="" />
                        {$LANG.add_new_tax_rate}
                    </a>
                </td>
            </tr>

    {/if}
    {if $preferences == null}
            <tr>
                <td>
                     {$LANG.setup_add_inv_pref}&nbsp;  
                </td>
                <td>
                    </a>
                    <a href="./index.php?module=preferences&amp;view=add" class="positive">
                        <img src="./images/common/page_white_edit.png" alt="" />
                        {$LANG.add_new_preference}
                    </a>
                </td>
            </tr>


    {/if}
                </td>
            </tr>
            </table>
        <br />

{else}
{include file="$path/header.tpl" }

<table align="left">
	<tr>
		<td colspan="3">
		<table id="itemtable">
			<tbody id="itemtable-tbody">
			<tr>
				<td class="details_screen"></td>
				<td class="details_screen">{$LANG.quantity}</td>
				<td class="details_screen">{$LANG.item}</td>
				{section name=tax_header loop=$defaults.tax_per_line_item }
					<td class="details_screen">{$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if} </td>
				{/section}
				<td class="details_screen">{$LANG.unit_price}</td>
			</tr>
			</tbody>
	
	        {section name=line start=0 loop=$dynamic_line_items step=1}
		{ assign var="lineNumber" value=$smarty.section.line.index } 
				<tbody class="line_item" id="row{$smarty.section.line.index|htmlsafe}">
					<tr>
						<td>
							{if $smarty.section.line.index == "0"}
							<a 
								href="#" 
								class="trash_link"
								id="trash_link{$smarty.section.line.index|htmlsafe}"
								title="{$LANG.cannot_delete_first_row|htmlsafe}"
							>
								<img 
									id="trash_image{$smarty.section.line.index|htmlsafe}"
									src="./images/common/blank.gif"
									height="16px"
									width="16px"
									title="{$LANG.cannot_delete_first_row}"
									alt=""
								 />
							</a>
							{/if}
							{if $smarty.section.line.index != 0}
							{* can't delete line 0 *}
							<!-- onclick="delete_row({$smarty.section.line.index|htmlsafe});" --> 
							<a 
								id="trash_link{$smarty.section.line.index|htmlsafe}"
								class="trash_link"
								title="{$LANG.delete_row}" 
								rel="{$smarty.section.line.index|htmlsafe}"
								href="#" 
								style="display: inline;"
							>
								<img src="./images/common/delete_item.png" alt="" />
							</a>
							{/if}
						</td>
						<td>
							<input 
                                type="text" 
                                {if $smarty.section.line.index == "0"} 
                                    class="validate[required]" 
                                {/if} 
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
							class="{if $smarty.section.line.index == "0"}validate[required]{/if} product_change"						
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
							<input 
								id="unit_price{$smarty.section.line.index|htmlsafe}" 
								name="unit_price{$smarty.section.line.index|htmlsafe}" 
								size="7"
								{if $smarty.get.unit_price.$lineNumber}
								    value="{$smarty.get.unit_price.$lineNumber}"
								{else}
								   value=""
								{/if}
                                {if $smarty.section.line.index == "0"} class="validate[required]" {/if}
							/>
						</td>	
					</tr>
							
					<tr class="note">
							<td>
							</td>
							<td colspan="4">
								<textarea input type="text" class="note" name="description{$smarty.section.line.index|htmlsafe}" id="description{$smarty.section.line.index|htmlsafe}" rows="3" cols=3 WRAP=nowrap></textarea>
								
								</td>
					</tr>
				</tbody>
	        {/section}
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="buttons" align="left">
				<tr>
					<td>
						{* onclick="add_line_item();" *}
						<a 
							href="#" 
							class="add_line_item"
						>
							<img 
								src="./images/common/add.png"
								alt=""
							/>
							{$LANG.add_new_row}
						</a>
				
					</td>
					<td>
					<a href='#' class="show-note" onclick="javascript: $('.note').show();$('.show-note').hide();">
						<img src="./images/common/page_white_add.png" title="{$LANG.show_details}" alt="" />{$LANG.show_details}</a>
					<a href='#' class="note" onclick="javascript: $('.note').hide();$('.show-note').show();">
						<img src="./images/common/page_white_delete.png" title="{$LANG.hide_details}" alt="" />{$LANG.hide_details}</a>
					</td>
				</tr>
		 </table>
		</td>
	</tr>
			{$show_custom_field.1}
			{$show_custom_field.2}
			{$show_custom_field.3}
			{$show_custom_field.4}
			{*
				{showCustomFields categorieId="4" itemId=""}
			*}
	<tr>
	        <td colspan="1" class="details_screen">{$LANG.notes}</td>
	</tr>
	
	<tr>
		<td colspan="4">
			<textarea input type="text" class="editor" name="note" rows="5" cols="50" wrap="nowrap">
				{$smarty.get.note}
			</textarea>
		</td>
	</tr>
	</tr>
	
	<tr>
	<td class="details_screen">{$LANG.inv_pref}
	&nbsp; 
	&nbsp; 
	{if $preferences == null }
		<p><em>{$LANG.no_preferences}</em></p>
	{else}
		<select name="preference_id">
		{foreach from=$preferences item=preference}
			<option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe}</option>
		{/foreach}
		</select>
	{/if}
	
	</td>
	</tr>	
	<tr>
		<td class=""> 
			<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" title="{$LANG.want_more_fields}"><img src="./images/common/help-small.png" alt="" /> {$LANG.want_more_fields}</a>
		</td>
	</tr>

</table>
</td>
</tr>
<tr>
<td>
<table class="buttons" align="center">
	<tr>
		<td>
		<button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>
            
		</td>
		<td>
		<input type="hidden" id="max_items" name="max_items" value="{$smarty.section.line.index|htmlsafe}" />
        	<input type="hidden" name="type" value="2" />
        	
            <a href="./index.php?module=invoices&amp;view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
</table>
</table>

</form>
{/if}
