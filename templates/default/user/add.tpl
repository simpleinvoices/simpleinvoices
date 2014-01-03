{*
* Script: add.tpl
* 	User add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}


{if $smarty.post.email != null && $smarty.post.submit != null } 
	{include file="../templates/default/user/save.tpl"}
{else}

<form name="frmpost" action="index.php?module=user&amp;view=add" method="post" id="frmpost">
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
			<img src="./images/common/required-small.png" alt="" />
			</a>	
			</th>
			<td><input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="35" id="email" autocomplete="off" class="validate[required]"  /></td>
		</tr>
		<tr>
			<th>{$LANG.role} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
					title="{$LANG.role}"
				> 
				<img src="./images/common/help-small.png" alt="" />
				</a>
			</th>
			<td>
					<select name="role">
						{foreach from=$roles item=role}
							<option  value="{$role.id|htmlsafe}">{$role.name|htmlsafe}</option>
						{/foreach}
					</select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.password}</th>
			<td><input type="password" name="password_field" value="{$smarty.post.password_field|htmlsafe}" size="25" /></td>
		</tr>
		<tr>
			<th>{$LANG.enabled}</th>
			<td>
				{html_options name=enabled options=$enabled selected=1}
			</td>
		</tr>
		<tr>
			<th>{$LANG.users}</th>
			<td><input type="text" name="user_id" value="{$smarty.post.user_id|htmlsafe}" size="12" id="user_id" autocomplete="off" class="validate[required]"  /></td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="Insert User">
                <img class="button_img" src="./images/common/tick.png" alt="" /> 
                {$LANG.save}
            </button>

            <a href="./index.php?module=user&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
	</div>

</div>
<input type="hidden" name="op" value="insert_user" />
</form>
{/if}
