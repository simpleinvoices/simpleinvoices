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
<div class="si_toolbar si_toolbar_top">
	<a href="./index.php?module=product_value&view=add" class="">
		<img src="./images/famfam/add.png" alt="add product_value button"/>
		{$LANG.add_product_value}
	</a>
<!--<h3>{$LANG.manage_product_values} :: <a href="index.php?module=product_value&view=add">{$LANG.add_product_value}</a></h3>
<hr />-->
</div>
<table id="manageGrid" style="display:none"></table>

{assign var=pos value=$smarty.template|strrpos:'/'}
{assign var=inc value=$smarty.template|substr:0:$pos}
{include file=$inc|cat:"/../../../modules/product_value/manage.js.php"}
{*include file='../modules/product_value/manage.js.php'*}
{/if}
