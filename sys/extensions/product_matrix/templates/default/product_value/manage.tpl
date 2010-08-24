{*
/*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* License:
*	 GPL v2 or above
*/
*}
{if preferences == null}
<P><em>{$LANG.no_preferences}.</em></p>
{else}
<h3>Manage Product Value :: <a href="index.php?module=product_value&view=add">Add Product Value</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../extensions/product_matrix/modules/product_value/manage.js.php'}
{/if}
