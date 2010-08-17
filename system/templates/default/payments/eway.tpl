{if $saved == 'true' }
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.save_eway_success}
<br />
<br />
{/if}
{if $saved == 'check_failed' }
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.save_eway_check_failed}
<br />
<br />
{/if}
{if $saved == 'false' }
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.save_eway_failure}
<br />
<br />
{/if}

{if $saved == false}

    {if $smarty.post.op == 'add' AND $smarty.post.invoice_id == ''}
        <div class="validation_alert"><img src="./images/common/important.png" alt="" />
        You must select an invoice</div>
        <hr />
    {/if}


<form name="frmFpost" action="index.php?module=payments&view=eway" method="POST" id="frmpost">
<br />

<table align="center">
<tr>
<td class="details_screen">{$LANG.invoice}</td>
<td>
<select name="invoice_id" class="validate[required]">
<option value=''></option>
{foreach from=$invoice_all item=invoice}
<option value="{$invoice.id|htmlsafe}" {if $smarty.get.id == $invoice.id} selected {/if} >{$invoice.index_name|htmlsafe}</option>
{/foreach}
</select>
</td>
</tr>
<tr>
    <td colspan=2>
        <br />
        {$LANG.warning_eway}
        <br />
    </td>
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

<input type="hidden" name="op" value="add" />

<a href="./index.php?module=cron&view=manage" class="negative">
<img src="./images/common/cross.png" alt="" />
{$LANG.cancel}
</a>

</td>
</tr>
</table>


</form>
{/if}


