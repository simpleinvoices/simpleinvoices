
{* if bill is updated or saved.*}

{if $smarty.post.description != "" && $smarty.post.id != null } 

	{include file="../templates/default/products/save.tpl"}

{else}
{* if  name was inserted *} 

	{if $smarty.post.id !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="important" />
		{$LANG.product_description_prompt}</div>
		<hr />
	{/if}

<form name="frmpost" action="index.php?module=products&amp;view=add" method="post" id="frmpost" onsubmit="return checkForm(this);">
	<div class="si_form">
		<div id="tabs_customer">
			<ul class="anchors">
				<li><a href="#section-1" target="_top">{$LANG.details}</a></li>
				<li><a href="#section-2" target="_top">{$LANG.custom_fields}</a></li>
				<li><a href="#section-3" target="_top">{$LANG.notes}</a></li>
			</ul>
		</div>
		
		<div id="section-1" class="fragment">

			<table>
				<tr>
					<th>{$LANG.description} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.required_field}"><img src="./images/common/required-small.png" alt="" /></a>
					</th>
					<td><input type="text" name="description" value="{$smarty.post.description|htmlsafe}" size="50" id="description" class="validate[required]" /></td>
				</tr>
				<tr>
					<th>{$LANG.unit_price}</th>
					<td><input type="text" class="edit" name="unit_price" value="{$smarty.post.unit_price|htmlsafe}" size="25" /></td>
				</tr>
{if $defaults.inventory == '1'}
					<tr>
						<th>
							{$LANG.cost} <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_cost" title="{$LANG.cost}"><img src="./images/common/help-small.png" alt="" /></a>
						</th>
						<td><input type="text" class="edit" name="cost" value="{$smarty.post.cost|htmlsafe}" size="25" /></td>
					</tr>
					<tr>
						<th>{$LANG.reorder_level}</th>
						<td><input type="text" class="edit" name="reorder_level" value="{$smarty.post.reorder_level|htmlsafe}" size="25" /></td>
					</tr>
				{/if}
				<tr>
					<th>{$LANG.default_tax}</th>
					<td>
					<select name="default_tax_id">
						<option value=''>{$LANG.select}&nbsp;</option>
{foreach from=$taxes item=tax}
						<option value="{$tax.tax_id|htmlsafe}">{$tax.tax_description|htmlsafe}</option>
{/foreach}
					</select>
					</td>
				</tr>
				<tr>
					<th>{$LANG.enabled}</th>
					<td>
{html_options class=edit name=enabled options=$enabled selected=1}
					</td>
				</tr>
			</table>
		</div><!--section1-->
		<div id="section-2" class="fragment">

			<table>
				<tr>
					<th>{$customFieldLabel.product_cf1|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="custom_field1" value="{$smarty.post.custom_field1|htmlsafe}"  size="50" /></td>
				</tr>
				<tr>
					<th>{$customFieldLabel.product_cf2|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="50" /></td>
				</tr>
				<tr>
					<th>{$customFieldLabel.product_cf3|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="50" /></td>
				</tr>
				<tr>
					<th>{$customFieldLabel.product_cf4|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="50" /></td>
				</tr>
				{if $defaults.product_attributes}
					<tr>
						<th class="details_screen">{$LANG.product_attributes}</th>
						<td>
						</td>
					</tr>
					{foreach from=$attributes item=attribute}
						<tr>
							<td></td>
							<th class="details_screen product_attribute">
							<input type="checkbox" name="attribute{$attribute.id}" value="true"/>
							{$attribute.name}
							</th>
						</tr>
					{/foreach}
				{/if}

				{if $defaults.product_lwhw}
				<tr>
					<th>{$LANG.product_length|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_length" title="{$LANG.product_length}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="product_length" value="{$smarty.post.product_length|htmlsafe}"  size="50" /></td>
				</tr>
				<tr>
					<th>{$LANG.product_width|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_width" title="{$LANG.product_width}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="product_width" value="{$smarty.post.product_width|htmlsafe}" size="50" /></td>
				</tr>
				<tr>
					<th>{$LANG.product_height|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_height" title="{$LANG.product_height}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="product_height" value="{$smarty.post.product_height|htmlsafe}" size="50" /></td>
				</tr>
				<tr>
					<th>{$LANG.product_weight|htmlsafe} 
						<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_product_weight" title="{$LANG.product_weight}"><img src="./images/common/help-small.png" alt="" /></a>
					</th>
					<td><input type="text" class="edit" name="product_weight" value="{$smarty.post.product_weight|htmlsafe}" size="50" /></td>
				</tr>
				{/if}
			</table>
		</div>
		<div id="section-3" class="fragment">

			<table>
				<tr>
					<th>{$LANG.notes}</th>
					<td><textarea class="editor" name='notes' rows="8" cols="50">{$smarty.post.notes|unescape}</textarea></td><!--input type="text" -->
				</tr>
					<tr>
						<th class="details_screen">{$LANG.note_attributes}</th>
						<td>
						</td>
					</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" name="notes_as_description" value='true'/>
						{$LANG.note_as_description}
						</th>
					</tr>
					<tr>
						<td></td>
						<th class="details_screen product_attribute">
						<input type="checkbox" name="show_description" value='true'/>
						{$LANG.note_expand}
						</th>
					</tr>
			</table>
		</div>
<!--	</div>si_form-->
		<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
				<img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<a id="cancelAddProduct" href="./index.php?module=products&amp;view=manage" class="negative">
				<img src="./images/common/cross.png" alt="" />
				{$LANG.cancel}
			</a>
		</div>
	</div>
	<input type="hidden" name="op" value="insert_product" />
</form>

<script type="text/javascript" language="JavaScript">
document.forms['frmpost'].elements['description'].focus();
</script>
{/if}
