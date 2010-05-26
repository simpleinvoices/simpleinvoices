{*
/*
* Script: email.tpl
* 	 Send invoice via email page template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $smarty.get.stage == 1 }

<form name="frmpost" action="index.php?module=statement&amp;view=email&amp;stage=2&amp;biller_id={$smarty.get.biller_id|urlencode}&amp;customer_id={$smarty.get.customer_id|urlencode}&amp;start_date={$smarty.get.start_date|urlencode}&amp;end_date={$smarty.get.end_date|urlencode}&amp;show_only_unpaid={$smarty.get.show_only_unpaid|urlencode}&amp;format=file" method="post">
<div id="top"><h3>{$LANG.email_statement_as_pdf}</h3></div>

<table align="center">
	<tr>
		<td class="details_screen">{$LANG.email_from}
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from" title="{$LANG.email_from}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="email_from" size="50" value="{$biller.email|htmlsafe}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email_to}
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to" title="{$LANG.email_to}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
		<td><input type="text" name="email_to" size="50" value="{$customer.email|htmlsafe}" /></td>
	</tr>
	<tr>
	<td class="details_screen">{$LANG.email_bcc}
		<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc" title="{$LANG.email_bcc}"><img src="./images/common/help-small.png" alt="" /></a>
		</td>
	<td><input type="text" name="email_bcc" size="50" value="{$biller.email|htmlsafe}" /></td>
	</tr>
	<tr>
	<td class="details_screen">{$LANG.subject}</td>
	<td><input type="text" name="email_subject" size="50" value="Statement of invoices from {$biller.name|htmlsafe} is attached" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.message}</td>
		<td><textarea name="email_notes" class="editor" rows="8" cols="50"></textarea></td>
	</tr>
</table>

<br />
	<table class="buttons" align="center">
	    <tr>
	        <td>
	            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}">
	                <img class="button_img" src="./images/common/tick.png" alt="" /> 
	                {$LANG.email}
	            </button>
	            <input type="hidden" name="op" value="insert_customer" />
			</td>
		    </tr>
	 </table>


</form>
{/if}

{if $smarty.get.stage == 2}

<div id="top"></b></div>

<table align="center">
	<tr>
		<td>{$message|outhtml}</td>
	</tr>
</table>

{/if}
