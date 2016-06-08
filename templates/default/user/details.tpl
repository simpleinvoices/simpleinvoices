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


<div class="si_form si_form_view">
	<table>
		<tr>
			<th>{$LANG.email}</th>
			<td>{$user.email|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.role}</th>
			<td>{$user.role_name|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.password}</th>
			<td>*********</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{$user.lang_enabled|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.users}</th>
			<td>{$user.user_id|htmlsafe}</td>
		</tr>
	</table>
</div>
<div class="si_toolbar si_toolbar_form">
            <a href="./index.php?module=user&view=details&id={$user.id|urlencode}&action=edit" class="positive">
                <img src="./images/famfam/report_edit.png" alt="" />
                {$LANG.edit}
            </a>

            <a href="./index.php?module=user&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
</div>
{/if}



{if $smarty.get.action== 'edit' }
<div class="si_form">
	<table>
		<tr>
			<th>{$LANG.email} 
			<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
					title="{$LANG.required_field}"
			>
			<img src="{$help_image_path}required-small.png" alt="" />
			</a>	
			</th>
			<td><input type="text" name="email" autocomplete="off" value="{$user.email|htmlsafe}" size="35" id="email"  class="validate[required]"  /></td>
		</tr>
		<tr>
			<th>{$LANG.role} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
					title="{$LANG.role}"
				> 
				<img src="{$help_image_path}help-small.png" alt="" />
				</a>
			</th>
			<td>
					<select name="role">
						{foreach from=$roles item=role}
							<option {if $role.id == $user.role_id} selected {/if} value="{$role.id|htmlsafe}">{$role.name|htmlsafe}</option>
						{/foreach}
					</select>
			</td>
		</tr>
		<tr>
			<th>
				{$LANG.new_password}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
					title="{$LANG.new_password}"
				> 
				<img src="{$help_image_path}help-small.png" alt="" />
				</a>
			</th>
			<td>
			<input type="password" name="password_field" value="" size="25" />
		</td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{html_options name=enabled options=$enabled selected=$user.enabled}</td>
		</tr>
		<tr>
			<th>{$LANG.users}</th>
			<td><input type="text" name="user_id" autocomplete="off" value="{$user.user_id|htmlsafe}" size="12" id="user_id"  class="validate[required]"  /></td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="save_user">
			<img class="button_img" src="./images/common/tick.png" alt="" /> 
			{$LANG.save}
			</button>
			<a href="./index.php?module=user&view=manage" class="negative">
			<img src="./images/common/cross.png" alt="" />
			{$LANG.cancel}
			</a>
	</div>

</div>

<input type="hidden" name="op" value="edit_user" />
<input type="hidden" name="id" value="{$user.id|htmlsafe}" />
{/if}
</form>
