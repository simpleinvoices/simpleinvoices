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
	<div class="si_toolbar si_toolbar_top">
		<a href="./index.php?module=product_attribute&view=add" class="">
			<img src="./images/famfam/add.png" alt="add product_attribute button"/>
			{$LANG.add_product_attribute}
		</a>
<!--<h3>{$LANG.manage_product_attributes} :: <a href="index.php?module=product_attribute&view=add">{$LANG.add_product_attribute}</a></h3>
<hr />-->
	</div>
	{*if $number_of_rows.count == 0 }
	<div class="si_message">{$LANG.no_products}</div>
	{else*}
	<table id="manageGrid" style="display:none"></table>

{*assign var=pos value=$smarty.template|strrpos:'/'}
{assign var=inc value=$smarty.template|substr:0:$pos}
{include file=$inc|cat:"/../../../modules/product_value/manage.js.php"*}
		{include file='../modules/product_attribute/manage.js.php'}
	{*/if*}
	<!--</div>-->
{/if}
