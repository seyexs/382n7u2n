Ext.define('Esmk.view.Proposal.index', {
    extend: 'Ext.Container',
    xtype: 'framed-panels',
    width: 970,
	requires: [
		'Ext.layout.container.Column',
		'Ext.layout.container.Anchor',
		'Ext.grid.property.*',
	],
    layout:'column',
	//style:'background:#ffffff;',
    defaults: {
        bodyPadding: 10,
		style:'background:#ffffff;',
        frame: true,
    },
	autoScroll: true,
	listeners:{
		'afterrender':function(){
			
			/*Ext.create('widget.uxNotification', {
                title: 'Informasi',
                position: 't',
                manager: 'demo1',
                iconCls: 'ux-notification-icon-information',
                autoHideDelay: 10000,
                autoHide: true,
				width:300,
				closeable:true,
                spacing: 0,
                html: 'Form Tidak valid, periksa kembali kelengkapan form.'
            }).show();*/
		}
	},
    initComponent: function () {
		var me=this;
        this.items = [
			{
				xtype:'container',
				style:'margin: 10px;',
				columnWidth:0.60,
				flex:true,
				layout:'fit',
				border:0,
				frame:false,
				defaults: {
					xtype: 'panel',
					bodyPadding: 10,
					
					frame: true,
				},
				items:[
				{
					xtype: 'form',
					bodyPadding: '10 10 0 10',
					border: false,
					title:'Proposal Pengajuan',
					iconCls:'report-paper',
					//style: 'background-color: #fff;',
					autoScroll: true,
					fieldDefaults: {
						anchor: '100%',
						labelAlign: 'left',
						allowBlank: false,
						combineErrors: true,
						msgTarget: 'side',
						labelWidth: 100,
					},
					dockedItems:{
						xtype: 'toolbar',
						dock:'top',
						items:['->',{
							xtype:'button',
							text:'Simpan',
							iconCls:'icon-save',
							handler:function(){
								me.actionSave(this);
							}
						}]
					},
					items: [
						{
							xtype:'combobox',
							name: 't_bantuan_program_id',
							id:'combo_t_bantuan_id',
							fieldLabel:'Nama Bantuan',
							flex:1,
							autoWidth:true,
							store:Ext.create('Esmk.store.TBantuanProgram'),
							mode: 'remote',
							valueField: 'id',
							displayField: 'nama',
							typeAhead: true,
							forceSelection: true,
							pageSize: 30,
							matchFieldWidth: false,
							minChars:2,
							editable: true,
							emptyText:'Pilih Bantuan',
							listConfig: {
								loadingText: 'Proses Pencarian...',
								emptyText: 'Bantuan Tidak Ditemukan',
								//width: '23%',
								//height:300,
								autoHeight:true,
								getInnerTpl: function() {
									return '<span style="margin-top:2px;margin-left:2px;">Nama: {nama}</br>Tahun: {tahun}</br>{r_bantuan_name}</span>';
								}
							},
							listeners:{
								select: function(combo, records, index) {
									var value = records[0].get('t_kuesioner_id');
									me.loadKuesioner(value);
									me.loadDetailBantuan(records[0].get('id'));
								},
								change:function(combo, records, index){
									//var value = records[0].get('t_kuesioner_id');
									//me.loadKuesioner(value);
								}
							}
								
						},{
							xtype: 'fileuploadfield',
							width: 330,
							id: 'file_lampiran',
							emptyText: 'Pilih dokumen',
							fieldLabel: 'Lampiran Dokumen',
							name: 'file_lampiran',
							buttonText: '',
								buttonConfig: {
								iconCls: 'blueprint'
							}
						},{
							xtype:'tabpanel',
							layout:'fit',
							border:0,
							frame:false,
							items:[{
								title:'Uraian',
								iconCls:'icon-pencil2',
								layout:'fit',
								border:0,
								frame:false,
								items:[new Ext.form.HtmlEditor({
										name : 'uraian',
										emptyText:'Uraian',
										flex:1,
										height:360,
										autoWidth:true
								})]
							},{
								title:'Form Pengajuan',
								iconCls:'icon-post',
								layout:'fit',
								id:'formpertanyaantabitemid',
								border:0,
								frame:false,
								items:[{xtype:'panel',id:'formpengisianpertanyaanpanelid'}]
								//items:[Ext.create('Esmk.view.Proposal.form_pengisian')]
							}]
						}]
				}
				]
			},{
				xtype:'container',
				style:'margin: 10px;',
				columnWidth:0.40,
				flex:true,
				layout:'fit',
				border:0,
				frame:false,
				items:[{
					xtype:'tabpanel',
					//tabPosition: 'left',
					height:525,
					width: 300,
					defaults:{
						bodyPadding:2
					},
					items:[{
						title:'Info Bantuan',
						id:'detailbantuantabitemid',
						iconCls:'icon-information',
						autoScroll:true,
						html:'<div style="margin:10px">Silahkan memilih jenis bantuan pada kolom "Nama Bantuan"</div>',
						items:[],
					},{
						title:'Biodata Sekolah',
						iconCls:'icon-bank',
						layout:'fit',
						items:[me.biodataSekolah]
					}]
				}]
			},
			
            
        ];

        this.callParent();
    },
	loadDetailBantuan:function(tbid){
		Ext.Ajax.request({
			url: base_url + 'TBantuanProgram/GetDetailBantuan',
			method:'POST',
			params:{tbid:tbid},
			success: function(response){
				var pnl=Ext.getCmp('detailbantuantabitemid');
				pnl.update(response.responseText);
			}
		});
	},
	loadBiodata:function(form){
		var store = Ext.create('Esmk.store.Sekolah');
		store.getProxy().api.read='sekolah/GetBiodata';
		store.load(function(){
			store.each(function(record){
				form.getForm().loadRecord(record);
			});
		});
		
		
	},
	loadKuesioner:function(kid){
		var tab=Ext.getCmp('formpertanyaantabitemid');
		tab.remove('formpengisianpertanyaanpanelid',true);
		var storeKuesioner=Ext.create('Esmk.store.TKuesionerPertanyaan');
		storeKuesioner.getProxy().api.read='TKuesionerPertanyaan/read/?kid='+kid;
		storeKuesioner.pageSize=100;
		var form={
			xtype:'panel',
			kid:kid,
			id:'formpengisianpertanyaanpanelid',
			bodyPadding: '10 10 0 10',
            style: 'background-color: #fff;',
            autoScroll: true,
			height:400,
			flex:1,
			autoScroll:true,
			items:[],
			listeners:{
				'afterrender':function(){
				}
			}
		};
		storeKuesioner.load(function(records){
			Ext.each(records,function(record){
				var opt=record.get('options');
				var id=record.get('id');
				var name='tkuesionerjawaban[k'+id+']';
				
				
				
				var items=[];
				if(opt && record.get('jenis_jawaban')=='1'){
					Ext.each(opt,function(o){
						items.push({
							name:name+'[t_kuesioner_pilihan_jawaban_id]',
							inputValue:o.id,
							bodyPadding: '10 0 10 0',
							boxLabel:o.pilihan_jawaban,
						});
					});
					var input={
						xtype:'radiogroup',
						allowBlank:false,
						layout:'anchor',
						bodyPadding: '10 0 10 0',
						defaults: {
							name: name,
						},
						items:items
					};
				}else if(opt && record.get('jenis_jawaban')=='2'){
					var input={
						xtype:'panel',
						border:0,
						style:'background:#fff;',
						flex:true,
						layout:'fit',
						bodyPadding: '10 0 10 0',
						frame:false,
						items:[]
					};
					input.items.push({
							xtype:'textareafield',
							name:name+'[catatan_jawaban]',
							anchor:'100%',
							flex:1,
							width:450,
							grow:true,
							border:0,
						});
					Ext.each(opt,function(op){
						input.items.push({
							xtype:'textfield',
							name:name+'[t_kuesioner_pilihan_jawaban_id]',
							value:op.id,
							hidden:true,
							style:'background:#fff;',
							border:0
						});
					});
					//alert(input.items[0].xtype);
					//alert(input.items[1].xtype);
					
				}
				
				var row={
					xtype:'panel',
					layout:'anchor',
					style:'background:#fff;border-bottom:1px solid #cccccc;',
					bodyPadding: '5 0 5 0',
					frame:false,
					border:0,
					items:[{
						xtype:'panel',
						html:record.get('pertanyaan'),
						frame:false,
						border:0
					},input]
				}
				form.items.push(row);
			});
			tab.add(form);
		});
		
		/*tab.add(Ext.create('Esmk.view.Proposal.form_pengisian',{
			kid:kid
		}));*/
	},
	actionSave:function(btn){
		var form=btn.up('form').getForm();
		if(form.isValid()){
            form.submit({
				url: 'index.php/Proposal/Create',
                waitMsg: 'Mohon tunggu hingga proses selesai...',
                success: function(form, action) {

                    Ext.create('widget.uxNotification', {
                        title: 'Notifikasi',
                        position: 'br',
                        manager: 'demo1',
                        iconCls: 'ux-notification-icon-information',
                        autoHideDelay: 5000,
                        autoHide: true,
                        spacing: 20,
                        html: 'Proposal Pengajuan Bantuan Dikirim',
                    }).show();
                    self.close();                                
                },
                failure: function(form, action) {
                    Ext.Msg.show({
                        title: 'Gagal',
                        msg:  action.result.message,
                        minWidth: 200,
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            });
        }else{
            Ext.create('widget.uxNotification', {
                title: 'Informasi',
                position: 'br',
                manager: 'demo1',
                iconCls: 'ux-notification-icon-information',
                autoHideDelay: 4000,
                autoHide: true,
                spacing: 20,
                html: 'Form Tidak valid, periksa kembali kelengkapan form.'
            }).show();
        }
	},
});

