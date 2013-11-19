{*
n Script: details.tpl
* 	Biller details template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above
*}
<form name="frmpost" action="index.php?module=billers&amp;view=save&amp;id={$smarty.get.id}" method="post" class="form-horizontal" id="frmpost" onsubmit="return checkForm(this);">
<h1 class="title">
	<a href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a> <span>/</span>
	
	{ if $subPageActive == "biller_view"}
		 {$LANG.view} 
		 <a href="./index.php?module=billers&amp;view=details&amp;action=edit&amp;id={$biller.id}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> {$LANG.edit}</a>{/if}

	{ if $subPageActive == "biller_edit"}
		 {$LANG.edit} 
		 <button type="submit" class="btn btn-default positive" name="save_biller" value="{$LANG.save_biller}">
              <span class="glyphicon glyphicon-floppy-disk"></span> {$LANG.save}
         </button>
            <a href="./index.php?module=billers&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
	 {/if}
</h1>

{if $smarty.get.action== 'view' }

<div class="si_form si_form_view table-responsive">
	<table class="table table-striped table-hover">
		<tr>
			<th>{$LANG.biller_name}</th>
			<td>{$biller.name}</td>
		</tr>
		<tr>
			<th>{$LANG.street}</th>
			<td>{$biller.street_address}</td>
		</tr>
		<tr>
			<th>{$LANG.street2}</th>
			<td>{$biller.street_address2}</td>
		</tr>
		<tr>
			<th>{$LANG.city}</th>
			<td>{$biller.city}</td>
		</tr>
		<tr>
			<th>{$LANG.zip}</th>
			<td>{$biller.zip_code}</td>
		</tr>
		<tr>
			<th>{$LANG.state}</th>
			<td>{$biller.state}</td>
		</tr>
		<tr>
			<th>{$LANG.country}</th>
			<td>{$biller.country}</td>
		</tr>
		<tr>
			<th>{$LANG.mobile_phone}</th>
			<td>{$biller.mobile_phone}</td>
		</tr>
		<tr>
			<th>{$LANG.phone}</th>
			<td>{$biller.phone}</td>
		</tr>
		<tr>
			<th>{$LANG.fax}</th>
			<td>{$biller.fax}</td>
		</tr>
		<tr>
			<th>{$LANG.email}</th>
			<td>{$biller.email}</td>
		</tr>
		<tr>
			<th>{$LANG.paypal_business_name}</th>
			<td>{$biller.paypal_business_name}</td>
		</tr>
		<tr>
			<th>{$LANG.paypal_notify_url}</th>
			<td>{$biller.paypal_notify_url}</td>
		</tr>
		<tr>
			<th>{$LANG.paypal_return_url}</th>
			<td>{$biller.paypal_return_url}</td>
		</tr>
		<tr>
			<th>{$LANG.eway_customer_id}</th>
			<td>{$biller.eway_customer_id}</td>
		</tr>
		<tr>
			<th>{$LANG.paymentsgateway_api_id}</th>
			<td>{$biller.paymentsgateway_api_id}</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.biller_cf1}</th>
			<td>{$biller.custom_field1}</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.biller_cf2}</th>
			<td>{$biller.custom_field2}</td>
		</tr>
		<tr>
			<th>{$customFieldLabel.biller_cf3}</th>
			<td>{$biller.custom_field3}</td>
		</tr>
		<tr>
			<th class="details_screen">{$customFieldLabel.biller_cf4}</th>
			<td>{$biller.custom_field4}</td>
		</tr>
		<tr>
			<th>{$LANG.logo_file}</th>
<!--			<td><a href="templates/invoices/logos/{$biller.logo}" target="new">{$biller.logo}</a></td> -->
			<td>
				{if $biller.logo != ''}
					<img src="templates/invoices/logos/{$biller.logo}" alt="{$biller.logo}"><br>{$biller.logo}
				{/if}
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_footer}</th>
			<td>{$biller.footer}</td>
		</tr>
		<tr>
			<th>{$LANG.notes}</th>
			<td>{$biller.notes}</td>
		</tr>
		{*
			{showCustomFields categorieId="1" itemId=$smarty.get.id }
		*}
		<tr>
			<th>{$LANG.enabled}</th>
			<td>{$biller.wording_for_enabled}</td>
		</tr>
	</table>
	<div class="col-sm-offset-1 col-sm-6">
		<a href="./index.php?module=billers&amp;view=details&amp;action=edit&amp;id={$biller.id}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> {$LANG.edit}</a>
    </div>
</div>

{/if}


