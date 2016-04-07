Ext.define('Esmk.view.User._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.userGrid',
	id:'usergridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'USER',
    store: Ext.create('Esmk.store.User'),
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 40,
                    sortable: false,
                    flex: false,
                },{
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'username',
                    text: 'USERNAME',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'displayname',
                    text: 'DISPLAYNAME',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'email',
                    text: 'EMAIL',
                    flex:true,
                     
                    
                }
                                
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
                            action: 'search',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch();
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-cross-shield',
                            text: 'Freeze',
							handler:function(){
								me.actionDelete();
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
	createForm:function(){
		var form=Ext.create('Esmk.view.User._form');
		form.show();
	},
	actionDbClick: function(dataview, record, item, index, e, options){
        var formMTahunPelajaran = Ext.create('Esmk.view.User._form',{mode:1});

        if (record) {

            formMTahunPelajaran.down('form').loadRecord(record);
			//formMTahunPelajaran.down('form').find('username').disabled=true;

        }    
    },
	actionSearch: function(button) {
        var win = this,
        form = win.down('textfield'),
        grid = win.down('grid'),
        values = form.getSubmitValue();
		
        this.getStore().load({params: {query: values}});
		this.getStore().getProxy().api.read='user/GetUserList/?query='+values;
    },
	actionDelete:function(){
		var me=this;
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				var data=Ext.getCmp('usergridid').getSelectionModel();
				var s = data.getSelection();
				
				var id=[];
				
				Ext.each(s, function(item) {
					id.push(item.get('id'));
				});
				
				var box = Ext.MessageBox.wait('Please wait while I do something or other', 'Performing Actions');
				Ext.Ajax.request({
					method: 'POST', 
					url: site_url + '/user/SoftDelete',
					params: {
						data: id.toString(),
					},
					success: function(response){
						me.getStore().load();
						box.hide();
					},
					scope: me
				});
			}
		});
	}
});


