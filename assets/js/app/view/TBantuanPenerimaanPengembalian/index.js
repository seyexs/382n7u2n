Ext.define('Esmk.view.TBantuanPenerimaanPengembalian.index', {
    extend: 'Ext.Container',
	style:'background:#fff',
	border:0,
	id:'indexpenerimaanpengembalianid',
	tbpid:0,
	items:[{
		xtype:'panel',
		style:'background:#fff',
		id:'viewpenerimaanpengembalianid',
		border:0,
		dockedItems:[{
			xtype:'toolbar',
			dock:'top',
			items:[{
				xtype:'button',
				text:'Buat Penerimaan/Pengembalian',
				iconCls:'icon-add',
				handler:function(){
					Ext.getCmp('indexpenerimaanpengembalianid').actionCreate();
				}
			},{
				xtype:'button',
				text:'Refresh',
				iconCls:'x-tbar-loading',
				handler:function(){
					Ext.getCmp('indexpenerimaanpengembalianid').actionLoad();
				}
			}]
		}],
		frame:false,
		html:''
	}],
	initComponent: function () {
		var me=this;
		this.actionLoad();
		
		this.callParent();
	},
	actionLoad:function(){
		var me=this;
		Ext.Ajax.request({
			url: 'TBantuanPenerimaanPengembalian/GetPenerimaanPengembalian',
			method:'POST',
			params:{tbpid:me.tbpid},
			success: function(response){
				Ext.getCmp('viewpenerimaanpengembalianid').update(response.responseText);
			}
		});
	},
	actionUpdate: function(dataview, record) { //function(grid, record) {
		var me=this;
        var formTBantuanPenerimaanPengembalian = Ext.create('Esmk.view.TBantuanPenerimaanPengembalian._form',{
			tbpid:me.tbpid
		});

        if (record) {

            formTBantuanPenerimaanPengembalian.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },
	actionHapus:function(tppid){
		var me=this;
		Ext.Msg.confirm('Konfirmasi','Apakah anda yakin?',function(id,value){
			if(id==='yes'){
				
				Ext.Ajax.request({
					url: base_url + 'TBantuanPenerimaanPengembalian/Hapus',
					method:'POST',
					params:{tppid:tppid},
					success: function(response){
						me.actionLoad();
					}
				});
			}
			
		});
	}
});