Ext.define('Esmk.view.TBantuanProgram._daftar_laporan_grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.daftarlaporanGrid',
	id:'daftarlaporangridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    //title: 'Jenis Laporan Pertanggung Jawaban',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.RBantuanDaftarPelaporan');
		this.store.getProxy().api.read='RBantuanDaftarPelaporan/ReadByJenisBantuan/?rbid='+this.r_bantuan_id;
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
                    dataIndex: 'r_bantuan_name',
                    text: 'Jenis Bantuan',
                    flex:true,
                    hidden:true, 
                    
                },{
                    dataIndex: 'nama',
                    text: 'Nama Laporan',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'kode_module',
                    text: 'Kode Modul',
                    flex:true,
                    hidden:true, 
                    
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
					//me.actionDbClick(dataview, index, item, e);
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
        var formRBantuanDaftarPelaporan = Ext.create('Esmk.view.RBantuanDaftarPelaporan._form');

        if (record) {

            formRBantuanDaftarPelaporan.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formRBantuanDaftarPelaporan = Ext.create('Esmk.view.RBantuanDaftarPelaporan._form');

        if (record) {

            formRBantuanDaftarPelaporan.down('form').loadRecord(record);

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
		grid.getStore().getProxy().api.read='RBantuanDaftarPelaporan/read/?q='+values;
    },
});


