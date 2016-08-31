{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/customers/manage.tpl
 * 	Customer manage template
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
            <a href="./index.php?module=customers&amp;view=add" class="add">
                <img src="./images/famfam/add.png" alt="add" />
                {$LANG.customer_add}
            </a>
 
{if $number_of_customers == 0}
		</div>
		<div class="si_message">
			{$LANG.no_customers}
		</div>

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
			<select id="selectrp" name="rp" onchange="location.href='./index.php?module=customers&amp;view=manage&amp;rp='+this.value">
	{foreach from=$array item=v key=k}
				<option value="{$v}"{if $smarty.get.rp==$v || ($smarty.get.rp=='' && $defaults.default_nrows==$k)} selected="selected"{/if}>{$v}</option>
	{/foreach}
			</select>
		</span>
	</div>
	<br />
	<table id="manageGrid" style="display:none"></table>
	{assign var=pos value=$smarty.template|strrpos:'/'}
	{assign var=inc value=$smarty.template|substr:0:$pos}
	{include file=$inc|cat:"/manage.js.tpl"}
	{*include file=$inc|cat:"/../../../modules/customers/manage.js.php"*}
{/if}
