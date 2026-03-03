
{{-- if customer is updated or saved. --}} 

@if(post('name') != "" && form_submitted() ) 
{{ $refresh_total }}

<br />
<br />
{{ $display_block }} 
<br />
<br />

@else
{{-- if  name was inserted --}} 
	@if(form_submitted()) 
		<div class="validation_alert"><img src="./images/common/important.png" alt="" />
		You must enter a name for the product attribute</div>
		<hr />
	@endif
<form name="frmpost" action="index.php?module=product_attribute&amp;view=add" method="post">

<h3>{{ $LANG['add_product_attribute'] ?? '' }}</h3>

<hr />


<table align="center">
<tr>
	<td class="details_screen">{{ $LANG['name'] ?? '' }}</td>
	<td><input type="text" name="name" value="{{ post('name') }}" size="25" /></td>
</tr>
		<tr>
			<th>{{ $LANG['type'] ?? '' }}</th>
			<td>
                <select name="type_id">
                    @foreach(($types ?? []) as $k => $v)
        				<option value="{{ $v['id'] }}">{{ $LANG[$v['id']] ?? '' }}</option>
                    @endforeach
                </select>
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>
				{html_options class=edit name=enabled options=$enabled selected=1}
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['visible'] ?? '' }}</th>
			<td>
				{html_options class=edit name=visible options=$enabled selected=1}
			</td>
		</tr>
</table>

<hr />
<div style="text-align:center;">
	<input type="submit" name="submit" value="{{ $LANG['insert_product_attribute'] ?? '' }}" />
	<input type="hidden" name="op" value="insert_product_attribute" />
</div>
</form>
	
@endif
