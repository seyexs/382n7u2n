Ext.define('Esmk.view.Menu._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.menuForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Menu',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var me=this;
		var parentStore=Ext.create('Esmk.store.Menu');
		parentStore.getProxy().api.read='menu/GetDropDownData';
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
								fieldLabel:'Parent Menu',
								name: 'parent_id',
								id:'parent_id',
								width:200,
								store:parentStore,
								mode: 'local',
								queryMode:'local',
								valueField: 'id',
								displayField: 'title',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Parent Menu',
								listeners:{
									afterrender:function(){
										Ext.getCmp('parent_id').getStore().load();
									}
								},
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Menu Ditemukan',
									width: '24%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{title}</span>';
									}
								},
								
							}, 
                            {
                                xtype: 'textfield',
                                fieldLabel: 'SORT',
                                name: 'sort',
                                                                
                            },
                             
                                                       
                            {
                                xtype: 'textfield',
                                fieldLabel: 'TITLE',
                                name: 'title',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'URL',
                                name: 'url',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'BIZRULE',
                                name: 'bizrule',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'CSSCLASS',
                                name: 'cssclass',
                                                                
                            },new Ext.form.HtmlEditor({
								name : 'petunjuk_penggunaan',
								emptyText:'Uraian',
								flex:1,
								//height:360,
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
            record = Ext.create('Esmk.model.Menu');
            record.set(values);
            Ext.getCmp('menugridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('menugridid').getStore().load();
        win.close();
    },
});


