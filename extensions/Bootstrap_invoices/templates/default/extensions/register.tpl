<h2>About to <i>{$action|htmlsafe}</i>: {$name|htmlsafe}</h2>


<form name="frmpost" action="index.php?module=extensions&view=save" method="post" onsubmit="return frmpost_Validator(this)">

<div class="si_form">
	<table>
		 <tr>
			<td>{$LANG.name}</td>
			<td>
				<input type="text" name="name" readonly="readonly" value="{$name|htmlsafe}" /> 
				<input type="text" size="3" name="id" value="{$id|htmlsafe}" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<td>{$LANG.description}</td>
			<td>
				<input type="text" name="description" size="40" value="{$description|htmlsafe}" />
			</td>
		</tr>	
	</table>

	<div class="si_toolbar si_toolbar_form">
				<button type="submit" class="positive" name="submit" value="{$LANG.save}">
					<img class="button_img" src="./images/common/tick.png" alt="{$LANG.save}" />{$LANG.save}
				</button>

				<a href="./index.php?module=extensions&view=manage" class="negative">
				<img src="./images/common/cross.png" alt="{$LANG.cancel}" />{$LANG.cancel}</a>
	</div>
</div>

{if ($action=="unregister" & $count > 0)}
<h3>WARNING: All {$count|htmlsafe} extension-specific settings will be deleted!</h3>
{/if}

<input name="action" value="{$action|htmlsafe}" type="hidden" />
</form>
