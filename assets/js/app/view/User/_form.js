Ext.define('Esmk.view.User._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.userForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form User',
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
				defaultType: 'textfield',
                items: [{
                                xtype: 'hidden',
                                name: 'id',
                                allowBlank: false,
                                //value: user_info.id
                            },{
                                name: 'username',
                                fieldLabel: 'Username',
                                allowBlank: false,
                                width: 330,
                                labelWidth: 125,
                                disabled: (me.mode)?true:false,
                                //value: user_info.username
                            },{
                                name: 'displayname',
                                fieldLabel: 'Nama User',
                                allowBlank: false,
                                width: 330,
                                labelWidth: 125,
                                //value: user_info.displayname
                            },{
                                name: 'email',
                                fieldLabel: 'Email',
                                width: 330,
								allowBlank:true,
                                labelWidth: 125,
                                //value: user_info.email
                            },{
                                inputType: 'password',
                                name: 'password',
                                fieldLabel: 'Password',
                                width: 330,
                                //value: '**&&**&&',
                                labelWidth: 125
                            },{
                                xtype: 'fileuploadfield',
                                width: 330,
                                labelWidth: 125,
                                id: 'avatar_file',
								allowBlank:true,
                                emptyText: 'Pilih sebuah foto',
                                fieldLabel: 'Foto',
                                name: 'avatar_file',
                                buttonText: '',
                                buttonConfig: {
                                    iconCls: 'upload-icon'
                                }
                            },{
									xtype:'radiogroup',
									fieldLabel:'Kelompok Kepemilikan',
									allowBlank:false,
									defaults: {
										name: 'kode_kepemilikan',
										//margin: '0 15 0 0'
									},
									items:[{
										inputValue:'SP',
										boxLabel:'Satuan Pendidikan',
									},{
										inputValue:'PD',
										boxLabel:'Peserta Didik',
										//checked:true
									},{
										inputValue:'L',
										boxLabel:'Lainnya',
										//checked:true
									}]
							},{
								xtype:'combobox',
								fieldLabel:'Pemilik',
								name: 'pemilik_id',
								id:'combo_user_pemilik_id',
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
								
							}
                    ]
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
						handler: function(){
							me.actionSave();
						}
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Tutup',
                        action: 'cancel',
						handler:function(){
							this.up('window').close();
						}
                    },]
            }];

        this.callParent(arguments);


    },
	actionSave:function(){
		var form = this.down('form').getForm();
        var self = this;
        if(form.isValid()){
            form.submit({
				url: 'user/Create',
				waitMsg: 'Mohon tunggu hingga proses selesai...',
				success: function(form, action) {
					self.down('form').getForm().reset();
					self.down('window').close();

					Ext.create('widget.uxNotification', {
						title: 'Notifikasi',
						position: 'br',
						manager: 'demo1',
						iconCls: 'ux-notification-icon-information',
						autoHideDelay: 5000,
						autoHide: true,
						spacing: 20,
						html: 'User berhasil dibuat'
					}).show();

														
				},
				failure: function(form, action) {
					//alert(JSON.stringify(action.result));
					self.down('form').getForm().reset();
					if(action.result.message)
						Ext.Msg.show({
							title: 'Gagal',
							msg:  action.result.message,
							minWidth: 200,
							modal: true,
							icon: Ext.Msg.ERROR,
							buttons: Ext.Msg.OK
						});
					
				}
			});
			Ext.getCmp('usergridid').getStore().reload();
		}else{
        Ext.create('widget.uxNotification', {
			title: 'Notifikasi',
            position: 'br',
            manager: 'demo1',
            iconCls: 'ux-notification-icon-information',
            autoHideDelay: 2000,
            autoHide: true,
            spacing: 20,
            html: 'Form tidak valid'
            }).show();
		}
	}
});


