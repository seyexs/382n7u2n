Ext.define('Esmk.view.Groups._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.GroupsForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        //'Ext.ux.DataTip',
        'Ext.data.*'
    ],
	//controller:['Esmk.controller.GroupsController'],
    title: 'Form Groups',
    layout: 'fit',
    autoShow: true,
    width: 600,
    //height: 400,
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
                        title: '<b>Groups</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [
                            
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Group Name',
                                name: 'name',
                                hidden:false,
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Group Description',
                                name: 'description',
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
							me.actionSave();
						}
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Batal',
                        action: 'cancel',
						handler:function(){
							me.actionCancel();
						}
                    },]
            }];

        this.callParent(arguments);


    },
	actionCancel:function(){
		this.close();
	},
	
	actionSave: function() {

        //var win = button.up('window'),
        var form = this.down('form');
        //record = form.getRecord();
        var values = form.getValues();
		
        var record = Ext.create('Esmk.model.AuthItem');
		record.set(values);
		var store = Ext.data.StoreManager.get("AuthItemStoreID");
				
		store.add(record);
		//store.sync();
        this.close();
        //this.getGroupsStore().sync(); use this code for autoSync : false

    },
    actionDelete: function(button) {

        var grid = this.getGroupsGrid();
        var record = grid.getSelectionModel().getSelection();
        var store = this.getGroupsStore();

        store.remove(record);
        //this.getGroupsStore().sync();

        this.getGroupsStore().load();
    },
	actionSearch: function(button) {
        var win = button.up('window'),
        form = win.down('textfield'),
        grid = win.down('grid'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});

    },
});


