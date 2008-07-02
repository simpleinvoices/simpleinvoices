<script>

{*  
This Script has to be inlined so that smarty renders it.

Assign Smarty vars to JS vars before we switch to literal/JS mode.  We'll be passing
these strings to String.format on each row, which also delimits with curly braces, so we need to use
{ldelim} and {rdelim}.
*}

{literal}

var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";

Ext.onReady(function(){

	Ext.QuickTips.init();

	//Create a clickable button for the Manage pages - toolbar
	Ext.LinkButton = Ext.extend(Ext.Button,
	{
		template: new Ext.Template(
		'<table border="0" cellpadding="0" cellspacing="0" class="x-btn-wrap"><tbody><tr>',
		'<td class="x-btn-left"><i> </i></td><td class="x-btn-center"><a class="x-btn-text" href="{1}" target="{2}">{0}</a></td><td class="x-btn-right"><i> </i></td>',
		"</tr></tbody></table>"),

		onRender: function(ct, position)
		{
			var btn, targs = [this.text || ' ', this.href, this.target || "_self"];

			if(position){
				btn = this.template.insertBefore(position, targs, true);
			}else{
				btn = this.template.append(ct, targs, true);
			}

			var btnEl = btn.child("a:first");
			btnEl.on('focus', this.onFocus, this);
			btnEl.on('blur', this.onBlur, this);

			this.initButtonEl(btn, btnEl);
			btn.un(this.clickEvent, this.onClick, this);
			Ext.ButtonToggleMgr.register(this);
		}
	});

	// create the Data Store
	var ds = new Ext.data.GroupingStore({
		// load using HTTP
		//url: 'sheldon2.xml',
		url: 'index.php?module=products&view=xml',
		// the return will be XML, so lets set up a reader
		reader: new Ext.data.XmlReader({
			// records will have an "Item" tag
			record: 'tablerow',
			id: 'id',
			type_id: 'type_id',
			totalRecords: 'total'
		}, [
		// set up the fields mapping into the xml doc
		// The first needs mapping, the others are very basic
		'actions',
		{name: 'id', mapping: 'id'},
		'description','unit_price','enabled'
		]),
		// turn on defautl grouping by Aging field
		//groupField: 'Aging',
		// turn on remote sorting
		remoteSort: true
	});

	ds.setDefaultSort('name', 'asc');
	// pluggable renders
	function renderActions(value, p, record ){
		
		var viewLink = String.format(
		'<!--0 Quick View --><a class="index_table" title="'+  +''+ view_tooltip +'"  href="index.php?module=products&view=details&id={1}&action=view"> <img src="images/common/view.png" height="16" border="-5px" padding="-4px" valign="bottom" /></a>',
		value,
		record.id,
		record.data.type_id,
		record.data.forumid);

		var editLink = String.format(
		'<!--1 Edit View --><a class="index_table" title="'+  +''+ edit_tooltip +'"  href="index.php?module=products&view=details&id={1}&action=edit"><img src="images/common/edit.png" height="16" border="-5px" padding="-4px" valign="bottom" /><!-- print --></a>',
		value,
		record.id,
		record.data.type_id,
		record.data.forumid);


		//Return a nice big link for the Actions column in the Manage Invoices page
		return viewLink + editLink ;
		
	}


	var cm = new Ext.grid.ColumnModel([
	{header: "Actions", width: 105, dataIndex: 'actions', sortable:false, renderer: renderActions },
	{header: "ID", width: 50, dataIndex: 'id'},
	{header: "Description", width: 180, dataIndex: 'description'},
	{header: "Unit Price", width: 115, dataIndex: 'unit_price'},
	{header: "Enabled", width: 100, dataIndex: 'enabled'}
	]);
	cm.defaultSortable = true;

	// create the grid
	var grid = new Ext.grid.GridPanel({
		ds: ds,
		cm: cm,
		title:'Manage Products',
		renderTo:'manageProductsGrid',
		autoHeight: true,
		viewConfig: {
			forceFit:true
		},
		view: new Ext.grid.GroupingView({
			forceFit:true,
			groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
		}),


		bbar: new Ext.PagingToolbar({
			pageSize: 25,
			store: ds,
			displayInfo: true,
			displayMsg: 'Displaying products {0} - {1} of {2}'
		}),
		layout:'fit'
	});

	function onButtonClick(btn){
		Ext.example.msg('Button Click','You clicked the "{0}" button.', btn.text);
	}

	ds.load({params:{start:0, limit:25}});

});
{/literal}

</script>
