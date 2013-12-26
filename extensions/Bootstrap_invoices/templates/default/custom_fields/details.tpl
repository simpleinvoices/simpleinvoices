{*
* Script: details.tpl
* 	Custom fields details template
*
* Website:
* 	 http://www.simpleinvoices.org
*
* License:
*	 GPL v3 or above
*}

<form name="frmpost" action="index.php?module=custom_fields&amp;view=save&amp;id={$smarty.get.id|urlencode}" method="POST" onsubmit="return frmpost_Validator(this);" class="form-horizontal">

{if $smarty.get.action == "view" }

<h1 class="title">
	<a href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a> <span>/</span>
		 {$LANG.view} 
		 <a href="./index.php?module=custom_fields&amp;view=details&amp;id={$cf.cf_id|urlencode}&amp;action=edit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a>
</h1>

<div class="si_form si_form_view table-responsive">	
	<table class="table table-striped table-hover">
		<tr>
			<th>{$LANG.id}</th>
			<td>{$cf.cf_id|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.custom_field_db_field_name}</th>
			<td>{$cf.cf_custom_field|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.custom_field}</th>
			<td>{$cf.name|htmlsafe}</td>
		</tr>
		<tr>
			<th>{$LANG.custom_label}</th>
			<td>{$cf.cf_custom_label|htmlsafe}</td>
		</tr>
	</table>
</div>

	<div class="col-sm-offset-1 col-sm-6">
		<a href="./index.php?module=custom_fields&amp;view=details&amp;id={$cf.cf_id|urlencode}&amp;action=edit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> {$LANG.edit}</a>
	</div>

{/if}




{if $smarty.get.action == "edit" }
<h1 class="title">
	<a href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_upper}</a> <span>/</span>
		 {$LANG.edit} 
		 		 <button type="submit" class="btn btn-default positive" name="save_custom_field" value="{$LANG.save}">
              <span class="glyphicon glyphicon-floppy-disk"></span> {$LANG.save}
         </button>
            <a href="./index.php?module=custom_fields&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
</h1>
<div class="si_form">	

        <div class="form-group">
                <label for="cf.cf_id" class="col-sm-3 control-label">{$LANG.id}</label>
				<div class="col-sm-6"><input type="text" name="cf.cf_id|htmlsafe" value="{$cf.cf_id|htmlsafe}" class="form-control" disabled/></div>
		</div>
		<div class="form-group">
                <label for="cf.cf_custom_field" class="col-sm-3 control-label">{$LANG.custom_field_db_field_name}</label>
                <div class="col-sm-6"><input type="text" name="cf.cf_custom_field|htmlsafe" value="{$cf.cf_custom_field|htmlsafe}" class="form-control"  disabled/></div>
        </div>
        <div class="form-group">
                <label for="cf.name" class="col-sm-3 control-label">{$LANG.custom_field}</label>
                <div class="col-sm-6"><input type="text" name="cf.name|htmlsafe" size="25" value="{$cf.name|htmlsafe}" class="form-control" disabled/></div>
        </div>
		<div class="form-group">
			<label for="cf.cf_custom_label" class="col-sm-3 control-label">{$LANG.custom_label}</label>
			<div class="col-sm-6"><input type="text" name="cf_custom_label" size="25" value="{$cf.cf_custom_label|htmlsafe}" class="form-control" /></div>
		</div>

	<div class="si_toolbar si_toolbar_form col-sm-offset-3 col-sm-6">
		 <button type="submit" class="btn btn-default positive" name="save_custom_field" value="{$LANG.save}">
              <span class="glyphicon glyphicon-floppy-disk"></span> {$LANG.save}
         </button>
            <a href="./index.php?module=custom_fields&amp;view=manage" class="btn btn-default negative">
            <span class="glyphicon glyphicon-remove"></span>
                {$LANG.cancel}
            </a>
	</div>
</div>

<input type="hidden" name="op" value="edit_custom_field">
{/if}
</form>
