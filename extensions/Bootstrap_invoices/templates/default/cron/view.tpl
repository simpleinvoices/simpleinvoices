<form name="frmpost" action="index.php?module=cron&view=edit&id={$cron.id|urlencode}" method="POST" id="frmpost" class="form-horizontal">
	<h1 class="title"><a href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a> <span>/</span> {$LANG.view} 
						<a href="./index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="btn btn-default">
					<span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a></h1>
<div class="table-responsive">	
<table class="table table-striped table-hover">
    <tr>
        <td class="details_screen">{$LANG.invoice}</td>
        <td>
                {$cron.index_name|htmlsafe}
        </td>
    </tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.start_date}</td>
            <td>
                {$cron.start_date|htmlsafe}    
            </td>
    </tr>
    <tr wrap="nowrap">
            <td class="details_screen">{$LANG.end_date}</td>
            <td >
                {$cron.end_date|htmlsafe}   
            </td>
    </tr>
    <tr>
        <td class="details_screen">{$LANG.recur_each}</td>
        <td>
        {$cron.recurrence|htmlsafe} {$cron.recurrence_type|htmlsafe}
         </td>
     </tr>
    <tr>
        <td class="details_screen">{$LANG.email_biller_after_cron}</td>
        <td>
             {if $cron.email_biller == '1'}{$LANG.yes}{/if}</option>
             {if $cron.email_biller == '0'}{$LANG.no}{/if}</option>
         </td>
     </tr>
    <tr>
        <td class="details_screen">{$LANG.email_customer_after_cron}</td>
        <td>
             {if $cron.email_customer == '1'}{$LANG.yes}{/if}</option>
             {if $cron.email_customer == '0'}{$LANG.no}{/if}</option>
         </td>
     </tr>


</table>
	<div class="form-group si_toolbar si_toolbar_form">
    	<div class="col-sm-offset-1 col-sm-6">
						<a href="./index.php?module=cron&amp;view=edit&amp;id={$cron.id|urlencode}" class="btn btn-default">
					<span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a>
	</div>

	</div>
</div>
</form>


