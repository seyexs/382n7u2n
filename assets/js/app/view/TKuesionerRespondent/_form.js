Ext.define('Esmk.view.TKuesionerRespondent._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tkuesionerrespondentForm',
	id:'tkuesionerrespondentformid',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Esmk - Form TKuesionerRespondent',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var me=this;
		//var authItemStore=Ext.create('Esmk.store.AuthItem');
		//if(this.tkid!=0)
			//authItemStore.getProxy().api.read="TKuesionerRespondent/GetGroups/?tkid="+this.tkid;
		
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
                        title: '<b>TKUESIONERRESPONDENT</b>',
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
								fieldLabel:'Kuesioner',
								name: 't_kuesioner_id',
								id:'t_kuesioner_id',
								width:200,
								store:Ext.create('Esmk.store.TKuesioner'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'judul',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Kuesioner',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Kuesioner Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{judul}</span>';
									}
								},
								listeners:{
									select: function(combo, records, index) {
										var value = records[0].get(combo.valueField);
										Ext.getCmp('authitem_name').getStore().getProxy().api.read="TKuesionerRespondent/GetGroups/?tkid="+value;
									}
								}
								
							}, 
                            {
								xtype:'combobox',
								fieldLabel:'Kelompok Responden',
								name: 'authitem_name',
								id:'authitem_name',
								width:200,
								store:Ext.create('Esmk.store.AuthItem'),
								mode: 'remote',
								valueField: 'name',
								displayField: 'name',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Kelompok Pengguna',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Group Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{name}</span>';
									}
								},
								listeners:{
									change:function(){
										//var tkid=Ext.getCmp('tkuesionerrespondentformid').tkid;
										//alert(Ext.getCmp('authitem_name').getValue(true));
										//if(tkid!=0)
										//Ext.getCmp('authitem_name').getStore().getProxy().api.read="TKuesionerRespondent/GetGroups/?tkid="+tkid;
									}
								}
								
							},new Ext.form.HtmlEditor({
								name : 'keterangan',
								flex:1,
								height:85
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
        if (values.id!='') {
            record.set(values); //saving line
        } else {
            record = Ext.create('Esmk.model.TKuesionerRespondent');
            record.set(values);
            Ext.getCmp('tkuesionerrespondentgridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('tkuesionerrespondentgridid').getStore().load();
        win.close();
    },
});


