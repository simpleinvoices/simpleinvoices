<form name="frmpost" action="index.php?module=extensions&view=manage" method="post" onsubmit="return frmpost_Validator(this)">
<h1>DEBUG: {$debug}</h1>
<h3>About to Register: {$name}</h3>
<hr />
<table>
 <tr>
  <td>Name</td>
  <td><input type="text" name="name" readonly="readonly" value="{$name}"</td>
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
</form>
