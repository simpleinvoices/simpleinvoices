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
<h3>{$LANG.manage_product_values} :: <a href="index.php?module=product_value&view=add">{$LANG.add_product_value}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/product_value/manage.js.php'}
{/if}
