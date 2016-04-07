Ext.define('Esmk.view.TKuesioner._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tkuesionerGrid',
	id:'tkuesionergridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Data Master Kuesioner',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TKuesioner');
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },{
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                    hidden:true,                     
                },{
                    dataIndex: 'nomor',
                    text: 'Nomor',
                    flex:true,
                },{
                    dataIndex: 'judul',
                    text: 'Judul',
                    flex:true,
                },{
                    dataIndex: 'keterangan',
                    text: 'Keterangan',
                    flex:true,
                },
                 
                /*{
                    dataIndex: 'is_open',
                    text: 'Telah Siap?',
                    flex:true,
                     
                    
                },*/
                                
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
							text: 'Ubah',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionUpdate(this,records);
							}
						},{
							xtype:'button',
							iconCls:'icon-base',
							text:'Detail',
							handler:function(){
								me.actionShowDetail(this);
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
	actionShowDetail:function(button){
		var grid=button.up('grid');
		var record=grid.getSelectionModel().getSelection()[0];
		this.actionDetail(record);
		/*var detailGrid=Ext.create('Esmk.view.TKuesionerPertanyaan.index',{
			kid:record.get('id')
		});*/
		
	},
	actionDetail:function(record){
		Ext.getCmp('tkuesionerpertanyaanpanelid').remove('tkuesionerpertanyaangridid',true);
		Ext.getCmp('tkuesionerpertanyaanpanelid').add(Ext.create('Esmk.view.TKuesionerPertanyaan._grid',{
			kid:record.get('id'),
		}));
		Ext.getCmp('tkuesionerpertanyaanpanelid').enable();
		Ext.getCmp('tkuesionerpertanyaanpanelid').expand();
		Ext.getCmp('tkuesionerpertanyaanpanelid').setTitle('Data: \" '+record.get('judul')+'\"');
	},
	actionDbClick: function(dataview, record, item, index, e, options){
        this.actionDetail(record);
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formTKuesioner = Ext.create('Esmk.view.TKuesioner._form');

        if (record) {

            formTKuesioner.down('form').loadRecord(record);

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
		grid.getStore().getProxy().api.read='TKuesioner/read/?q='+values;
    },
});


