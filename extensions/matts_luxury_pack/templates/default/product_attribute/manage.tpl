{*
/*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
{if preferences == null}
<P><em>{$LANG.no_preferences}.</em></p>
{else}
<h3>{$LANG.manage_product_attributes} :: <a href="index.php?module=product_attribute&view=add">{$LANG.add_product_attribute}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/product_attribute/manage.js.php'}
{/if}
