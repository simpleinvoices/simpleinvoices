<form name="frmpost" action="index.php?module=expense_account&view=save&id={$smarty.get.id}" method="post">


{if $smarty.get.action== 'view' }
<br />
	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.name}</td>
		<td>{$expense_account.name}</td>
	</tr>
</table>
	<br />
	<table class="buttons" align="center">
		<tr>
			<td>
				<a href="./index.php?module=expense_account&view=details&id={$expense_account.id}&action=edit" class="positive">
					<img src="./images/famfam/add.png" alt=""/>
					{$LANG.edit}
				</a>

			</td>
		</tr>
	</table>
{/if}


{if $smarty.get.action== 'edit' }
<br />

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG.name}</td>
		<td><input type="text" name="name" size="50" value="{$expense_account.name}" id="name"  class="validate[required]" /></td>
	</tr>
	</table>
{/if} 
{if $smarty.get.action== 'edit' }
	<br />
	<table class="buttons" align="center">
	<tr>
		<td>
			<button type="submit" class="positive" name="save_product" value="{$LANG.save}">
			    <img class="button_img" src="./images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="edit">
			<input type="hidden" name="id" value="{$expense_account.name}">
		
			<a href="./index.php?module=expense_account&view=manage" class="negative">
		        <img src="./images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>
		
	{/if}
</form>
