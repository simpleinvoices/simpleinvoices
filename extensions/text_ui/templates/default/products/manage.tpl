{*
/*
* Script: manage.tpl
* 	 Products manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $number_of_rows == null }
	<p><em>{$LANG.no_products}</em></p>
{else}
 
<b>{$LANG.manage_products} :: <a href="index.php?module=products&view=add">{$LANG.add_new_product}</a></b>
<table id="manageGrid" style="display:none"></table>

 {include file='../extensions/text_ui/modules/products/manage.js.php'}
{/if}
