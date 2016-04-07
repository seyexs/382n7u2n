Ext.define('Esmk.view.TBantuanTimPengelola._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuantimpengelolaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Tim Pengelola',
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
                                                                
                            },
                            {
								xtype:'panel',
								layout:'hbox',
								border:0,
								bodyPadding:'0 0 10 0',
								frame:false,
								items:[{
									flex:1,
									xtype:'combobox',
									fieldLabel:'Nama Tim Pengelola',
									name: 'nama',
									id:'combo_nama_tim',
									width:200,
									store:Ext.create('Esmk.store.TBantuanTimPengelola'),
									mode: 'remote',
									valueField: 'nama',
									displayField: 'nama',
									typeAhead: true,
									forceSelection: true,
									pageSize: 30,
									minChars:2,
									matchFieldWidth: false,
									editable: true,
									allowBlank:true,
									emptyText:'Pilih Tim Pengelola',
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
										'afterrender':function(){
											this.getStore().getProxy().api.read='TBantuanTimPengelola/GetNamaTimPengelola';
										}
									}
									
								},{
									xtype:'button',
									width:200,
									text:'Daftarkan Nama Tim Baru',
									handler:function(){
										var dlg = Ext.MessageBox.prompt('Pengelola', 'Masukan Nama Pengelola:', function(btn, text){
										if (btn == 'ok'){
											var form=Ext.create('Ext.form.Panel');
											form.submit({
												url: 'TBantuanTimPengelola/CreateTimPengelola',
												params:{nama:text},
												method:'POST',
												waitMsg: 'Mohon tunggu hingga proses selesai...',
												success: function(form, action) {
													//self.up('form').getForm().reset();
													//self.up('window').close();
													//if(action.result.success=='0'){
														Ext.create('widget.uxNotification', {
															title: 'Notifikasi',
															position: 't',
															manager: 'demo1',
															iconCls: 'ux-notification-icon-information',
															autoHideDelay: 2000,
															autoHide: true,
															spacing: 20,
															html: 'Tim \"'+text+'\" telah didaftarkan.'
														}).show();   
													//}
													
												},
												failure: function(form, action) {
													self.up('form').getForm().reset();
													if(action.result.message){
														alert(action.result.message);
													}
												}
											});
										}
									});
									},
									iconCls:'icon-add'
								}]
							},
							
                            {
								xtype:'combobox',
								fieldLabel:'Nama Pengguna',
								name: 'user_id',
								id:'combo_user_id',
								width:200,
								store:Ext.create('Esmk.store.User'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'displayname',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								allowBlank:true,
								emptyText:'Pilih Pengguna',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Data Tidak Ditemukan',
									width: '23%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{displayname}</span>';
									}
								},
								listeners:{
									'afterrender':function(){
										this.getStore().getProxy().api.read='User/GetUserPengelolaBantuan';
									}
								}
								
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
			Ext.getCmp('tbantuantimpengelolagridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanTimPengelola');
            record.set(values);
            Ext.getCmp('tbantuantimpengelolagridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuantimpengelolagridid').getStore().load();
        }
		
        win.close();
    },
});


