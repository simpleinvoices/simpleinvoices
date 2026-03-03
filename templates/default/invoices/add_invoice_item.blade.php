{{-- /*
* Script: add_invoice_item.tpl
* 	 Add new item to an existing invoice 
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}
@if(form_submitted())
	<meta http-equiv="refresh" content="1;URL=index.php?module=invoices&amp;view=details&amp;id={{ urlencode(post('id')) }}&amp;type={{ urlencode(post('type')) }}" />
	<br /><br />
	<div class="alert alert-success">{{ $LANG['save_invoice_items_success'] ?? '' }};</div>
@else
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['add_invoice_item'] ?? '' }}</h3>
	</div>
	<div id="gmail_loading" class="gmailLoader position-absolute top-0 end-0 m-3" style="display: none;">
		<i class="ti ti-loader spinner me-1"></i> {{ $LANG['loading'] ?? '' }} ...
	</div>
	<form name="add_invoice_item" action="index.php?module=invoices&amp;view=add_invoice_item" method="post">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<td class="details_screen">{{ $LANG['quantity'] ?? '' }}</td>
				<td>
					<input type="text" id="quantity1" name="quantity1" size="5" class="form-control" />
				</td>
			</tr>
			<tr>
				<td class="details_screen">{{ $LANG['product'] ?? '' }}</td>
				<td><input type="text" name="description" class="form-control" />
					@if($products == null )
						<p class="text-muted"><em>{{ $LANG['no_products'] ?? '' }}</em></p>
					@else
						<select name="product1" class="form-select mt-2 product_change" rel="1">
							<option value=""></option>
							@foreach(($products ?? []) as $product)
								<option @if($product['id'] == $defaults->product) selected @endif value="{{ $product['id'] ?? '' }}">{{ $product['description'] ?? '' }}</option>
							@endforeach
						</select>
					@endif
				</td>
			</tr>
			<tr>
				<td class="details_screen">{{ $LANG['unit_price'] ?? '' }}</td>
				<td>
					<input id="unit_price1" name="unit_price1" size="7" value="{{ number_format($invoiceItem['unit_price'] ?? 0, 2) }}" class="form-control" />
				</td>
			</tr>
 
			@if($type == 3)               
			<tr>
				<td class="details_screen" colspan="2">{{ $LANG['description'] ?? '' }}</td>
			</tr>
			<tr>
				<td colspan="2"><textarea input type="text" class="form-control editor" name="description" rows="3" cols="80" wrap="nowrap"></textarea></td>
			</tr>
			@endif
		</table>
	</div>
		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['add_item'] ?? '' }}"><i class="ti ti-plus me-1"></i>{{ $LANG['add_item'] ?? '' }}</button>
			<input type="hidden" name="id" value="{{ get('id') }}" />
			<input type="hidden" name="type" value="{{ get('type') }}" />
			<input type="hidden" name="tax_id" value="{{ get('tax_id') }}" />
		</div>
	</form>
</div>
@endif
