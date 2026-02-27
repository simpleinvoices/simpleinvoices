{{-- if tax rate is updated or saved. --}} 

@if($smarty->post->tax_description != "" && $smarty->post->submit != null ) 
{{ $refresh_total }}

<br />
<br />
{{ $display_block }} 
<br />
<br />

@else
{{-- if  name was inserted --}} 
	@if($smarty->post->submit !=null) 
		<div class="alert alert-warning"><i class="ti ti-alert-circle"></i>
		You must enter a Tax description</div>
	@endif


<form name="frmpost" action="index.php?module=tax_rates&amp;view=add" method="POST">

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['add_tax_rate'] ?? $LANG['description'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['description'] ?? '' }}</label>
			<input type="text" class="form-control validate[required]" name="tax_description" value="{{ $smarty->post->tax_description ?? '' }}" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['rate'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign" title="{{ $LANG['tax_rate'] ?? '' }}">
				<i class="ti ti-help"></i>
			</a>
			</label>
			<div class="row g-2">
				<div class="col-auto">
					<input type="text" name="tax_percentage" value="{{ $smarty->post->tax_percentage ?? '' }}" class="form-control" />
				</div>
				<div class="col-auto">
					{html_options name=type options=$types selected=$tax['type']}
				</div>
			</div>
			<div class="form-text">{{ $LANG['ie_10_for_10'] ?? '' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			<select name="tax_enabled" class="form-select" value="{{ $smarty->post->tax_enabled ?? '' }}">
				<option value="1" selected>{{ $LANG['enabled'] ?? '' }}</option>
				<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
			</select>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['insert_tax_rate'] ?? '' }}">
			<i class="ti ti-check"></i>
			{{ $LANG['save'] ?? '' }}
		</button>
		<a href="./index.php?module=tax_rates&view=manage" class="btn btn-outline-secondary">
			<i class="ti ti-x"></i>
			{{ $LANG['cancel'] ?? '' }}
		</a>
	</div>
</div>

<input type="hidden" name="op" value="insert_tax_rate" />
</form>
@endif
