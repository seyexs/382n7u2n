Ext.define('Esmk.view.TBantuanLaporanPhoto.index', {
    extend: 'Ext.Container',
	layout:'fit',
	maxHeight:530,
	height:530,
	title:'Foto Kegiatan',
	style:'background:#9197a3;',
	
	initComponent: function() {
        var me = this;
		this.items=[{
			xtype:'panel',
			
			layout:{
				type: 'vbox',
				align: 'center',
				//pack: 'center'
			},
			items:[{
				xtype:'form',
				id:'form-post-photo',
				margin:'2px',
				layout:{
					type: 'vbox',
					align: 'center',
					pack: 'center'
				},
				//width:600,
				bodyPadding:'5px 10px 5px 10px',
				items:[{
					xtype:'textareafield',
					name:'keterangan_photo',
					height:70,
					emptyText:'Keterangan',
					width:330
				},{
					xtype: 'fileuploadfield',
					width: 330,
					id: 'path_photo',
					emptyText: 'Pilih foto',
					//fieldLabel: 'Lampiran Dokumen',
					name: 'path_photo',
					buttonText: '',
					buttonConfig: {
						iconCls: 'icon-picture'
					}
				},{
					xtype:'radiogroup',
					width: 330,
					//fieldLabel:'Bentuk Bantuan',
					allowBlank:false,
					defaults: {
						name: 'kategori_progres',
						//margin: '0 15 0 0'
					},
					items:[{
						inputValue:'1',
						boxLabel:'0%',
					},{
						inputValue:'2',
						boxLabel:'25%',
						//checked:true
					},{
						inputValue:'3',
						boxLabel:'50%',
						//checked:true
					},{
						inputValue:'4',
						boxLabel:'100%',
						//checked:true
					}]
				},{
					xtype:'button',
					text:'Post',
					iconCls:'arrow',
					handler:function(){
						me.actionSubmitPost(this);
					}
				}]
			},{
				xtype:'panel',
				maxWidth:1500,
				autoScroll:true,
				layout:{
					type:'table',
					columns:4,
					align: 'center',
					pack: 'center',
					tdAttrs: { style: 'padding: 10px;' }
				},
				listeners:{
					'afterrender':function(){
						me.actionLoadPost();
					}
				},
				bodyPadding:'5px 10px 5px 10px',
				id:'post-preview',
				items:[]
			}]
		}];
		this.callParent(arguments);
	},
	actionSubmitPost(button){
		var me=this;
        var form = Ext.getCmp('form-post-photo').getForm();
		if(form.isValid()){
            form.submit({
				url:'TBantuanLaporanPhoto/SubmitPost',
				params:{tbpid:me.tbpid},
				waitMsg: 'Mohon tunggu hingga proses selesai...',
				success: function(form, action) {
					form.reset();
					
					Ext.create('widget.uxNotification', {
						title: 'Notifikasi',
						position: 'br',
						manager: 'demo1',
						iconCls: 'ux-notification-icon-information',
						autoHideDelay: 5000,
						autoHide: true,
						spacing: 20,
						html: 'Upload foto berhasil,Terima Kasih'
					}).show();
					me.actionLoadPost();								   
					//var now = new Date();
					//var src = document.getElementById('profile-image-crop').src;
					//document.getElementById('profile-image-crop').src = src + '?' + now.getTime(); 
														
				},
				failure: function(form, action) {
					//win.up('form').getForm().reset();
					Ext.Msg.show({
						title: 'Gagal',
						msg:  action.result.msg,
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.ERROR,
						buttons: Ext.Msg.OK
					});
				}
			});
		}else{
			alert('Silahkan lengkapi form tersebut!');
        }
	},
	showAction:function(pid){
		var me=this;
		var myWindow=Ext.create('Ext.window.Window', {
			title:'Pilihan Aksi',
			width:300,
			height:95,
			maximized:false,
			modal:true,
			id:'pilihanaksi'+pid,
			requires: ['Ext.form.Panel',
				'Ext.form.field.Text',
				'Ext.ux.DataTip',
				'Ext.data.*'
			],
			items:[{
				xtype:'button',
				text:'Hapus!',
				width:141,
				iconCls:'icon-delete',
				handler:function(){
					Ext.Msg.confirm('Konfirmasi Pengahapusan','Apakah anda yakin ingin menghapus data ini?',function(id,value){
						if(id==='yes'){
							Ext.Ajax.request({
								url:'TBantuanLaporanPhoto/DeletePost',
								method:'POST',
								params:{id:pid},
								success: function(response){
									me.actionLoadPost();
								}
							});
						}
						
					});
				}
			},{
				xtype:'button',
				text:'Perbesar',
				width:141,
				iconCls:'icon-search'
			}],
			buttons: [{ 
				text: 'Tutup',
				iconCls: 'icon-reset',
				handler:function(){
					myWindow.close();
				}
			}]
		});
		myWindow.show();
	}
});