{{-- /*
* Script: add.tpl
* 	 Payment type add template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}
<form name="frmpost" action="index.php?module=payment_types&amp;view=save" method="post">

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">Payment type description
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}">
				<i class="ti ti-asterisk text-danger"></i>
			</a>
			</label>
			<input class="form-control validate[required]" type="text" name="pt_description" size="30" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			<select name="pt_enabled" class="form-select">
				<option value="1" selected>{{ $LANG['enabled'] ?? '' }}</option>
				<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
			</select>
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_types&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="insert_preference" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="insert_payment_type" />
</form>
