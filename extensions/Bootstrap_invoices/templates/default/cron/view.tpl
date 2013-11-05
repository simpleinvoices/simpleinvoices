<form name="frmpost" action="index.php?module=cron&view=edit&id={$cron.id|urlencode}" method="POST" id="frmpost" class="form-horizontal">
	<h1 class="title"><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a> <span>/</span> {$LANG.view} 
						<a href="./index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="btn btn-default">
					<span class="glyphicon glyphicon-edit"></span> {$LANG.edit}</a></h1>
	
<div class="form-group">
		<label for="invoice_id" class="col-sm-3 control-label details_screen">{$LANG.invoice}</label>
		<div class="col-sm-6">
		<select class="form-control" name="invoice_id" class="validate[required]" disabled >
				<option value="{$invoice.id|htmlsafe}" selected>{$cron.index_name|htmlsafe}</option>
		</select>
		</div>
	</div>
    <div class="form-group" wrap="nowrap">
            <label for="start_date" class="col-sm-3 control-label details_screen">{$LANG.start_date}</label>
            <div class="col-sm-6" wrap="nowrap">
                <input type="text" class="form-control" size="10" name="start_date" id="date" value='{$cron.start_date|htmlsafe}' disabled />   
            </div>
    </div>
    <div class="form-group" wrap="nowrap">
            <label for="end_date" class="col-sm-3 control-label details_screen">{$LANG.end_date}</label>
            <div class="col-sm-6" wrap="nowrap">
                <input type="text" class="form-control" size="10" name="end_date" id="date" value='{$cron.end_date|htmlsafe}' disabled />   
            </div>
    </div>
	<div class="form-group">
		<label for="recurrence" class="col-sm-3 control-label details_screen">{$LANG.recur_each}</label>
		<div>
		<div class="col-sm-3">
		<input name="recurrence" size="10" class="form-control validate[required]" value='{$cron.recurrence|htmlsafe}' disabled />
		</div>
		<div class="col-sm-3">
         <select name="recurrence_type" class="form-control validate[required]" disabled>
             <option value="day"  {if $cron.recurrence_type == 'day'}selected{/if}  >{$LANG.days}</option>
             <option value="week" {if $cron.recurrence_type == 'week'}selected{/if} >{$LANG.weeks}</option>
             <option value="month" {if $cron.recurrence_type == 'month'}selected{/if} >{$LANG.months}</option>
             <option value="year" {if $cron.recurrence_type == 'year'}selected{/if} >{$LANG.years}</option>
             </select>
         </div>
         </div>
     </div>
	<div class="form-group">
		<label for="email_biller" class="col-sm-3 control-label details_screen">{$LANG.email_biller_after_cron}</label>
		<div class="col-sm-6">
             <select name="email_biller" class="form-control validate[required]" disabled >
             <option value="1" {if $cron.email_biller == '1'}selected{/if}>{$LANG.yes}</option>
             <option value="0" {if $cron.email_biller == '0'}selected{/if}>{$LANG.no}</option>
             </select>
         </div>
     </div>
	<div class="form-group">
		<label for="email_customer" class="col-sm-3 control-label details_screen">{$LANG.email_customer_after_cron}</label>
		<div class="col-sm-6">
             <select name="email_customer" class="form-control validate[required]" disabled >
             <option value="1" {if $cron.email_customer == '1'}selected{/if}>{$LANG.yes}</option>
             <option value="0" {if $cron.email_customer == '0'}selected{/if}>{$LANG.no}</option>
             </select>
         </div>
     </div>
	<div class="form-group si_toolbar si_toolbar_form">
    	<div class="col-sm-offset-3 col-sm-6">
						<a href="./index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="btn btn-default">
					<span class="glyphicon glyphicon-edit"></span> {$LANG.edit}</a>
	</div>

	</div>
	</div>

</form>


