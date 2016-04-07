Ext.define('Esmk.administration.user.UserRolePanel', {
    extend: 'Ext.panel.Panel',
    animCollapse: true,
    selectedNode : null,

    initComponent: function(){
        var me = this;
        this.initContextMenu();

        Ext.apply(this, {
            layout:'fit',
            items:[
            this.createUserRoleTree()
            ],
            tbar: [
            {
                xtype: 'label',
                text: 'Cari',
                margins: '0 5 0 10'
            },
            new Ext.app.SearchFieldTree({
                tree:  me.tree,
                store:  me.user_role_store,
                width: 150
            }),
            '->',
            {
                xtype: 'button',
                id: 'icon-toolbar',
                hidden: true,
                text: 'Tambah',
                iconCls:'icon-plus-circle',
                menu: new Ext.menu.Menu({
                    items: [{
                        text: 'Tambah Organisasi',
                        iconCls: 'icon-departement-plus',
                        handler: me.addOrganization,
                        scope:me
                    },
                    {
                        text: 'Tambah Jabatan',
                        iconCls: 'icon-plus-circle',
                        handler: me.addRole,
                        scope:me
                    }]
                }),
                scope:me
            }]
        });

        this.addEvents(
            'organizationChange'
            );
        this.callParent(arguments);
    },

    initContextMenu : function(){
        var me = this;

        this.satkerContext = new Ext.menu.Menu({
            items: [
            {
                iconCls :'icon-departement-plus',
                id: 'user-context-add-organization',
                text: 'Tambah Organisasi'
            },
            {
                iconCls :'icon-plus-circle',
                id: 'user-context-add-role',
                text: 'Tambah Jabatan'
            }
            ],
            listeners: {
                click: function( menu,item, e, eOpts ) {
                    if(item.id == 'user-context-add-organization'){
                        me.addOrganization();
                    }
                    else if(item.id == 'user-context-add-role'){
                        me.addRole();
                    }
                }
            },
            scope: me
        });

        this.organizationContext = new Ext.menu.Menu({
            items: [
            {
                iconCls :'icon-departement-plus',
                id: 'user-context-organization-add-organization',
                text: 'Tambah Organisasi'
            },
            {
                iconCls :'icon-plus-circle',
                id: 'user-context-organization-add-role',
                text: 'Tambah Jabatan'
            },
            {
                iconCls :'icon-pencil',
                id: 'user-context-organization-change',
                text: 'Ubah Organisasi'
            },
            {
                iconCls :'icon-minus-circle',
                id: 'user-context-organization-delete',
                text: 'Hapus Organisasi'
            }
            ],
            listeners: {
                click: function( menu,item, e, eOpts ) {
                    if(item.id == 'user-context-organization-add-organization'){
                        me.addOrganization();
                    }
                    else if(item.id == 'user-context-organization-add-role'){
                        me.addRole();
                    }
                    else if(item.id == 'user-context-organization-change'){
                        me.editRole(me.selectedNode);
                    }else if(item.id == 'user-context-organization-delete'){
                        me.deleteRole(me.selectedNode);
                    }
                }
            },
            scope: me
        });

        this.roleContext = new Ext.menu.Menu({
            items: [
            {
                iconCls :'icon-pencil',
                id: 'user-context-role-change',
                text: 'Ubah Jabatan'
            },
            {
                iconCls :'icon-minus-circle',
                id: 'user-context-role-delete',
                text: 'Hapus Jabatan'
            }
            ],
            listeners: {
                click: function( menu,item, e, eOpts ) {
                    if(item.id == 'user-context-role-change'){
                        me.editRole(me.selectedNode);
                    }else if(item.id == 'user-context-role-delete'){
                        me.deleteRole(me.selectedNode);
                    }
                }
            },
            scope: me
        });
    },

    deleteRole: function(organization_detail){
        var me = this;
        
        var jabatan = "";
        
        if(organization_detail.iconCls=="icon-users")
            jabatan = "Jabatan";
        else
            jabatan = "Organisasi"
        
        
        var form =  Ext.widget('form',{
            layout: 'anchor',
            bodyPadding:10,
            items: [{
                xtype: 'hidden',
                name: 'role_id',
                allowBlank: false,
                value: organization_detail.id,
                disable: true
            },
            this.createJabatan(organization_detail.id)],
            buttons: [{
                text: 'Batal',
                margins: '2 2 2 2',
                handler: function (){
                    this.up('window').close();
                }
            },{
                text: 'Hapus Jabatan',
                margins: '2 2 2 2',
                handler: function(form,action){
                    var form = this.up('form').getForm();
                    var self = this;

                    if(form.isValid()){
                        form.submit({
                            url: BASE_URL + '/role/delete',
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
                                    html: 'Data '+jabatan+' berhasil dihapus'
                                }).show();
                                
                                me.user_role_store.load();
                                Ext.getCmp('icon-add-user').hide();
                                me.fireEvent('organizationChange', this, null);
                            },
                            failure: function(form, action) {
                                Ext.Msg.show({
                                    title: 'Gagal',
                                    msg:  'Data '+jabatan+' gagal dihapus',
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
                            msg:  'Form Tidak Valid',
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
            items: form,
            modal: true,
            title: 'Pindahkan Jabatan User'
        });
        
        window.show();

    },
    
    editRole: function(role_detail){ 
        var me = this;
        
        Ext.Ajax.request({
            url: BASE_URL + '/role/getRole',
            params: {
                role_id: role_detail.id
            },
            success: function(response){
                var json = Ext.JSON.decode(response.responseText);
                me.createFormEditRole(json[0]);
            },

            scope: me
        });
    },
    
    createUserRoleTree: function(){
        var me = this;
        
        Ext.define('Role', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'id',
                type: 'string'
            },
            {
                name: 'text',
                type: 'string'
            }]
        });

        this.user_role_store = Ext.create('Ext.data.TreeStore', {
            model: 'Role',
            id:'user_role_store',
            proxy: {
                type: 'ajax',
                url : BASE_URL + '/user/roledata'
            }
        });

        this.tree = Ext.create('Ext.tree.Panel', {
            margin: '2 0 0 0',
            bodyCls:'tree-user-role',
            rootVisible: false,
            store: this.user_role_store,
            border:0,
            useArrows: true,
            expand:true,
            listeners: {
                render: {
                    fn: function() {
                        Ext.getBody().on("contextmenu", Ext.emptyFn,
                            null, {
                                preventDefault: true
                            });
                    }
                },
                itemcontextmenu: function( view, record, item, index,  e, eOpts ){
                    var id = record.data.id;
                    view.select(record);
                    me.selectedNode = record.data;
                    var x = e.getX();
                    var y = e.getY();
                    
                    //IF SATKER
                    if (id.length == 6)
                    {
                        me.satkerContext.showAt([x, y]);
                    }
                    //IF ADMIN
                    if (id.length == 9 && id.substr(7,2) == '00')
                    {
                        
                    }
                    else if (id.length > 6){
                        if(record.data.iconCls == 'icon-users'){
                            me.roleContext.showAt([x, y]);
                        }
                        else{
                            me.organizationContext.showAt([x, y]);
                        }
                    }
                    
                    me.fireEvent('organizationChange', this, record.data);
                    console.log(record.data);
                    
                    if(record.data.iconCls == 'icon-users'){
                        Ext.getCmp('icon-toolbar').hide();
                    }else{
                        Ext.getCmp('icon-toolbar').show();
                    }
                },
                itemclick : function( view, record, item, index, e, eOpts ){
                    me.selectedNode = record.data;
                    me.fireEvent('organizationChange', this, record.data);
                    
                    if(record.data.iconCls == 'icon-users'){
                        Ext.getCmp('icon-toolbar').hide();
                    }else{
                        Ext.getCmp('icon-toolbar').show();
                    }
                }
            },
            scope:me
        });

        return this.tree;
    },

    addOrganization:function(){
        var me = this;
        var error = false;
        var msg = '';
        
        if(this.selectedNode == null){
            error = true;
            msg = 'Harap pilih organisasi terlebih dahulu';
        }
        else if (this.selectedNode.iconCls == 'icon-users'){
            error = true;
            msg = 'Tidak dapat menambahkan Organisasi di bawah Jabatan.';
        }

        if (error){
            Ext.Msg.show({
                title: 'Kesalahan Prosedur',
                msg:  msg,
                minWidth: 200,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }else{
            /*
            * Form untuk menambah organisasi
            */
            var form = Ext.widget('form', {
                bodyPadding:10,
                width: 480,
                defaultType: 'textfield',
                fieldDefaults: {
                    labelAlign: 'left',
                    labelWidth: 180,
                    anchor: '100%'
                },
                items: [{
                    xtype: 'hidden',
                    name: 'role_id',
                    allowBlank: false,
                    value: this.selectedNode.id,
                    disable: true
                },{
                    name: 'role_name',
                    fieldLabel: 'Nama Organisasi',
                    allowBlank: false
                },{
                    name: 'role_short',
                    fieldLabel: 'Kependekan Organisasi'
                },{
                    xtype: 'textareafield',
                    name: 'role_description',
                    fieldLabel: 'Deskripsi Organisasi',
                    allowBlank: false
                },{
                    xtype: 'hidden',
                    name: 'role_structural',
                    allowBlank: false,
                    value: '0',
                    disable: true
                },{
                    name: 'role_active',
                    fieldLabel: 'Status Organisasi (Aktif/Tidak)',
                    xtype:'checkbox',
                    checked:true
                }],
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
                                url: BASE_URL + '/role/add',
                                success: function(form, action) {
                                    self.up('form').getForm().reset();
                                    self.up('window').close();

                                    me.createNotification('Data Organisasi berhasil ditambahkan');
                                    me.user_role_store.load();
                                },
                                failure: function(form, action) {
                                    self.up('form').getForm().reset();
                                    Ext.Msg.show({
                                        title: 'Gagal',
                                        msg:  'Data Organisasi Gagal ditambahkan',
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
            
            var win = Ext.widget('window',{
                title: 'Tambah Organisasi',
                resizable: false,
                modal: true,
                items:form
            });
            win.show();

        }
    },

    addRole:function(){
        var error = false;
        var msg = '';
        var me = this;
        if (this.selectedNode == null){
            error = true;
            msg = 'Harap pilih organisasi terlebih dahulu';
        }
        else if (this.selectedNode.iconCls == 'icon-users'){
            error = true;
            msg = 'Tidak dapat menambahkan jabatan di bawah jabatan.';
        }
        if (error){
            Ext.Msg.show({
                title: 'Kesalahan Prosedur',
                msg:  msg,
                minWidth: 200,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }
        else{
            /*
            * Form untuk menambah jabatan
            */

            var form = Ext.widget('form', {
                bodyPadding:10,
                width: 480,
                defaultType: 'textfield',
                fieldDefaults: {
                    labelAlign: 'left',
                    labelWidth: 180,
                    anchor: '100%'
                },
                items: [{
                    xtype: 'hidden',
                    name: 'role_id',
                    allowBlank: false,
                    value: this.selectedNode.id,
                    disable: true
                },{
                    name: 'role_name',
                    fieldLabel: 'Nama Jabatan',
                    allowBlank: false
                },{
                    name: 'role_short',
                    fieldLabel: 'Kependekan Jabatan'
                },{
                    xtype: 'textareafield',
                    name: 'role_description',
                    fieldLabel: 'Deskripsi Jabatan',
                    allowBlank: false
                },{
                    xtype: 'hidden',
                    name: 'role_structural',
                    allowBlank: false,
                    value: '1',
                    disable: true
                },{
                    name: 'role_active',
                    fieldLabel: 'Status Jabatan (Aktif/Tidak)',
                    xtype:'checkbox',
                    checked:true
                }],
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
                                url: BASE_URL + '/role/add',
                                success: function(form, action) {
                                    self.up('form').getForm().reset();
                                    self.up('window').close();
                                    
                                    me.createNotification('Data Jabatan Berhasil ditambahkan');
                                    me.user_role_store.load();
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
            
            var win = Ext.widget('window',{
                title: 'Tambah Jabatan',
                resizable: false,
                modal: true,
                items:form
            });
            win.show();

        }
    },
    
    createJabatan : function(role_id){
        var combo = Ext.create('Esmk.component.RolePicker', {
            name: 'new_role_id',
            fieldLabel: 'Jabatan',
            width: 330,
            labelWidth: 125,
            notSelected: role_id,
            allowBlank: false
        });
        return combo;
    },

    createFormEditRole : function(role){
        var me = this;
        var check_active=false;
        var check_j=false;
        var check_o=false;

        if(role.role_active=1)
            check_active=true;

        if(role.role_structural == 1)
            check_j=true
        else
            check_o=true

        var label = 'Organisasi';
        if (check_j)
        {
            label = 'Jabatan';
        }

        var form = Ext.widget('form',{
            bodyPadding:10,
            width: 480,
            defaultType: 'textfield',
            fieldDefaults: {
                labelAlign: 'left',
                labelWidth: 180,
                anchor: '100%'
            },
            items:[{
                xtype: 'hidden',
                name: 'role_id',
                value: role.role_id
            },{
                name: 'role_name',
                value: role.role_name,
                fieldLabel: 'Nama ' + label
            },{
                name: 'role_short',
                value: role.role_short,
                fieldLabel: 'Kependekan ' + label
            },{
                xtype: 'textareafield',
                name: 'role_description',
                value: role.role_description,
                allowBlank: false,
                fieldLabel: 'Deskripsi ' + label
            },{
                name: 'role_active',
                fieldLabel: 'Status ' + label + ' (Aktif/Tidak)',
                xtype:'checkbox',
                checked:check_active
            },
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
                            url: BASE_URL + '/role/update',
                            success: function(form, action) {
                                self.up('form').getForm().reset();
                                self.up('window').close();

                                me.createNotification('Data Struktur Organisasi Berhasil Diubah');
                                me.user_role_store.load();
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
            items:form,
            modal:true,
            title:'Edit Struktural Organisasi'
        }).show();
    },
    createNotification: function(message){
        Ext.create('widget.uxNotification', {
            title: 'Notifikasi',
            position: 'br',
            manager: 'demo1',
            iconCls: 'ux-notification-icon-information',
            autoHideDelay: 5000,
            autoHide: true,
            spacing: 20,
            html: message
        }).show();
    }
});
;
