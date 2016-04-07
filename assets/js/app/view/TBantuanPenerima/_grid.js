Ext.define('Esmk.view.TBantuanPenerima._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuanpenerimaGrid',
	id:'tbantuanpenerimagridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.selection.CellModel',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Siban - TBANTUANPENERIMA',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
	autoScroll:true,
	height:460,
    initComponent: function() {
        var me = this;
		this.cellEditing = new Ext.grid.plugin.CellEditing({
            clicksToEdit: 1
        });
		this.store=Ext.create('Esmk.store.TBantuanPenerima');
		this.getStore.pageSize=25;
		this.getStore().getProxy().api.read='TBantuanPenerima/read/?tbid='+me.tbid;
        Ext.applyIf(me, {
			plugins: [this.cellEditing],
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
                    dataIndex: 'sekolah_id',
                    text: 'Nama Sekolah',
                    flex:true,
                    hidden:true 
                    
                },{
                    dataIndex: 't_bantuan_program_id',
                    text: 'Nama Bantuan',
                    flex:true,
                    hidden:true 
                    
                },{
                    dataIndex: 'prop',
                    text: 'Provinsi',
                    flex:true
                },{
                    dataIndex: 'kab',
                    text: 'Kab/Kota',
                    flex:true
                },{
                    dataIndex: 'nama_sekolah',
                    text: 'Nama Sekolah',
                    flex:true
                }/*,{
                    dataIndex: 'nama_bantuan',
                    text: 'Nama Bantuan',
                    flex:true
                }*/,{
                    dataIndex: 'jumlah_bantuan',
                    text: 'Paket/Jumlah Bantuan',
                    flex:true,
					editor: {
						allowBlank: false,
						xtype:'numberfield'
					} 
                },{
                    dataIndex: 'jumlah_dana',
                    text: 'Total/Besar Dana Bantuan',
                    flex:true,
					editor: {
						allowBlank: false,
						xtype:'numberfield'
					},
					renderer: function(val) {
						return 'Rp. '+Ext.util.Format.number(val,'0,000');
					}
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
                        },'-',
                        {
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete(this);
							}
                        },{
							xtype: 'button',
							iconCls: 'icon-add',
							text: 'Tambah Penerima',
							handler:function(){
								me.actionCreate();
							}
						},'-',{
							xtype:'button',
							iconCls:'icon-xls',
							text:'Export Data',
							handler:function(){
								me.actionDownloadExcelPenerima();
							}
						},'-',{
							xtype:'form',
							frame:false,
							border:0,
							layout:'hbox',
							items:[{
								xtype: 'fileuploadfield',
								width: 330,
								id: 'file_upload_excel_penerima',
								emptyText: 'Pilih File Excel Hasil Export Data',
								//fieldLabel: 'Lampiran Dokumen',
								name: 'file_upload_excel_penerima',
								buttonText: '',
								buttonConfig: {
									iconCls: 'icon-xls'
								}
							},{
								xtype:'button',
								text:'Upload',
								iconCls:'icon-up',
								handler:function(){
									me.actionUpload(this);
								}
							}]
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
		//alert(record.get('id'));
         var detail=Ext.create('Esmk.view.TBantuanPenerimaSiswa.index',{
			tbpid:record.get('id')
		}); 
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formTBantuanPenerima = Ext.create('Esmk.view.TBantuanPenerima._form');

        if (record) {

            formTBantuanPenerima.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
		var me=this;
		var grid=null;
		if(me.kodepenerima=='SK'){
			grid=Ext.create('Esmk.view.TBantuanPenerima._form_sekolah',{tbid:me.tbid});
		}else if(me.kodepenerima=='SS'){
			grid=Ext.create('Esmk.view.TBantuanPenerima._form_siswa',{tbid:me.tbid});
		}
        var myWindow=Ext.create('Ext.window.Window',{
			maximized:true,
			modal:true,
			layout:'fit',
			items:[grid],
			buttons: [{ 
				text: 'Close',
				iconCls: 'icon-reset',
				handler:function(){
					myWindow.close();
				}
			}]
		});
		myWindow.show();
    },
	actionDelete: function(button) {
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		if(!records)
			alert('Silahkan pilih data yang akan dihapus!');
		var store = grid.getStore();
		Ext.Msg.confirm('Konfirmasi Penghapusan!','Apakah anda yakin ingin menghapus data ini?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					store.remove(item);
				});

				store.load();
			}
			
		});
        
    },
    
    actionSearch: function(button) {
		var me=this;
        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='TBantuanPenerima/read/?q='+values+'&tbid='+me.tbid;
    },
	actionDownloadExcelPenerima:function(){
		var me=this;
		var form = Ext.create('Ext.form.Panel', {
						standardSubmit: true,
						url:'TBantuanPenerima/read',
						method:'GET'
					});
		var tbid=this.tbid;
		if(!tbid)
			return;
		// Call the submit to begin the file download.
		form.submit({
			target: '_blank', // Avoids leaving the page. 
			params:{tbid:me.tbid,excel:true}
		});
		Ext.defer(function(){
			form.close();
		}, 100);
	},
	actionUpload:function(button){
		var form = this.down('form').getForm();
        var self = this;
		var store=button.up('grid').getStore();
        if(form.isValid()){
            form.submit({
				url: base_url + 'TBantuanPenerima/SubmitExcel',
                waitMsg: 'Mohon tunggu hingga proses selesai...',
                success: function(form, action) {
                    self.down('form').getForm().reset();
                    //self.up('window').close();
					if(action.result.success){
						Ext.create('widget.uxNotification', {
							title: 'Notifikasi',
							position: 'br',
							manager: 'demo1',
							iconCls: 'ux-notification-icon-information',
							autoHideDelay: 5000,
							autoHide: true,
							spacing: 20,
							html: 'Data Telah Diperbarui'
						}).show(); 
						store.load();
					}
					
                },
                failure: function(form, action) {
                    self.down('form').getForm().reset();
					if(action.result.message){
						alert(action.result.message);
					}					
                }
			});
		}  
		
	}
});


