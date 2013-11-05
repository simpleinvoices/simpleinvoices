{if $saved == 'true' }
	<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
	<h1 class="title"><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a> <span>/</span> {$LANG.save}</h1>
	 <div class="si_message_ok"><span class="glyphicon glyphicon-floppy-saved"></span> {$LANG.save_cron_success}</div>
{/if}
{if $saved == 'false' }
	<meta http-equiv="refresh" content="2;URL=index.php?module=cron&amp;view=manage" />
	<h1 class="title"><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a> <span>/</span> {$LANG.save}</h1>
	<div class="si_message_error"><span class="glyphicon glyphicon-floppy-remove"></span>  {$LANG.save_cron_failure}</div>
{/if}

{if $saved ==false}

	{if $smarty.post.op == 'add' AND $smarty.post.invoice_id == ''} 
		<div class="validation_alert si_message_warning"><span class="glyphicon glyphicon-exclamation-sign"></span>
		You must select an invoice</div>
		<hr />
	{/if}


<form name="frmpost" action="index.php?module=cron&view=add" method="POST" id="frmpost" class="form-horizontal">
	<h1 class="title"><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a> <span>/</span>  {$LANG.add}
		<button type="submit" class="btn btn-default positive" name="id" value="{$LANG.save}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button> 
		<a href="./index.php?module=cron&view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a></h1>
<div class="si_form" id="si_form_cron">
	<div class="form-group">
		<label for="invoice_id" class="col-sm-3 control-label">{$LANG.invoice}</label>
		<div class="col-sm-6">
		<select class="form-control" name="invoice_id" class="validate[required]">
		    <option value=''></option>
			{foreach from=$invoice_all item=invoice}
				<option value="{$invoice.id|htmlsafe}">{$invoice.index_name|htmlsafe} ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe}, {$invoice.invoice_total|siLocal_number})</option>
			{/foreach}
		</select>
		</div>
	</div>
    <div class="form-group" wrap="nowrap">
            <label for="start_date" class="col-sm-3 control-label">{$LANG.start_date}</label>
            <div class="col-sm-6" wrap="nowrap">
                <input type="text" class="form-control validate[required,custom[date],length[0,10]] date-picker" size="10" name="start_date" id="date" value='{"+1 days"|date_format:"%Y-%m-%d"}' />   
            </div>
    </div>
    <div class="form-group" wrap="nowrap">
            <label for="end_date" class="col-sm-3 control-label">{$LANG.end_date}</label>
            <div class="col-sm-6" wrap="nowrap">
                <input type="text" class="form-control date-picker" size="10" name="end_date" id="date" value='' />   
            </div>
    </div>
	<div class="form-group">
		<label for="recurrence" class="col-sm-3 control-label">{$LANG.recur_each}</label>
		<div>
			<div class="col-sm-3">
		<input name="recurrence" size="10" class="form-control validate[required]"></input>
			</div>
			<div class="col-sm-3">
             <select name="recurrence_type" class="form-control validate[required]">
             <option value="day">{$LANG.days}</option>
             <option value="week">{$LANG.weeks}</option>
             <option value="month">{$LANG.months}</option>
             <option value="year">{$LANG.years}</option>
             </select>
         </div>
         </div>
    </div>
	<div class="form-group">
		<label for="email_biller" class="col-sm-3 control-label">{$LANG.email_biller_after_cron}</label>
		<div class="col-sm-6">
             <select name="email_biller" class="form-control validate[required]">
             <option value="1">{$LANG.yes}</option>
             <option value="0">{$LANG.no}</option>
             </select>
  	   </div>
     </div>
	<div class="form-group">
		<label for="email_customer" class="col-sm-3 control-label">{$LANG.email_customer_after_cron}</label>
		<div class="col-sm-6">
             <select name="email_customer" class="form-control validate[required]">
            	 <option value="1">{$LANG.yes}</option>
            	 <option value="0">{$LANG.no}</option>
             </select>
         </div>
     </div>
	</div>

	<div class="form-group si_toolbar si_toolbar_form">
    	<div class="col-sm-offset-3 col-sm-6">
		<button type="submit" class="btn btn-default positive" name="id" value="{$LANG.save}"> <span class="glyphicon glyphicon-ok"></span> {$LANG.save}</button>
		<a href="./index.php?module=cron&view=manage" class="btn btn-default negative"> <span class="glyphicon glyphicon-remove"></span> {$LANG.cancel}</a>
	</div>
	</div>

<input type="hidden" name="op" value="add" />
</form>
{/if}
