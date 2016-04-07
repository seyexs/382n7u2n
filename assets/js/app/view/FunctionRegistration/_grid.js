Ext.define('Esmk.view.FunctionRegistration._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.functionregistrationGrid',
	id:'functionregistrationGridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Function Registration',
    //store: Ext.data.StoreManager.lookup('MTahunPelajaranStoreID'),
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		var store = Ext.create("Esmk.store.FunctionRegistration");
		this.store = store;
		//this.callParent(arguments); 
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },{
                    dataIndex: 'name',
                    text: 'Function',
                    flex:true,
                     
                    
                }
                                
            ],
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
					this.getStore.pageSize=100;
					this.store.load();
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
                            text: 'Register',
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
		var data=Ext.getCmp('functionregistrationGridid').getSelectionModel();
		var s = data.getSelection();
		var me=this;
		var name=[];
		
		Ext.each(s, function(item) {
			
			name.push(item.get('name'));
		});
		var box = Ext.MessageBox.wait('Please wait while I do something or other', 'Performing Actions');
		Ext.Ajax.request({
			method: 'POST', 
            url: site_url + '/functionregistration/QuickRegister',
            params: {
                data: name.toString()
            },
            success: function(response){
                me.getStore().load();
				box.hide();
            },
            scope: me
        });
	}
	
});


