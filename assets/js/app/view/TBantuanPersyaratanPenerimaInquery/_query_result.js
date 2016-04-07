Ext.define('Esmk.view.TBantuanPersyaratanPenerimaInquery._query_result', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpersyaratanpenerimainqueryresult',
	id:'tbantuanpersyaratanpenerimainqueryresult',
    requires: [
		'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Data Query',
    layout: 'fit',
	iconCls:'icon-grid',
    autoShow: true,
    width: 900,
    autoHeight:true,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var me=this;
        this.items=[{
					xtype:'grid',
					height: 500,
					width: 700,
					id:'querydatagridid',
					store: me.store,
					viewConfig: { emptyText: 'Data Kosong' },
					columns:me.GridColumn,
					dockedItems:{
						xtype: 'pagingtoolbar',
						dock: 'bottom',
						displayInfo: true,
						emptyMsg: 'Data Kosong',
						store: me.store,
						plugins: new Ext.ux.ProgressBarPager(),
					},
					listeners:{
						viewready:function(){
							
						}
					}
						
				   }];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
                        iconCls: 'icon-reset',
                        text: 'Tutup',
                        action: 'cancel',
						handler:function(button, e, options){
							me.actionCancel(button, e, options);
						}
                    },]
            }];

        this.callParent(arguments);
		

    },


    actionCancel: function(button, e, options) {

        var win = button.up('window');
        win.close();

    },
	
});


