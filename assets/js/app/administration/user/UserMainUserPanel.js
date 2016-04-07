Ext.define('Esmk.administration.user.UserMainUserPanel', {
    extend: 'Ext.panel.Panel',
    selectedRole : '',
    selectedUser : '',
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            layout:'fit',
            items:[
            this.createGridPanel()
            ]
        });

        this.callParent(arguments);
    },
    
    createGridPanel: function(){
        var me = this;
        
        //Model merepresentasikan beberapa object yang akan diatur
        Ext.define('User', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'user_id',   
                type: 'int'
            },

            {
                name: 'user_name',
                type: 'string'
            },

            {
                name: 'user_realname',
                type: 'string'
            },

            {
                name: 'user_phone',
                type: 'string'
            },

            {
                name: 'user_email',
                type: 'string'
            },
            {
                name: 'user_active',
                type: 'bool'
            },
            {
                name: 'user_nip',
                type: 'string'
            },

            {
                name: 'role_id',
                type: 'string'
            },

            {
                name: 'role_name',
                type: 'string'
            },{
                name: 'user_image',
                type: 'string'
            }
            ]
        });

        //Store memuat data melalui proxy, dan juga menyediakan
        //fungsi untuk sorting, filtering dan query.
        var store = Ext.create('Ext.data.Store', {
            storeId:'userStore',
            model: "User",
            //Jumlah per halaman
            pageSize: 5,
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/user/',
                    create : BASE_URL + '/user/' + 'insert/',
                    update : BASE_URL + '/user/' + 'update/',
                    destroy : BASE_URL + '/user/' + 'delete/'
                },
                reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            },
            sorters: [
            {
                property : 'user_name',
                direction: 'ASC'
            }
            ],
            //Untuk sorting
            remoteSort:true
        });

        //Inisialisasi pertama bila pagging belum dilakukan
        store.load({
            params:{
                start:0,
                limit: 5
            }
        });

        //Membuat grid user
        var GridPanelUser = Ext.create('Ext.grid.Panel', {
            id:'panelUser',
            title: 'User Manager',
            layout:'fit',
            scroll:true,
            border:0,
            
            //Me-link kan dengan Store
            store: Ext.data.StoreManager.lookup('userStore'),
            
            //Kolom yang ditampilkan
            columns: [{
                xtype: 'templatecolumn',
                text: 'User',
                flex: 1,
                dataIndex: 'description',
                sortable: false,
                tpl: Ext.create('Ext.XTemplate','<img class="img-left framed" src="{user_image}"><h2>{user_realname}<br>( {user_name} )</h2><table style="margin-top:4px;"><tr><td style="width:50px;">Email</td><td>:</td><td>{user_email}</td></tr><tr><td>Jabatan</td><td> : &nbsp;</td><td>{role_name}</td></tr></table>')
            }
            ],
            selType: 'rowmodel',

            //Pagging control
            bbar: new Ext.PagingToolbar({
                store:  Ext.data.StoreManager.lookup('userStore'),
                displayInfo: true
            }),

            tbar: [
            {
                xtype: 'button',
                text: 'Tambah',
                id: 'icon-add-user',
                hidden: true,
                iconCls: 'icon-user-plus',
                handler: function(){
                    //console.log(store.proxy.extraParams['role_id']);
                    me.addUser(me.selectedRole);
                    
                }
            },{
                xtype: 'button',
                text: 'Ubah',
                id: 'icon-edit-user',
                hidden: true,
                iconCls:'icon-user-pencil',
                handler: function(){
                    me.editUser(me.selectedUser,me.selectedRole);
                }
            },
            '->',
            {
                xtype: 'label',
                text: 'Cari',
                margins: '0 5 0 10'
            },
            new Ext.app.SearchField({
                width: 200,
                store: Ext.data.StoreManager.lookup('userStore')
            }),
            ],
            
            listeners: {
                selectionchange: function(model, records) {
                    
                },
                itemdblclick :function(view, record, item, index, e, eOpts ){
                    me.selectedUser = record.data;
                    //alert("User ID : " + me.selectedUser.user_id);
                },
                itemclick :function(view, record, item, index, e, eOpts ){
                    var store = Ext.data.StoreManager.lookup('userStore');
                    me.selectedUser = record.data;
                    Ext.getCmp('icon-edit-user').show();
                //me.selectedUser.role_id = store.proxy.extraParams['role_id'];
                }
            }
        });

        return GridPanelUser;
    },

    setRole: function(role){
        if(role!=null){
            var me = this;
            var o = {
                start: 0
            };
            var store = Ext.data.StoreManager.lookup('userStore');
            store.proxy.extraParams['role_id'] = role.id;

            me.selectedRole = role;
            me.selectedUser = '';

            Ext.getCmp('icon-add-user').hide();
            if(me.selectedRole.iconCls == 'icon-users'){
                Ext.getCmp('icon-add-user').show();
            }else{
                Ext.getCmp('icon-add-user').hide();
            }
            Ext.getCmp('icon-edit-user').hide();

            store.loadPage(1,{
                params:o
            });
        }
    },
    
    addUser: function(role){
        
        var isStructural = 1;
        if (role.iconCls != 'icon-users'){
            isStructural = 0;
        }
        var me = this;
        
        if(role=="" || isStructural==0){
            Ext.Msg.show({
                title: 'Gagal',
                msg:  'Pilih jabatan terlebih dahulu',
                minWidth: 200,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }else{
            var len = role.id.length;
            var arr_role = role.id.split(':');
            var last_id = arr_role[arr_role.length - 1].valueOf();
            last_id++;
            last_id--;
            
            var form = Ext.widget('form',{
                defaultType: 'textfield',
                layout: 'anchor',
                bodyPadding:10,
                items: [{
                    name: 'user_name',
                    fieldLabel: 'Username',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125
                },{
                    inputType: 'password',
                    name: 'user_password',
                    fieldLabel: 'Password',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125
                },{
                    name: 'user_realname',
                    fieldLabel: 'Nama',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125
                },{
                    name: 'user_nip',
                    fieldLabel: 'NIP',
                    width: 330,
                    labelWidth: 125
                },{
                    name: 'user_phone',
                    fieldLabel: 'Nomor Telepon',
                    width: 330,
                    labelWidth: 125
                },{
                    name: 'user_email',
                    fieldLabel: 'Email',
                    width: 330,
                    labelWidth: 125
                },{
                    xtype: 'checkboxfield',
                    name: 'user_active',
                    fieldLabel: 'Aktif',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125,
                    checked: true
                },this.createJabatan(role.id)
                ],
                buttons:[{
                    text: 'Batal',
                    margins: '2 2 2 2',
                    handler: function (){
                        this.up('form').getForm().reset();
                        this.up('window').hide();
                    }
                },{
                    text: 'Simpan',
                    iconCls: 'icon-disk-black',
                    margins: '2 2 2 2',
                    handler: function(form,action){
                        var form = this.up('form').getForm();
                        var self = this;
                        if(form.isValid()){
                            form.submit({
                                url: BASE_URL + '/user/add',
                                success: function(form, action) {
                                    self.up('form').getForm().reset();
                                    self.up('window').close();

                                    Ext.create('widget.uxNotification', {
                                        title: 'Notifikasi',
                                        position: 'br',
                                        manager: 'demo1',
                                        iconCls: 'ux-notification-icon-information',
                                        autoHideDelay: 5000,
                                        autoHide: true,
                                        spacing: 20,
                                        html: 'Data user berhasil ditambahkan'
                                    }).show();
                                    Ext.data.StoreManager.lookup('userStore').load();

                                },
                                failure: function(form, action) {
                                    //self.up('form').getForm().reset();
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
                        }else{
                            Ext.Msg.show({
                                title: 'Gagal',
                                msg:  'Isian tidak valid',
                                minWidth: 200,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                        }
                    }
                }]
            });

            var window = Ext.widget('window',{
                title: 'Tambah User Baru',
                resizable: false,
                modal: true,
                items: form
            }).show();
        }
    },
    
    editUser: function(user_info, role_info){
        var me = this;
        
        if(user_info == ""){
            Ext.Msg.show({
                title: 'Kesalahan Prosedur',
                msg:  'Pilih User Dahulu',
                minWidth: 200,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }else{
            var form = Ext.widget('form',{
                defaultType: 'textfield',
                layout: 'anchor',
                bodyPadding:10,
                items: [{
                    xtype: 'hidden',
                    name: 'user_id',
                    allowBlank: false,
                    value: user_info.user_id
                },{
                    name: 'user_name',
                    fieldLabel: 'Username',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125,
                    value: user_info.user_name
                },{
                    inputType: 'password',
                    name: 'user_password',
                    fieldLabel: 'Password',
                    value: '**&&**&&',
                    width: 330,
                    allowBlank: false,
                    labelWidth: 125
                },{
                    name: 'user_realname',
                    fieldLabel: 'Nama',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125,
                    value: user_info.user_realname
                },{
                    name: 'user_nip',
                    fieldLabel: 'NIP',
                    width: 330,
                    labelWidth: 125,
                    value: user_info.user_nip
                },{
                    name: 'user_phone',
                    fieldLabel: 'Telepon',
                    width: 330,
                    labelWidth: 125,
                    value: user_info.user_phone
                },{
                    name: 'user_email',
                    fieldLabel: 'Email',
                    width: 330,
                    labelWidth: 125,
                    value: user_info.user_email
                },{
                    xtype: 'checkboxfield',
                    name: 'user_active',
                    fieldLabel: 'Aktif',
                    allowBlank: false,
                    width: 330,
                    labelWidth: 125,
                    checked: user_info.user_active
                },this.createJabatan(user_info.role_id)
                ],
                buttons:[{
                    text: 'Batal',
                    margins: '2 2 2 2',
                    handler: function (){
                        this.up('form').getForm().reset();
                        this.up('window').hide();
                    }
                },{
                    text: 'Simpan',
                    iconCls: 'icon-disk-black',
                    margins: '2 2 2 2',
                    handler: function(form,action){
                        var form = this.up('form').getForm();
                        var self = this;
                        if(form.isValid()){
                            form.submit({
                                url: BASE_URL + '/user/update',
                                success: function(form, action) {
                                    self.up('form').getForm().reset();
                                    self.up('window').close();

                                    Ext.create('widget.uxNotification', {
                                        title: 'Notifikasi',
                                        position: 'br',
                                        manager: 'demo1',
                                        iconCls: 'ux-notification-icon-information',
                                        autoHideDelay: 5000,
                                        autoHide: true,
                                        spacing: 20,
                                        html: 'Data user berhasil dirubah'
                                    }).show();
                                    Ext.data.StoreManager.lookup('userStore').load();
                                },
                                failure: function(form, action) {
                                    self.up('form').getForm().reset();
                                    Ext.Msg.show({
                                        title: 'Gagal',
                                        msg:  'Data user gagal dirubah',
                                        minWidth: 200,
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                }
                            });
                        }else{
                            Ext.Msg.show({
                                title: 'Gagal',
                                msg:  'Isian tidak valid',
                                minWidth: 200,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                        }
                    }
                }]
            });

            var window = Ext.widget('window',{
                title: 'Edit User : ' + user_info.user_realname + '',
                resizable: false,
                modal: true,
                items: form
            }).show();
        }
    },
    
    createJabatan : function(role_id){
        var combo = Ext.create('Esmk.component.RolePicker', {
            name: 'role_id',
            fieldLabel: 'Jabatan',
            width: 330,
            labelWidth: 125,
            allowBlank: false,
            defaultValue: role_id
        });
        return combo;
    }
});