{*
* Script: add.tpl
* 	 Customers add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}

{* if customer is updated or saved.*} 

{if $smarty.post.name != "" && $smarty.post.name != null } 
	{include file="../../../extensions/Bootstrap_invoices/templates/default/customers/save.tpl"}

{else}
{* if  name was inserted *} 
{if $smarty.post.id !=null}
{*
		<div class="validation_alert si_message_warning"><span class="glyphicon glyphicon-exclamation-sign"></span>
		You must enter a description for the Customer</div>
*}
	{/if}	
<form name="frmpost" action="index.php?module=customers&amp;view=add" method="post" id="frmpost" onsubmit="return checkForm(this);" class="form-horizontal">
	<h1 class="title"><a href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a> <span>/</span>  {$LANG.add}
		<button type="submit" class="btn btn-default positive" name="id" value="{$LANG.save}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button> 
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a></h1>
<div class="si_form">
	<div class="form-group">
		<label for="name" class="col-sm-3 control-label">{$LANG.customer_name}
		<a 
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
			title="{$LANG.required_field}"
		>
		<span class="glyphicon glyphicon-asterisk"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="name" id="name" value="{$smarty.post.name|htmlsafe}" size="25" class="form-control validate[required]" /></div>
	</div>
	<div class="form-group">
		<label for="attention" class="col-sm-3 control-label">{$LANG.customer_contact}
		<a
			rel="index.php?module=documentation&amp;view=view&amp;page=help_customer_contact"
			href="#"
			class="cluetip"
			title="{$LANG.customer_contact}"
		>
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="attention" value="{$smarty.post.attention|htmlsafe}" size="25" class="form-control" /></div>
	</div>
	<div class="form-group">
		<label for="street_address" class="col-sm-3 control-label">{$LANG.street}</label>
		<div class="col-sm-6"><input type="text" name="street_address" value="{$smarty.post.street_address|htmlsafe}" size="25"  class="form-control" /></div>
	</div>
	<div class="form-group">
		<label for="street_address2" class="col-sm-3 control-label">{$LANG.street2}
		<a
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_street2"
			title="{$LANG.street2}"
		> 
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="street_address2" value="{$smarty.post.street_address2|htmlsafe}" size="25" class="form-control" /></div>
	</div>
	<div class="form-group">
		<label for="city" class="col-sm-3 control-label">{$LANG.city}</label>
		<div class="col-sm-6"><input type="text" name="city" value="{$smarty.post.city|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="state" class="col-sm-3 control-label">{$LANG.state}</label>
		<div class="col-sm-6"><input type="text" name="state" value="{$smarty.post.state|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="zip_code" class="col-sm-3 control-label">{$LANG.zip}</label>
		<div class="col-sm-6"><input type="text" name="zip_code" value="{$smarty.post.zip_code|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="country" class="col-sm-3 control-label">{$LANG.country}</label>
		<div class="col-sm-6"><input type="text" name="country" value="{$smarty.post.country|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="phone" class="col-sm-3 control-label">{$LANG.phone}</label>
		<div class="col-sm-6"><input type="text" name="phone" value="{$smarty.post.phone|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="mobile_phone" class="col-sm-3 control-label">{$LANG.mobile_phone}</label>
		<div class="col-sm-6"><input type="text" name="mobile_phone" value="{$smarty.post.mobile_phone|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="fax" class="col-sm-3 control-label">{$LANG.fax}</label>
		<div class="col-sm-6"><input type="text" name="fax" value="{$smarty.post.fax|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">{$LANG.email}</label>
		<div class="col-sm-6"><input type="text" name="email" value="{$smarty.post.email|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="credit_card_holder_name" class="col-sm-3 control-label">{$LANG.credit_card_holder_name}</label>
		<div class="col-sm-6">
			<input type="text" name="credit_card_holder_name" value="{$smarty.post.credit_card_holder_name|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="credit_card_number" class="col-sm-3 control-label">{$LANG.credit_card_number}</label>
		<div class="col-sm-6">
			<input type="text" name="credit_card_number" value="{$smarty.post.credit_card_number|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="credit_card_expiry_month" class="col-sm-3 control-label">{$LANG.credit_card_expiry_month}</label>
		<div class="col-sm-6">
			<input type="text" name="credit_card_expiry_month" value="{$smarty.post.credit_card_expiry_month|htmlsafe}" size="5" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="credit_card_expiry_year" class="col-sm-3 control-label">{$LANG.credit_card_expiry_year}</label>
		<div class="col-sm-6">
			<input type="text" name="credit_card_expiry_year" value="{$smarty.post.credit_card_expiry_year|htmlsafe}" size="5"class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="custom_field1" class="col-sm-3 control-label">{$customFieldLabel.customer_cf1|htmlsafe}
 		<a
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
			title="{$LANG.custom_fields}"
		>
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="custom_field1" value="{$smarty.post.custom_field1|htmlsafe}" size="25" class="form-control" /></div>
	</div>
	<div class="form-group">
		<label for="custom_field2" class="col-sm-3 control-label">{$customFieldLabel.customer_cf2|htmlsafe}
		<a
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
			title="{$LANG.custom_fields}"
		> 
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="25" class="form-control"/></div> 
	</div>
	<div class="form-group">
		<label for="custom_field3" class="col-sm-3 control-label">{$customFieldLabel.customer_cf3|htmlsafe}
		<a
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
			title="{$LANG.custom_fields}"
		> 
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="custom_field4" class="col-sm-3 control-label">{$customFieldLabel.customer_cf4|htmlsafe}
		<a
			class="cluetip"
			href="#"
			rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
			title="{$LANG.custom_fields}"
		> 
		<span class="glyphicon glyphicon-question-sign"></span>
		</a>
		</label>
		<div class="col-sm-6"><input type="text" name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="25" class="form-control"/></div>
	</div>
	<div class="form-group">
		<label for="notes" class="col-sm-3 control-label">{$LANG.notes}</label>
		<div class="col-sm-6"><textarea  name="notes" class="editor" rows="8" cols="50">{$smarty.post.notes|outhtml}</textarea></div>
	</div>
	<div class="form-group">
		<label for="enabled" class="col-sm-3 control-label">{$LANG.enabled}</label>
		<div class="col-sm-6">
			{html_options class="form-control" name=enabled options=$enabled selected=1}
		</div>
	</div>
	
	{* 
		{showCustomFields categorieId="2"}
	*}

	<div class="form-group si_toolbar si_toolbar_form">
    	<div class="col-sm-offset-3 col-sm-6">		
<button type="submit" class="btn btn-default positive" name="id" value="{$LANG.save}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button> 
		<a href="./index.php?module=customers&amp;view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a>
	</div>
	</div>

</div>

<input type="hidden" name="op" value="insert_customer" />
</form>
{/if}
