Ext.define('Esmk.view.TBantuanTimPengelola._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuantimpengelolaGrid',
	id:'tbantuantimpengelolagridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Tim Pengelola Bantuan',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanTimPengelola');
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false
                },
                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true
                     
                    
                },
                 
                {
                    dataIndex: 'nama',
                    text: 'NAMA',
                    flex:true
                     
                    
                },
                 
                {
                    dataIndex: 'user_displayname',
                    text: 'Nama Pengguan',
                    flex:true
                     
                    
                }
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>Data Kosong</b></h3>'
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
								me.actionDelete(this);
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
							text:'Ubah',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionUpdate(this,records);
							}
						}
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'Data Kosong',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	actionDbClick: function(dataview, record, item, index, e, options){
        var formTBantuanTimPengelola = Ext.create('Esmk.view.TBantuanTimPengelola._form');

        if (record) {

            formTBantuanTimPengelola.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formTBantuanTimPengelola = Ext.create('Esmk.view.TBantuanTimPengelola._form');

        if (record) {

            formTBantuanTimPengelola.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },
	actionDelete: function(button) {
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		if(!records)
			alert('Silahkan pilih data yang akan dihapus!');
		var store = grid.getStore();
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					store.remove(item);
				});

				store.load();
			}
			
		});
        
    },
    
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='TBantuanTimPengelola/read/?q='+values;
    },
});


