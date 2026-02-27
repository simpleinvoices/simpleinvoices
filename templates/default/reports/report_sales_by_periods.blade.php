{{-- Display the rate column ? --}}
{assign var=show_rates value=1}

{{-- How may decimals for rate (0-2) --}}
{assign var=rate_precision value='1'}


{{-- ------------------------------------------------------------------------------- --}}

{assign var=years_shown value=$all_years|@count}
{assign var=years_shown value=$years_shown-1}
{assign var=years value=$all_years['0']|range:$all_years.$years_shown}


@verbatim
<script language="javascript">
$(document).ready(function() {
	$('.but_show_rates').click(function(e){
		e.preventDefault();
		$('.rate').toggle();
	});
});
</script>
@endverbatim


<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['monthly_sales_per_year'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<h4>{{ $LANG['sales'] ?? '' }}</h4>
{php}
	/* moving to smarty 3 would allow us to use a nice smarty function tag instead of this dirty workaround */
	$this->assign('this_data',$this->_tpl_vars['data']['sales']);
{/php}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}

		<h4 class="mt-4">{{ $LANG['payments'] ?? '' }}</h4>
{php}
	$this->assign('this_data',$this->_tpl_vars['data']['payments']);
{/php}
{include file=$path|cat:'report_sales_by_periods_include.tpl'}
	</div>
</div>
