Ext.define('Esmk.view.TBantuanPersyaratanPenerimaInquery._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuanpersyaratanpenerimainqueryGrid',
	id:'tbantuanpersyaratanpenerimainquerygridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: (this.rbid)?'Data CutOff Dapodik':'Query Kriteria Data',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanPersyaratanPenerimaInquery');
		if(this.rbid)
			this.store.getProxy().api.read='TBantuanPersyaratanPenerimaInquery/read/?rbid='+this.rbid;
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
                    dataIndex: 't_bantuan_program_nama',
                    text: 'Nama Bantuan',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'query',
                    text: 'Skrip Query',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'keterangan',
                    text: 'Keterangan Query',
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
						},{
							xtype:'button',
							iconCls:'table-sheet',
							text:'Hasil Query',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionLihatData(this,records);
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
	actionLihatData:function(dataview, record){
		if(!record){
			Ext.Msg.alert('Informasi','Silahkan Telebih dahulu memilih data pada tabel di bawah. ');
			return;
		}
		var form = Ext.create('Ext.form.Panel',{
			method: 'POST',
		});
		var id=record.get('id');
		form.submit({
				url:'TBantuanPersyaratanPenerimaInquery/Execute',
                waitMsg: 'Mohon tunggu hingga proses selesai...',
				params:{id:id},
                success: function(form, action) {
					if(!action.result.modelColumn){
						Ext.Msg.alert('Error','Query Error!');
						return;
					}
					var modelColumn=action.result.modelColumn;
					var GridColumn=action.result.gridColumn;
					var result=action.result;
					Ext.define('QueryData', {
						extend: 'Ext.data.Model',
						fields:modelColumn
					});
					
					var store = Ext.create('Ext.data.Store', {
						autoLoad: false,
						data : result,
						totalProperty: result.total,
						model: 'QueryData',
						proxy: {
							type: 'memory',
							reader: {
								type: 'json',
								root: 'data'
							}
						}
					});
					
					Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._query_result',{
						store:store,
						GridColumn:GridColumn
					});
					
                },
                failure: function(form, action) {
                    
                    Ext.Msg.show({
                        title: 'Gagal',
                        msg:  action.result.message,
                        minWidth: 200,
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            });
	},
	actionDbClick: function(dataview, record, item, index, e, options){
        if (record) {
			var formTBantuanPersyaratanPenerimaInquery = Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._form',{
				status:true,
				rbid:(this.rbid)?this.rbid:'',
			});
            formTBantuanPersyaratanPenerimaInquery.down('form').loadRecord(record);

        }else{
			var formTBantuanPersyaratanPenerimaInquery = Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._form',{
				status:false,
				rbid:(this.rbid)?this.rbid:'',
			});
		}    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        if (record) {
			var formTBantuanPersyaratanPenerimaInquery = Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._form',{
				rbid:(this.rbid)?this.rbid:'',
				status:true
			});
            formTBantuanPersyaratanPenerimaInquery.down('form').loadRecord(record);

        }else{
			var formTBantuanPersyaratanPenerimaInquery = Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._form',{
				rbid:(this.rbid)?this.rbid:'',
				status:false
			});
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
		grid.getStore().getProxy().api.read='TBantuanPersyaratanPenerimaInquery/read/?q='+values;
    },
});


