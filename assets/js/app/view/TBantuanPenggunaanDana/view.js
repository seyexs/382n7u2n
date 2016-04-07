Ext.define('Esmk.view.TBantuanPenggunaanDana.view', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuanpenggunaandanaGrid',
	id:'tbantuanpenggunaandanagridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
		'Ext.grid.feature.Grouping'
    ],
    iconCls: 'icon-grid',
    title: 'Rencana Penggunaan Dana BOS',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanPenggunaanDana');
		this.getStore().getProxy().api.read='TBantuanPenggunaanDana/read/?tbpid='+this.tbpid+'&s=R';
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 30,
					fieldLabel:'No.',
                    sortable: false,
                    flex: false,
                },
                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'tanggal_transaksi',
                    text: 'Tanggal Transaksi',
                    width:150,
                },{
                    dataIndex: 'uraian',
                    text: 'Uraian Transaksi',
                    flex:true,
					summaryType:function(){
						return '<b>TOTAL :</b>';
					}
                },{
                    dataIndex: 'qty',
                    text: 'Qty',
                    width:80,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
                },{
                    dataIndex: 'satuan_id',
                    text: 'Satuan',
                    width:80,
                },{
                    dataIndex: 'no_bukti',
                    text: 'No.Bukti Kwitansi',
                    width:150,
                     
                    
                },{
                    dataIndex: 'status_data',
                    text: 'Status Data',
                    flex:true,
                    hidden:true
                    
                },{
                    dataIndex: 'r_peruntukan_dana_bos_nama',
                    text: 'Kelompok Peruntukan Dana',
                    flex:true,
                },{
                    dataIndex: 'harga_total',
                    text: 'Total Biaya',
                    width:150,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('harga_total')) && isFinite(record.get('harga_total'))))?parseFloat(record.get('harga_total')):0;
							total += dt;
						});
						return '<b>Rp.'+Ext.util.Format.number(total,'0,000') +',-</b>';
					},
                    
                }
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>Data Kosong</b></h3>'
            },
			features: [{
				ftype: 'summary',
				dock: 'bottom',
			}],
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
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete(this);
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
		
		this.groupingFeature = this.view.getFeature('bulanGrouping');
    },
	actionDbClick: function(dataview, record, item, index, e, options){
        var formTBantuanPenggunaanDana = Ext.create('Esmk.view.TBantuanPenggunaanDana._form');

        if (record) {

            formTBantuanPenggunaanDana.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formTBantuanPenggunaanDana = Ext.create('Esmk.view.TBantuanPenggunaanDana._form');

        if (record) {

            formTBantuanPenggunaanDana.down('form').loadRecord(record);

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
		grid.getStore().getProxy().api.read='TBantuanPenggunaanDana/read/?q='+values+'&tbpid='+this.tbpid;
    },
});


