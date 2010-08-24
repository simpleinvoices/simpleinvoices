{*
* Script: details.tpl
* 	Biller details template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}
<form name="frmpost" action="index.php?module=user&view=save&id={$smarty.get.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">
{if $smarty.get.action== 'view' }
<br />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$user.email|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.role}</td>
		<td>{$user.role_name|htmlsafe}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.password}</td>
		<td>*********</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$user.lang_enabled|htmlsafe}</td>
	</tr>
</table>
<br />
<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=user&view=details&id={$user.id|urlencode}&action=edit" class="positive">
                <img src="./images/famfam/report_edit.png" alt="" />
                {$LANG.edit}
            </a>

            <a href="./index.php?module=user&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
 </table>
{/if}



{if $smarty.get.action== 'edit' }


<br />
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.email} 
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
				title="{$LANG.Required_Field}"
		>
		<img src="./images/common/required-small.png" alt="" />
		</a>	
		</td>
		<td><input type="text" name="email" autocomplete="off" value="{$user.email|htmlsafe}" size="35" id="email"  class="validate[required]"  /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.role} 
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
				title="{$LANG.role}"
			> 
			<img src="./images/common/help-small.png" alt="" />
			</a>
		</td>
		<td>
				<select name="role">
					{foreach from=$roles item=role}
						<option {if $role.id == $user.role_id} selected {/if} value="{$role.id|htmlsafe}">{$role.name|htmlsafe}</option>
					{/foreach}
				</select>
		</td>
	</tr>
	<tr>
		<td class="details_screen">
			{$LANG.new_password}
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
				title="{$LANG.new_password}"
			> 
			<img src="./images/common/help-small.png" alt="" />
			</a>
		</td>
		<td>
			<input type="password" name="password_field" value="" size="25" />
		</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{html_options name=enabled options=$enabled selected=$user.enabled}</td>
	</tr>
</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="save_user">
			<img class="button_img" src="./images/common/tick.png" alt="" /> 
			{$LANG.save}
			</button>			<input type="hidden" name="op" value="edit_user" />
			<input type="hidden" name="id" value="{$user.id|htmlsafe}" />
		</td>
		<td>
			<a href="./index.php?module=user&view=manage" class="negative">
			<img src="./images/common/cross.png" alt="" />
			{$LANG.cancel}
			</a>
		</td>
	</tr>
</table>
{/if}
</form>
