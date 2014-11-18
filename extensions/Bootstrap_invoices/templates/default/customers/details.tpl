{*
/*
* Script: details.tpl
* 	 Customer details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $smarty.get.action == 'view' }
<div class="si_form si_form_view" id="si_form_cust">
	<h1 class="title"><a href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a> <span>/</span> {$LANG.view}
		<a href="./index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="btn btn-default"> <span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a></h1>
	<div class="si_cust_info table-responsive">
		<table class="table table-striped table-hover">
			<tr>
				<th>{$LANG.customer_name}</th>
				<td>{$customer.name}</td>
				<td class="td_sep"></td>
				<th>{$LANG.phone}</th>
				<td>{$customer.phone|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.attention_short}</th>
				<td>{$customer.attention|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.mobile_phone}</th>
				<td>{$customer.mobile_phone|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.street}</th>
				<td>{$customer.street_address|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.fax}</th>
				<td>{$customer.fax|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.street2}</th>
				<td>{$customer.street_address2|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$LANG.email}</th>
				<td><a href="mailto:{$customer.email|htmlsafe}">{$customer.email|htmlsafe}</a></td>
			</tr>
			<tr>
				<th>{$LANG.city}</th>
				<td>{$customer.city|htmlsafe}</td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<th>{$LANG.zip}</th>
				<td>{$customer.zip_code|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf1}</th>
				<td>{$customer.custom_field1|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.state}</th>
				<td>{$customer.state|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf2}</th>
				<td>{$customer.custom_field2|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.country}</th>
				<td>{$customer.country|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf3}</th>
				<td>{$customer.custom_field3|htmlsafe}</td>
			</tr>
			<tr>
				<th>{$LANG.enabled}</th>
				<td>{$customer.wording_for_enabled|htmlsafe}</td>
				<td class="td_sep"></td>
				<th>{$customFieldLabel.customer_cf4}</th>
				<td>{$customer.custom_field4|htmlsafe}</td>
			</tr>
		</table>
	</div>

<div >
	<ul class="nav nav-tabs">
		<li class="active"><a href="#summary_of_accounts" data-toggle="tab">{$LANG.summary_of_accounts}</a></li>
		<li><a href="#credit_card_details" data-toggle="tab">{$LANG.credit_card_details}</a></li>
		<li><a href="#unpaid_invoices" data-toggle="tab" >{$LANG.unpaid_invoices}</a></li>
		<li><a href="#invoice_listings" data-toggle="tab">{$LANG.customer} {$LANG.invoice_listings}</a></li>
		<li><a href="#notes" data-toggle="tab">{$LANG.notes}</a></li>
	</ul>
	<div class="tab-content">
	<div id="summary_of_accounts" class="tab-pane fade in active">
		<div class="si_cust_account table-responsive">
		    <table class="table table-striped table-hover">
			<tr>
			    <th>{$LANG.total_invoices}</th>
			    <td class="si_right">{$stuff.total|number_format:2}</td>
			</tr>
			<tr>
			    <th><a href="index.php?module=payments&view=manage&c_id={$customer.id|urlencode}">{$LANG.total_paid}</a></th>
			    <td class="si_right">{$stuff.paid|number_format:2}</td>
			</tr>
			<tr>
			    <th>{$LANG.total_owing}</th>
			    <td class="si_right"><u>{$stuff.owing|number_format:2}</u></td>
			</tr>
		    </table>
		</div>
	</div>

	<div id="credit_card_details" class="tab-pane fade">

		<div class="si_cust_card table-responsive">
		    <table class="table table-striped table-hover">
			<tr>
			    <th>{$LANG.credit_card_holder_name}</th>
			    <td>{$customer.credit_card_holder_name|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_number}</th>
			    <td>{$customer.credit_card_number|regex_replace:'/^............/':"************"|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_expiry_month}</th>
			    <td>{$customer.credit_card_expiry_month|htmlsafe}</td>
			</tr>
			<tr>
			    <th>{$LANG.credit_card_expiry_year}</th>
			    <td>{$customer.credit_card_expiry_year|htmlsafe}</td>
			</tr>
		    </table>
		</div>
        </div>
	
	<div id="unpaid_invoices" class="tab-pane fade">
		<div class="si_cust_invoices table-responsive">
		    <table class="table table-striped table-hover">
			<thead>
			    <tr class="tr_head">
				<th class="first">
			<!--6 Payment -->{$LANG.actions}
				</th>
				<th>{$LANG.id}</th>
				<th>{$LANG.date_created}</th>
				<th>{$LANG.total}</th>
				<th>{$LANG.paid}</th>
				<th>{$LANG.owing}</th>
			    </tr>
			</thead>
			<tbody>
			{foreach from=$invoices item=invoice}
			    <tr class="index_table">
				<td class="first">
			<!--6 Payment --><a title="{$LANG.process_payment_for} {$invoice.preference} {$invoice.id}"  href='index.php?module=payments&view=process&id={$invoice.id}&op=pay_selected_invoice'><img src='images/common/money_dollar.png' class='action' /></a>
				<a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}"><img src='images/common/view.png' class='action' /></a>
				</td>
				<td ><a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.index_id|htmlsafe}</a></td>
				<td>{$invoice.date|htmlsafe}</td>
				<td>{$invoice.total|number_format:2}</td>
				<td>{$invoice.paid|number_format:2}</td>
				<td>{$invoice.owing|number_format:2}</td>
			    </tr>
			{/foreach}
			</tbody>
		    </table>
		</div>
	</div>
	<div id="invoice_listings" class="tab-pane fade">
		<div class="si_cust_invoices table-responsive">
		    <table class="table table-striped table-hover">
			<thead>
			    <tr class="tr_head">
				<th class="first">{$LANG.id}</th>
				<th>{$LANG.date_created}</th>
				<th>{$LANG.total}</th>
				<th>{$LANG.paid}</th>
				<th>{$LANG.owing}</th>
			    </tr>
			</thead>
			<tbody>
			{foreach from=$invoices item=invoice}
			    <tr class="index_table">
				<td class="first"><a href="index.php?module=invoices&amp;view=quick_view&id={$invoice.id|urlencode}">{$invoice.index_id|htmlsafe}</a></td>
				<td>{$invoice.date|htmlsafe}</td>
				<td>{$invoice.total|number_format:2}</td>
				<td>{$invoice.paid|number_format:2}</td>
				<td>{$invoice.owing|number_format:2}</td>
			    </tr>
			{/foreach}
			</tbody>
		    </table>
		</div>
	</div>
	<div id="notes" class="tab-pane fade">

		<div class="si_cust_notes table-responsive">
		    <table class="table table-striped table-hover">
		    	<tr><td>{$customer.notes|outhtml}</tr></td>
		</table>
		</div>
    	</div>
</div>
</div>
    	<div class="col-sm-offset-1 col-sm-6">
						<a href="./index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=edit" class="btn btn-default"> <span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a>
	</div>
</div>
{/if}


{* ##################################################################################################### *}



{if $smarty.get.action == 'edit' }

<form name="frmpost" action="index.php?module=customers&amp;view=save&amp;id={$customer.id|urlencode}" method="post" id="frmpost" onsubmit="return checkForm(this);" class="form-horizontal">
	<h1 class="title"><a href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a> <span>/</span> {$LANG.edit}
				<button type="submit" class="btn btn-default positive" name="save_customer" value="{$LANG.save_customer}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button>
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a></h1>
<div class="si_form" id="si_form_cust_edit">
		<div class="form-group">
			<label for="customer_name" class="col-sm-3 control-label details_screen">{$LANG.customer_name}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.required_field}">
					<span class="glyphicon glyphicon-asterisk"></span>
				</a>
			</label>
			<div class="col-sm-6"><input class="form-control validate[required]" type="text" name="name" value="{$customer.name|htmlsafe}" size="50" id="name" /></div>
		</div>
		<div class="form-group">
			<label for="attention_short" class="col-sm-3 control-label details_screen">{$LANG.attention_short}
				<a rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact" href="#" class="cluetip" title="{$LANG.customer_contact}">
					<span class="glyphicon glyphicon-question-sign"></span>
				</a>
			</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="attention" value="{$customer.attention|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="street" class="col-sm-3 control-label details_screen">{$LANG.street}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="street_address" value="{$customer.street_address|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="street2" class="col-sm-3 control-label details_screen">{$LANG.street2}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_street2" title="{$LANG.street2}"> 
					<span class="glyphicon glyphicon-question-sign"></span>
				</a>
			</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="street_address2" value="{$customer.street_address2|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="city" class="col-sm-3 control-label details_screen">{$LANG.city}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="city" value="{$customer.city|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="zip" class="col-sm-3 control-label details_screen">{$LANG.zip}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="zip_code" value="{$customer.zip_code|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="state" class="col-sm-3 control-label details_screen">{$LANG.state}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="state" value="{$customer.state|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="country" class="col-sm-3 control-label details_screen">{$LANG.country}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="country" value="{$customer.country|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="phone" class="col-sm-3 control-label details_screen">{$LANG.phone}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="phone" value="{$customer.phone|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="mobile_phone" class="col-sm-3 control-label details_screen">{$LANG.mobile_phone}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="mobile_phone" value="{$customer.mobile_phone|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="fax" class="col-sm-3 control-label details_screen">{$LANG.fax}</label>
			<div class="col-sm-6"><input class="form-control" type="text" name="fax" value="{$customer.fax|htmlsafe}" size="50" /></div>
		</div>
		<div class="form-group">
			<label for="email" class="col-sm-3 control-label details_screen">{$LANG.email}</label>
			<div class="col-sm-6">
				<input class="form-control" type="text" name="email" value="{$customer.email|htmlsafe}" size="50" /></div
		></div>
		<div class="form-group">
			<label for="credit_card_holder_name" class="col-sm-3 control-label details_screen">{$LANG.credit_card_holder_name}</label>
			<div class="col-sm-6">
				<input class="form-control"
					type="text" name="credit_card_holder_name"
					value="{$customer.credit_card_holder_name|htmlsafe}" size="25"
				 />
			</div>
		</div>
		<div class="form-group">
			<label for="credit_card_number" class="col-sm-3 control-label details_screen">{$LANG.credit_card_number}</label>
			<div class="col-sm-6">
						{$LANG.credit_card_number_encrypted}
			</div>
		</div>
		<div class="form-group">
			<label for="credit_card_number_new" class="col-sm-3 control-label details_screen">{$LANG.credit_card_number_new}</label>
			<div class="col-sm-6">
				<input class="form-control"
					type="text" name="credit_card_number_new"
					value="{$customer.credit_card_holder_name_new|htmlsafe}" size="25"
				 />
			</div>
		</div>
		<div class="form-group">
			<label for="credit_card_expiry_month" class="col-sm-3 control-label details_screen">{$LANG.credit_card_expiry_month}</label>
			<div class="col-sm-6">
				<input class="form-control"
					type="text" name="credit_card_expiry_month"
					value="{$customer.credit_card_expiry_month|htmlsafe}" size="5"
				 />
			</div>
		</div>
		<div class="form-group">
			<label for="credit_card_expiry_year" class="col-sm-3 control-label details_screen">{$LANG.credit_card_expiry_year}</label>
			<div class="col-sm-6">
				<input class="form-control"
					type="text" name="credit_card_expiry_year"
					value="{$customer.credit_card_expiry_year|htmlsafe}" size="5"
				 />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_cf1" class="col-sm-3 control-label details_screen">{$customFieldLabel.customer_cf1|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <span class="glyphicon glyphicon-question-sign"></span></a>
			</label>
			<div class="col-sm-6">
				<input class="form-control" type="text" name="custom_field1" value="{$customer.custom_field1|htmlsafe}" size="50" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_cf2" class="col-sm-3 control-label details_screen">{$customFieldLabel.customer_cf2|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <span class="glyphicon glyphicon-question-sign"></span></a>
			</label>
			<div class="col-sm-6">
				<input class="form-control" type="text" name="custom_field2" value="{$customer.custom_field2|htmlsafe}" size="50" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_cf3" class="col-sm-3 control-label details_screen">{$customFieldLabel.customer_cf3|htmlsafe} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			<span class="glyphicon glyphicon-question-sign"></span></a>
			</label>
			<div class="col-sm-6">
				<input class="form-control" type="text" name="custom_field3" value="{$customer.custom_field3|htmlsafe}" size="50" />
			</div>
		</div>
		<div class="form-group">
			<label for="customer_cf4" class="col-sm-3 control-label details_screen">{$customFieldLabel.customer_cf4|htmlsafe}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
					title="{$LANG.custom_fields}"
				> 
			 <span class="glyphicon glyphicon-question-sign"></span></a>
			</label>
			<div class="col-sm-6">
				<input class="form-control" type="text" name="custom_field4" value="{$customer.custom_field4|htmlsafe}" size="50" />
			</div>
		</div>
		<div class="form-group">
			<label for="notes" class="col-sm-3 control-label details_screen">{$LANG.notes}</label>
			<div class="col-sm-6"><textarea  name="notes"  class="editor" rows="8" cols="50">{$customer.notes|outhtml}</textarea></div>
		</div>
		{*
			{showCustomFields categorieId="2" itemId=$smarty.get.customer }
		*}
		<div class="form-group">
			<label for="enabled" class="col-sm-3 control-label details_screen">{$LANG.enabled}</label>
			<div class="col-sm-6">
				{html_options name=enabled class="form-control" options=$enabled selected=$customer.enabled}
			</div>
		</div>
	<div class="form-group">
	<div class="col-sm-offset-3 col-sm-6 si_toolbar si_toolbar_form">
	<button type="submit" class="btn btn-default positive" name="save_customer" value="{$LANG.save_customer}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button>
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a>
	</div>

	</div>

</div>

<input type="hidden" name="op" value="edit_customer">
</form>
{/if}

