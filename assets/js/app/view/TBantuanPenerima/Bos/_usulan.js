Ext.define('Esmk.view.TBantuanPenerima.Bos._usulan', {
	extend: 'Ext.grid.Panel',
	id:'tbantuanpeneriusulanbos',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Siban - TBANTUANPENERIMA',
    loadMask: true,
    //selType : 'checkboxmodel',
	/*selModel : 
	{
		mode : 'MULTI'
	},*/
	maxHeight:480,
	layout:'fit',
	
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.UsulanBos');
		this.getStore().getProxy().api.read='UsulanBos/ReadPropinsi/?tbid='+me.tbid
        Ext.applyIf(me, {
			columns: [
                {
                    xtype: 'rownumberer',
                    sortable: false,
                    flex: false,
					text:'No.',
					//summaryRenderer:function(){return '-';}
                },{
					dataIndex:'propinsi',
					text:'Provinsi',
					flex:true,
					summaryType: function(records){
						var prop='';
						
						Ext.each(records,function(record){
							prop=record.get('propinsi');
							
						});
						return '<b>'+prop+'</b>';
					},
				},{
					dataIndex:'kabupaten',
					text:'Kabupaten/Kota',
					flex:true,
					groupable: false,
					summaryType: function(records){
						return '<b>'+records.length+' Kab/Kota</b>';
					},
				},{
					dataIndex:'jumlah_sekolah',
					text:'Total SMK',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_sekolah')) && isFinite(record.get('jumlah_sekolah'))))?parseInt(record.get('jumlah_sekolah')):0;
							total += dt;
						});
						Ext.getCmp('tbantuanpeneriusulanbos').jml_sekolah_prop=total;
						return '<b>'+Ext.util.Format.number(total,'0,000') +' SMK</b>';
					},					
					
				},{
					dataIndex:'jumlah_siswa',
					text:'Total Siswa',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_siswa')) && isFinite(record.get('jumlah_sekolah'))))?parseInt(record.get('jumlah_siswa')):0;
							total += dt;
						});
						return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
					},
				},{
					dataIndex:'jumlah_siswa_tdk_bernisn',
					text:'Total Siswa Tidak ber-NISN',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_siswa_tdk_bernisn')) && isFinite(record.get('jumlah_siswa_tdk_bernisn'))))?parseInt(record.get('jumlah_siswa_tdk_bernisn')):0;
							total += dt;
						});
						return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
					},
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
			features: [{
				ftype: 'summary',
				dock: 'bottom',
			}],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
						{
							xtype:'button',
							text:'Kembali',
							id:'btndatakemabali',
							hidden:(me.modeData==1),
							iconCls:'arrow-180-medium',
							handler:function(){
								me.actionKembali();
							}
						},'|',
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
                        },'|',{
							xtype:'button',
							iconCls:'icon-xls',
							text:'Unduh Data',
							handler:function(){
								me.actionExportExcel();
							}
						},{
							xtype:'button',
							iconCls:'icon-tick-check',
							text:'Approve',
							id:'btnapprovesk',
							handler:function(){
								me.actionApprove();
							}
						}
                        /*{
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
						}*/
                    ]
                },
                /*{
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'Data Kosong',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }*/
            ]
		});
		me.callParent(arguments);
	},
	actionDbClick: function(dataview, record, item, index, e, options){
		if(this.modeData==1){
			this.actionDetailKab(record);
		}else if(this.modeData==2){
			this.actionDetailSekolah(record);
		}
    },
	actionKembali:function(){
		if(this.modeData==2){
			this.actionRekapProp();
		}else if(this.modeData==3){
			var record=null;
			this.actionDetailKab(record);
		}
	},
	actionApprove:function(){
		var form = Ext.create('Ext.form.Panel', {
			method: 'POST'
		});
		var me=this;
		Ext.Msg.confirm('Konfirmasi Persetujuan','Anda anda menyetujui data tersebut sebagai penerima bantuan BOS ?',function(id,value){
			if(id==='yes'){
				/*var pMsg = Ext.create('Ext.window.Window', {
						title:'Proses persetujuan sedang berjalan',
						width:250,
						height:200,
						closeable:false,
						modal:false,
						id:'pMsgPersetujuan',
						html:''
				});
				pMsg.show();*/
				form.submit({
					url: 'UsulanBos/Pengeskaan',
					waitMsg: 'Mohon tunggu hingga proses selesai...',
					params:{tbid:me.tbid},
					success: function(form, action) {
						Ext.create('widget.uxNotification', {
							title: 'Notifikasi',
							position: 't',
							manager: 'demo1',
							iconCls: 'ux-notification-icon-information',
							autoHideDelay: 5000,
							autoHide: true,
							spacing: 20,
							html: 'Proses persetujuan sedang berjalan'
						}).show();  
						me.getStore().load();						
					},
					failure: function(form, action) {
						Ext.Msg.show({
							title: 'Failed!',
							msg:  'Usulan Penerima gagal disetujui,'+action.result.message,
							minWidth: 200,
							modal: true,
							icon: Ext.Msg.ERROR,
							buttons: Ext.Msg.OK
						});
					}
				});
			}
			
		});
		
	},
	actionCheckPersetujuan:function(){
		var me=this;
		var observerInterval =setInterval(function () {
			Ext.Ajax.request({
				url:'UsulanBos/checkPersetujuan/?kdw='+me.kodeWilayahProp,
				success: function(response){
					var jml=parseInt(response.responseText);
					var info='<span style="font-size:20px;text-align:center;"'+jml+'</div>';
					Ext.getCmp('pMsgPersetujuan').update(info);
					if(me.jml_sekolah_prop<=jml){
						clearInterval(observerInterval);
					}
				}
			});
		},5000);
	},
	actionRekapProp:function(){
		Ext.suspendLayouts();
		var grid=this;
		var store=Ext.create('Esmk.store.UsulanBos');
		grid.modeData=1;
		store.getProxy().api.read='UsulanBos/ReadPropinsi/?tbid='+this.tbid;
        store.load();
		grid.reconfigure(store, [{
                    xtype: 'rownumberer',
                    sortable: false,
                    flex: false,
					text:'No.',
					//summaryRenderer:function(){return '-';}
                },{
					dataIndex:'propinsi',
					text:'Provinsi',
					flex:true,
					summaryType: function(records){
						var prop='';
						Ext.each(records,function(record){
							prop=record.get('propinsi')
						});
						return '<b>'+prop+'</b>';
					},
				},{
					dataIndex:'kabupaten',
					text:'Kabupaten/Kota',
					flex:true,
					groupable: false,
					summaryType: function(records){
						return '<b>'+records.length+' Kab/Kota</b>';
					},
				},{
					dataIndex:'jumlah_sekolah',
					text:'Total SMK',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_sekolah')) && isFinite(record.get('jumlah_sekolah'))))?parseInt(record.get('jumlah_sekolah')):0;
							total += dt;
						});
						return '<b>'+Ext.util.Format.number(total,'0,000') +' SMK</b>';
					},						
					
				},{
					dataIndex:'jumlah_siswa',
					text:'Total Siswa SMK',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_siswa')) && isFinite(record.get('jumlah_sekolah'))))?parseInt(record.get('jumlah_siswa')):0;
							total += dt;
						});
						return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
					},
				},{
					dataIndex:'jumlah_siswa_tdk_bernisn',
					text:'Total Siswa Tidak ber-NISN',
					flex:true,
					groupable: false,
					renderer:function(value){
						return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
					},
					summaryType: function(records){
						var i = 0,
							length = records.length,
							total = 0,
							record;
						Ext.each(records,function(record){
							var dt=(!isNaN(parseFloat(record.get('jumlah_siswa_tdk_bernisn')) && isFinite(record.get('jumlah_siswa_tdk_bernisn'))))?parseInt(record.get('jumlah_siswa_tdk_bernisn')):0;
							total += dt;
						});
						return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
					},
				}
		]);
		Ext.getCmp('btndatakemabali').setVisible(false);
		Ext.getCmp('btnapprovesk').setVisible(true);
        Ext.resumeLayouts(true); 
	},
	actionDetailKab:function(record){
		Ext.suspendLayouts();
		var grid=this;
		grid.modeData=2;
		var store=Ext.create('Esmk.store.UsulanBos');
		var kodeWilayah=(record)?record.get('kode_wilayah'):grid.kodeWilayah;
		grid.kodeWilayah=kodeWilayah;
		grid.dataUrl='ReadKabupaten';
		store.getProxy().api.read='UsulanBos/ReadKabupaten/?tbid='+this.tbid+'&kdw='+kodeWilayah;
        store.load();
		grid.reconfigure(store, [{
				xtype: 'rownumberer',
				sortable: false,
				flex: false,
				text:'No.',
				width:50,
			},{
				text: 'Kab/Kota',
				dataIndex: 'kabupaten',
				flex:true,
				summaryType: function(records){
					var kab='';
					Ext.each(records,function(record){
						kab=record.get('kabupaten')
					});
					return '<b>'+kab+'</b>';
				},
			},{
				text: 'Nama SMK',
				dataIndex: 'nama',
				flex:true,
				summaryType: function(records){
					return '<b>'+records.length+' SMK</b>';
				},
			},{
				text: 'Jumlah Siswa',
				dataIndex: 'jumlah_siswa',
				renderer:function(value){
					return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
				},
				summaryType: function(records){
					var i = 0,
						length = records.length,
						total = 0,
						record;
					Ext.each(records,function(record){
						var dt=(!isNaN(parseFloat(record.get('jumlah_siswa')) && isFinite(record.get('jumlah_sekolah'))))?parseInt(record.get('jumlah_siswa')):0;
						total += dt;
					});
					return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
				},
			},{
				dataIndex:'jumlah_siswa_tdk_bernisn',
				text:'Siswa Tidak ber-NISN',
				flex:true,
				groupable: false,
				renderer:function(value){
					return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
				},
				summaryType: function(records){
					var i = 0,
						length = records.length,
						total = 0,
						record;
					Ext.each(records,function(record){
						var dt=(!isNaN(parseFloat(record.get('jumlah_siswa_tdk_bernisn')) && isFinite(record.get('jumlah_siswa_tdk_bernisn'))))?parseInt(record.get('jumlah_siswa_tdk_bernisn')):0;
						total += dt;
					});
					return '<b>'+Ext.util.Format.number(total,'0,000') +' Siswa</b>';
				},
			}
		]);
		Ext.getCmp('btndatakemabali').setVisible(true);
		Ext.getCmp('btnapprovesk').setVisible(false);
        Ext.resumeLayouts(true);
		grid.doLayout();		
	},
	actionDetailSekolah:function(record){
		Ext.suspendLayouts();
		var grid=this;
		var store=Ext.create('Esmk.store.PesertaDidik');
		grid.modeData=3;
		store.getProxy().api.read='UsulanBos/ReadSekolah/?tbid='+this.tbid+'&kdw='+record.get('kode_wilayah')+'&sid='+record.get('sekolah_id');
        store.load();
		grid.reconfigure(store, [{
            xtype: 'rownumberer',
            sortable: false,
            width:50,
			text:'No.',
        },{
            text: 'Kab/Kota',
            dataIndex: 'kabupaten',
			width:180,
			summaryType: function(records){
				var kab='';
				Ext.each(records,function(record){
					kab=record.get('kabupaten')
				});
				return '<b>'+kab+'</b>';
			},
        }, {
            text: 'Nama SMK',
            dataIndex: 'sekolah',
			width:180,
			summaryType: function(records){
				var s='';
				Ext.each(records,function(record){
					s=record.get('sekolah')
				});
				return '<b>'+s+'</b>';
			},
			
        },{
            text: 'NISN',
            dataIndex: 'nisn',
			width:110,
        },{
            text: 'Nama Siswa',
            dataIndex: 'nama',
			flex:true,
			renderer:function(value){
				return (!isNaN(parseFloat(value)) && isFinite(value))?Ext.util.Format.number(value,'0,000'):value;
			},
			summaryType: function(records){
				return '<b>'+records.length+' Siswa</b>';
			},
        },{
            text: 'Alamat',
            dataIndex: 'alamat_jalan',
			flex:true,
			
        }
		]);
		Ext.getCmp('btndatakemabali').setVisible(true);
		Ext.getCmp('btnapprovesk').setVisible(false);
        Ext.resumeLayouts(true);
	},
	actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		if(grid.modeData==1){
			grid.getStore().getProxy().api.read='UsulanBos/ReadPropinsi/?tbid='+this.tbid+'&q='+values;
		}else if(grid.modeData==2){
			grid.getStore().getProxy().api.read='UsulanBos/ReadKabupaten/?tbid='+this.tbid+'&q='+values+'&kdw='+grid.kodeWilayah;
		}else if(grid.modeData==3){
			grid.getStore().getProxy().api.read='UsulanBos/ReadSekolah/?tbid='+this.tbid+'&q='+values+'&kdw='+grid.kodeWilayah;
		}
    },
	actionExportExcel:function(){
		var mode=this.modeData;
		this.downloadFile({
			url:'UsulanBos/ExportExcel/?mode='+mode,
			method:'POST',
			//params:{id:qid}
		});	
	},
    downloadFile: function(config){
		config = config || {};
		var url = config.url,
			method = config.method || 'POST',// Either GET or POST. Default is POST.
			params = config.params || {};
		
		// Create form panel. It contains a basic form that we need for the file download.
		var form = Ext.create('Ext.form.Panel', {
			standardSubmit: true,
			url: url,
			method: method
		});

		// Call the submit to begin the file download.
		form.submit({
			target: '_blank', // Avoids leaving the page. 
			//params: params
		});

		// Clean-up the form after 100 milliseconds.
		// Once the submit is called, the browser does not care anymore with the form object.
		Ext.defer(function(){
			form.close();
		}, 100);

	},
	
});