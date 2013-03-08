<div id="si_install_logo">
	<img src="images/common/simple_invoices_logo.jpg" class="si_install_logo" width="300"/>
</div>

<div id="tabmenu" class="si_install_steps">
	<div id="money" class="ui-tabs-panel si_center" style="">
		<ul class="subnav">
			<li><a class="{if $view==''}active{/if}" href="index.php?module=install&view=index">Simple Invoices installer</a></li>
			<li><a class="{if $view=='structure'}active active_subpage{/if}" href="index.php?module=install&view=structure">Step 1: Install Database</a></li>
			<li><a class="{if $view=='essential'}active active_subpage{/if}" href="index.php?module=install&view=essential">Step 2: Install essential data</a></li>
			<li><a class="{if $view=='sample_data'}active active_subpage{/if}" href="index.php?module=install&view=sample_data">Step 3: Import sample data</a></li>
		</ul>
	</div>
</div>
