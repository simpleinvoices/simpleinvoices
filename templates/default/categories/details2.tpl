<form name="frmpost" action="index.php?module=categories&view=save&id={$smarty.get.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">

{if $smarty.get.action== 'view' }
<div class="si_form si_form_view">

	<table>
		<tr>
			<th>{$LANG.category}</th>
			<td>{$category.name|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.slug}</th>
			<td>{$category.slug|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.reference}</th>
			<td>
				{$category.referencia|htmlsafe}
			</td>
		</tr>
	</table>
</div>
<div class="si_toolbar si_toolbar_form">
				<a href="./index.php?module=categories&view=details&id={$category.category_id|htmlsafe}&action=edit" class="positive">
					<img src="./images/famfam/add.png" alt=""/>
					{$LANG.edit}
				</a>
</div>
{/if}


{if $smarty.get.action== 'edit' }
<div class="si_form">

	<table align="center">
	<tr>
		<th>{$LANG.category}</th>
		<td><input type="text" name="name" size="50" value="{$category.name|htmlsafe}" id="description"  class="validate[required]" /></td>
	</tr>
	<tr>
		<th>{$LANG.slug}</th>
		<td><input type="text" name="slug" size="25" value="{$category.slug|htmlsafe}" /></td>
	</tr>

	<tr>
		<th>{$LANG.reference}</th>
		<td><input type="text" name="referencia" size="50" value="{$category.referencia|htmlsafe}" class="validate[required]"/></td>
	</tr>
	<tr>
		<th>{$LANG.category_parent} 
		</th>
		<select name="parent" class="validate[required]">
		    <option value='-1'>Elegir</option>
			{foreach from=$categories item=cat}
				{if $cat.parent == 0}
					{if $cat.category_id == $category.parent}
						<option value="{$cat.category_id|htmlsafe}" selected="selected">{$cat.name|htmlsafe}</option>
					{else}
						<option value="{$cat.category_id|htmlsafe}">{$cat.name|htmlsafe}</option>
					{/if}
				{else}
					{if $cat.category_id == $category.parent}
						<option value="{$cat.category_id|htmlsafe}" selected="selected">&nbsp;&nbsp;&nbsp;{$cat.name|htmlsafe}</option>
					{else}
						<option value="{$cat.category_id|htmlsafe}">&nbsp;&nbsp;&nbsp;{$cat.name|htmlsafe}</option>
					{/if}
				{/if}
			{/foreach}
		</select>
	</tr>
		<th>{$LANG.enabled}</th>
		<td>
<select name="category_enabled">
<option value="{$category.enabled|htmlsafe}" selected style="font-weight: bold">{$category.category_enabled|htmlsafe}</option>
<option value="1">{$LANG.enabled}</option>
<option value="0">{$LANG.disabled}</option>
</select>
		</td>
	</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="save_category" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>	
			<a href="./index.php?module=categories&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	</div>

</div>
<input type="hidden" name="op" value="edit_category">	
{/if}
</form>
