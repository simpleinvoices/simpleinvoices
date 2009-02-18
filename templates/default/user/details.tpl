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
<form name="frmpost" action="index.php?module=user&view=save&id={$smarty.get.id}" method="post" id="frmpost" onSubmit="return checkForm(this);">


{if $smarty.get.action== 'view' }

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$user.email}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.role}</td>
		<td>{$user.role_name}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.password}</td>
		<td>*********</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$user.lang_enabled}</td>
	</tr>
</table>
<br>
<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=user&view=details&id={$user.id}&action=edit" class="positive">
                <img src="./images/famfam/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>

            <a href="./index.php?module=user&view=manage" class="negative">
                <img src="./images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
 </table>
{/if}



{if $smarty.get.action== 'edit' }

<h3>{$LANG.edit}</h3>
<table align="center">

	<tr>
		<td class="details_screen">{$LANG.email} 
		<a 
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=required_field"
				title="{$LANG.Required_Field}"
		>
		<img src="./images/common/required-small.png"></img>
		</a>	
		</td>
		<td><input type=text name="email" value="{$user.email}" size=35 id="email" class="required"></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.role} 
			<a
				class="cluetip"
				href="#"
				rel="docs.php?t=help&p=user_role"
				title="{$LANG.role}"
			> 
			<img
				src="./images/common/help-small.png">
			</img> 
			</a>
		</td>
		<td>
				<select name="role">
					{foreach from=$roles item=role}
						<option {if $role.id == $user.role_id} selected {/if} value="{$role.id}">{$role.name}</option>
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
				rel="docs.php?t=help&p=new_password"
				title="{$LANG.new_password}"
			> 
			<img
				src="./images/common/help-small.png">
			</img> 
			</a>
		</td>
		<td><input type=password name="password_field" value="" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=$user.enabled}
		</td>
	</tr>
</table>
<br>
	<table class="buttons" align="center">
    <tr>
        <td>
            <button type="submit" class="positive" name="save_biller" value="{$LANG.save_biller}">
                <img class="button_img" src="./images/common/tick.png" alt=""/> 
                {$LANG.save}
            </button>

            <input type="hidden" name="op" value="edit_user">
            <input type="hidden" name="id" value="{$user.id}">
		</td>
		<td>
            <a href="./index.php?module=billers&view=manage" class="negative">
                <img src="./images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
    
        </td>
    </tr>
	</table>
{/if}
</form>
