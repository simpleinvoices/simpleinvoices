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
			<img src="{$help_image_path}required-small.png" alt="" />
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
				<img src="{$help_image_path}help-small.png" alt="" />
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
				<td> <select name="user_id"> </select> </td>
<!--			<td><input type="text" name="user_id" value="{$smarty.post.user_id|htmlsafe}" size="12" id="user_id" autocomplete="off" class="validate[required]"  /></td>-->
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

<script type="text/javascript">
<!--
{literal}
	var roles = document.frmpost.role;
	var userlist = document.frmpost.user_id;
	var users = new Array();
	users[0] = "";
	users[1] = "";
	users[2] = [{/literal}{assign var=arrayLength value=$customers|@count}"{$LANG.select}|0|true|true",{foreach from=$customers item=customer key=k}"{$customer.name}|{$customer.id}|false|{if $smarty.post.user_id==$customer.id}true{else}false{/if}"{if $k < ($arrayLength-1)},{/if}{/foreach}{literal}];/*customers text|value|defaultSelected|selected*/
	users[3] = [{/literal}{assign var=arrayLength value=$billers|@count}"{$LANG.select}|0|true|true",{foreach from=$billers item=biller key=k}"{$biller.name}|{$biller.id}|false|{if $smarty.post.user_id==$biller.id}true{else}false{/if}"{if $k < ($arrayLength-1)},{/if}{/foreach}{literal}];/*billers text|value|defaultSelected|selected*/
	userlist.parentNode.parentNode.className = "si_hide";
	
	function populateUsers (selectedRole)
	{
		userlist.options.length = 0;
		if (selectedRole==2 || selectedRole==3)
		{
			for (var i=0; i<users[selectedRole].length; i++) {
				userlist.options[userlist.options.length] = new Option(users[selectedRole][i].split("|")[0], users[selectedRole][i].split("|")[1]);/*, users[selectedRole][i].split("|")[2], users[selectedRole][i].split("|")[3]);*/
			}
			userlist.parentNode.parentNode.className = "";
		} else {
			userlist.parentNode.parentNode.className = "si_hide";
		}
	}

	function initUserAdd() {
		if (roles.addEventListener){
			roles.addEventListener("change", function() { populateUsers(this.selectedIndex); }, false);
		} else if (roles.attachEvent){
			roles.attachEvent("onchange", function() { populateUsers(this.selectedIndex); });
		}
	};

if (window.addEventListener) {
	window.addEventListener("load", initUserAdd, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", initUserAdd);
} else {
	document.addEventListener("load", initUserAdd, false);
}

{/literal}
//-->
</script>
