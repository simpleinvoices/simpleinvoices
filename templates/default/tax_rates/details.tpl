<form name="frmpost" action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlencode}" method="post" onsubmit="return frmpost_Validator(this)">

{if $smarty.get.action === 'view' }

<div class="si_form si_form_view">
	<table>
		<tr>
			<th>{$LANG.description}</th>
			<td>{$tax.tax_description|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.rate}
				<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
					title="{$LANG.tax_rate}"
				>
				<img src="{$help_image_path}help-small.png" />
				</a>
			</th>
			<td>
				{$tax.tax_percentage|siLocal_number} {$tax.type|htmlsafe}
			</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{$tax.enabled|htmlsafe}</td>
		</tr>
	</table>
</div>

	<div class="si_toolbar si_toolbar_form">
		<a href="index.php?module=tax_rates&amp;view=details&amp;id={$tax.tax_id|urlencode}&amp;action=edit" class="positive">
			<img src="images/famfam/report_edit.png" alt="" />
			{$LANG.edit}
		</a>
	</div>
{/if}




{if $smarty.get.action === 'edit'}

<div class="si_form">
	<table>
		<tr>
			<th>{$LANG.description}</th>
			<td><input type="text" name="tax_description" value="{$tax.tax_description|htmlsafe}"  class="validate[required]" size="25" /></td>
		</tr>
		<tr>
			<th>{$LANG.rate}
			<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign"
				title="{$LANG.tax_rate}"
			>
				<img src="{$help_image_path}help-small.png" />
			</a>
			</th>
			<td>
				<input type="text" name="tax_percentage" value="{$tax.tax_percentage|siLocal_number}" size="10" />
				{html_options name=type options=$types selected=$tax.type}
			</td>
		</tr>
		<tr>
			<th>{$LANG.enabled} </th>
			<td>
				<select name="tax_enabled">
					<option value="{$tax.tax_enabled|htmlsafe}" selected style="font-weight: bold">{$tax.enabled|htmlsafe}</option>
					<option value="1">{$LANG.enabled}</option>
					<option value="0">{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.save_tax_rate}">
                <img class="button_img" src="images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <a href="index.php?module=tax_rates&view=manage" class="negative">
                <img src="images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
	</div>

</div>

<input type="hidden" name="op" value="edit_tax_rate" />
{/if}
</form>

