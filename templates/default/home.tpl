
<div>

{if $mysql < 5 && $db_server == 'mysql'}

		NOTE <a href='docs.php?t=help&p=mysql4' rel='gb_page_center[450, 450]' ><img src='./images/common/help-small.png'></img></a> : As you are using Mysql 4 some features have been disabled<br>
{/if}


<!-- Welcome message - start -->

<h1>Welcome --username-- !</h1>
Thank you for choosing Simple Invoices! There are just a couple of things to do before you can start invoicing<br><br>
1 - Setup yourself up as a biller - <a href="index.php?module=biller&view=add">click here</a><br>
2 - Add a client - <a href="index.php?module=customers&view=add">click here</a><br>
3 - Add some products - <a href="index.php?module=products&view=add">click here</a><br>
4 - go nuts creating invoices - <a href="index.php?module=invoices&view=itemised">click here</a><br><br>
If you need to customise some of the <a href="index.php?module=options&view=index">settings</a> (ie. language, default items, etc..) <a href="">click here</a> and adjust as required
<br><br>
Already know Simple Invoices by heart? You can <a href="">hide this text</a> forever then  <a href="">click here</a>


<!-- Welcome message - end -->
<!-- Do stuff menu  - start -->
<div>
	<!-- Need help mini menu  - start -->
	<div class="floatRight">
		<h2>Need help?</h2>
		<a href="">Simple Invoices Help ></a><br>
		<a href="http://www.simpleinvoices.org/forum">Community Forums ></a><br>
		<a href="http://www.simpleinvoices.org/blog">Simple Invoices Blog ></a>
	</div>
	<!-- Need help mini menu  - end -->
<h2>Start working</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=customers&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                Add a new Invoice {* LANG TODO*}
            </a>
            
             <a href="index.php?module=customers&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                 Add a new Client {* LANG TODO*}
            </a>
    
    
           <a href="index.php?module=customers&view=manage" class="positive">
                <img src="./images/common/add.png" alt=""/>
                 Add a new Product {* LANG TODO*}
            </a>
        </td>
    </tr>
</table>
<br>

</div>
<!-- Do stuff menu  - end -->

<!-- Don't forget to menu - start -->
<h2>Don't forget to</h2>
<table class="buttons" >
    <tr>
        <td>

            
             <a href="index.php?module=options&view=index" class="">
                <img src="./images/common/cog_edit.png" alt=""/>
                 Customise the settings {* LANG TODO*}
            </a>
    
    
           <a href="./index.php?module=customers&view=manage" class="">
                <img src="./images/common/database_save.png" alt=""/>
                 Backup your Database now {* LANG TODO*}
            </a>
        </td>
    </tr>
</table>

<!-- Don't forget to menu - end -->

<!-- Reports menu - start -->
<h2>Your reports</h2>
--fancy graph here-- --some links on the right--
<!-- Reports menu - end -->


