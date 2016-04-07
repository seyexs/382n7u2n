Ext.define('Esmk.view.TBantuanPenerima._form_penerimaan_bantuan', {
    extend: 'Ext.panel.Panel', //use this code for panel form
    alias: 'widget.tbantuanpenerimaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Peneriamaan Bantuan',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
	border:0,
	frame:false,
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
                        items: [
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                 
                                hidden:true,
                                                                
                            },{
                                xtype: 'numberfield',
                                fieldLabel: 'Nilai/Jumlah Bantuan',
                                name: 'jumlah_bantuan',
                            },{
								xtype:'datefield',
								name:'tanggal_diterima_bantuan',
								format:'Y-m-d'
							},{
								xtype: 'fileuploadfield',
								width: 330,
								id: 'file_lampiran',
								emptyText: 'Lampiran dalam bentuk gambar',
								fieldLabel: 'Lampiran Bukti Penerimaan',
								name: 'bukti_penerimaan_bantuan',
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
							me.actionSave(this);
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
	actionSave: function(button) {

        var win = button.up('window'),
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
        if (values.id !='') {
			var dt=new Date(values.tanggal_diterima_bantuan);
			values.tanggal_diterima_bantuan=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
        
            record.set(values); //saving line
			Ext.getCmp('tbantuanpenerimagridid').getStore().load();
        } 
		
        win.close();
    },
});


