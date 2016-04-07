Ext.define('Esmk.view.TDashboard._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tdashboardForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Pengelolaan Dashboard',
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
								name: 'authitem_name',
								id:'combo_authitem_name',
								fieldLabel:'Nama Group',
								flex:1,
								autoWidth:true,
								store:Ext.create('Esmk.store.AuthItem'),
								mode: 'remote',
								valueField: 'name',
								displayField: 'name',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								matchFieldWidth: false,
								minChars:2,
								editable: true,
								emptyText:'Pilih Group',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Group Tidak Ditemukan',
									//width: '23%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{name}</span>';
									}
								},
								listeners:{
									select: function(combo, records, index) {
										var value = records[0].get('t_kuesioner_id');
										//me.loadKuesioner(value);
									},
									
								}
									
							},{
                                xtype: 'textfield',
                                fieldLabel: 'Kode Module',
                                name: 'kode_module',
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Properties',
                                name: 'properties',
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
			Ext.getCmp('tdashboardgridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TDashboard');
            record.set(values);
            Ext.getCmp('tdashboardgridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tdashboardgridid').getStore().load();
        }
		
        win.close();
    },
});


