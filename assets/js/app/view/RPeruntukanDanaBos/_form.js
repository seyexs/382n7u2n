Ext.define('Esmk.view.RPeruntukanDanaBos._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.rperuntukandanabosForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Peruntukan Dana Bos',
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
                        items: [
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                 
                                hidden:true,
                                                                
                            },
                             
                            {
                                xtype: 'textareafield',
                                fieldLabel: 'Peruntukan Dana',
                                name: 'peruntukan_dana',
                                                                
                            },new Ext.form.HtmlEditor({
								name : 'penjelasan',
								fieldLabel: 'Penjelasan',
								emptyText:'Uraian',
								flex:1,
								autoWidth:true
							})
                             
                            
                                                     
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
            record.set(values); //saving line
			Ext.getCmp('rperuntukandanabosgridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.RPeruntukanDanaBos');
            record.set(values);
            Ext.getCmp('rperuntukandanabosgridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('rperuntukandanabosgridid').getStore().load();
        }
		
        win.close();
    },
});


