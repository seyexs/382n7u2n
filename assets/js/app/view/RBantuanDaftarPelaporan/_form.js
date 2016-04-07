Ext.define('Esmk.view.RBantuanDaftarPelaporan._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.rbantuandaftarpelaporanForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Pelaporan Pertanggung Jawaban',
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
                                fieldLabel: 'ID',
                                name: 'id',
                                hidden:true,
                                                                
                            },
							{
								xtype:'combobox',
								fieldLabel:'Jenis Bantuan',
								name: 'r_bantuan_id',
								id:'combo_id_kab',
								width:200,
								store:Ext.create('Esmk.store.RBantuan'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'name',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Jenis Bantuan',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Jenis Bantuan Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{name}</span>';
									}
								},
								listeners:{
									'beforerender':function(){
										this.getStore().getProxy().api.read="RBantuan/RawRead";
									}
								}
								
							},{
                                xtype: 'textfield',
                                fieldLabel: 'Nama Laporan',
                                name: 'nama',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Modul',
                                name: 'kode_module',
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Properties',
                                name: 'properties',
                                                                
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
            record.set(values); //saving line
			Ext.getCmp('rbantuandaftarpelaporangridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.RBantuanDaftarPelaporan');
            record.set(values);
            Ext.getCmp('rbantuandaftarpelaporangridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('rbantuandaftarpelaporangridid').getStore().load();
        }
		
        win.close();
    },
});


