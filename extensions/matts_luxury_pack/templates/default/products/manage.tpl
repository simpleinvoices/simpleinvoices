{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/products/manage.tpl
 * 	Products manage template
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-08-31
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
	<div class="si_toolbar si_toolbar_top">
		<a href="./index.php?module=products&view=add" class="">
			<img src="./images/famfam/add.png" alt="add product button"/>
			{$LANG.add_new_product}
		</a>
{*	
	</div>
	<div class="si_alphabet_zoom">
	ABC | DEF | GHI | JKL | MNO | PQRS | TUV | WXYZ
*}
{if $number_of_rows.count == 0 }

	</div>
	<div class="si_message">{$LANG.no_products}</div>

{else}

{foreach from=$array item=v}
	{if $defaults.default_nrows==$v && $smarty.get.rp==''}
	<script type="text/javascript">
		location.href += '&rp={$v}';
	</script>
	{/if}
{/foreach}

		<span style="float: right;">
			<span class="si_filters_title">{$LANG.rows_per_page}:</span>
			<select id="selectrp" name="rp" onchange="location.href='./index.php?module=products&amp;view=manage&amp;rp='+this.value">
{foreach from=$array item=v key=k}
				<option value="{$v}"{if $smarty.get.rp==$v || ($smarty.get.rp=='' && $d==$k)} selected="selected"{/if}>{$v}</option>
{/foreach}
			</select>
		</span>
	</div>

	<table id="manageGrid" style="display:none"></table>
{assign var=pos value=$smarty.template|strrpos:'/'}
{assign var=inc value=$smarty.template|substr:0:$pos}
{include file=$inc|cat:"/manage.js.php"}
{*include file=$inc|cat:"/../../../modules/products/manage.js.php"*}
{*include file='../extensions/product_add_LxWxH_weight/templates/default/products/manage.js.php'*}
	{*include file='../modules/products/manage.js.php'*}
{/if}
