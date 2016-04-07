Ext.define('Esmk.view.TBantuanUsulanPenerima._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanusulanpenerimaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Usulan Penerima Bantuan',
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
                bodyPadding: '10 5 0 10',
                border: false,
                style: 'background-color: #fff;',
                autoScroll: true,
                fieldDefaults: {
                    anchor: '100%',
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: true,
                    msgTarget: 'side',
                    labelWidth: 100,
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
								name: 't_bantuan_program_id',
								id:'combo_t_bantuan_id',
								fieldLabel:'Nama Bantuan',
								flex:true,
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
									width: '23%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">Nama: {nama}</br>Tahun: {tahun}</br>{r_bantuan_name}</span>';
									}
								},
								listeners:{
									click:function(combo, records, index){
										var value = records[0].get('id');
										me.loadDaftarSekolah(value);
									}
								}
									
							},{
								xtype:'combobox',
								fieldLabel:'Nama Sekolah',
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
								emptyText:'Pilih Sekolah',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Data Tidak Ditemukan',
									width: '23%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
									}
								},
								listeners:{
									
								}
								
							},new Ext.form.HtmlEditor({
								name : 'catatan',
								flex:1,
								fieldLabel:'Catatan',
								emptyText:'Catatan',
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
			Ext.getCmp('tbantuanusulanpenerimagridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanUsulanPenerima');
            record.set(values);
            Ext.getCmp('tbantuanusulanpenerimagridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanusulanpenerimagridid').getStore().load();
        }
		
        win.close();
    },
	loadDaftarSekolah:function(tbid){
		
	}
});


