
<div>
<h3>{$title}</h3>
<hr />

<!--
{if $patch > $max_patches_applied}

                NOTE <a href='docs.php?t=help&p=database_patches' rel='gb_page_center[450, 450]'><img src='./images/common/help-small.png'></img></a> :   There are database patches that need to be applied, please select <a href="./index.php?module=options&view=database_sqlpatches ">'Database Upgrade Manager'</a> from the Options menu and follow the instructions<br>
{/if}
-->

{if $mysql < 5}

		NOTE <a href='docs.php?t=help&p=mysql4' rel='gb_page_center[450, 450]' ><img src='./images/common/help-small.png'></img></a> : As you are using Mysql 4 some features have been disabled<br>
{/if}

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
						<a href="docs.php?p=ReadMe#faqs-what"><img src="images/common/question.png"></img>{$LANG.faqs_what}</a>
					</td>		
					<td>
						<a href="docs.php?p=ReadMe#faqs-need"><img src="images/common/question.png"></img>{$LANG.faqs_need}</a>
					</td>		
				</tr>
				<tr>
					<td>
						<a href="docs.php?p=ReadMe#faqs-how"><img src="images/common/question.png"></img>{$LANG.faqs_how}</a>
					</td>		
					<td>
						<a href="docs.php?p=ReadMe#faqs-types"><img src="images/common/question.png"></img>{$LANG.faqs_type}</a>
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
						<a href="docs.php?p=ReadMe#faqs-types"><img src="images/common/question.png"></img>{$LANG.faqs_type}</a>
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
						<a href="docs.php?p=ReadMe#installation"><img src="images/common/help.png"></img>{$LANG.installation}</a>
					</td>	
					<td>
						<a href="docs.php?p=ReadMe#upgrading"><img src="images/common/help.png"></img>{$LANG.upgrading_simple_invoices}</a>
					</td>	
				</tr>
				<tr>
					<td class="align_center" colspan="2">
						<a href="docs.php?p=ReadMe#prepare"><img src="images/common/help.png"></img>{$LANG.prepare_simple_invoices}</a>
					</td>	
				</tr>
			</table>
		</div>
	</div>
</div>
	
</div>
</div>