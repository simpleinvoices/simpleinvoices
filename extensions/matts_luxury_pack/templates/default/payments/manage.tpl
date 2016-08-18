{*
/*
* Script: /simple/extensions/payment_rows_per_page/templates/default/payments/manage.tpl
* 	 Payments manage template
*
*
* Last edited:
* 	 2008-09-01
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<!--d={$d|htmlsafe}-->
{foreach from=$array item=v key=k}
	{if $d==$k && $smarty.get.rp==''}
	<script type="text/javascript">
		location.href='./index.php?module=payments&view=manage&rp={$v}';
	</script>
	{/if}
{/foreach}

	<div class="si_toolbar si_toolbar_top">
		<a href="./index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="process"><img src="./images/famfam/add.png" alt="add"/>{$LANG.process_payment}</a>

		<span style="float: right;">
			<span class="si_filters_title">{$LANG.rows_per_page}:</span>
			<select id="selectrp" name="rp" onchange="location.href='./index.php?module=payments&amp;view=manage&amp;rp='+this.value">
{foreach from=$array item=v key=k}
				<option value="{$v}"{if $smarty.get.rp==$v || ($smarty.get.rp=='' && $d==$k)} selected="selected"{/if}>{$v}</option>
{/foreach}
			</select>
		</span>
 
{if $smarty.get.id}
        	<a href="./index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlencode}&amp;op=pay_selected_invoice" class=""><img src="./images/famfam/money.png" alt=""/>{$LANG.payments_filtered_invoice}</a>
	</div>
        
	{if $payments == null}
		<div class="si_message">
        		{$LANG.no_payments_invoice}
		</div>        		
	{else}
        	<table id="manageGrid" style="display:none"></table>
        	{*include file='../modules/payments/manage.js.php' get=$smarty.get*}
	{include file='../extensions/payment_rows_per_page/templates/default/payments/manage.js.php'}
	{/if}

{elseif $smarty.get.c_id }
	</div>

        {if $payments == null}
		<div class="si_message">
			{$LANG.no_payments_customer}
		</div>        		
        {else}
		<table id="manageGrid" style="display:none"></table>
        	{*include file='../modules/payments/manage.js.php' get=$smarty.get*}
	{include file='../extensions/payment_rows_per_page/templates/default/payments/manage.js.php'}
        {/if}

	{else}
	</div>

        {if $payments == null}
		<div class="si_message">
        		{$LANG.no_payments}
		</div>        		
        {else}
        	<table id="manageGrid" style="display:none"></table>
        	{*include file='../modules/payments/manage.js.php' get=$smarty.get*}
	{include file='../extensions/payment_rows_per_page/templates/default/payments/manage.js.php'}
        {/if}

{/if}

<div class="si_help_div">
	<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{$LANG.wheres_the_edit_button}"><img src="./images/common/help-small.png" alt="help" />{$LANG.wheres_the_edit_button}<!--Where's the Edit button?--></a>
</div>
