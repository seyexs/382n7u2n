Ext.define('Esmk.view.TBantuanProgram.index', {
    extend: 'Ext.panel.Panel',
	layout:'accordion',
	id:'detailbantuanprogrampanelid',
	bantuanId:null,
	requires: [
		//'Esmk.view.TBantuanProgram.fileBrowser',
		//'Esmk.view.TBantuanProgram.fileInfo'
	],
	listeners:{
		'afterrender':function(){
				Ext.getCmp('tbantuanprogramgridid').isProp=this.isProp;
			}
	},
	items:[Ext.create('Esmk.view.TBantuanProgram._grid'),
	{
		xtype: 'panel',
        layout: 'fit',
		iconCls: 'icon-grid',
		id:'tbantuandocpanel',
		title: 'Detail Terkait Program Bantuan',
		//bodyStyle: "padding: 5px;",
		collapsible: false,
		collapsed:true,
		disabled:true,
		
		items:[
			{
				xtype:'tabpanel',
				layout:'fit',
				id:'tabpanelbantuanproperties',
				items:[
					{
						title:'Dokumen Terkait',
						iconCls:'icon-blogs-stack',
						id:'dokterkaittabitem',
						layout: 'fit',
						items:[Ext.create('Esmk.view.TBantuanProgram.fileBrowser')],
						tbar:[{
							xtype:'form',
							layout:'hbox',
							items:[{
									xtype:'button',
									iconCls:'icon-up',
									text:'Up',
									handler:function(){
										Ext.getCmp('bantuanprogramfilebrowserviewid').actionUp();
									}
							},{
									xtype: 'fileuploadfield',
									width: 330,
									id: 'tbantuandoc_file',
									emptyText: 'Pilih dokumen',
									//fieldLabel: 'Upload Dokumen',
									name: 'tbantuandoc_file',
									buttonText: '',
									buttonConfig: {
										iconCls: 'blueprint'
									}
							},{
									xtype:'button',
									text:'Upload',
									iconCls:'upload-icon',
									handler:function(){
										var form = this.up('form').getForm();
										var self = this;
										var bantuanId=Ext.getCmp('bantuanprogramfilebrowserviewid').bantuanId;
										var parentId=Ext.getCmp('bantuanprogramfilebrowserviewid').parentId;
										
										if(!parentId)
											parentId=0;
										if(form.isValid()){
											var share_file=0;
											Ext.Msg.confirm('Konfirmasi','Apakah nantinya File ini boleh dibagikan dengan penerima bantuan?',function(id,value){
												if(id==='yes'){
													share_file=1;
												}
												form.submit({
													url: 'TBantuanDoc/uploadDok',
													params:{bantuanId:bantuanId,parentId:parentId,share_file:share_file},
													waitMsg: 'Mohon tunggu hingga proses selesai...',
													success: function(form, action) {
														self.up('form').getForm().reset();
														Ext.getCmp('bantuanprogramfilebrowserviewid').getStore().load();
														//self.up('window').close();
														if(action.result.success=='0'){
															Ext.create('widget.uxNotification', {
																title: 'Notifikasi',
																position: 'br',
																manager: 'demo1',
																iconCls: 'ux-notification-icon-information',
																autoHideDelay: 5000,
																autoHide: true,
																spacing: 20,
																html: 'Dokumen Telah Disimpan.'
															}).show();   
														}
														
													},
													failure: function(form, action) {
														self.up('form').getForm().reset();
														if(action.result.message){
															alert(action.result.message);
														}
														Ext.getCmp('bantuanprogramfilebrowserviewid').getStore().load();
													}
												});
											});
											
										}  
									}
							},{
								xtype:'button',
								text:'Folder Baru',
								iconCls:'icon-new-folder',
								handler:function(){
									var form = this.up('form').getForm();
									var self = this;
									var bantuanId=Ext.getCmp('bantuanprogramfilebrowserviewid').bantuanId;
									var parentId=Ext.getCmp('bantuanprogramfilebrowserviewid').parentId;
									var dlg = Ext.MessageBox.prompt('Name', 'Please enter your new folder\'s name:', function(btn, text){
										if (btn == 'ok'){
											form.submit({
												url: 'TBantuanDoc/createNewFolder',
												params:{bantuanId:bantuanId,id:parentId,dirname:text},
												waitMsg: 'Mohon tunggu hingga proses selesai...',
												success: function(form, action) {
													self.up('form').getForm().reset();
													Ext.getCmp('bantuanprogramfilebrowserviewid').getStore().load();
													//self.up('window').close();
													if(action.result.success=='0'){
														Ext.create('widget.uxNotification', {
															title: 'Notifikasi',
															position: 'br',
															manager: 'demo1',
															iconCls: 'ux-notification-icon-information',
															autoHideDelay: 5000,
															autoHide: true,
															spacing: 20,
															html: 'Dokumen Telah Disimpan.'
														}).show();   
													}
													
												},
												failure: function(form, action) {
													self.up('form').getForm().reset();
													if(action.result.message){
														alert(action.result.message);
													}
													Ext.getCmp('bantuanprogramfilebrowserviewid').getStore().load();
												}
											});
										}
									});

									//var textboxEl = dlg.getDialog().body.child('input[class=ext-mb-input]', true);
									//textboxEl.setAttribute('maxlength', 1);   // second parameter is character length allowed so change it according to your need
									//dlg.show();
								}
							}]
						}],
						fbar:[{
							xtype:'label',
							id:'lblfileinfo',
							region:'east',
							text:''
						}]
						
					},{
						title:'Persyaratan Penerima',
						iconCls:'icon-system-config',
						layout: 'fit',
						id:'persyaratanpenerimaitemid',
						bantuanId:null,
						items:[Ext.create('Esmk.view.TBantuanPersyaratanPenerima._grid')]
					},{
						title:'Instrumen Pengajuan',
						layout:'fit',
						iconCls:'icon-system-config',
						kid:0,
						id:'instrumenverifikasipenerimaid',
						items:[Ext.create('Esmk.view.TKuesionerJawaban.form_pengisian')]
					},{
						title:'Pelaporan Pertanggung Jawaban',
						iconCls:'report-paper',
						layout: 'fit',
						id:'pelaporanpertanggungjawabanitemid',
						bantuanId:null,
						items:[Ext.create('Esmk.view.TBantuanProgram._daftar_laporan_grid')]
					},{
						title:'Agenda',
						layout: 'fit',
						iconCls:'icon-task',
						id:'agendaitemid',
						items:[Ext.create('Esmk.view.TBantuanJadwalKegiatan._grid')]
					},{
						title:'Daftar Peserta',
						iconCls:'icon-bank',
						layout: 'fit',
						id:'daftarpesertatabitemid',
						bantuanId:null,
						title:'Daftar Penerima',
						//items:[Ext.create('Esmk.view.TBantuanProgram._daftar_peserta_grid')]
					},
					
				]
			}
		],
		listeners: {
			collapse: function(){
				this.setDisabled(true);
			},
			
		},
	}],
	actionUpload:function(){
		alert(1);
		return;
		
		
		
	}
});