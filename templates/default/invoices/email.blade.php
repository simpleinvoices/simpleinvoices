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

<div class="si_center">
<h3>Email {{ $invoice['index_name'] ?? '' }} to Customer as PDF</h3>
</div>

<form name="frmpost" action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={{ urlencode(get('id')) }}" method="post">

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['email_from'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from" title="{{ $LANG['email_from'] ?? '' }}"><i class="ti ti-help"></i></a>
				</th>
				<td><input type="text" name="email_from" size="50" value="{{ $biller['email'] ?? '' }}" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['email_to'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to" title="{{ $LANG['email_to'] ?? '' }}"><i class="ti ti-help"></i></a>
				</th>
				<td><input type="text" name="email_to" size="50" value="{{ $customer['email'] ?? '' }}" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['email_bcc'] ?? '' }}
					<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc" title="{{ $LANG['email_bcc'] ?? '' }}"><i class="ti ti-help"></i></a>
				</th>
				<td><input type="text" name="email_bcc" size="50" value="{{ $biller['email'] ?? '' }}" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['subject'] ?? '' }}</th>
				<td><input type="text" name="email_subject" size="70" value="{{ $invoice['index_name'] ?? '' }} from {{ $biller['name'] ?? '' }} is attached" class="form-control" /></td>
			</tr>
			<tr>
				<th>{{ $LANG['message'] ?? '' }}</th>
				<td><textarea name="email_notes" class="form-control editor" rows="16" cols="70"></textarea></td>
			</tr>
		</table>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['email'] ?? '' }}"><i class="ti ti-mail me-1"></i>{{ $LANG['email'] ?? '' }}</button>
	</div>
</div>

<input type="hidden" name="op" value="insert_customer" />
</form>
@endif




@if(get('stage') == 2)
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage" />

<div class="si_message">
	{!! outhtml($message) !!}
</div>


@endif
