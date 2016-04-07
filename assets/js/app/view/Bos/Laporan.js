Ext.define('Esmk.view.Bos.Laporan', {
    extend: 'Ext.Container',
	style:'background:#fff',
	border:0,
	id:'laporanid',
	tbpid:0,
	
	initComponent: function () {
		var me=this;
		this.items=[{
			xtype:'panel',
			style:'background:#fff',
			id:'viewlaporanid'+me.id,
			maxHeight:520,
			autoScroll:true,
			border:0,
			frame:false,
			dockedItems:[{
				xtype:'toolbar',
				dock:'top',
				items:['->',{
					xtype:'button',
					text:'Export Exel',
					iconCls:'icon-xls',
					handler:function(){
						var params=me.dataParams;
						params.pdf=1;
						//var tbpid=Ext.getCmp('paktaintegritasid').tbpid;
						//tgl_cut_off=this.up('panel').tanggalCutOff;
						var form = Ext.create('Ext.form.Panel', {
							standardSubmit: true,
							url: me.dataUrl,
							method:'POST'
						});
						
									// Call the submit to begin the file download.
						form.submit({
							target: '_blank', // Avoids leaving the page. 
							params:params
						});
						Ext.defer(function(){
							form.close();
						}, 100);
									
					}
				}]
			}]
		}];
		Ext.Ajax.request({
			url:me.dataUrl,
			method:'POST',
			params:me.dataParams,
			success: function(response){
				me.update('');
				Ext.getCmp('viewlaporanid'+me.id).update(response.responseText);
				
			}
		});
		
		this.callParent();
	},
	
	actionExportPdf:function(){
		
	}
});