{*
* Script: add.tpl
* 	Biller add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}

{* if bill is updated or saved.*}

{if $smarty.post.name != "" && $smarty.post.submit != null } 

	{include file="../templates/default/billers/save.tpl"}

{else}

{* if no biller name was inserted *}
<form class="form-horizontal" name="frmpost" action="index.php?module=billers&amp;view=add" method="post" id="frmpost">
<h1 class="title">
	<a href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a> <span>/</span> {$LANG.add} 
            <button type="submit" class="btn btn-default positive" name="submit" value="{$LANG.insert_biller}">
                <span class="glyphicon glyphicon-ok"></span>
                {$LANG.save}
            </button> 
        
            <a href="./index.php?module=billers&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
</h1>
	<div class="si_form">
	  <div class="form-group">
	    <label for="name" class="col-sm-3 control-label">{$LANG.biller_name} 
		<a 
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
				title="{$LANG.required_field}"
		>
		<span class="glyphicon glyphicon-asterisk"></span>
		</a></label>
	    <div class="col-sm-6">
	      <input type="text" name="name" id="name" class="form-control validate[required]" placeholder="{$LANG.biller_name}" value="{$smarty.post.name|htmlsafe}" size="25" >
	    </div>
	  </div>
<div class="form-group">
		<label for="street_address" class="col-sm-3 control-label">{$LANG.street}</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="street_address" value="{$smarty.post.street_address|htmlsafe}" size="25" placeholder="{$LANG.street}" /></div>
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
		<div class="col-sm-6"><input type="text" class="form-control" name="street_address2" value="{$smarty.post.street_address2|htmlsafe}" size="25" placeholder="{$LANG.street2}"/></div>
	</div>
	<div class="form-group">
		<label for="city" class="col-sm-3 control-label">{$LANG.city}</label>
		<div class="col-sm-6"><input type="text" placeholder="{$LANG.city}" class="form-control" name="city" value="{$smarty.post.city|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="state" class="col-sm-3 control-label">{$LANG.state}</label>
		<div class="col-sm-6"><input type="text" placeholder="{$LANG.state}" class="form-control" name="state" value="{$smarty.post.state|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="zip_code" class="col-sm-3 control-label">{$LANG.zip}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.zip}" name="zip_code" value="{$smarty.post.zip_code|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="country" class="col-sm-3 control-label">{$LANG.country}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.country}"name="country" value="{$smarty.post.country|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="phone" class="col-sm-3 control-label">{$LANG.phone}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.phone}"name="phone" value="{$smarty.post.phone|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="mobile_phone" class="col-sm-3 control-label">{$LANG.mobile_phone}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.mobile_phone}" name="mobile_phone" value="{$smarty.post.mobile_phone|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="fax" class="col-sm-3 control-label">{$LANG.fax}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.fax}" name="fax" value="{$smarty.post.fax|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">{$LANG.email}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.email}" name="email" value="{$smarty.post.email|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_business_name" class="col-sm-3 control-label">{$LANG.paypal_business_name}</label>
		<div class="col-sm-6"><input type="text" class="form-control" name="paypal_business_name"  placeholder="{$LANG.paypal_business_name}" value="{$smarty.post.paypal_business_name|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_notify_url" class="col-sm-3 control-label">{$LANG.paypal_notify_url}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.paypal_notify_url}"  name="paypal_notify_url" value="{$smarty.post.paypal_notify_url|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_return_url" class="col-sm-3 control-label">{$LANG.paypal_return_url}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.paypal_return_url}" name="paypal_return_url" value="{$smarty.post.paypal_return_url|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="eway_customer_id" class="col-sm-3 control-label">{$LANG.eway_customer_id}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.eway_customer_id}"  name="eway_customer_id" value="{$smarty.post.eway_customer_id|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paymentsgateway_api_id" class="col-sm-3 control-label">{$LANG.paymentsgateway_api_id}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.paymentsgateway_api_id}" name="paymentsgateway_api_id" value="{$smarty.post.paymentsgateway_api_id|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="custom_field1" class="col-sm-3 control-label">{$customFieldLabel.biller_cf1|htmlsafe}
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.custom_fields}"
			> 
<span class="glyphicon glyphicon-question-sign"></span></a>
		</label>
		<div class="col-sm-6"><input  placeholder="{$customFieldLabel.biller_cf1|htmlsafe} " type="text" class="form-control" name="custom_field1" value="{$smarty.post.custom_field1}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="custom_field2" class="col-sm-3 control-label">{$customFieldLabel.biller_cf2} 
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.custom_fields}"
			> 
<span class="glyphicon glyphicon-question-sign"></span></a>
		</label>
		<div class="col-sm-6"><input   placeholder=" {$customFieldLabel.biller_cf2} " type="text" class="form-control" name="custom_field2" value="{$smarty.post.custom_field2|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="custom_field3" class="col-sm-3 control-label">{$customFieldLabel.biller_cf3|htmlsafe} 
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.custom_fields}"
			> 
<span class="glyphicon glyphicon-question-sign"></span></a>
		</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder=" {$customFieldLabel.biller_cf3|htmlsafe} "  name="custom_field3" value="{$smarty.post.custom_field3|htmlsafe}" size="25" /></div>
	</div>
	<div class="form-group">
		<label for="custom_field4" class="col-sm-3 control-label">{$customFieldLabel.biller_cf4|htmlsafe} 
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields"
				title="{$LANG.custom_fields}"
			> 
<span class="glyphicon glyphicon-question-sign"></span></a>

		</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$customFieldLabel.biller_cf4|htmlsafe}  "  name="custom_field4" value="{$smarty.post.custom_field4|htmlsafe}" size="25" /></div>
	</div>



	<div class="form-group">
		<label for="logo" class="col-sm-3 control-label">{$LANG.logo_file} 
			<a
				class="cluetip"
				href="#"
				rel="index.php?module=documentation&amp;view=view&amp;page=help_insert_biller_text"
				title="{$LANG.logo_file}"
			> 
<span class="glyphicon glyphicon-question-sign"></span></a>
			</label>
		<div class="col-sm-6">
			{html_options  class=form-control name=logo output=$files values=$files selected=$files[0] }
		</div>
	</div>


	<div class="form-group">
		<label for="footer" class="col-sm-3 control-label">{$LANG.invoice_footer}</label>
		<div class="col-sm-6"><textarea input type="text" class="editor" name="footer" rows="4" cols="50">{$smarty.post.footer|htmlsafe}</textarea></div>
	</div>
	<div class="form-group">
		<label for="notes" class="col-sm-3 control-label">{$LANG.notes}</label>
		<div class="col-sm-6"><textarea  input type="text" class="editor" name="notes" rows="8" cols="50">{$smarty.post.notes|htmlsafe}</textarea></div>
	</div>
	<div class="form-group">
		<label for="enabled" class="col-sm-3 control-label">{$LANG.enabled}</label>
		<div class="col-sm-6">
			{html_options class=form-control name=enabled options=$enabled selected=1}
		</div>
	</div>
	{* 
		{showCustomFields categorieId="1" itemId=""}
	*}

	<div class="form-group si_toolbar si_toolbar_form">
    	<div class="col-sm-offset-3 col-sm-6">		
            <button type="submit" class="btn btn-default positive" name="submit" value="{$LANG.insert_biller}">
                <span class="glyphicon glyphicon-ok"></span>
                {$LANG.save}
            </button>
        
            <a href="./index.php?module=billers&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
        </div>
	</div>

<input type="hidden" name="op" value="insert_biller" />

  </div>
</form>
{/if}
