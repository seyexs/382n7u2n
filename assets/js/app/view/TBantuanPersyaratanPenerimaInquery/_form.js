Ext.define('Esmk.view.TBantuanPersyaratanPenerimaInquery._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpersyaratanpenerimainqueryForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Query Kriteria Data',
    layout: 'fit',
    autoShow: true,
    width: 900,
    autoHeight:true,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var rbid=this.rbid;
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
								fieldLabel:'Nama Bantuan',
								name: 't_bantuan_program_id',
								id:'combo_t_bantuan_program_id',
								autoWidth:true,
								store:Ext.create('Esmk.store.TBantuanProgram'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'nama',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								disabled:me.status,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Nama Bantuan',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Bantuan Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
									}
								},
								listeners:{
									'afterrender':function(){
										if(!me.status)
											this.getStore().getProxy().api.read="TBantuanPersyaratanPenerimaInquery/GetBantuan/?rbid="+rbid;
										
									}
								}
								
							},{
                                xtype: 'textarea',
                                fieldLabel: 'Skrip Query',
								height:300,
								grow: true,
                                name: 'query',
                                                                
                            },{
                                xtype: 'textarea',
                                fieldLabel: 'Keterngan Query',
                                name: 'keterangan',
                                                                
                            },
                                                     
                        ],
                    }],
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
			Ext.getCmp('tbantuanpersyaratanpenerimainquerygridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanPersyaratanPenerimaInquery');
            record.set(values);
            Ext.getCmp('tbantuanpersyaratanpenerimainquerygridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanpersyaratanpenerimainquerygridid').getStore().load();
        }
		
        win.close();
    },
});


