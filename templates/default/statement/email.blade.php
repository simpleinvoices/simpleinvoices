{{-- /*
* Script: email.tpl
* 	 Send invoice via email page template
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
@if(get('stage') == 1 )

<form name="frmpost" action="index.php?module=statement&amp;view=email&amp;stage=2&amp;biller_id={{ urlencode(get('biller_id')) }}&amp;customer_id={{ urlencode(get('customer_id')) }}&amp;start_date={{ urlencode(get('start_date')) }}&amp;end_date={{ urlencode(get('end_date')) }}&amp;show_only_unpaid={{ urlencode(get('show_only_unpaid')) }}&amp;format=file" method="post">
<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<td class="details_screen">{{ $LANG['email_from'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from" title="{{ $LANG['email_from'] ?? '' }}"><i class="ti ti-help"></i></a>
			</td>
			<td><input type="text" name="email_from" size="50" value="{{ $biller['email'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['email_to'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to" title="{{ $LANG['email_to'] ?? '' }}"><i class="ti ti-help"></i></a>
			</td>
			<td><input type="text" name="email_to" size="50" value="{{ $customer['email'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
		<td class="details_screen">{{ $LANG['email_bcc'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc" title="{{ $LANG['email_bcc'] ?? '' }}"><i class="ti ti-help"></i></a>
			</td>
		<td><input type="text" name="email_bcc" size="50" value="{{ $biller['email'] ?? '' }}" class="form-control" /></td>
		</tr>
		<tr>
		<td class="details_screen">{{ $LANG['subject'] ?? '' }}</td>
		<td><input type="text" name="email_subject" size="50" value="Statement of invoices from {{ $biller['name'] ?? '' }} is attached" class="form-control" /></td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['message'] ?? '' }}</td>
			<td><textarea name="email_notes" class="form-control editor" rows="8" cols="50"></textarea></td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=statement&amp;view=index" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<input type="hidden" name="op" value="insert_customer" />
			<button type="submit" class="btn btn-primary ms-auto invoice_save" name="submit" value="{{ $LANG['email'] ?? '' }}"><i class="ti ti-mail me-1"></i>{{ $LANG['email'] ?? '' }}</button>
		</div>
	</div>
</div>
</form>
@endif

@if(get('stage') == 2)

<div class="alert alert-success">
	{!! outhtml($message) !!}
</div>

@endif
