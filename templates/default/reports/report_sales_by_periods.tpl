{* Display the rate column ? *}
{assign var=show_rates value=1}
{* How may decimals for rate (0-2) *}
{assign var=rate_precision value='1'}
{*-------------------------------------------------------------------------------*}
{assign var=years_shown value=$all_years|@count}
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

<div class='si_report_title1'>{$LANG.monthly_sales_per_year}</div>

<div class='si_report_title2'>{$LANG.sales}</div>
{totals_by_period type='sales'}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}

<div class='si_report_title2'>{$LANG.payments}</div>
{totals_by_period type='payments'}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}
