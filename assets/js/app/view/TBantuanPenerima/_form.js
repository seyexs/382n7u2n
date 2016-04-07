Ext.define('Esmk.view.TBantuanPenerima._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpenerimaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Siban - Form TBantuanPenerima',
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
                                                                
                            },{
								xtype:'combobox',
								fieldLabel:'Pemilik',
								name: 'sekolah_id',
								id:'combo_sekolah_id',
								width:200,
								store:Ext.create('Esmk.store.Sekolah'),
								mode: 'remote',
								valueField: 'sekolah_id',
								displayField: 'nama',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								allowBlank:true,
								emptyText:'Pemilik',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Data Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
									}
								},
								listeners:{
									'beforerender':function(){
										//this.getStore().getProxy().api.read="RBantuan/RawRead";
									}
								}
								
							},{
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
									},
									change:function(combo, records, index){
										//var value = records[0].get('t_kuesioner_id');
										//me.loadKuesioner(value);
									}
								}
									
							},{
                                xtype: 'textfield',
                                fieldLabel: 'Jumlah',
                                name: 'jumlah_bantuan',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Tanggal SK',
                                name: 'tanggal_cetak_sk',
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
            record.set(values); //saving line
			Ext.getCmp('tbantuanpenerimagridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanPenerima');
            record.set(values);
            Ext.getCmp('tbantuanpenerimagridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanpenerimagridid').getStore().load();
        }
		
        win.close();
    },
});


