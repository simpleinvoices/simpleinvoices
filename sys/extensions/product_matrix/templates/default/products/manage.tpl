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
 {include file='../modules/products/manage.js.php' LANG=$LANG}
 
<h3>{$LANG.manage_products} :: <a href="index.php?module=products&amp;view=add">{$LANG.add_new_product}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/products/manage.js.php'}
{/if}
