{*
	/*
	* Script: /simple/extensions/invoice_add_display_no/templates/default/invoices/header.tpl
	* 	 Header file for invoice template
	* called by itemised.tpl
	*
	* License:
	*	 GPL v3 or above
	*
	* Website:
	*	http://www.simpleinvoices.org
	*/
#$Id$
*}


	<input type="hidden" name="action" value="insert" />

    <div class="si_filters si_buttons_invoice_header">
    	<span class="si_filters_links">
	    	<a href="index.php?module=invoices&amp;view=itemised" class="first{if $view=='itemised'} selected{/if}"><img class="action" src="./images/common/edit.png"/>{$LANG.itemised_style}</a>
	    	<a href="index.php?module=invoices&amp;view=total" class="{if $view=='total'}selected{/if}"><img class="action" src="./images/common/page_white_edit.png"/>{$LANG.total_style}</a>
		</span>
    	<span class="si_filters_title">
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types" title="{$LANG.invoice_type}"><img class="" src="./images/common/help-small.png" alt="help" /></a>
		</span>
	</div>


	<table class='si_invoice_top'>
		<tr>
			<th>{$LANG.pro_invoice}</th>
{php}
	$sql = "SELECT * FROM ".TB_PREFIX."index WHERE node = 'invoice' AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
	$idetils = $sth->fetch(PDO::FETCH_ASSOC);
	global $smarty;
	$smarty->assign("inv_details", $idetils);
{/php}
			<td>{$inv_details.id+1}</td>
		</tr>
		<tr wrap="nowrap">
			<th>{$LANG.date_formatted}</th>
			<td wrap="nowrap">
				<input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date1" 
				{if $smarty.get.date}
					value="{$smarty.get.date}" />
				{else}
					value='{$smarty.now|date_format:"%Y-%m-%d"}' />
				{/if}
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>{$LANG.biller}</th>
			<td>
			{if $billers == null }
				<p><em>{$LANG.no_billers}</em></p>
			{else}
				<select name="biller_id">
				{foreach from=$billers item=biller}
					<option {if $biller.id == $defaults.biller} selected {/if} value="{$biller.id|htmlsafe}">{$biller.name|htmlsafe}</option>
				{/foreach}
				</select>
			{/if}
			</td>
		</tr>
		<tr>
			<th>{$LANG.customer}</th>
			<td>
			{if $customers == null }
				<em>{$LANG.no_customers}</em>
			{else}
				<select name="customer_id" id="customer_id">
				{foreach from=$customers item=customer}
					<option {if $customer.id == $defaults.customer}selected="selected" {/if}value="{$customer.id|htmlsafe}">{$customer.name|htmlsafe}</option>
				{/foreach}
				</select>
			{/if}
			</td>
			<td style="width:21px;"></td>
			<td id="customer_street_address" style="font-size: 9px;">{if $defaults.use_modal}<a rel="superbox[iframe][1075x600]" class="show-details modal customer_view" title="{$LANG.view} {$LANG.customer}" href="index.php?module=customers&view=details&id=&action=view">{/if}<img src="images/common/view.png">&nbsp;{if $defaults.use_modal}</a>{/if}
</td>
		</tr>
<!-- Ship To - added by Matt 2016-07-23 -->
{if $defaults.use_ship_to}
		<tr>
			<th>{$LANG.ship_to}</th>
			<td>
				<select name="ship_to_customer_id" id="ship_to_customer_id">
					<option value="0" selected="selected">{$LANG.no_ship_to}</option>
				{foreach from=$customers item=customer}
					<option value="{$customer.id|htmlsafe}">{$customer.name|htmlsafe}</option>
				{/foreach}
				</select>
			</td>
			<td style="width:21px;"></td>
			<td id="ship_street_address" style="font-size: 9px;">{if $defaults.use_modal}<a rel="superbox[iframe][1075x600]" class="show-details modal customer_view" title="{$LANG.view} {$LANG.customer}" href="index.php?module=customers&view=details&id=&action=view">{/if}<img src="images/common/view.png">&nbsp;{if $defaults.use_modal}</a>{/if}</td>
		</tr>
{/if}
<!-- end Ship To -->