{* ######################################################################################### *}


{if $smarty.get.action== 'edit' }
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
	      <input type="text" name="name" id="name" class="form-control validate[required]" placeholder="{$LANG.biller_name}" value="{$biller.name|htmlsafe}" size="50" >
	    </div>
	  </div>
<div class="form-group">
		<label for="street_address" class="col-sm-3 control-label">{$LANG.street}</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" name="street_address" value="{$biller.street_address|htmlsafe}" size="50" placeholder="{$LANG.street}" /></div>
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
		<div class="col-sm-6"><input type="text" class="form-control" name="street_address2" value="{$biller.street_address2|htmlsafe}" size="50" placeholder="{$LANG.street2}"/></div>
	</div>
	<div class="form-group">
		<label for="city" class="col-sm-3 control-label">{$LANG.city}</label>
		<div class="col-sm-6"><input type="text" placeholder="{$LANG.city}" class="form-control" name="city" value="{$biller.city|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="state" class="col-sm-3 control-label">{$LANG.state}</label>
		<div class="col-sm-6"><input type="text" placeholder="{$LANG.state}" class="form-control" name="state" value="{$biller.state|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="zip_code" class="col-sm-3 control-label">{$LANG.zip}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.zip}" name="zip_code" value="{$biller.zip_code|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="country" class="col-sm-3 control-label">{$LANG.country}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.country}"name="country" value="{$biller.country|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="phone" class="col-sm-3 control-label">{$LANG.phone}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.phone}"name="phone" value="{$biller.phone|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="mobile_phone" class="col-sm-3 control-label">{$LANG.mobile_phone}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.mobile_phone}" name="mobile_phone" value="{$biller.mobile_phone|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="fax" class="col-sm-3 control-label">{$LANG.fax}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.fax}" name="fax" value="{$biller.fax|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">{$LANG.email}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.email}" name="email" value="{$biller.email|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_business_name" class="col-sm-3 control-label">{$LANG.paypal_business_name}</label>
		<div class="col-sm-6"><input type="text" class="form-control" name="paypal_business_name"  placeholder="{$LANG.paypal_business_name}" value="{$biller.paypal_business_name|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_notify_url" class="col-sm-3 control-label">{$LANG.paypal_notify_url}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.paypal_notify_url}"  name="paypal_notify_url" value="{$biller.paypal_notify_url|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paypal_return_url" class="col-sm-3 control-label">{$LANG.paypal_return_url}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.paypal_return_url}" name="paypal_return_url" value="{$biller.paypal_return_url|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="eway_customer_id" class="col-sm-3 control-label">{$LANG.eway_customer_id}</label>
		<div class="col-sm-6"><input type="text" class="form-control" placeholder="{$LANG.eway_customer_id}"  name="eway_customer_id" value="{$biller.eway_customer_id|htmlsafe}" size="50" /></div>
	</div>
	<div class="form-group">
		<label for="paymentsgateway_api_id" class="col-sm-3 control-label">{$LANG.paymentsgateway_api_id}</label>
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$LANG.paymentsgateway_api_id}" name="paymentsgateway_api_id" value="{$biller.paymentsgateway_api_id|htmlsafe}" size="50" /></div>
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
		<div class="col-sm-6"><input  placeholder="{$customFieldLabel.biller_cf1|htmlsafe} " type="text" class="form-control" name="custom_field1" value="{$biller.custom_field1}" size="50" /></div>
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
		<div class="col-sm-6"><input   placeholder=" {$customFieldLabel.biller_cf2} " type="text" class="form-control" name="custom_field2" value="{$biller.custom_field2|htmlsafe}" size="50" /></div>
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
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder=" {$customFieldLabel.biller_cf3|htmlsafe} "  name="custom_field3" value="{$biller.custom_field3|htmlsafe}" size="50" /></div>
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
		<div class="col-sm-6"><input type="text" class="form-control"  placeholder="{$customFieldLabel.biller_cf4|htmlsafe}  "  name="custom_field4" value="{$biller.custom_field4|htmlsafe}" size="50" /></div>
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
		<div class="col-sm-6"><textarea input type="text" class="editor" name="footer" rows="4" cols="50">{$biller.footer|htmlsafe}</textarea></div>
	</div>
	<div class="form-group">
		<label for="notes" class="col-sm-3 control-label">{$LANG.notes}</label>
		<div class="col-sm-6"><textarea  input type="text" class="editor" name="notes" rows="8" cols="50">{$biller.notes|htmlsafe}</textarea></div>
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
    	            <button type="submit" class="btn btn-default positive" name="save_biller" value="{$LANG.save_biller}">
                <span class="glyphicon glyphicon-floppy-disk"></span> {$LANG.save}
            </button>
            <a href="./index.php?module=billers&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
        </div>
	</div>


  </div>

<input type="hidden" name="op" value="edit_biller">
<input type="hidden" name="categorie" value="1" />
{/if}

</form>
