{*
/*
* Script: ./extensions/matts_luxury_pack/templates/default/products/save.tpl
* 	Biller save template
*
* Authors:
*	 git0matt@gmail.com, Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2016-09-14
*
* License:
*	 GPL v2 or above
*/
*}
{if $saved == true}
	<br />
		<div class="si_message_ok">{$LANG.saved_product}<br />{$LANG.redirect_products}</div>
	<br />
	<br />
{else}
	<br />
		<div class="si_message_error">{$LANG.save_product_failure}</div>
	<br />
	<br />
{/if}

<script type="text/javascript">
if (inIframe())
{ldelim}
	document.write('<div class="si_toolbar xsi_toolbar_form">' +
	'	<table class="center">' +
	'		<tr>' +
	'			<td>&nbsp;</td>' +
	'			<td>' +
	'				<a id="modal_addproduct" href="./index.php?module=products&amp;view=add" class="button">' +
	'					<img src="./images/common/add.png" alt="add" />{ $LANG.another }</a>' +
	'			</td>' +
	'			<td>&nbsp;</td>' +
	'		</tr>' +
	'		<tr>' +
	'			<td>' +
	'				<a id="modal_manage_products" href="./index.php?module=products&amp;view=manage" class="button">' +
	'					<img src="./images/common/database_table.png" alt="manage" />{ $LANG.manage_products }</a>' +
	'			</td>' +
	'			<td>&nbsp;</td>' +
	'			<td>' +
	'				<a id="cancelAddProduct" href="javascript:void(0)" onclick="top.closeModal();top.regenProds()" class="button">' +
	'					<img src="./images/common/cross.png" alt="close" />{ $LANG.close }+{ $LANG.regenProds }</a>' +
	'			</td>' +
	'		</tr>' +
	'	</table>' +
	'</div>');
{rdelim}
else
{ldelim}
{if $smarty.post.cancel == null}
	document.write('<meta http-equiv="refresh" content="2;URL=index.php?module=products&view=manage" />');
{else}
	document.write('<meta http-equiv="refresh" content="0;URL=index.php?module=products&view=manage" />');
{/if}
{rdelim}//-->
</script>