<!-- terms :: Added by Matt 20160802 -->
{if $defaults.use_terms}
		<tr wrap="nowrap">
			<th >{$LANG.terms}</th>
			<td wrap="nowrap">
				<input type="text" class="terms" size="30" name="terms" id="terms" 
				{if $smarty.get.terms}
					value="{$smarty.get.terms|htmlsafe}" />
				{else}
					value="{$invoice.terms|htmlsafe}" />
				{/if}
			</td>
		</tr>
{/if}
<!-- end terms -->
	</table>

<script type="text/javascript">
<!--
var json_customers = {$customers|@json_encode};
{literal}
function putAddress(val, where) {
	var elem = document.getElementById(where);
	var child = elem.firstElementChild || elem.firstChild;
	for (var i=0; i<json_customers.length; i++) {
		if (json_customers[i].id == val) {
			child.href = "index.php?module=customers&view=details&id="+ val+ "&action=view";
			child.innerHTML = '(<img src="images/common/view.png">&nbsp;' + json_customers[i].street_address + ' - ' + json_customers[i].attention + '{/literal}{if $defaults.use_modal}</a>{/if}{literal})';
			break;
		}
	}
}

function invoice_changeProduct (product, row_number, quantity, cust_id) {
	$('#gmail_loading').show();
	$.ajax({
		type: 'GET',
		url: './index.php?module=invoices&view=product_ajax&id='+product+'&row='+row_number+'&cid='+cust_id,
		data: "id: "+product,
		dataType: "json",
		success: function(data) {
			$('#gmail_loading').hide();
			$("#json_html"+row_number).remove();
			if (quantity=="") {	
				$("#quantity"+row_number).attr("value","1");
			}
			$("#unit_price"+row_number).attr("value",data['unit_price']);
			$("#tax_id\\["+row_number+"\\]\\[0\\]").val(data['default_tax_id']);
			if (data['default_tax_id_2']== null) {
				$("#tax_id\\["+row_number+"\\]\\[1\\]").val('');
			}
			if (data['default_tax_id_2'] !== null) {
				$("#tax_id\\["+row_number+"\\]\\[1\\]").val(data['default_tax_id_2']);
			}
			//do the product matric code
			if (data['show_description'] =="Y") {	
				$("tbody#row"+row_number+" tr.details").removeClass('si_hide');
			} else {
				$("tbody#row"+row_number+" tr.details").addClass('si_hide');
			}
			if($("#description"+row_number).val() == $("#description"+row_number).attr('rel') || $("#description"+row_number).val() =='Description')
			{
				if (data['notes_as_description'] =="Y") {	
					$("#description"+row_number).val(data['notes']);
					$("#description"+row_number).attr('rel',data['notes']);
				} else {
					$("#description"+row_number).val('Description');
					$("#description"+row_number).attr('rel','Description');
				}
			} 
			if (data['json_html'] !=="") {
				$("tbody#row"+row_number+" tr.details").before(data['json_html']);
			}
		}
	});
};

function changeProductSelection (objct) {
	var $row_number = $(objct).attr("rel");
	var $product = $(objct).val();
	var $quantity = $("#quantity" + $row_number).attr("value");
	var elem = document.getElementById("customer_id");
	var cust_id = elem.options[elem.selectedIndex].value;
	invoice_changeProduct($product, $row_number, $quantity, cust_id);
	siLog('debug', 'Description');
}

function initial() {
	var elem = document.getElementById("customer_id");
	var cto = document.getElementById("customer_street_address");
	cto.style.display = 'none';
	if (elem.addEventListener){
		elem.addEventListener("change", function(){
			if (window.getSelection){
				cto.style.display='block';
				putAddress(this.value,'customer_street_address');
			}
		}, false);
	} else if (elem.attachEvent){
		elem.attachEvent("onchange", function(){
			if (window.getSelection){
				cto.style.display='block';
				putAddress(this.value,'customer_street_address');
			}
		});
	}
	var ele = document.getElementById("ship_to_customer_id");
	var csto = document.getElementById("ship_street_address");
	csto.style.display = 'none';
	if (ele.addEventListener){
		ele.addEventListener("change", function(){
			if (window.getSelection){
				putAddress(this.value,'ship_street_address');
				csto.style.display='block';
			}
		}, false);
	} else if (ele.attachEvent){
		ele.attachEvent("onchange", function(){
			if (window.getSelection){
				putAddress(this.value,'ship_street_address');
				csto.style.display='block';
			}
		});
	}
};

if (window.addEventListener) {
	window.addEventListener("load", initial, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", initial);
} else {
	document.addEventListener("load", initial, false);
}
{/literal}
//-->
</script>
