Ext.define('Esmk.view.MPegawai._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.mpegawaiGrid',
	id:'mpegawaigridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Pegawai',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.MPegawai');
		
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },
                                  
                {
                    dataIndex: 'nip',
                    text: 'NIP',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'nama',
                    text: 'Nama',
                    flex:true,
                    renderer:function(value,p,record){
						return record.get('gelar_depan')+value+record.get('gelar_belakang');
					} 
                    
                },
                 
                {
                    dataIndex: 'gelar_depan',
                    text: 'Gelar Depan',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'gelar_belakang',
                    text: 'Gelar Belakang',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'jenis_kelamin',
                    text: 'Jenis Kelamin',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'foto',
                    text: 'Foto',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'tanggal_lahir',
                    text: 'Tanggal Lahir',
                    flex:true,
                     
                    
                },
                 
                
                                
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
        var formMPegawai = Ext.create('Esmk.view.MPegawai._form');

        if (record) {

            formMPegawai.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formMPegawai = Ext.create('Esmk.view.MPegawai._form');

        if (record) {

            formMPegawai.down('form').loadRecord(record);

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
					store.getProxy().api.destroy="MPegawai/delete/?nip="+item.get('nip');
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
		grid.getStore().getProxy().api.read='MPegawai/read/?q='+values;
    },
});


