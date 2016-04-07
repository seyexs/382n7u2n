Ext.define('Esmk.view.Bos.penggunaan_dana_sekolah', {
    extend: 'Ext.Container',
	style:'background:#fff',
	border:0,
	id:'lappengunaandanasekolahid',
	tbpid:0,
	items:[{
		xtype:'panel',
		style:'background:#fff',
		id:'viewpenggunaandanasekolahid',
		border:0,
		
		frame:false,
		html:''
	}],
	initComponent: function () {
		var me=this;
		Ext.Ajax.request({
			url:'UsulanBos/GetPaktaIntegritas',
			method:'POST',
			params:{tbpid:me.tbpid},
			success: function(response){
				var json=Ext.JSON.decode(response.responseText);
				var tabitems=[];
				
				for(var i=0;i<json.data.length;i++){
					var dt=json.data[i];
					tabitems.push({
						tanggalCutOff:dt.tgl_cut_off,
						title:'Cut Off '+dt.tgl_cut_off,
						iconCls:'icon-grid',
						id:'datacutoff-'+dt.tgl_cut_off,
						loader: {
							url: 'TBantuanPenggunaanDana/GetViewPenggunaanDana/?tbpid='+me.tbpid+'&tanggal='+dt.tgl_cut_off+'&s='+me.modeData,
							renderer: 'html',
							autoLoad: true,
							scripts: true
						},
						dockedItems:[{
							xtype:'toolbar',
							dock:'top',
							items:['->',{
								xtype:'button',
								text:'Export PDF',
								iconCls:'icon-pdf',
								handler:function(){
									//var tbpid=Ext.getCmp('paktaintegritasid').tbpid;
									tgl_cut_off=this.up('panel').tanggalCutOff;
									var form = Ext.create('Ext.form.Panel', {
										standardSubmit: true,
										url: 'TBantuanPenggunaanDana/GetViewPenggunaanDana/',
										method:'GET',
									});
						
									// Call the submit to begin the file download.
									form.submit({
										target: '_blank', // Avoids leaving the page. 
										params:{tbpid:me.tbpid,pdf:1,tanggal:tgl_cut_off,s:me.modeData},
									});
									Ext.defer(function(){
										form.close();
									}, 100);
									
								}
							}]
						}]
						
					});
				}
				
				Ext.getCmp('viewpenggunaandanasekolahid').add({
					xtype:'tabpanel',
					layout:'fit',
					items:tabitems,
					
				});
				
			}
		});

		
		this.callParent();
	},
	
	actionExportPdf:function(){
		
	}
});