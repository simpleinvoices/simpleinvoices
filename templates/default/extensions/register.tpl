<form name="frmpost" action="index.php?module=extensions&view=save" method="post" onsubmit="return frmpost_Validator(this)">
<h2>About to <i>{$action|htmlsafe}</i>: {$name|htmlsafe}</h2>
<input name="action" value="{$action|htmlsafe}" type="hidden" />
<hr />
<table>
 <tr>
  <td>Name</td>
  <td><input type="text" name="name" readonly="readonly" value="{$name|htmlsafe}" /> <input type="text" size="3" name="id" value="{$id|htmlsafe}" readonly="readonly" /></td>
 </tr><tr>
  <td>Description</td>
  <td><input type="text" name="description" size="40" value="{$description|htmlsafe}" />
 </tr><tr>
  <td><button type="submit" class="positive" name="submit" value="{$LANG.save}">
	<img class="button_img" src="./images/common/tick.png" alt="{$LANG.save}" />{$LANG.save}
      </button></td>
  <td><a href="./index.php?module=extensions&view=manage" class="negative">
	<img src="./images/common/cross.png" alt="{$LANG.cancel}" />{$LANG.cancel}</a></td>
 </tr>
</table>
{if ($action=="unregister" & $count > 0)}
<h3>WARNING: All {$count|htmlsafe} extension-specific settings will be deleted!</h3>
{/if}
</form>
