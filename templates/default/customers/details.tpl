<form name="frmpost"
	action="index.php?module=customers&view=save&submit={$smarty.get.submit}"
	method="post">

{if $smarty.get.action== 'view' }
<b>{$LANG.customer} :: <a href="index.php?module=customers&view=details&submit={$customer.id}&action=edit">{$LANG.edit}</a>
</b>
<hr></hr>
<table align="center">
	<tr>
		<td colspan="7" align="center"> </td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="align_center"><i>{$LANG.customer_details}</i></td>
		<td width="10%"></td>
		<td colspan="2" align="center" class="align_center"><i>{$LANG.summary_of_accounts}</i></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer} {$LANG.id}</td>
		<td>{$customer.id}</td>
		<td colspan="2"></td>
		<td></td>
		<td class="details_screen">{$LANG.total_invoices}</td>
		<td>{$stuff.total_format}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td colspan="2">{$customer.name}</td>
		<td colspan="2"></td>
		<td class="details_screen">{$LANG.total_paid}</td>
		<td>{$stuff.paid_format}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short} <a href="docs.php?t=help&p=customer_contact" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td colspan="2">{$customer.attention}</td>
		<td colspan=2></td>
		<td class="details_screen">{$LANG.total_owing}</td>
		<td><u>{$stuff.owing}</u></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td>{$customer.street_address}</td>
	</tr>
	<tr>
		<td class="details_screen" NOWRAP>{$LANG.street2} <a href="docs.php?t=help&p=street2" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>{$customer.street_address2}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td>{$customer.city}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td>{$customer.zip_code}</td>
		<td class="details_screen">{$LANG.phone}</td>
		<td>{$customer.phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td>{$customer.state}</td>
		<td class="details_screen" NOWRAP>{$LANG.mobile_phone}</td>
		<td>{$customer.mobile_phone}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td>{$customer.country}</td>
		<td class="details_screen">{$LANG.fax}</td>
		<td>{$customer.fax}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>{$customer.wording_for_enabled}</td>
		<td class="details_screen">{$LANG.email}</td>
		<td>{$customer.email}</td>
	</tr>
</table>
<br />
<div id="container-1">
	<ul class="anchors">
		<li><a href="#section-1">{$LANG.custom_fields}</a></li>
		<li><a href="#section-2">{$LANG.customer} {$LANG.invoice_listings}</a></li>
		<li><a href="#section-3">{$LANG.notes}</a></li>
	</ul>
	<div id="section-1" class="fragment">
		<h4><u>{$LANG.customer} {$LANG.custom_fields}</u></h4>
		<p>
			<table>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf1} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer.custom_field1}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf2} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer.custom_field2}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf3} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer.custom_field3}</td>
				</tr>
				<tr>
					<td class="details_screen">{$customFieldLabel.customer_cf4} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
					</td>
					<td>{$customer.custom_field4}</td>
				</tr>
			</table>
		</p>
	</div>
	<div id="section-2" class="fragment">
		<h4><u>{$LANG.invoice_listings}</u></h4>
		<p >
		<table width="100%" align="center">
			<tr class="sortHeader">

				<th class="sortable">{$LANG.id}</th>
				<th class="sortable">{$LANG.total}</th>
				<th class="sortable">{$LANG.owing}</th>
				<th class="sortable">{$LANG.date_created}</th>

			</tr>
		
			{foreach from=$invoices item=invoice}
	
			<tr class="index_table">
				<td class="details_screen">{$invoice.id}</td>
				<td class="details_screen">{$invoice.total}</td>
				<td class="details_screen">{$invoice.owing}</td>
				<td class="details_screen">{$invoice.date}</td>
			</tr>

			{/foreach}
		</table>	
		</p>
	</div>
	<div id="section-3" class="fragment">
		<h4><u>{$LANG.customer} {$LANG.notes}</u></h4>
		<p>
			<div id="left">
				{$customer.notes}
			</div>
		</p>
	</div>
</div>


<hr></hr>
<a href="index.php?module=customers&view=details&submit={$customer.id}&action=edit">{$LANG.edit}</a>
{/if}

{if $smarty.get.action== 'edit' }

<div id="top"><b>{$LANG.customer_edit}</b></div>
<hr></hr>
<table align="center">
	<tr>
		<td class="details_screen">{$LANG.customer} {$LANG.id}</td>
		<td>{$customer.id}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.customer_name}</td>
		<td><input type="text" name="name" value="{$customer.name}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.attention_short} <a href="docs.php?t=help&p=customer_contact" rel="gb_page_center[450, 450]" ><img src="./images/common/help-small.png"></img></a>
		</td>
		<td><input type="text" name="attention" value="{$customer.attention}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street}</td>
		<td><input type="text" name="street_address" value="{$customer.street_address}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.street2} <a href="docs.php?t=help&p=street2" rel="gb_page_center[450, 450]" ><img src="./images/common/help-small.png"></img></a>
		</td>
		<td><input type="text" name="street_address2" value="{$customer.street_address2}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.city}</td>
		<td><input type="text" name="city" value="{$customer.city}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.zip}</td>
		<td><input type="text" name="zip_code" value="{$customer.zip_code}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.state}</td>
		<td><input type="text" name="state" value="{$customer.state}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.country}</td>
		<td><input type="text" name="country" value="{$customer.country}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.phone}</td>
		<td><input type="text" name="phone" value="{$customer.phone}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.mobile_phone}</td>
		<td><input type="text" name="mobile_phone" value="{$customer.mobile_phone}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.fax}</td>
		<td><input type="text" name="fax" value="{$customer.fax}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.email}</td>
		<td>
			<input type="text" name="email" value="{$customer.email}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf1} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="custom_field1" value="{$customer.custom_field1}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf2} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="custom_field2" value="{$customer.custom_field2}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf3} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="custom_field3" value="{$customer.custom_field3}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel.customer_cf4} <a href="docs.php?t=help&p=custom_fields" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a>
		</td>
		<td>
			<input type="text" name="custom_field4" value="{$customer.custom_field4}" size="50" /></td
	</tr>
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea name="notes" rows="8" cols="50">{$customer.notes}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options name=enabled options=$enabled selected=$customer.enabled}
		</td>
	</tr>
</table>


<hr></hr>
<input type="submit" name="cancel" value="{$LANG.cancel}" />
<input type="submit" name="save_customer" value="{$LANG.save_customer}" />
<input type="hidden" name="op" value="edit_customer" />


</form>
{/if}
