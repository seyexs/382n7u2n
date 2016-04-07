

Ext.define('Esmk.administration.user.UserProfilePanel', {
    extend: 'Ext.panel.Panel',
    selectedRole : '',
    selectedProfile : '',
    selectedActiveProfile : '',
    
    initComponent: function(){
        var me = this;
        Ext.tip.QuickTipManager.init()
        
        Ext.apply(this, {
            layout:'fit',
            items:[
                this.createSelectionPanel()
            ]
        });
        this.callParent(arguments);
    },
    
    createSelectionPanel: function(){
        var me = this;
        var json;
        var mainForm;
        
        Ext.define('ProfileModel',{
            extend: 'Ext.data.Model',
            fields: [{
                    name: 'profile_id',
                    type: 'int'
            },{
                    name: 'profile_name',
                    type: 'string'
            },{
                    name: 'profile_description',
                    type: 'string'
            },{
                    name: 'profile_visible',
                    type: 'int'
        
            }]
        });
        
        var store = Ext.create('Ext.data.Store',{
            storeId: 'profileStore',
            model: 'ProfileModel',
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/profile/currentProfile/?role_id=' + me.selectedRole.id
                },
                reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            }
        });
        store.load();


        var allProfileStore = Ext.create('Ext.data.Store',{
            storeId: 'allProfileStore',
            model: 'ProfileModel',
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/profile/allProfile/'                },
                    reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            }
        });
        allProfileStore.load();
        

        var sm = Ext.create('Ext.selection.CheckboxModel');
        var listView = Ext.create('Ext.grid.Panel', {
            renderTo: Ext.getBody(),
            border: 0,
            scroll:true,
            store: allProfileStore,
            viewConfig: {
                emptyText: 'No images to display'
            },

            columns: [{
                text: 'Nama Profil',
                flex: 50,
                dataIndex: 'profile_name'
            }],
            listeners: {
                selectionchange: function(model, records) {},
                itemdblclick: function(view, record, item, index, e, eOpts ){},
                itemclick: function(view, record, item, index, e, eOpts){
                    
                    me.selectedProfile  = record.data;
                    Ext.getCmp('icon-lock-pencil').show();
                    Ext.getCmp('icon-lock-unlock').show();
                    Ext.getCmp('icon-lock-delete').show();
                    
                   
                    if(me.selectedRole != ""){
                        Ext.getCmp('icon-lock-plus').show();
                        Ext.getCmp('icon-lock-delete').show();
                        if(me.selectedRole.iconCls=="icon-users")
                        Ext.getCmp('icon-lock-arrow').show();
                    }
                    
                    if(record.data.profile_id==1){
                        Ext.getCmp('icon-lock-delete').hide();
                    }
                }
            }
        });

        var listView2 = Ext.create('Ext.grid.Panel', {
            height:'100%',
            renderTo: Ext.getBody(),
            border: 0,
            scroll:true,
            store: store,
            viewConfig: {
                emptyText: ''
            },

            columns: [{
                flex: 50,
                dataIndex: 'profile_name'
            }],
            listeners: {
                selectionchange: function(model, records) {},
                itemdblclick :function(view, record, item, index, e, eOpts ){},
                itemclick :function(view, record, item, index, e, eOpts ){
                    me.selectedActiveProfile  = record.data;
                    if(me.selectedRole!="")
                        Ext.getCmp('icon-minus-circle').show();
                }
            }
        });
        
        var leftPanel = Ext.createWidget('panel', {
            title: 'Profile Tersedia',
            scroll: true,
            region: 'west',
            width: 200,
            margins: '5 0 0 5',
            items:[
                listView
            ],
            tbar: [{
                    xtype: 'button',
                    iconCls: 'icon-lock-plus',
                    id: 'icon-lock-plus',
                    tooltip: 'Tambah Profil',
                    handler: function(){
                        me.addProfile();
                    }
                },{
                    xtype: 'button',
                    iconCls: 'icon-lock-pencil',
                    id: 'icon-lock-pencil',
                    hidden: true,
                    tooltip: 'Ubah Profil',
                    handler: function(){
                        me.editProfile(me.selectedProfile);
                    }
                },{
                    xtype: 'button',
                    iconCls: 'icon-lock-delete',
                    id: 'icon-lock-delete',
                    hidden: true,
                    tooltip: 'Hapus Profil',
                    handler: function(){
                        me.deleteProfile(me.selectedProfile);
                    }
                },{
                    xtype: 'button',
                    iconCls: 'icon-lock-unlock',
                    id: 'icon-lock-unlock',
                    hidden: true,
                    tooltip: 'Hak Akses Modul',
                    handler: function(){
                        me.editPage(me.selectedProfile);
                    }
                },{
                    xtype: 'button',
                    iconCls: 'icon-lock-arrow',
                    id: 'icon-lock-arrow',
                    hidden: true,
                    tooltip: 'Mendaftarkan Profil ke Jabatan',
                    handler: function(){
                        me.assignProfile(me.selectedProfile);
                    }
                }]
            });
        var rightPanel = Ext.createWidget('panel', {
            scroll: true,
            region: 'center',
            margins: '5 0 0 5',
            items:[
                listView2
            ],tbar:[{
                xtype: 'button',
                text: 'Hapus Profile',
                iconCls: 'icon-minus-circle',
                id: 'icon-minus-circle',
                hidden: true,
                handler: function(){
                    me.unAssignProfile(me.selectedActiveProfile);
                }
            },"-"]
        });
        
        var mainPanel = Ext.createWidget('panel',{
            layout:'border',
            border: 0,
            items:[leftPanel,rightPanel]
        });
        return mainPanel;
    },
    
    editPage: function(selectedProfile){
        var me = this;
        Ext.Ajax.request({
            url: BASE_URL + '/page/all/',
            params: {
                profile_id : selectedProfile.profile_id
            },
            success: function(response){
                var json = Ext.JSON.decode(response.responseText);
                
                var loop = json.length;
                var i=0;
                var checkboxes = new Array();
                var check = false;
                
                while(i<loop){
                    check = (json[i].checked==1) ? true : false;

                    checkboxes[i] = {
                        boxLabel : json[i].page_name,
                        name : 'page_id[]',
                        inputValue: json[i].page_id,
                        checked: check
                    };
                    i++;
                }
                me.createFormEditPage(selectedProfile.profile_id,checkboxes);
            },
            scope: me
        });
    },
    
    createFormEditPage: function(profile_id,checkboxes){
        var me = this;
        
        var form = Ext.widget('form',{
            bodyPadding:10,
            autoScroll: true,
            items:[{
                    xtype: 'hiddenfield',
                    name: 'profile_id',
                    value: profile_id
                }, {
                    fieldLabel: 'Halaman',
                    labelWidth: 100,
                    vertical: true,
                    columns: 1,
                    xtype: 'checkboxgroup',
                    items: checkboxes
                }
            ],
            buttons:[{
                text: 'Batal',
                margins: '2 2 2 2',
                handler: function (){
                    this.up('form').getForm().reset();
                    this.up('window').close();
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
                            url: BASE_URL + '/page/edit',
                            success: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();
                                me.createNotification('Data Organisasi Berhasil Diubah');
                            },
                            failure: function(form, action) {
                                self.up('form').getForm().reset();
                                Ext.Msg.show({
                                    title: 'Gagal',
                                    msg:  'Data Jabatan Gagal ditambahkan',
                                    minWidth: 200,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }
                        });
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
            }]
        });
        
        var window = Ext.widget('window',{
            modal: true,
            height: 300,
            width: 480,
            layout: 'fit',
            title: 'Edit Permission Halaman',
            items: form
        });
        window.show();
        this.doLayout();
    },
    
    addProfile: function(){
        var me = this;
        var form = Ext.widget('form',{
            width: 360,
            defaultType: 'textfield',
            layout: 'anchor',
            bodyPadding:10,
            items: [{
                    xtype: 'textfield',
                    name : 'profile_name',
                    fieldLabel: 'Nama Profil',
                    allowBlank: false,
                    labelWidth: 100,
                    width: 340
            },{
                    xtype: 'textareafield',
                    name : 'profile_description',
                    fieldLabel: 'Deskripsi Profil',
                    labelWidth: 100,
                    width: 340
            },{
                    xtype: 'checkbox',
                    name : 'profile_visible',
                    fieldLabel: 'Status Profil (Aktif/Tidak)',
                    checked:true,
                    labelWidth: 100,
                    width: 340
            }],
            buttons: [{
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
                            url: BASE_URL + '/profile/add',
                            success: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();
                                me.createNotification('Data profil berhasil ditambahkan');
                                var store = Ext.data.StoreManager.lookup('allProfileStore');
                                store.load();
                            },
                            failure: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();
                                me.notificationError('Data gagal disimpan');
                            }
                        });
                    }
                }
            }]
        });
        var window = Ext.widget('window',{
            modal: true,
            title:'Tambahkan Profil',
            items: form
        });
        window.show();
    },
    
    editProfile: function(selectedProfile){
        var me = this;
        Ext.Ajax.request({
            url: BASE_URL + '/profile/single',
            params: {
                profile_id: selectedProfile.profile_id
            },
            success: function(response){
                var json = Ext.JSON.decode(response.responseText);
                me.createFormEditProfile(json[0]);
            },
            scope: me
        });
    },
    
    createFormEditProfile: function(profile_detail){
        var me = this;
        
        var c = false;
        if(profile_detail.profile_visible==1){
            c = true;
        }
        
        var form = Ext.widget('form',{
            width: 360,
            defaultType: 'textfield',
            layout: 'anchor',
            bodyPadding:10,
            items: [{
                    xtype: 'hidden',
                    name : 'profile_id',
                    value: profile_detail.profile_id
            },{
                    xtype: 'textfield',
                    name : 'profile_name',
                    fieldLabel: 'Nama Profil',
                    value: profile_detail.profile_name,
                    labelWidth: 100,
                    width: 340
            },{
                    xtype: 'textareafield',
                    name : 'profile_description',
                    fieldLabel: 'Deskripsi Profile',
                    value: profile_detail.profile_description,
                    labelWidth: 100,
                    width: 340
            },{
                    xtype: 'checkbox',
                    name : 'profile_visible',
                    fieldLabel: 'Status Profil (Aktif/Tidak)',
                    checked: c,
                    labelWidth: 100,
                    width: 340
            }],
            buttons: [{
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
                            url: BASE_URL + '/profile/update',
                            success: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();

                                me.createNotification('Data profil berhasil diubah');
                                var store = Ext.data.StoreManager.lookup('allProfileStore');
                                store.load();
                            },
                            failure: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();
                            }
                        });
                    }
                }
            }]
        });
        var window = Ext.widget('window',{
            modal: true,
            title:'Ubah Profil',
            items: form
        });
        window.show();
    },
    
    assignProfile: function(selectedProfile){
        var me = this;
        if(me.selectedRole != ""){
            if(me.selectedRole.iconCls != "icon-department" && selectedProfile != ""){
                Ext.Ajax.request({
                    url: BASE_URL + '/profile/assign',
                    params: {
                        role_id: me.selectedRole.id,
                        profile_id: selectedProfile.profile_id
                    },
                    success: function(response){
                        var json = Ext.JSON.decode(response.responseText);
                        if(json.success==false){
                            me.createNotification(json.message);
                        }else{
                            me.createNotification('Profil ' + selectedProfile.profile_name + ' berhasil ditambahkan ke Jabatan : ' + me.selectedRole.text);
                            var store = Ext.data.StoreManager.lookup('profileStore');
                            store.proxy.extraParams['role_id'] = me.selectedRole.id;
                            store.load();
                        }
                    },
                    failure: function(response){
                    },
                    scope: me
                });
            }else{
                me.notificationError('Pilih Profil Terlebih Dahulu');
            }
        }else{
            me.notificationError('Pilih Jabatan Terlebih Dahulu');
        }
    },
    
    deleteProfile: function(selectedProfile){
        var me = this;
        Ext.Ajax.request({
            url: BASE_URL + '/profile/delete',
            params: {
                profile_id: selectedProfile.profile_id
            },
            success: function(response){
                me.createNotification('Profil ' + selectedProfile.profile_name + ' berhasil dihapus');

                var stores = Ext.data.StoreManager.lookup('allProfileStore');
                stores.load();

                var store = Ext.data.StoreManager.lookup('profileStore');
                store.proxy.extraParams['role_id'] = me.selectedRole.id;
                store.load();
            },
            failure: function(response){
            },
            scope: me
        });
    },

    unAssignProfile: function(selectedProfile){
        var me = this;
        if(selectedProfile != "" && me.selectedRole != ""){
            Ext.Ajax.request({
                url: BASE_URL + '/profile/unAssign',
                params: {
                    role_id: me.selectedRole.id,
                    profile_id: selectedProfile.profile_id
                },
                success: function(response){
                    me.createNotification('Profil ' + selectedProfile.profile_name + ' berhasil dikeluarkan dari Jabatan : ' + me.selectedRole.text);
                    me.selectedActiveRole = "";
                    var store = Ext.data.StoreManager.lookup('profileStore');
                    store.proxy.extraParams['role_id'] = me.selectedRole.id;
                    store.load();
                },
                scope: me
            });
        }else{
            me.notificationError('Pilih Jabatan dan Profil Terlebih Dahulu');
        }
    },

    setRole: function(role){
        if(role!=null){
            var me = this;

            var stores = Ext.data.StoreManager.lookup('allProfileStore');
            stores.load();

            var store = Ext.data.StoreManager.lookup('profileStore');
            store.proxy.extraParams['role_id'] = role.id;
            store.load();


            me.selectedRole = role;
            Ext.getCmp('icon-lock-plus').show();
            Ext.getCmp('icon-lock-unlock').hide();
            Ext.getCmp('icon-lock-delete').hide();
            Ext.getCmp('icon-lock-pencil').hide();
            Ext.getCmp('icon-minus-circle').hide();
            Ext.getCmp('icon-lock-arrow').hide();


        }
    },
    
    notificationError: function(message){
        Ext.Msg.show({
            title: 'Kesalahan Prosedur',
            msg:  message,
            minWidth: 200,
            modal: true,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.OK
        });
    },
    createNotification: function(message){
        Ext.create('widget.uxNotification', {
            title: 'Notifikasi',
            position: 'br',
            width: 200,
            manager: 'demo1',
            iconCls: 'ux-notification-icon-information',
            autoHideDelay: 5000,
            autoHide: true,
            spacing: 20,
            html: message
        }).show();
    }
    
});