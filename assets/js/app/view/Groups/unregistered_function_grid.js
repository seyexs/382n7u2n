Ext.define('Esmk.view.Groups.unregistered_function_grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.unregisteredfunctionnGrid',
	id:'unregisteredfunctionngridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Available Functions',
    store: null,
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
	//autoScroll: true,
    initComponent: function() {
        var me = this;
		var store = Ext.create("Esmk.store.FunctionRegistration");
		this.store=store;
		//this.store = store;
		//this.callParent(arguments); 
        Ext.applyIf(me, {
            columns: [{
					xtype: 'rownumberer',
					width: 50,
					sortable: false,
					flex: false,
			},{
					dataIndex: 'name',
					text: 'Function',
					flex:true,										
			}],
            viewConfig: {
                emptyText: '<h1><b>Data Tidak Ditemukan</b></h1>'
            },
			/*features:[{
				ftype:'grouping',
				//tpl: Ext.create('Ext.XTemplate','{kode} ({children.length})'),
				//groupHeaderTpl:'{kode}{id}{status}',
				enableNoGroups:true
			}],*/
            listeners: {
                viewready: function() {
					this.getStore().getProxy().api.read='functionRegistration/GetUnRegisteredFunctionForGroup/?groupname='+me.groupName;
					this.getStore().pageSize=15;
					this.getStore().load({
						//limit:10,
						//url:'functionRegistration/GetUnRegisteredFunctionForGroup/?groupname='+me.groupName,
					});
                },
				itemdblclick: function(dataview, index, item, e) {
					//me.actionDbClick(dataview, index, item, e);
				}
            },
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            action: 'create',
                            iconCls: 'icon-add',
                            text: 'Apply to Group',
							handler:function(){
								me.actionRegister();
							}
                        }
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: this.store,
                }
            ]

        });

        me.callParent(arguments);
    },
	actionRegister:function(){
		var data=Ext.getCmp('unregisteredfunctionngridid').getSelectionModel();
		var s = data.getSelection();
		var me=this;
		var name=[];
		
		Ext.each(s, function(item) {
			
			name.push(item.get('name'));
		});
		var box = Ext.MessageBox.wait('Please wait while I do something or other', 'Performing Actions');
		Ext.Ajax.request({
			method: 'POST', 
            url: site_url + '/groups/ApplyFunction',
            params: {
                data: name.toString(),
				groupname:me.groupName
            },
            success: function(response){
                me.getStore().load();
				box.hide();
            },
            scope: me
        });
	}
});


