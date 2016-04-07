Ext.define('Esmk.view.Groups._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.GroupsGrid',
	id:'groupsgridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Groups',
    //store: Ext.data.StoreManager.lookup('GroupsStoreID'),
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		var store = Ext.create("Esmk.store.AuthItem");
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
                    text: 'Group Name',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'description',
                    text: 'Group Description',
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
                            action: 'search',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch();
							}
                        },
                        {
                            xtype: 'button',
                            action: 'delete',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete(this);
							}
                        },
                        {
                            xtype: 'button',
                            action: 'create',
                            iconCls: 'icon-add',
                            text: 'Buat Baru',
							handler:function(){
								me.createForm();
							}
                        },
						{
                            xtype: 'button',
                            iconCls: 'icon-base',
                            text: 'Detail',
							handler:function(){
								me.actionDetail();
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
	actionDelete: function(button) {
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				var grid = button.up('grid');
				var records = grid.getSelectionModel().getSelection();
				var store = grid.getStore();
				//var name='';
				Ext.each(records, function(item) {
					//name=item.get('name');
					store.getProxy().api.destroy='Groups/delete/?name='+item.get('name')
					store.remove(item);
				});
				

				grid.getStore().load();
			}
		});
    },
	actionSearch: function(button) {
        var win = this,
        form = win.down('textfield'),
        grid = win.down('grid'),
        values = form.getSubmitValue();
		
        this.getStore().load({params: {q: values}});
		this.getStore().getProxy().api.read='Groups/read/?q='+values;
    },
	createForm:function(){
		var form=Ext.create('Esmk.view.Groups._form');
		form.show();
	},
	actionDetail:function(){
		var data=Ext.getCmp('groupsgridid').getSelectionModel();
		var s = data.getSelection();
		var me=this;
		var name = null,
			description=null;
		
		Ext.each(s, function(item) {
			name=item.get('name');
			description=item.get('description');
		});
		if (name) {
			var formGroups = Ext.create('Esmk.view.Groups.detail',{
				groupName:name
			});
            formGroups.down('form').getForm().setValues({
				name:name,
				description:description
			});
        }  
	},
	actionDbClick: function(dataview, record, item, index, e, options){
        var formGroups = Ext.create('Esmk.view.Groups.detail',{
			groupName:record.get('name')
		});
		
        if (record) {

            formGroups.down('form').loadRecord(record);

        }    
    },
});


