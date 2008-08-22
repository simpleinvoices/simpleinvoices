{*
/*
* Script: manage.tpl
* 	 Products manage template
*
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
{if $number_of_rows == null }
	<p><em>{$LANG.no_products}</em></p>
{else}
 {include file='../modules/products/manage.js.php' LANG=$LANG}
 
<h3>{$LANG.manage_products} :: <a href="index.php?module=products&view=add">{$LANG.add_new_product}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/products/manage.js.php'}
{/if}
