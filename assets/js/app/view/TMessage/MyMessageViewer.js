Ext.define('Esmk.view.TMessage.MyMessageViewer', {
    extend: 'Ext.panel.Panel',
    requires: [
		'Ext.ux.ProgressBarPager',
    ],
    selectedSendId: null,
    selectedReceiveId: null,
    
    initComponent: function(){
        var me = this;
        

        Ext.apply(this, {
            padding: 5,
            layout: 'fit',
			//autoLoad:true,
            items: [{
                xtype:'tabpanel',
				//layout: 'fit',
				//autoLoad:true,
                id:'mymessage-tabpanel',
                border:0,
                items:[
                this.createGridPanelReceive(),
                this.createGridPanelSend(),
                ]
            }
            ]
        });

        this.callParent(arguments);
    },
    
    createGridPanelReceive: function(){
        var me = this;

        //Model merepresentasikan beberapa object yang akan diatur
        Ext.define('Message', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'message_id',   
                type: 'int'
            },
            {
                name: 'message_title',
                type: 'string'
            },
            {
                name: 'message_from',
                type: 'string'
            },
            
            {
                name: 'message_content',
                type: 'string'
            },{
                name: 'message_date',
                type: 'date',
                dateFormat: 'Y-m-d H:i:s'
            },
            {
                name: 'message_status',
                type: 'int'
            },
            {
                name: 'message_action',
                type: 'string'
            },
            {
                name: 'text',
                type: 'string'
            },
            {
                name: 'cls',
                type: 'string'
            },
            {
                name: 'url',
                type: 'string'
            }
            ]
        });
        
        
        //Store memuat data melalui proxy, dan juga menyediakan
        //fungsi untuk sorting, filtering dan query.
        var store = Ext.create('Ext.data.Store', {
            storeId:'messageStore',
            model: 'Message',
            //Jumlah per halaman
            pageSize: 20,
            proxy: {
                type: 'ajax',
                api : {
                    read : 'tmessage/messagelist/'
                },
                reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            },
            sorters: [
            {
                property : 'displayname',
                direction: 'ASC'
            }
            ],
            //Untuk sorting
            remoteSort:true
        });

        //Inisialisasi pertama bila pagging
        //belum dilakukan
        store.load({
            params:{
                start:0,
                limit: 20
            }
        });

        //Membuat grid user
        var GridPanelMessage = Ext.create('Ext.grid.Panel', {
            id:'panelReceiveMessage',
            layout:'fit',
            scroll:true,
            border:0,
            title: 'Pesan Masuk',
            iconCls: 'icon-mail-receive',
            //Me-link kan dengan Store
            store: Ext.data.StoreManager.lookup('messageStore'),
			
            //Kolom yang ditampilkan
            columns: [{
                xtype: 'templatecolumn',
                text: '',
                width:28,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','<tpl if="message_status == 0"><div style="width:16px;height:16px;" class="icon-mail">&nbsp</div></tpl><tpl if="message_status == 1"><div style="width:16px;height:16px;" class="icon-mail-open">&nbsp</div></tpl>')
            },
            {
                xtype: 'templatecolumn',
                text: 'Dari',
                flex: 1,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','<tpl if="message_status == 0"><b>{message_content}</b></tpl><tpl if="message_status == 1">{message_content}</tpl>')
            },
            {
                xtype: 'templatecolumn',
                text: 'Judul',
                flex: 2,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','<tpl if="message_status == 0"><b>{message_title}</b></tpl><tpl if="message_status == 1">{message_title}</tpl>')
            },
            {
                xtype: 'templatecolumn',
                text: 'Link',
                dataIndex: 'message_action',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                width:120,
                tpl: Ext.create('Ext.XTemplate','<tpl if="message_action == 0"><b>-</b></tpl><tpl if="message_action != 0"><a href="javascript:void(0);" onclick="GetRemoteForm(\'{text}\', \'{cls}\', \'{url}\');">{text}</a></tpl>')
            },
            {
                text: 'Tanggal',
                dataIndex: 'message_date',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                width:125,
                renderer: Ext.util.Format.dateRenderer('d/m/Y H:i:s')
            }
            ],
            selType: 'rowmodel',
			
            //Pagging control
            bbar: new Ext.PagingToolbar({
                store:  Ext.data.StoreManager.lookup('messageStore'),
                displayInfo: true,
				plugins: new Ext.ux.ProgressBarPager()
            }),

            tbar: [
            {
                xtype: 'button',
                text: 'Buat Email',
                iconCls: 'icon-mail-plus',
                handler: me.showCompose
            },{
                xtype: 'button',
                text: 'Hapus',
                iconCls: 'icon-mail-minus',
                handler: function(){
                    if (me.selectedReceiveId != null){
						Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
							if(id==='yes'){
								Ext.Ajax.request({
									url:'tmessage/messagedelete/',
									params: {
										message_id: me.selectedReceiveId,
										message_mode: 'receive'
									},
									success: function(response){
										Ext.data.StoreManager.lookup('messageStore').load({});
									},
									scope: me
								});
							}
						});						
                        
                    }
                },
                scope: me
            },
            {
                xtype: 'button',
                text: 'Refresh',
                iconCls: 'icon-refresh',
                handler: function(){
                    Ext.data.StoreManager.lookup('messageStore').load({});
                }
            },
            '->',
            {
                xtype: 'label',
                text: 'Cari',
                margins: '0 5 0 10'
            },
            new Ext.app.SearchField({
                width: 200,
                store: Ext.data.StoreManager.lookup('messageStore')
            }),
            ],
            
            listeners: {
                selectionchange: function(model, records) {
                    if (records[0] != null)
                        me.selectedReceiveId = records[0].data.message_id;
                },
                itemdblclick :function( view, record,  item, index, e, eOpts ){
                    
                    var appContent = Ext.getCmp('mymessage-tabpanel');
                    
                    var tab = Ext.getCmp('receive-message-'+ record.data.message_id);
                    
                    if (tab != null){
                        appContent.setActiveTab(tab);
                    }
                    else{
						//var datadate=record.data.message_date.split('.');
						//var date=datadate[0];
                        tab = Ext.create('Esmk.view.TMessage.MyMessageDetail',
                        {
                            title:record.data.message_title,
                            id: 'receive-message-' + record.data.message_id,
                            closable:true,
                            iconCls:'icon-mail-reply',
                            mode: 'receive',
                            message_id: record.data.message_id,
                            from: record.data.message_content,
                            date:record.data.message_date
                        });
						
                        appContent.add(tab);
                        appContent.setActiveTab(tab);
                    }
                    Ext.data.StoreManager.lookup('messageStore').load({});
                    me.doLayout();
                }
            }
        });

        return GridPanelMessage;
    },
    
    createGridPanelSend: function(){
        var me = this;

        //Model merepresentasikan beberapa object yang akan diatur
        Ext.define('Message', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'message_id',   
                type: 'int'
            },
            {
                name: 'message_title',
                type: 'string'
            },
            {
                name: 'message_from',
                type: 'int'
            },
            {
                name: 'message_to',
                type: 'int'
            },
            {
                name: 'user_realname',
                type: 'string'
            },
            {
                name: 'message_content',
                type: 'string'
            },
            {
                name: 'message_status',
                type: 'int'
            },{
                name: 'message_date',
                type: 'date',
                dateFormat: 'Y-m-d H:i:s'
            },
            {
                name: 'message_action',
                type: 'string'
            },
            {
                name: 'text',
                type: 'string'
            },
            {
                name: 'cls',
                type: 'string'
            },
            {
                name: 'url',
                type: 'string'
            }
            ]
        });
        
        
        //Store memuat data melalui proxy, dan juga menyediakan
        //fungsi untuk sorting, filtering dan query.
        var store = Ext.create('Ext.data.Store', {
            storeId:'messageSendStore',
            model: 'Message',
            //Jumlah per halaman
            pageSize: 20,
            proxy: {
                type: 'ajax',
                api : {
                    read : 'tmessage/messagesend/'
                },
                reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            },
            
            //Untuk sorting
            remoteSort:true
        });

        //Inisialisasi pertama bila pagging
        //belum dilakukan
        store.load({
            params:{
                start:0,
                limit: 20
            }
        });

        //Membuat grid user
        var GridPanelMessage = Ext.create('Ext.grid.Panel', {
            id:'panelSendMessage',
            layout:'fit',
            scroll:true,
            border:0,
            title: 'Pesan Keluar',
            iconCls: 'icon-mail-send',
            //Me-link kan dengan Store
            store: Ext.data.StoreManager.lookup('messageSendStore'),
            //Kolom yang ditampilkan
            columns: [{
                xtype: 'templatecolumn',
                text: '',
                width:28,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','<div style="width:16px;height:16px;" class="icon-mail-open">&nbsp</div>')
            },
            {
                xtype: 'templatecolumn',
                text: 'Kepada',
                flex: 1,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','{message_content}')
            },
            {
                xtype: 'templatecolumn',
                text: 'Judul',
                flex: 2,
                dataIndex: 'description',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                tpl: Ext.create('Ext.XTemplate','{message_title}')
            },
            {
                xtype: 'templatecolumn',
                text: 'Link',
                dataIndex: 'message_action',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                width:120,
                tpl: Ext.create('Ext.XTemplate','<tpl if="message_action == 0"><b>-</b></tpl><tpl if="message_action != 0"><a href="javascript:void(0);" onclick="GetRemoteForm(\'{text}\', \'{cls}\', \'{url}\');">{text}</a></tpl>')
            },
            {
                text: 'Tanggal',
                dataIndex: 'message_date',
                sortable: false,
                hideable: false,
                menuDisabled: true,
                width:125,
                renderer: Ext.util.Format.dateRenderer('d/m/Y H:i:s')
            }
            ],
            selType: 'rowmodel',

            //Pagging control
            bbar: new Ext.PagingToolbar({
                store:  Ext.data.StoreManager.lookup('messageSendStore'),
                displayInfo: true,
				plugins: new Ext.ux.ProgressBarPager()
            }),
            tbar: [
            {
                xtype: 'button',
                text: 'Buat Email',
                iconCls: 'icon-mail-plus',
                handler: me.showCompose
            },{
                xtype: 'button',
                text: 'Hapus',
                iconCls: 'icon-mail-minus',
                handler: function(){
                    if (me.selectedSendId != null){
						Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
							if(id==='yes'){
								Ext.Ajax.request({
									url: 'tmessage/messagedelete/',
									params: {
										message_id: me.selectedReceiveId,
										message_mode: 'send'
									},
									success: function(response){
										Ext.data.StoreManager.lookup('messageSendStore').load({});
									},
									scope: me
								});
							}
						});
                        
                        
                    }
                },
                scope: me
            },
            {
                xtype: 'button',
                text: 'Refresh',
                iconCls: 'icon-refresh',
                handler: function(){
                    Ext.data.StoreManager.lookup('messageSendStore').load({});
                }
            },
            '->',
            {
                xtype: 'label',
                text: 'Cari',
                margins: '0 5 0 10'
            },
            new Ext.app.SearchField({
                width: 200,
                store: Ext.data.StoreManager.lookup('messageSendStore')
            }),
            ],
            
            listeners: {
                selectionchange: function(model, records) {
                    if (records[0] != null)
                        me.selectedSendId = records[0].data.message_id;
                },
                itemdblclick :function( view, record,  item, index, e, eOpts ){
                    
                    var appContent = Ext.getCmp('mymessage-tabpanel');
                    
                    var tab = Ext.getCmp('send-message-'+ record.data.message_id);
                    
                    if (tab != null){
                        appContent.setActiveTab(tab);
                    }
                    else{
                        tab = Ext.create('Esmk.view.TMessage.MyMessageDetail',
                        {
                            title:record.data.message_title,
                            id: 'send-message-' + record.data.message_id,
                            closable:true,
                            iconCls:'icon-mail-forward',
                            mode: 'send',
                            message_id: record.data.message_id,
                            from: record.data.message_content,
                            date: record.data.message_date
                        });
                        appContent.add(tab);
                        appContent.setActiveTab(tab);
                    }
                    me.doLayout();
                }
            },
            scope: me
        });

        return GridPanelMessage;
    },
    
    showCompose:function(){
        var me = this;
        var appContent = Ext.getCmp('mymessage-tabpanel');
        var tab = Ext.create('Esmk.view.TMessage.MyMessageCompose',
        {
            title:"Pesan Baru",
            closable:true,
            iconCls:'icon-mail-plus',
            listeners: {
                scope: me,
                completeSend: function(){
                    Ext.data.StoreManager.lookup('messageSendStore').load({});
                }
            }
        });
        appContent.add(tab);
		appContent.setActiveTab(tab);
		//Ext.getCmp('docs-icon-arrange').add([{xtype:'panel','title':'tes','hmtml':'testes'}]);
    }
});