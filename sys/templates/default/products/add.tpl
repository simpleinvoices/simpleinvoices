
{* if bill is updated or saved.*}

{if $smarty.post.description != "" && $smarty.post.id != null } 
	{include file="$smarty_embed_path/sys/templates/default/products/save.tpl"}
{else}
{* if  name was inserted *} 
	{if $smarty.post.id !=null} 
		<div class="validation_alert"><img src="{$include_dir}sys/images/common/important.png" alt="" />
		You must enter a description for the product</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=products&view=add" method="POST" id="frmpost" onsubmit="return checkForm(this);">
<br />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.description} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.Required_Field}"><img src="{$include_dir}sys/images/common/required-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="description" value="{$smarty.post.description|htmlsafe}" size="50" id="description"  class="validate[required]" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.details} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_detail" title="{$LANG.details}"><img src="./sys/images/common/help-small.png" alt="" /></a>
		</td>
        <td>
            <textarea input type="text" name='detail' rows="3" cols="50">{$smarty.post.detail|unescape}</textarea>
        </td>

	</tr>
	<tr>
		<td class="details_screen">{$LANG.unit_price}</td>
		<td><input type="text" class="edit" name="unit_price" value="{$smarty.post.unit_price|htmlsafe}"  size="25" /></td>
	</tr>
    {if $defaults.inventory == '1'}
        <tr>
            <td class="details_screen">

                {$LANG.cost}

		        <a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{$LANG.cost}">
                    <img src="{$include_dir}sys/images/common/help-small.png" alt="" />
                </a>

            </td>
            <td><input type="text" class="edit" name="cost" value="{$smarty.post.cost|htmlsafe}"  size="25" /></td>
        </tr>
        <tr>
            <td class="details_screen">{$LANG.reorder_level}</td>
            <td><input type="text" class="edit" name="reorder_level" value="{$smarty.post.reorder_level|htmlsafe}"  size="25" /></td>
        </tr>
    {/if}
	<tr>
		<td class="details_screen">{$LANG.default_tax}</td>
		<td>
		<select name="default_tax_id">
		    <option value=''></option>
			{foreach from=$taxes item=tax}
				<option value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1|htmlsafe} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$include_dir}sys/images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field1" value="{$smarty.post.custom_field1|htmlsafe}"  size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2|htmlsafe} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$include_dir}sys/images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3|htmlsafe} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$include_dir}sys/images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4|htmlsafe} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="{$include_dir}sys/images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea input type="text" class="editor" name='notes' rows="8" cols="50">{$smarty.post.notes|unescape}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options class=edit name=enabled options=$enabled selected=1}
		</td>
	</tr>
	{*	{showCustomFields categorieId="3" itemId=""} *}
</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
			    <img class="button_img" src="{$include_dir}sys/images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="insert_product" />
		
			<a href="./index.php?module=products&view=manage" class="negative">
		        <img src="{$include_dir}sys/images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
