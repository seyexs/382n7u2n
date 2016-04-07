Ext.define('Esmk.view.TKuesionerPilihanJawaban._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tkuesionerpilihanjawabanForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Esmk - Form TKuesionerPilihanJawaban',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'icon-new-data',
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
                        title: '<b>TKUESIONERPILIHANJAWABAN</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                 
                                hidden:true,
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'T_PERTANYAAN_ID',
                                name: 't_pertanyaan_id',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'PILIHAN_JAWABAN',
                                name: 'pilihan_jawaban',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'KETERANGAN_TAMBAHAN',
                                name: 'keterangan_tambahan',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'URUTAN',
                                name: 'urutan',
                                                                
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
                        text: 'Batal',
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
            record.set(values); //saving line
        } else {
            record = Ext.create('Esmk.model.TKuesionerPilihanJawaban');
            record.set(values);
            Ext.getCmp('tkuesionerpilihanjawabangridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('tkuesionerpilihanjawabangridid').getStore().load();
        win.close();
    },
});


