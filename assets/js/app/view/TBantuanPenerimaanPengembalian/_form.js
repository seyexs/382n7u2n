Ext.define('Esmk.view.TBantuanPenerimaanPengembalian._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpenerimaanpengembalianForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Penerimaan & Pengembalian',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'bogus',
    initComponent: function() {
		var me=this;
        this.items = [
            {
                xtype: 'form',
                bodyPadding: '10 10 0 10',
                border: false,
                style: 'background-color: #fff;',
                autoScroll: true,
                fieldDefaults: {
                    anchor: '100%',
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: true,
                    msgTarget: 'side',
                    labelWidth: 200,
                },
                items: [
                    {
                        xtype: 'fieldset',
                        title: '<b>Form Isian</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                hidden:true,
                                allowBlank:true                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'T_BANTUAN_PENERIMA_ID',
                                name: 't_bantuan_penerima_id',
                                value:me.tbpid,
								hidden:true
                            },{
								xtype:'radiogroup',
								fieldLabel:'Jenis Transaksi',
								allowBlank:false,
								defaults: {
									name: 'status',
									//margin: '0 15 0 0'
								},
								items:[{
									inputValue:'1',
									boxLabel:'Penerimaan',
								},{
									inputValue:'0',
									boxLabel:'Pengembalian',
									//checked:true
								}]
							},{
                                xtype: 'datefield',
                                fieldLabel: 'Tanggal Diterima/Dikembalikan',
                                name: 'tanggal_diterima_dikembalikan',
								format:'Y-m-d',
								submitFormat:'Y-m-d',
								value:new Date()
                                                                
                            },{
                                xtype: 'numberfield',
                                fieldLabel: 'Jumlah Bantuan',
                                name: 'jumlah_bantuan',
                                                                
                            },{
								xtype: 'fileuploadfield',
								//width: 330,
								id: 'bukti_diterima_dikembalikan',
								emptyText: 'Lampiran dalam bentuk gambar',
								fieldLabel: 'Lampiran Bukti Penerimaan',
								name: 'bukti_diterima_dikembalikan',
								buttonText: '',
								buttonConfig: {
									iconCls: 'blueprint'
								}
							}
                             
                            
                                                     
                        ]
                    }]
            }];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
                        iconCls: 'icon-save',
                        text: 'Simpan',
                        action: 'save',
						handler:function(){
							me.actionSubmit(this);
						}
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Tutup',
                        action: 'cancel',
						handler:function(button, e, options){
							me.actionCancel(button, e, options);
						}
                    },]
            }];

        this.callParent(arguments);


    },
	actionReset: function(button, e, options) {
        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
    },

    actionCancel: function(button, e, options) {

        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
        win.close();

    },
	actionSubmit:function(button){
		var win = button.up('window'),
        form = win.down('form').getForm();
		if(form.isValid()){
            form.submit({
				url:'TBantuanPenerimaanPengembalian/Create',
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
                        html: 'Data Telah Disimpan',
                    }).show();
					Ext.getCmp('indexpenerimaanpengembalianid').actionLoad();
                    win.close();
					
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
	actionSave: function(button) {

        var win = button.up('window'),
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
        if (values.id !='') {
			var dt=new Date(values.tanggal_diterima_dikembalikan);
			values.tanggal_diterima_dikembalikan=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
        
            record.set(values); //saving line
			Ext.getCmp('tbantuanpenerimaanpengembaliangridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanPenerimaanPengembalian');
			var dt=new Date(values.tanggal_diterima_dikembalikan);
			values.tanggal_diterima_dikembalikan=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
			
            record.set(values);
            Ext.getCmp('tbantuanpenerimaanpengembaliangridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanpenerimaanpengembaliangridid').getStore().load();
        }
		
        win.close();
    },
});


