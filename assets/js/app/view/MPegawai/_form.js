Ext.define('Esmk.view.MPegawai._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.mpegawaiForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Pegawai',
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
                        title: '<b>Form Isian</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'NIP',
                                name: 'nip',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nama',
                                name: 'nama',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Gelar Depan',
								allowBlank:true,
                                name: 'gelar_depan',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Gelar Belakang',
								allowBlank:true,
                                name: 'gelar_belakang',
                                                                
                            },{
									xtype:'radiogroup',
									fieldLabel:'Jenis Kelamin',
									allowBlank:false,
									defaults: {
										name: 'jenis_kelamin',
										//margin: '0 15 0 0'
									},
									items:[{
										inputValue:'1',
										boxLabel:'Pria',
									},{
										inputValue:'0',
										boxLabel:'Wanita',
										//checked:true
									}]
							},
                             
                            /*{
                                xtype: 'textfield',
                                fieldLabel: 'FOTO',
                                name: 'foto',
                                                                
                            },*/
                             
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Tanggal Lahir',
                                name: 'tanggal_lahir',
                                format:'Y-m-d'                                
                            },
                             
                            
                                                     
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
			var dt=new Date(values.tanggal_lahir);
			values.tanggal_lahir=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
            record.set(values); //saving line
			Ext.getCmp('mpegawaigridid').getStore().load();
        } else {
			var dt=new Date(values.tanggal_lahir);
			values.tanggal_lahir=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
            record = Ext.create('Esmk.model.MPegawai');
            record.set(values);
            Ext.getCmp('mpegawaigridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('mpegawaigridid').getStore().load();
        }
		
        win.close();
    },
});