<!--
<div id="accordian">
	<div id="list1">
	<h2><img src="./images/common/reports.png"></img>{$LANG.stats}</h2>
	
		<div id="item11">
			<div class="title">{$LANG.stats_debtor}</div>
			<div class="content">{$debtor.Customer}</div>
		</div>

		<div id="item12">
			<div class="title">{$LANG.stats_customer}</div>
			<div class="content">{$customer.Customer}</div>
		</div>

		<div id="item13">
			<div class="title">{$LANG.stats_biller}</div>
			<div class="content">{$biller.name}</div>
		</div>
	</div>

	<div id="list2">
	<h2><img src="./images/common/menu.png">{$LANG.shortcut}</h2>

	<div id="item21">
	<div class="mytitle">{$LANG.getting_started}</div>
		<div class="mycontent">
			<table>
				<tr>
					<td>
						<a class="cluetip" href="#"	rel="docs.php?t=help&p=simple_invoices" title="{$LANG.using_simple_invoices}"><img src="./images/common/help-small.png"></img> {$LANG.faqs_need}</a>
					</td>		
					<td>
						<a class="cluetip" href="#"	rel="docs.php?t=help&p=invoice_start" title="{$LANG.invoice_start}"><img src="./images/common/help-small.png"></img> {$LANG.faqs_need}</a>
					</td>		
				</tr>
				<tr>
					<td>
						<a class="cluetip" href="#"	rel="docs.php?t=help&p=invoice_create" title="{$LANG.invoice_create}"><img src="./images/common/help-small.png"></img> {$LANG.faqs_how}</a>
					</td>		
					<td>
						<a class="cluetip" href="#"	rel="docs.php?t=help&p=invoice_types" title="{$LANG.invoice_type}"><img src="./images/common/help-small.png"></img> {$LANG.faqs_type}</a>
					</td>		
				</tr>
			</table>
		</div>
	</div>

	<div id="item22">
	<div class="mytitle">{$LANG.create_invoice}</div>
		<div class="mycontent">
			<table>
				<tr>
					<td>
						<a href="index.php?module=invoices&view=itemised"><img src="images/common/itemised.png"></img>{$LANG.itemised_style}</a>
					</td>		
					<td>
						<a href="index.php?module=invoices&view=total"><img src="images/common/total.png"></img>{$LANG.total_style}</a>
					</td>
					<td>
						<a href="index.php?module=invoices&view=consulting"><img src="images/common/consulting.png"></img>{$LANG.consulting_style}</a>
					</td>
				</tr>
				<tr>
					<td colspan=3 align=center class="align_center">
						<a class="cluetip" href="#"	rel="docs.php?t=help&p=invoice_types" title="{$LANG.invoice_type}"><img src="./images/common/help-small.png"></img> {$LANG.faqs_type}</a>
					</td>		
				</tr>
			</table>
		</div>
	</div>

	<div id="item23">
	<div class="mytitle">{$LANG.manage_existing_invoice}</div>
		<div class="mycontent">
			<table>
				<tr>
					<td align=center class="align_center">
						<a href="index.php?module=invoices&view=manage"><img src="images/common/manage.png"></img>{$LANG.manage_invoices}</a>
					</td>
				</tr>
			</table>
		 </div>
	</div>

	<div id="item24">
	<div class="mytitle">{$LANG.manage_data}</div>
		<div class="mycontent">
			<table>
				 <tr>
					<td>
						<a href="index.php?module=customers&view=add"><img src="images/common/add.png"></img>{$LANG.insert_customer}</a>
					</td>
					<td>
						<a href="index.php?module=billers&view=add"><img src="images/common/add.png"></img>{$LANG.insert_biller}</a>
					</td>
					<td>
						<a href="index.php?module=products&view=add"><img src="images/common/add.png"></img>{$LANG.insert_product}</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="index.php?module=customers&view=manage"><img src="images/common/customers.png"></img>{$LANG.manage_customers}</a>
					</td>
					<td>
						<a href="index.php?module=billers&view=manage"><img src="images/common/biller.png"></img>{$LANG.manage_billers}</a>
					</td>
					<td>
						<a href="index.php?module=products&view=manage"><img src="images/common/products.png"></img>{$LANG.manage_products}</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
		
	<div id="item25">
	<div class="mytitle">{$LANG.options}</div>
		<div class="mycontent">
			<table>
				<tr>
					<td>
						<a href="index.php?module=system_defaults&view=manage"><img src="images/common/defaults.png"></img>{$LANG.system_defaults}</a>
					</td>
					<td>
						<a href="index.php?module=tax_rates&view=manage"><img src="images/common/tax.png"></img>{$LANG.tax_rates}</a>
					</td>
					<td>
						<a href="index.php?module=preferences&view=manage"><img src="images/common/preferences.png"></img>{$LANG.invoice_preferences}</a>
					</td>
				</tr>
				<tr>
					<td>
						<a href="index.php?module=payment_types&view=manage"><img src="images/common/payment.png"></img>{$LANG.payment_types}</a>
					</td>
					<td>
						<a href="index.php?module=options&view=database_sqlpatches"><img src="images/common/upgrade.png"></img>{$LANG.database_upgrade_manager}</a>
					</td>
					<td>
						<a href="index.php?module=options&view=backup_database"><img src="images/common/backup.png"></img>{$LANG.backup_database}</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div id="item26">
	<div class="mytitle">{$LANG.help}</div>
		<div class="mycontent">
			<table>
				<tr>
					<td>
						<a href="docs.php?p=ReadMe#installation" target="_blank"><img src="images/common/help.png"></img>{$LANG.installation} </a>
					</td>	
					<td>
						<a href="docs.php?p=ReadMe#upgrading" target="_blank"><img src="images/common/help.png"></img>{$LANG.upgrading_simple_invoices} </a>
					</td>	
				</tr>
				<tr>
					<td class="align_center" colspan="2">
						<a href="docs.php?p=ReadMe#prepare" target="_blank"><img src="images/common/help.png"></img>{$LANG.prepare_simple_invoices}</a>
					</td>	
				</tr>
			</table>
		</div>
	</div>
</div>

-->

</div>

<br><br>

</div>
