Ext.define('Esmk.view.TBantuanPenerima.index',{
	extend:'Ext.Container',
	//autoScroll: true,
	id:'bantuanpenerimaindex',
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
							var kodepenerima=records[0].get('r_bantuan_penerima_kode');
							var v = records[0].get('t_bantuan_persyaratan_penerima_inquery_id');
							var tbid= records[0].get('id');
								me.actionBuildGrid(v,tbid);
								me.actionBuildGridPenerima(v,tbid,kodepenerima);
								
								
						},
						
					}				
				}]				
			},{
				xtype:'tabpanel',
				layout:'fit',
				//autoScroll: true,
				//tabPosition: 'left',
				//height:500,
				items:[{
					title:'Calon Penerima Bantuan',
					iconCls:'icon-usulan-penerima',
					id:'calonpenerimabantuantabitemid',
					//autoScroll: true,
					layout:'fit',
					items:[{xtype:'panel',id:'foopanel'}]
				},{
					title:'Daftar Penerima Bantuan',
					iconCls:'icon-calon-penerima',
					id:'daftarpenerimabantuantabitemid',
					html:''
				},{
					title:'SK Penerima Bantuan',
					iconCls:'icon-penerima',
					id:'SKpenerimabantuanitemid',
					bodyPadding:5,
					dockedItems: [
					{
						xtype: 'toolbar',
						dock: 'top',
						items: [{
							xtype:'button',
							text:'Refresh',
							iconCls:'icon-refresh',
							handler:function(){
								var tbid=me.tbid;
								if(tbid)
									me.actionBuildSKPenerima(tbid);
							}
						},'-',{
							xtype:'button',
							text:'PDF',
							iconCls:'icon-pdf',
							handler:function(){
								me.actionDownloadSKPenerima();
							}
						}]
					}],
					items:[/*Ext.create('Esmk.view.TBantuanPenerima._grid',{
						title:null,
						frame:false,
						iconCls:null,
						border:0,
						dockedItems:null
					})*/]
				}]
			}]
		});
		me.callParent(arguments);
	},
	actionBuildGrid:function(qid,tbid){
		var tab=Ext.getCmp('calonpenerimabantuantabitemid');
		tab.removeAll();
		//tab.remove('calonpenerimabantuantabitemcontentid',true);
		tab.add(Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._query_result_process',{
			queryId:qid,
			tbid:tbid,
			id:'calonpenerimabantuantabitemcontentid'
			//autoScroll:true,
		}));
	},
	actionBuildGridPenerima:function(qid,tbid,kodepenerima){
		var tab=Ext.getCmp('daftarpenerimabantuantabitemid');
		tab.removeAll();
		//tab.remove('calonpenerimabantuantabitemcontentid',true);
		tab.add(Ext.create('Esmk.view.TBantuanPenerima._grid',{
			tbid:tbid,
			kodepenerima:kodepenerima,
			title:null,
			iconCls:null,
			frame:false,
			id:'daftarpenerimabantuantabitemcontentid'
			//autoScroll:true,
		}));
		this.actionBuildSKPenerima(tbid);
	},
	actionBuildSKPenerima:function(tbid){
		Ext.getCmp('SKpenerimabantuanitemid').update('Status : Memproses data....');
		this.tbid=tbid;
		Ext.Ajax.request({
			url:'Laporan/GetViewSKPenerimaBantuan',
			method:'POST',
			params:{tbid:tbid},
			success: function(response){
				//me.update('');
				Ext.getCmp('SKpenerimabantuanitemid').update(response.responseText);
				
			}
		});
	},
	actionDownloadSKPenerima:function(){
		var form = Ext.create('Ext.form.Panel', {
						standardSubmit: true,
						url:'Laporan/GetViewSKPenerimaBantuan',
						method:'POST'
					});
		var tbid=this.tbid;
		if(!tbid)
			return;
		// Call the submit to begin the file download.
		form.submit({
			target: '_blank', // Avoids leaving the page. 
			params:{tbid:tbid,pdf:true}
		});
		Ext.defer(function(){
			form.close();
		}, 100);
	}
	
	
});