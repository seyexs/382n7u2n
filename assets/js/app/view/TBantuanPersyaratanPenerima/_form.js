Ext.define('Esmk.view.TBantuanPersyaratanPenerima._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpersyaratanpenerimaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
	t_bantuan_program_id:null,
    title: 'Kriteria Persyaratan Penerima',
    layout: 'fit',
    autoShow: true,
    width: 800,
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
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'T_BANTUAN_PROGRAM_ID',
                                name: 't_bantuan_program_id',
								hidden:true,
                                value:(me.t_bantuan_program_id)?me.t_bantuan_program_id:''                                
                            },{
                                xtype: 'numberfield',
                                fieldLabel: 'No Urut',
                                name: 'urutan',
                                                                
                            },{
                                xtype: 'textareafield',
                                fieldLabel: 'Kriteria Yang Diajukan',
                                name: 'keterangan',
                                                                
                            }/*{
									xtype:'radiogroup',
									fieldLabel:'Jenis Jawaban',
									allowBlank:false,
									defaults: {
										name: 'jenis_jawaban',
										//margin: '0 15 0 0'
									},
									items:[{
										inputValue:'1',
										boxLabel:'Ya/Tdk',
									},{
										inputValue:'2',
										boxLabel:'Upload File',
										//checked:true
									},{
										inputValue:'3',
										boxLabel:'Ya/Tidak & Upload File',
										//checked:true
									}]
							},{
                                xtype: 'numberfield',
                                fieldLabel: 'Skor Nilai jika terpenuhi/menjawab (Ya)',
                                name: 'skor',
                                                                
                            },*/
                                                     
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
			Ext.getCmp('detailbantuanprogrampanelid').down('#tbantuanpersyaratanpenerimagridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanPersyaratanPenerima');
            record.set(values);
            Ext.getCmp('tbantuanpersyaratanpenerimagridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('detailbantuanprogrampanelid').down('#tbantuanpersyaratanpenerimagridid').getStore().load();
        }
		
        win.close();
    },
});


