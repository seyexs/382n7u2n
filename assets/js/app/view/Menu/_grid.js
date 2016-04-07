Ext.define('Esmk.view.Menu._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.menuGrid',
	id:'menugridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Daftar MENU',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.Menu');
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },
                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },
                 
                {
                    dataIndex: 'sort',
                    text: 'SORT',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'parent_id',
                    text: 'PARENT_ID',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'title',
                    text: 'TITLE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'url',
                    text: 'URL',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'bizrule',
                    text: 'BIZRULE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'cssclass',
                    text: 'CSSCLASS',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'last_sync',
                    text: 'LAST_SYNC',
                    flex:true,
                     
                    
                },
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>No data found</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				itemdblclick: function(dataview, index, item, e) {
					me.actionDbClick(dataview, index, item, e);
				}
            },
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        'Pencarian', {
                            xtype: 'textfield',
                            name: 'searchfield',
							listeners:{
								keyup: {
									element: 'el',
									fn: function(event, target){ 
											if(event.keyCode=='13'){
												me.actionSearch(me.down('textfield'));
											}
									}
								}
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch(this);
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete();
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-add',
                            text: 'Buat Baru',
							handler:function(){
								me.actionCreate();
							}
                        },{
							xtype:'button',
							iconCls:'icon-pencil',
							text: 'Ubah',
							handler:function(){
								me.actionUpdateData();
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
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	actionDbClick: function(dataview, record, item, index, e, options){
        var formMenu = Ext.create('Esmk.view.Menu._form');

        if (record) {

            formMenu.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formMenu = Ext.create('Esmk.view.Menu._form');

        if (record) {

            formMenu.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },
	actionDelete: function(button) {
		var grid = Ext.getCmp('menugridid');
		var records = grid.getSelectionModel().getSelection();
		var store = grid.getStore();
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					store.remove(item);
				});

				
			}
		});
        store.load();
    },
    
    actionSearch: function(button) {

        var grid = Ext.getCmp('menugridid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='Menu/read/?q='+values;
    },
});


