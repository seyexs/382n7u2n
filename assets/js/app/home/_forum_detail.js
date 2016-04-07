Ext.define('Esmk.home._forum_detail', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
	id:'forumdetailwindowid',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: '-',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'bogus',
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
                        xtype: 'panel',
                        //title: '<b>Detail</b>',
                        collapsible: false,
                        layout: 'fit',
                        items: [
                            new Ext.form.HtmlEditor({
								name : 'forum_content',
								flex:1,
								height:150
							})
                        ],
						dockedItems:[{
							xtype: 'toolbar',
							//dock: 'bottom',
							//ui: 'footer',
							items: [ 
								{
									iconCls: 'icon-save',
									text: 'Simpan',
									action: 'save',
									handler:function(){
										me.actionSave(this);
									}
								}]
						}]
                    },{
						xtype:'panel',
						layout:'anchor',
						items:[]
					}],
            }];
		
        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
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


