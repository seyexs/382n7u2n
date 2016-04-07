Ext.define('Esmk.view.Groups.detail', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.GroupsForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        //'Ext.ux.DataTip',
        'Ext.data.*'
    ],
	//controller:['Esmk.controller.GroupsController'],
    title: 'Detail Group',
    layout: 'fit',
    autoShow: true,
    width: 800,
    height: 600,
	autoScroll:true,
	//autoHeight:true,
	iconCls: 'icon-base',
    initComponent: function() {
		var me=this;
		var availableFunction=Ext.create("Esmk.store.FunctionRegistration");
		var me=this;
        this.items = [
			{
				xtype:'tabpanel',
				border:0,
				plain:true,
				items:[{
					title:'Module Assignment',
					iconCls:'icon-function',
					items:[
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
									title: '<b>Group Info</b>',
									collapsible: false,
									layout: 'anchor',
									items: [
										
										{
											xtype: 'textfield',
											fieldLabel: 'Group Name',
											name: 'name',
											hidden:false,
											disabled:true                                
										},{
											xtype: 'textfield',
											fieldLabel: 'Group Description',
											name: 'description',
											listeners:{
												blur:function(){
													me.actionUpdateDescription(this.up('form'));
												}
											}
										}
																 
									]
								},{
									xtype:'tabpanel',
									border:0,
									plain:true,
									layout: 'fit',
									items:[
										new Ext.create('Esmk.view.Groups.registered_function_grid',{
											groupName:me.groupName
										}),
										new Ext.create('Esmk.view.Groups.unregistered_function_grid',{
											groupName:me.groupName
										})
									]
								}]
						}
					]
				},{
					title:'Users',
					iconCls:'icon-user-config',
					items:[new Ext.create('Esmk.view.Groups.users_grid',{
						groupName:me.groupName
					})]
				}]
			},
			
		];

        this.dockedItems = [];

        this.callParent(arguments);


    },
	actionCancel:function(){
		this.close();
	},
	actionUpdateDescription:function(form){
		var record = form.getRecord(),
			values = form.getValues();
		
        record.set(values); //saving line
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


