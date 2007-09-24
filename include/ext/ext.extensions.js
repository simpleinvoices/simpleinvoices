Ext.grid.TableGrid = function(table, config) {
    config = config || {};
    var cf = config.fields || [], ch = config.columns || [];
    table = Ext.get(table);

    var ct = table.insertSibling();

    var fields = [], cols = [];
    var headers = table.query("thead th");
	for (var i = 0, h; h = headers[i]; i++) {
		var text = h.innerHTML;
		var name = 'tcol-'+i;

        fields.push(Ext.applyIf(cf[i] || {}, {
            name: name,
            mapping: 'td:nth('+(i+1)+')/@innerHTML'
        }));

		cols.push(Ext.applyIf(ch[i] || {}, {
			'header': text,
			'dataIndex': name,
			'width': h.offsetWidth,
			'tooltip': h.title,
            'sortable': true
        }));
	}

    var ds  = new Ext.data.Store({
        reader: new Ext.data.XmlReader({
            record:'tbody tr'
        }, fields)
    });

	ds.loadData(table.dom);

    var cm = new Ext.grid.ColumnModel(cols);

    if(config.width || config.height){
        ct.setSize(config.width || 'auto', config.height || 'auto');
    }
    if(config.remove !== false){
        table.remove();
    }

    Ext.grid.TableGrid.superclass.constructor.call(this, ct,
        Ext.applyIf(config, {
            'ds': ds,
            'cm': cm,
            'sm': new Ext.grid.RowSelectionModel(),
            autoHeight:true,
            autoWidth:true
        }
    ));
};

Ext.extend(Ext.grid.TableGrid, Ext.grid.Grid);