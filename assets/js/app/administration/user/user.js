
//Jumlah record per halaman
var itemsPerPage = 10;

Ext.onReady(function() {

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
            name: 'role_id', 
            type: 'string'
        },

        {
            name: 'role_name', 
            type: 'string'
        }
        ]
    });

    Ext.define('Role', {
        extend: 'Ext.data.Model',
        fields: [
        {
            name: 'role_id',   
            type: 'string'
        },

        {
            name: 'role_name', 
            type: 'string'
        },

        {
            name: 'role_description', 
            type: 'string'
        }
        ]
    });
    
    //class Store merangkum cache sisi client objek model.
    //Store memuat data melalui proxy, dan juga menyediakan
    //fungsi untuk sorting, filtering dan query.
    var store = Ext.create('Ext.data.Store', {
        storeId:'userStore',
        model: "User",
        //Jumlah per halaman
        pageSize: itemsPerPage,
        proxy: {
            type: 'ajax',
            api : {
                read : BASE_URL + 'user/',
                create : BASE_URL + 'user/' + 'insert/',
                update : BASE_URL + 'user/' + 'update/',
                destroy : BASE_URL + 'user/' + 'delete/'
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
        
        
    var storeRole = Ext.create('Ext.data.Store', {
        storeId:'roleStore',
        model: "Role",
        //Jumlah per halaman
        pageSize: itemsPerPage,
        proxy: {
            type: 'ajax',
            url : BASE_URL + 'administration/rolelist',
            reader: {
                //Kita gunakan tipe data json
                type: 'json',
                //Parent element untuk data
                root: 'rows',
                //Jumlah total record
                totalProperty: 'total'
            }
        },
        //Untuk sorting
        remoteSort:true
    });


    //Inisialisasi pertama bila pagging
    //belum dilakukan
    store.load({
        params:{
            start:0,
            limit: itemsPerPage
        }
    });
        
    //Inisialisasi pertama bila pagging
    //belum dilakukan
    storeRole.load({
        params:{
            start:0,
            limit: itemsPerPage
        }
    });
        
    /**
         * Column definition
         */
    var column_row_number = new Ext.grid.RowNumberer();
        
    var column_user_name = new Ext.grid.column.Column({
        header: 'User Name', 
        dataIndex: 'user_name', 
        width: 110,
        editor: {
            allowBlank: false,
            vtype:'alphanum'
        }
    });
        
    var column_user_realname = new Ext.grid.column.Column({
        header: 'Name', 
        dataIndex: 'user_realname', 
        width: 150,
        flex: 1,
        editor: {
            xtype:'textfield',
            allowBlank: false
        }
    });
        
    var column_user_phone = new Ext.grid.column.Column({
        header: 'Phone Number',
        dataIndex: 'user_phone', 
        width: 100, 
        editor: {
            xtype:'textfield',
            allowBlank: true
        }
    });  
        
    var column_user_email = new Ext.grid.column.Column({
        header: 'Email',
        dataIndex: 'user_email', 
        width: 150,
        editor: {
            xtype:'textfield',
            allowBlank: true
        }
    });
        
    var column_role_name = new Ext.grid.column.Column({
        header: 'Role',
        dataIndex: 'role_name', 
        width: 225,
        editor: {
            xtype:'textfield',
            allowBlank: false
        }
    });
        
    var column_user_active = new Ext.grid.column.Boolean({
        header: 'Active',
        dataIndex: 'user_active',
        trueText: 'Yes',
        falseText: 'No',
        align : 'center',
        sortable: false,
        width: 60,
        editor: {
            xtype:'checkbox'
        }
    });

    //Membuat grid user
    var GridPanelUser = Ext.create('Ext.grid.Panel', {
        id:'panelUser',
        title: 'User Manager',
        region: 'center',
        layout:'fit',
        scroll:true,
        collapsible: true,
        border:0,
        //Me-link kan dengan Store
        store: Ext.data.StoreManager.lookup('userStore'),
        //Kolom yang ditampilkan
        columns: [
        column_row_number,
        column_user_name,
        column_user_realname,
        column_role_name,
        column_user_phone,              
        column_user_email,
        column_user_active
        ],
        selType: 'rowmodel',
            
        //Pagging control
        bbar: new Ext.PagingToolbar({
            store:  Ext.data.StoreManager.lookup('userStore'),
            displayInfo: true
        }),

        tbar: [
        '->' ,
        {
            xtype: 'label',
            text: 'Search',
            margins: '0 5 0 10'
        },
        new Ext.app.SearchField({
            store:  Ext.data.StoreManager.lookup('userStore'),
            width: 240
        })
        ],
        listeners: {
            selectionchange: function(model, records) {
                
                if (records[0]) {
                    var form = Ext.getCmp('user-panel-form').getForm();
                    
                    var user_name_field = form.findField('user_name');
                    var user_realname_field = form.findField('user_realname');
                    
                    user_name_field.setValue(records[0].get('user_name'));
                    user_realname_field.setValue(records[0].get('user_realname'));
                    
                }
            }
        }
    });
        
        
    var column_row_number_role = new Ext.grid.RowNumberer();
        
    var column_role_name_role = new Ext.grid.column.Column({
        header: 'Role Name', 
        dataIndex: 'role_name', 
        width: 210,
        editor: {
            xtype:'textfield',
            allowBlank: false
        }
    });
        
    var column_role_description_role = new Ext.grid.column.Column({
        header: 'Role Description', 
        dataIndex: 'role_description', 
        width: 210,
        flex:1,
        editor: {
            xtype:'textfield'
        }
    });
        
    //Membuat grid user
    var GridPanelRole = Ext.create('Ext.grid.Panel', {
        id:'panelRole',
        title: 'Role Manager',
        region: 'south',
        layout:'fit',
        height:250,
        scroll:true,
        collapsible:true,
        border:0,
        //Me-link kan dengan Store
        store: Ext.data.StoreManager.lookup('roleStore'),
        //Kolom yang ditampilkan
        columns: [
        column_row_number_role,
        column_role_name_role,
        column_role_description_role
        ],
        selType: 'cellmodel',
        plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 2
        })
        ],
        //Pagging control
        bbar: new Ext.PagingToolbar({
            store:  Ext.data.StoreManager.lookup('roleStore'),
            displayInfo: true
        }),

        tbar: [
        {
            xtype: 'button',
            text: 'Add',
            tooltip: 'Add new record',
            iconCls:'icon-user-add'
        },
        '-' ,
        {
            text: 'Delete',
            tooltip: 'Delete Record',
            iconCls:'icon-user-delete'
        }
        ,'-' ,
        {
            text: 'Save',
            tooltip: 'Save Records',
            iconCls:'icon-save'
        },
        '->' ,
        {
            xtype: 'label',
            text: 'Search',
            margins: '0 5 0 10'
        },
        new Ext.app.SearchField({
            store:  Ext.data.StoreManager.lookup('roleStore'),
            width: 240
        })]
    });
        
    var UserPanelForm = Ext.create('Ext.form.Panel', {
        id:'user-panel-form',
        title: 'User Panel',
        bodyPadding: 5,
        width: 350,
        height: 250,
        region: 'south',
        collapsible:'true',
        border: 0,

        //Me-link kan dengan Store
        store: Ext.data.StoreManager.lookup('userStore'),
            
        // Fields will be arranged vertically, stretched to full width
        layout: 'anchor',
        defaults: {
            anchor: '100%'
        },
        // The fields
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'User Name',
            name: 'user_name',
            dataIndex: 'user_name',
            allowBlank: false
        },{
            fieldLabel: 'Name',
            name: 'user_realname',
            dataIndex: 'user_realname',
            allowBlank: false
        }],

        tbar: [
        {
            text: 'Add',
            tooltip: 'Add new record',
            iconCls:'icon-user-add'
        },
        '-' ,
        {
            text: 'Delete',
            tooltip: 'Delete Record',
            iconCls:'icon-user-delete'
        }
        ,'-' ,
        {
            text: 'Save',
            tooltip: 'Save Records',
            iconCls:'icon-save',
            handler: function() {
                Ext.data.StoreManager.lookup('userStore').save();
            }
        }
        ]
            
    });
        
    var UserAdministration = Ext.create('Ext.panel.Panel', {
        id:'user-administration',
        border:0,
        layout: {
            type: 'border',
            padding: '0 0 0 0'
        },
        items:[
        GridPanelUser,
        UserPanelForm//GridPanelRole
        ]
    });
        
    //Tambahkan elemen ke tab
    Ext.getCmp('docs-icon-user-config').add(UserAdministration);
    Ext.getCmp('docs-icon-user-config').doLayout();
});