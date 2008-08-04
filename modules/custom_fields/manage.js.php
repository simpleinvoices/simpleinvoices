<script type="text/javascript">
{literal}

			$('#flex2').flexigrid
			(
			{
			url: 'index.php?module=custom_fields&view=xml',
			dataType: 'xml',
			colModel : [
				{display: 'ISO', name : 'id', width : 40, sortable : true, align: 'center'},
				{display: 'Name', name : 'biller_id', width : 180, sortable : true, align: 'left'},
				{display: 'Printable Name', name : 'customer_id', width : 120, sortable : true, align: 'left'}
				],
			searchitems : [
				{display: 'ISO', name : 'id'},
				{display: 'Name', name : 'name', isdefault: true}
				],
			sortname: 'id',
			sortorder: 'asc',
			usepager: true,
			title: 'Countries',
			useRp: true,
			rp: 15,
			showTableToggleBtn: true,
			width: 700,
			height: 200
			}
			);
{/literal}
</script>
