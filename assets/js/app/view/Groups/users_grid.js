Ext.define('Esmk.view.Groups.users_grid', {
    extend: 'Ext.container.Container',
    
    requires: [
        'Ext.grid.*',
        'Ext.layout.container.HBox',
		//'Esmk.model.User'
    ],
	autoWidth:true,
	
    //width: 650,
    //height: 300,
    layout: {
        type: 'hbox',
        align: 'stretch',
        padding: 5
    },   
    initComponent: function(){
		var me=this;
        var group1 = this.id + 'group1',
            group2 = this.id + 'group2',
            columns = [{dataIndex:'id',hidden:true},{name: 'displayname',dataIndex:'displayname', type: 'string',flex:true}];
		
        var storeUserIn = Ext.create("Esmk.store.User");
		var storeUserOut = Ext.create("Esmk.store.User");		
        this.items = [{
            itemId: 'griduserin',
            flex: 1,
            xtype: 'grid',
            multiSelect: true,
			viewConfig: {
				plugins: {
					ptype: 'gridviewdragdrop',
					dragText: 'Keluarkan Dari group '+this.groupName,
					dropText:'Masukkan Ke group '+this.groupName,
					dragGroup: group1,
					dropGroup: group2
				},
				listeners: {
					drop: function(node, data, dropRec, dropPosition) {
						//alert('di grid in '+data.records[0].get('displayname'));
						me.actionAddUserToGroup(data.records);
							//var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
							//alert('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
							//Ext.example.msg('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
					},
					viewready: function() {
						this.getStore().getProxy().api.read='Groups/GetUsersInsideGroup/?groupname='+me.groupName;
						this.getStore().getProxy().api.destroy='Groups/GetUsersInsideGroup/?groupname='+me.groupName;
						this.getStore().pageSize=20;
						this.getStore().load();
					},
				}
            },
            store:storeUserIn,
            columns: columns,
            stripeRows: true,
            title: 'Inside The Group',
            tools: [{
                type: 'refresh',
                tooltip: 'Reset both grids',
                scope: this,
                handler: this.onResetClick
            }],
            margins: '0 5 0 0',
			dockedItems:[{
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
							handler:this.actionSearchUserIn
                        }
                    ]
                },{
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: storeUserIn,
            }]
        }, {
            itemId: 'griduserout',
            flex: 1,
            xtype: 'grid',
			multiSelect: true,
            viewConfig: {
                plugins: {
                    ptype: 'gridviewdragdrop',
					dragText: 'Masukkan Ke group '+this.groupName,
					dropText:'Kelurkan Dari group '+this.groupName,
                    dragGroup: group2,
                    dropGroup: group1
                },
                listeners: {
                    drop: function(node, data, dropRec, dropPosition) {
                        //var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
						//alert('Drag from left to right', 'Dropped ' + data.records[0].get('name') + dropOn);
                        //Ext.example.msg('Drag from left to right', 'Dropped ' + data.records[0].get('name') + dropOn);
						me.actionRemoveUserFromGroup(data.records);
						//alert( data.records[0].get('name'));
                    },
					viewready: function() {
						this.getStore().getProxy().api.read='Groups/GetUsersOutOfGroup/?groupname='+me.groupName;
						this.getStore().getProxy().api.destroy='Groups/GetUsersOutOfGroup/?groupname='+me.groupName;
						this.getStore().pageSize=20;
						this.getStore().load();
					},
                }
            },
            store:storeUserOut,
            columns: columns,
            stripeRows: true,
            title: 'Outside The Group',
			dockedItems:[{
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
								me.actionSearchUserOut();
							}
                        }
                    ]
                },{
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: storeUserOut,
            }]
        }];

        this.callParent();
    },
    actionSearchUserIn:function(){
		var win = this,
        form = this.down('#griduserin').down('textfield'),
        grid = this.down('#griduserin').down('grid'),
        values = form.getSubmitValue();

        this.down('#griduserin').getStore().load({params: {q: values}});
	},
	actionSearchUserOut:function(){
		var win = this,
        form = this.down('#griduserout').down('textfield'),
        grid = this.down('#griduserout').down('grid'),
        values = form.getSubmitValue();
		
        this.down('#griduserout').getStore().load({params: {q: values}});
	},
    onResetClick: function(){
        //refresh source grid
        this.down('#griduserin').getStore().reload();

        //purge destination grid
        this.down('#griduserout').getStore().reload();
    },
	actionAddUserToGroup:function(records){
		var id=[];
		
		Ext.each(records, function(item) {
			
			id.push(item.get('id'));
		});
		var box = Ext.MessageBox.wait('Please wait while I do something or other', 'Performing Actions');
		Ext.Ajax.request({
			method: 'POST', 
            url: site_url + '/Groups/AddUserToGroup',
            params: {
                data: id.toString(),
				groupname:this.groupName
            },
            success: function(response){
                this.onResetClick();
				box.hide();
            },
            scope: this
        });
	},
	actionRemoveUserFromGroup:function(records){
		var id=[];
		
		Ext.each(records, function(item) {
			
			id.push(item.get('id'));
		});
		var box = Ext.MessageBox.wait('Please wait while I do something or other', 'Performing Actions');
		Ext.Ajax.request({
			method: 'POST', 
            url: site_url + '/Groups/RemoveUserFromGroup',
            params: {
                data: id.toString(),
				groupname:this.groupName
            },
            success: function(response){
                this.onResetClick();
				box.hide();
            },
            scope: this
        });
	}
});
