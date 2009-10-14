<img src="images/common/simple_invoices_logo.jpg"/>
<br/>
<br/>
<table align="center">
<tr><td>
<div id="money" class="ui-tabs-panel" style="">
    <ul class="subnav">
        <li>
            <a class="active" href="index.php?module=install&view=index">Simple Invoices installer</a>
        </li>
        <li>
            <a href="index.php?module=install&view=structure">Step 1: Install Database</a>
        </li>
        <li>
            <a href="index.php?module=install&view=essential">Step 2: Import essentail data</a>
        </li>
        <li>
            <a class="active active_subpage" href="index.php?module=install&view=sample_data">Step 3: Import sample data</a>
        </li>
    </ul>
</div>
</td></tr>
</table>
<br />
<br />
{if $saved == true }
        Sample data has been imported into Simple Invoics
	<br />
	<br />
	<meta http-equiv="refresh" content="2;URL=index.php" />
{else}
        Something bad happened. Sample data has NOT been imported into Simple Invoics
	<br />
	<br />
{/if}

<br />
<br />
<br />
<strong>Note:</strong> 
If you have any problems or queries re installation 
please refer to <a href="http://www.simpleinvoices.org/install" target="blank">install</a> 
documenation on the website
<br /> -> <a href="http://www.simpleinvoices.org/install" target="blank">http://www.simpleinvoices.org/install</a>
