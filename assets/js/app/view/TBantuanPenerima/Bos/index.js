Ext.define('Esmk.view.TBantuanPenerima.Bos.index',{
	extend:'Ext.Container',
	//autoScroll: true,
	id:'bantuanpenerimabosindex',
	border:0,
	initComponent: function () {
		var me=this;
		Ext.applyIf(me, {
			items:[{
				xtype:'panel',
				layout:'anchor',
				frame:false,
				bodyPadding:'0 0 0 5px',
				border:0,
				items:[{
					xtype:'combobox',
					flex:true,
					fieldLabel:'Program Bantuan',
					//name: 't_program_bantuan_id',
					allowBlank:true,
					width:500,
					store:Ext.create('Esmk.store.TBantuanProgram'),
					mode: 'remote',
					valueField: 'id',
					displayField: 'nama',
					typeAhead: true,
					forceSelection: true,
					pageSize: 30,
					minChars:2,
					matchFieldWidth: false,
					editable: true,
					emptyText:'Nama Program Bantuan',
						listConfig: {
							loadingText: 'Proses Pencarian...',
							emptyText: 'Data Tidak Ditemukan',
							//width: '71%',
							//height:300,
							autoHeight:true,
							getInnerTpl: function() {
								return '<span style="margin-top:2px;margin-left:2px;">{nama}</br>Tahun: {tahun}</span>';
							}
						},
					listeners:{
						select: function(combo, records, index) {
							var v = records[0].get('id');
								me.actionBuildGridBOS(v);
								me.actionBuildPaktaIntegritas(v);
								
						},
						'afterrender':function(){
							if(me.rbid)
								this.getStore().getProxy().api.read='TBantuanProgram/GetBantuanByRBantuan/?rbid='+me.rbid;
						}
					}				
				}]				
			},{
				xtype:'tabpanel',
				layout:'fit',
				//autoScroll: true,
				//tabPosition: 'left',
				//height:500,
				items:[{
					title:'Usulan Penerima Bantuan',
					iconCls:'icon-usulan-penerima',
					id:'calonpenerimabantuanbostabitemid',
					autoScroll: true,
					layout:'fit',
					items:[{xtype:'panel',id:'foopanel'}]
				},{
					title:'SK Penerima Bantuan',
					iconCls:'icon-penerima',
					items:[{
						xtype:'tabpanel',
						id:'penerimabantuanbostabitemid',
					}]
				}]
			}]
		});
		me.callParent(arguments);
	},
	actionBuildPaktaIntegritas:function(tbid){
		var me=this;
		var tab=Ext.getCmp('penerimabantuanbostabitemid');
		tab.removeAll();
		Ext.Ajax.request({
			url:'UsulanBos/GetTanggalCutOff',
			method:'POST',
			params:{tbid:tbid},
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				var data=json.data
				for(var i=0;i<data.length;i++){
					var tgl=data[i].tanggal;
					tab.add({
						title:'Data Cut Off '+tgl,
						layout:'fit',
						iconCls:'icon-grid',
						items:[
							Ext.create('Esmk.view.Bos.Laporan',{
								id:'laporanpaktapropinsi'+tgl,
								minHeight:450,
								border:0,
								frame:false,
								html:'Memproses data....',
								dataUrl:'UsulanBos/GetPaktaIntegritasPropinsi',
								dataParams:{tbid:tbid,tanggal:tgl}
							})
						]
					});
				}
			}
		});
		/*
		tab.add(
			Ext.create('Esmk.view.Bos.Laporan',{
				dataUrl:'UsulanBos/GetPaktaIntegritasPropinsi',
				dataParams:{tbid:tbid}
			})
		);
		*/
	},
	actionBuildGridBOS:function(tbid){
		var me=this;
		var tab=Ext.getCmp('calonpenerimabantuanbostabitemid');
		tab.removeAll();
		tab.add(Ext.create('Esmk.view.TBantuanPenerima.Bos._usulan',{
			tbid:tbid,
			kodeWilayahProp:me.kodeWilayahProp,
			iconCls:null,
			title:null,
			frame:false,
			border:0,
			autoHeight:true,
			minHeight:480,
			modeData:1,
			//autoScroll:true,
		}));
		//this.actionBuildPaktaIntegritas(tbid);
	},
	
});