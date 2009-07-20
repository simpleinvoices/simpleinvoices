
{* if bill is updated or saved.*}

{if $smarty.post.name != "" } 
	{include file="../extensions/expense/templates/default/expense_account/save.tpl"}
{else}
{* if  name was inserted *} 
	{if $smarty.post.name !=null} 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a name for the account</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=expense_account&view=add" method="POST" >
<br />

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.description} 
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.Required_Field}"><img src="./images/common/required-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="name" value="{$smarty.post.name}" size="50" id="name"  class="validate[required]" /></td>
	</tr>
</table>
<br />
<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="insert" />
		
			<a href="./index.php?module=products&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
