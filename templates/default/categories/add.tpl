
{* if bill is updated or saved.*}

{if $smarty.post.name != "" && $smarty.post.referencia != "" } 
	{include file="../templates/default/categories/save.tpl"}
{else}
{* if  name was inserted *} 
	{if $smarty.post.referencia !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		La has cagado</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=categories&view=add" method="POST" id="frmpost" onsubmit="return checkForm(this);">
<br />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.category} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.Required_Field}"><img src="./images/common/required-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="name" value="{$smarty.post.category_name|htmlsafe}" size="25" id="name"  class="validate[required]" /></td>
	</tr>
    <tr>
		<td class="details_screen">{$LANG.category_code} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.Required_Field}"><img src="./images/common/required-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="referencia" value="{$smarty.post.referencia|htmlsafe}" size="25" id="referencia"  class="validate[required]" /></td>
	</tr>
<tr>
		<td class="details_screen">{$LANG.category_parent}</td>
		<td>
		<select name="parent" class="validate[required]">
		    <option value='-1'>Elegir</option>
			{foreach from=$categories item=category}
				{if $category.parent == 0}
					<option value="{$category.category_id|htmlsafe}">{$category.name|htmlsafe}</option>
				{else}
					<option value="{$category.category_id|htmlsafe}">&nbsp;&nbsp;&nbsp;{$category.name|htmlsafe}</option>
				{/if}
			{/foreach}
		</select>
		</td>
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
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="insert_categories" />
		
			<a href="./index.php?module=categories&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
