Ext.define('Esmk.view.Bos.LaporanAwal', {
    extend: 'Ext.Container',
	style:'background:#fff',
	border:0,
	id:'laporanawalid',
	tbpid:0,
	items:[{
		xtype:'panel',
		style:'background:#fff',
		id:'laporanawalboscontainerid',
		border:0,
		dockedItems:[{
			xtype:'toolbar',
			dock:'top',
			items:['->',{
				xtype:'button',
				text:'Export PDF',
				iconCls:'icon-pdf',
				handler:function(){
					var tbpid=Ext.getCmp('laporanawalid').tbpid;
					var form = Ext.create('Ext.form.Panel', {
						standardSubmit: true,
						url:'UsulanBos/GetLaporanAwal',
						method:'POST'
						
					});

					// Call the submit to begin the file download.
					form.submit({
						target: '_blank', // Avoids leaving the page. 
						params:{tbpid:tbpid,pdf:true}
					});
					Ext.defer(function(){
						form.close();
					}, 100);
					
				}
			}]
		}],
		frame:false,
		html:''
	}],
	initComponent: function () {
		var me=this;
		Ext.Ajax.request({
			url: base_url + 'UsulanBos/GetLaporanAwal',
			method:'POST',
			params:{tbpid:me.tbpid},
			success: function(response){
				Ext.getCmp('laporanawalboscontainerid').update(response.responseText);
			}
		});
		
		this.callParent();
	},
	
	actionExportPdf:function(){
		
	}
});