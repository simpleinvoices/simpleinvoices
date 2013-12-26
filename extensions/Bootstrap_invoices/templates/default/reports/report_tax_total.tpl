<h1 class="title"><a href="index.php?module=reports&amp;view=index">{$LANG.all_reports}</a> <span>/</span> {$LANG.total_taxes}</h1>

<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{$LANG.total_taxes}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="align_center">{$total_taxes|siLocal_number:'2'|default:'-'}</td>
		</tr>
	</tbody>
</table>
</div>
