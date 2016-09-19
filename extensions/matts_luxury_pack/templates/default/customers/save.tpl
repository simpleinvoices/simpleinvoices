{*
/*
* Script: ./extensions/matts_luxury_pack/templates/default/customers/save.tpl
* 	 Customer save template
*
* Authors:
*	 git0matt@gmail.com, Justin Kelly, Nicolas Ruflin, Soif
*
* Last edited:
* 	 2016-09-14
*
* License:
*	 GPL v2 or above
*/
*}

{if $saved == true}
	<div class="si_message_ok">{$LANG.saved_customer}<br />{$LANG.redirect_customers}</div>
{else}
	<div class="si_message_error">{$LANG.save_customer_failure}</div>
{/if}

<script type="text/javascript">
<!--
if (inIframe())
{ldelim}
	document.write('<div class="si_toolbar si_toolbar_form">' +
	'	<table class="center">' +
	'		<tr>' +
	'			<td>&nbsp;</td>' +
	'			<td>' +
	'				<a id="modal_addcustomer" href="./index.php?module=customers&amp;view=add" class="button">' +
	'					<img src="./images/common/add.png" alt="add" />{ $LANG.another }</a>' +
	'			</td>' +
	'			<td>&nbsp;</td>' +
	'		</tr>' +
	'		<tr>' +
	'			<td>' +
	'				<a id="modal_manage_customers" href="./index.php?module=customers&amp;view=manage" class="button">' +
	'					<img src="./images/common/database_table.png" alt="manage" />{ $LANG.manage_customers }</a>' +
	'			</td>' +
	'			<td>&nbsp;</td>' +
	'			<td>' +
	'				<a id="cancelAddProduct" href="javascript:void(0)" onclick="top.closeModal();top.regenCusts()" class="button">' +
	'					<img src="./images/common/cog_edit.png" alt="close_reload" />{ $LANG.close }+{ $LANG.regenCusts }</a>' +
	'			</td>' +
	'		</tr>' +
	'	</table>' +
	'</div>');
{	if $smarty.post.cancel == null}
	document.write('<meta http-equiv="refresh" content="10;URL=index.php?module=customers&view=manage" />');
{	else}
	document.write('<meta http-equiv="refresh" content="0;URL=index.php?module=customers&view=manage" />');
{	/if}
{rdelim}
else
{ldelim}
{if $smarty.post.cancel == null}
	document.write('<meta http-equiv="refresh" content="2;URL=index.php?module=customers&view=manage" />');
{else}
	document.write('<meta http-equiv="refresh" content="0;URL=index.php?module=customers&view=manage" />');
{/if}
{rdelim}
</script>
