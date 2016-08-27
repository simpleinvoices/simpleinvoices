{*
* Script: details.tpl
*   Biller details template
*
* Last edited:
*    2008-08-25
*
* License:
*   GPL v3 or above
*}
<form name="frmpost" action="index.php?module=user&view=save&id={$smarty.get.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);">
{if $smarty.get.action== 'view'}

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
				<td>{$user.enabled_txt|htmlsafe}</td>
			</tr>
			<tr>{*if $user.role_name!=2 || $user.role_name!=3} class="si_hide"{/if}>*}
				<th>{$LANG.users}</th>
				<td><input name="user_id" value="{$user.user_id|htmlsafe}" /></td>
			</tr>
		</table>
	</div>
	<div class="si_toolbar si_toolbar_form">
		<a href="./index.php?module=user&view=details&id={$user.id|urlencode}&action=edit" class="positive">
			<img src="./images/famfam/report_edit.png" alt="report_edit" />
			{$LANG.edit}
		</a>

		<a href="./index.php?module=user&view=manage" class="negative">
			<img src="./images/common/cross.png" alt="cross" />
			{$LANG.cancel}
		</a>
	</div>
{**********************************************************************}
{elseif $smarty.get.action== 'edit'}
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
						<img src="{$help_image_path}required-small.png" alt="required" />
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
						<img src="{$help_image_path}help-small.png" alt="help" />
					</a>
				</th>
				<td>
					<select name="role">
{foreach from=$roles item=role}
						<option{if $role.id == $user.role_id} selected {/if} value="{$role.id|htmlsafe}">{$role.name|htmlsafe}</option>
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
						<img src="{$help_image_path}help-small.png" alt="help" />
					</a>
				</th>
				<td>
				<input type="password" name="password_field" value="" size="25" />
			</td>
			</tr>
			<tr>
				<th>{$LANG.enabled}</th>
				<td>{html_options name=enabled options=$enabled_options selected=$user.enabled}</td>
			</tr>
			<tr>
				<th>{$LANG.users}</th>
				<td>
					<select name="user_id" id="user_id" class="validate[required]">
					</select>
				</td>
				<!--<td><input type="text" name="user_id" autocomplete="off" value="{$user.user_id|htmlsafe}" size="12" id="user_id" class="validate[required]"  /></td>-->
			</tr>
		</table>

		<div class="si_toolbar si_toolbar_form">
			<button type="submit" class="positive" name="save_user">
				<img class="button_img" src="./images/common/tick.png" alt="tick" /> 
				{$LANG.save}
			</button>
			<a href="./index.php?module=user&view=manage" class="negative">
				<img src="./images/common/cross.png" alt="cross" />
				{$LANG.cancel}
			</a>
		</div>
	</div>
	<input type="hidden" name="op" value="edit_user" />
	<input type="hidden" name="id" value="{$user.id|htmlsafe}" />
{/if}
</form>
<script type="text/javascript">
{*<!--acc:{$user.user_id},role:{$user.role_id}-->*}<!--
	var userlist = document.frmpost.user_id;
{if $smarty.get.action== 'view'}
	{if $user.role_id==7}
		{foreach from=$customers item=customer}
			{if $customer.id==$user.user_id}
	userlist.value = "{$customer.name}";
			{/if}
		{/foreach}
	{else}
		{if $user.role_id==8}
			{foreach from=$billers item=biller}
				{if $biller.id==$user.user_id}
	userlist.value = "{$biller.name}";
				{/if}
			{/foreach}
		{/if}
	{/if}
{elseif $smarty.get.action== 'edit'}
	var roles = document.frmpost.role;
	var users = new Array();
	users[2] = [{assign var=arrayLength value=$customers|@count}"{$LANG.select}|0|1|",{foreach from=$customers item=customer key=k}"{$customer.name}|{$customer.id}|{if $user.user_id==$customer.id}1|1{else}|{/if}"{if $k < ($arrayLength-1)},{/if}{/foreach}]; /*customers text|value*/
	users[3] = [{assign var=arrayLength value=$billers|@count}"{$LANG.select}|0|true|",{foreach from=$billers item=biller key=k}"{$biller.name}|{$biller.id}|{if $user.user_id==$biller.id}true|true{else}|{/if}"{if $k < ($arrayLength-1)},{/if}{/foreach}]; /*billers text|value*/
	var curr = {$user.role_id}-5;
	if (curr)	populateUsers(curr);
	else		userlist.parentNode.parentNode.className = "si_hide";
{literal}
	
	function populateUsers (selectedRole)
	{
		userlist.options.length = 0;
		if (selectedRole==2 || selectedRole==3)
		{
			for (var i=0; i<users[selectedRole].length; i++) {
				userlist.options[userlist.options.length] = new Option(users[selectedRole][i].split("|")[0], users[selectedRole][i].split("|")[1], (users[selectedRole][i].split("|")[2]), (users[selectedRole][i].split("|")[3]));
			}
			userlist.parentNode.parentNode.className = "";
		} else {
			userlist.parentNode.parentNode.className = "si_hide";
		}
	}
{/literal}{/if}{literal}
	function initUserDetails() {
		if (roles.addEventListener){
			roles.addEventListener("change", function() { populateUsers(this.selectedIndex); }, false);
			/*this.options[this.selectedIndex]*/
		} else if (roles.attachEvent){
			roles.attachEvent("onchange", function() { populateUsers(this.selectedIndex); });
		}
	};

if (window.addEventListener) {
	window.addEventListener("load", initUserDetails, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", initUserDetails);
} else {
	document.addEventListener("load", initUserDetails, false);
}
{/literal}
//-->
</script>
