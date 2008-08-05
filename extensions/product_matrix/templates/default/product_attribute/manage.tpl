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
<h3>Manage Product Attributes :: <a href="index.php?module=product_attribute&view=add">Add Attribute</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../extensions/product_matrix/modules/product_attribute/manage.js.php'}
{/if}
