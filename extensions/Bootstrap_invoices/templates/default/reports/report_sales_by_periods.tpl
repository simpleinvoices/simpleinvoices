{* Display the rate column ? *}
{assign var=show_rates value=1}

{* How many years to show?  *}
{assign var=years_shown value=5}

{* How may decimals for rate (0-2)  *}
{assign var=rate_precision value='1'}


{* ------------------------------------------------------------------------------- *}

{* keep only years to show*}
{if $years_shown > $all_years|@count}{assign var=years_shown value=$all_years|@count}{/if}
{assign var=years_shown value=$years_shown-1}
{assign var=years value=$all_years.0|range:$all_years.$years_shown}


{literal}
<script language="javascript">
$(document).ready(function() {
	$('.but_show_rates').click(function(e){
		e.preventDefault();
		$('.rate').toggle();
	});
});
</script>
{/literal}
<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.monthly_sales_per_year}</h1>

<h2>{$LANG.sales}</h2>
{php}
	/* moving to smarty 3 would allow us to use a nice smarty function tag instead of this dirty workaround */
	$this->assign('this_data',$this->_tpl_vars['data']['sales']);
{/php}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}


<h2>{$LANG.payments}</h2>
{php}
	$this->assign('this_data',$this->_tpl_vars['data']['payments']);
{/php}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}
