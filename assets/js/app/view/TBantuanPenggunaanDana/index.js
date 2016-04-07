Ext.define('Esmk.view.TBantuanPenggunaanDana.index', {
	extend:'Ext.Container',
	layout:'fit',
	border:1,
	statusData:'R',
	tbpid:null,
	bodyPadding:'1 0 2 0',
	listeners:{
		'afterrender':function(){
		
		}
	},
	
	initComponent: function() {
		var me=this;
		Ext.applyIf(me, {
			items:[{
				xtype:'form',
				id:'penggunaandanaformid',
				//bodyPadding: '0 10 0 1',
				bodyPadding:2,
				border: 1,
				layout:'fit',
				style: 'background-color: #ddd;',
				autoScroll: true,
				items:[{
					//xtype: 'fieldcontainer',
					xtype: 'container',
					height:120,
					labelStyle: 'font-weight:bold;padding:0',
					layout: 'anchor',
					defaultType: 'textfield',
					fieldDefaults: {
						//labelAlign: 'top'
					},
					style: 'background-color: #ddd;',
					defaults:{
						width:150,
					},
					items:[{
						xtype:'fieldcontainer',
						layout:'hbox',
						fieldDefaults: {
							labelAlign: 'top'
						},
						items:[{
							xtype: 'textfield',
							name:'t_bantuan_penerima_id',
							hidden:true,
							value:me.tbpid,
							flex:true
						},{
							xtype: 'textfield',
							name:'status_data',
							hidden:true,
							value:me.statusData,
							flex:true
						},{
							xtype:'datefield',
							name: 'tanggal_transaksi',
							fieldLabel:'Tanggal Transaksi',
							maxValue: new Date(),
							format:'d-m-Y',
							sumbitFormat:'Y-m-d'
						},{
							xtype:'textfield',
							name: 'uraian',
							fieldLabel:'Uraian Transaksi',
							width:532
						},{
							xtype:'numberfield',
							name: 'qty',
							fieldLabel:'Jumlah '
						}]
					},{
						xtype:'fieldcontainer',
						layout:'hbox',
						fieldDefaults: {
							labelAlign: 'top'
						},
						items:[{
							xtype:'textfield',
							name: 'satuan_id',
							fieldLabel:'satuan '
						},{
							xtype:'numberfield',
							minValue:1,
							name: 'harga_total',
							fieldLabel:'Total Biaya '
						},{
							xtype:'combobox',
							store:Ext.create('Esmk.store.RPeruntukanDanaBos'),
							mode: 'remote',
							name:'r_peruntukan_dana_bos_id',
							valueField: 'id',
							displayField: 'peruntukan_dana',
							typeAhead: true,
							forceSelection: true,
							pageSize: 30,
							minChars:2,
							matchFieldWidth: false,
							editable: true,
							emptyText:'Peruntukan Dana',
							fieldLabel:'Peruntukan Dana ',
							listConfig: {
								loadingText: 'Proses Pencarian...',
								emptyText: 'Data Tidak Ditemukan',
								//width: '71%',
								//height:300,
								autoHeight:true,
								getInnerTpl: function() {
									return '<span style="margin-top:2px;margin-left:2px;">{peruntukan_dana}</span>';
								}
							},
						},{
							xtype:'textfield',
							name:'no_bukti',
							fieldLabel:'No.Bukti ',
							emptyText:'Nomor'
						},{
							name: 'status_pembelian_pengeluaran',
							xtype: 'combo',
							fieldLabel: 'Jenis ',
							store: new Ext.data.ArrayStore({
								fields: ['kode','jenis'],
								data : [['1','Pengeluaran'],['2','Pembelian Barang/Jasa']]
							}),
							triggerAction: 'all',
							valueField: 'kode',
							displayField: 'jenis',
							queryMode: 'local',
							forceSelection: true,
							selectOnFocus: true,
							listeners:{
								select: function(combo, records, index) {
									var value = records[0].get('kode');
									if(value==1){
										Ext.getCmp('toko_pembelian').setValue('');
										Ext.getCmp('toko_pembelian').setDisabled(true);
									}else{
										Ext.getCmp('toko_pembelian').setDisabled(false);
									}
										
								},
							}
						},{
							xtype:'textfield',
							name:'toko_pembelian',
							id:'toko_pembelian',
							fieldLabel:'Nama Toko ',
							emptyText:'',
							allowBlank:true,
						},{
							xtype: 'fileuploadfield',
							//width:110,
							id: 'bukti_kwitansi',
							emptyText: 'Pilih dokumen',
							fieldLabel: 'Upload Nota',
							name: 'bukti_kwitansi',
							buttonText: '',
							buttonConfig: {
								iconCls: 'blueprint'
							},
							hidden:true
						}]
					}]
				}],
				dockedItems:[{
					xtype: 'toolbar',
					dock: 'bottom',
					id: 'buttons',
					ui: 'footer',
					items:['->',{
						xtype:'button',
						text:'Simpan',
						iconCls:'icon-save',
						handler:function(){
							me.actionSave(this);
						}
					},{
						xtype:'button',
						text:'Reset',
						iconCls:'icon-undo',
						handler:function(){
							me.actionReset();
						}
					}]
				}],
				fieldDefaults: {
					anchor: '100%',
					labelAlign: 'center',
					margins: '5 5 2 0',
					allowBlank: false,
					combineErrors: true,
					//msgTarget: 'side',
					labelWidth: 200,
				},
			},{
				xtype:'panel',
				border:0,
				frame:false,
				minHeight:450,
				bodyPadding:'2 0 0 0',
				layout:'fit',
				items:[Ext.create('Esmk.view.TBantuanPenggunaanDana._grid',{
					title:null,
					iconCls:null,
					//border:0,
					//frame:false,
					tbpid:me.tbpid
				})]
			}]
		});
		me.callParent(arguments);
	},
	actionReset: function() {
        var form = Ext.getCmp('penggunaandanaformid').getForm();
        form.reset();
    },
	
	actionSave: function(button) {
        //var c=button.up('container');
		//alert(c.tbpid);
		var form = Ext.getCmp('penggunaandanaformid').getForm();
        var values = form.getValues(false, false, false, true);
		
        var isNewRecord = false;
        var dt=new Date(values.tanggal_transaksi);
		values.tanggal_transaksi=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
        record = Ext.create('Esmk.model.TBantuanPenggunaanDana');
        record.set(values);
        Ext.getCmp('tbantuanpenggunaandanagridid').getStore().add(record);
        isNewRecord = true;
		Ext.getCmp('tbantuanpenggunaandanagridid').getStore().load();
		form.reset();
		
        //win.close();
    },
});