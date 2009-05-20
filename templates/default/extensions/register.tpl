<form name="frmpost" action="index.php?module=extensions&view=save" method="post" onsubmit="return frmpost_Validator(this)">
<h2>About to <i>{$action}</i>: {$name}</h2>
<input name="action" value="{$action}" type="hidden" />
<hr />
<table>
 <tr>
  <td>Name</td>
  <td><input type="text" name="name" readonly="readonly" value="{$name}" /> <input type="text" size="3" name="id" value="{$id}" readonly="readonly" /></td>
 </tr><tr>
  <td>Description</td>
  <td><input type="text" name="description" size="40" value="{$description}" />
 </tr><tr>
  <td><button type="submit" class="positive" name="submit" value="{$LANG.save}">
	<img class="button_img" src="./images/common/tick.png" alt="{$LANG.save}" />{$LANG.save}
      </button></td>
  <td><a href="./index.php?module=extensions&view=manage" class="negative">
	<img src="./images/common/cross.png" alt="{$LANG.cancel}" />{$LANG.cancel}</a></td>
 </tr>
</table>
{if ($action=="unregister" & $count > 0)}
<h3>WARNING: All {$count} extension-specific settings will be deleted!</h3>
{/if}
</form>
