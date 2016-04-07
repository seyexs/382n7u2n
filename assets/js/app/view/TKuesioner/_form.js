Ext.define('Esmk.view.TKuesioner._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tkuesionerForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Master Keusioner',
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
                        title: '<b>Kuesioner</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                 
                                hidden:true,
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Nomor',
                                name: 'nomor',
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Judul',
                                name: 'judul',
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Keterangan',
                                name: 'keterangan',
                                                                
                            }/*,{
								xtype: 'checkbox',
								fieldLabel: 'telah siap?',
								name: 'is_open',
								inputValue:'1',
							} */
                            
                                                     
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
            record = Ext.create('Esmk.model.TKuesioner');
            record.set(values);
            Ext.getCmp('tkuesionergridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('tkuesionergridid').getStore().load();
        win.close();
    },
});


