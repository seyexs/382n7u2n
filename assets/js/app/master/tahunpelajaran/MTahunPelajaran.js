
//Jumlah record per halaman
var itemsPerPage = 10;

Ext.onReady(function() {

    //Model merepresentasikan beberapa object yang akan diatur
    Ext.define('MTahunPelajaran', {
        extend: 'Ext.data.Model',
        fields: [
        {
            name: 'id',   
            type: 'int'
        },

        {
            name: 'kode', 
            type: 'string'
        },

        {
            name: 'status', 
            type: 'int'
        }
        ]
    });

       
    //class Store merangkum cache sisi client objek model.
    //Store memuat data melalui proxy, dan juga menyediakan
    //fungsi untuk sorting, filtering dan query.
    var store = Ext.create('Ext.data.Store', {
        storeId:'mtahunpelajaranStore',
        model: "MTahunPelajaran",
        //Jumlah per halaman
        pageSize: itemsPerPage,
        proxy: {
            type: 'ajax',
            api : {
                read : BASE_URL + 'mtahunpelajaran/',
                create : BASE_URL + 'mtahunpelajaran/' + 'insert/',
                update : BASE_URL + 'mtahunpelajaran/' + 'update/',
                destroy : BASE_URL + 'mtahunpelajaran/' + 'delete/'
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
            property : 'id',
            direction: 'ASC'
        }
        ],
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
        
    /**
         * Column definition
         */
    var column_row_number = new Ext.grid.RowNumberer();
    var column_id = new Ext.grid.column.Column({
        header: 'ID', 
        dataIndex: 'id', 
        width: 100,
        flex: 1,
        editor: {
            xtype:'textfield',
            allowBlank: false
        }
    });    
    var column_kode = new Ext.grid.column.Column({
        header: 'Kode', 
        dataIndex: 'kode', 
        width: 150,
        editor: {
            allowBlank: false,
            vtype:'alphanum'
        }
    });
        
    var column_status = new Ext.grid.column.Column({
        header: 'Status Aktif', 
        dataIndex: 'status', 
        width: 100,
        flex: 1,
        editor: {
            xtype:'textfield',
            allowBlank: false
        }
    });
        
    

    //Membuat grid MTahunPelajaran
    var GridPanelMTahunPelajaran = Ext.create('Ext.grid.Panel', {
        id:'panelMTahunPelajaran',
        title: 'Tahun Pelajaran',
        region: 'center',
        layout:'fit',
        scroll:true,
        collapsible: true,
        border:0,
        //Me-link kan dengan Store
        store: Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
        //Kolom yang ditampilkan
        columns: [
        column_row_number,
        column_id,
        column_kode,
        column_status
        ],
        selType: 'rowmodel',
            
        //Pagging control
        bbar: new Ext.PagingToolbar({
            store:  Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
            displayInfo: true
        }),

        tbar: [
		/*{
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
        },*/
        '->' ,
        {
            xtype: 'label',
            text: 'Search',
            margins: '0 5 0 10'
        },
        new Ext.app.SearchField({
            store:  Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
            width: 240
        })
        ],
        listeners: {
            selectionchange: function(model, records) {
                
                if (records[0]) {
                    var form = Ext.getCmp('mtahunpelajaran-panel-form').getForm();
                    var id_field = form.findField('id');
                    var kode_field = form.findField('kode');
                    var status_field = form.findField('status');
                    
                    id_field.setValue(records[0].get('id'));
					kode_field.setValue(records[0].get('kode'));
                    status_field.setValue(records[0].get('status'));
                    
                }
            }
        }
    });
        
     //membuat form input 
    var MTahunPelajaranPanelForm = Ext.create('Ext.form.Panel', {
        id:'mtahunpelajaran-panel-form',
        title: 'Tahun Pelajaran Panel',
        bodyPadding: 5,
        width: 350,
        height: 250,
        region: 'south',
        collapsible:'true',
        border: 0,

        //Me-link kan dengan Store
        store: Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
            
        // Fields will be arranged vertically, stretched to full width
        layout: 'anchor',
        defaults: {
            anchor: '100%'
        },
        // The fields
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'ID',
            name: 'id',
            dataIndex: 'id',
            allowBlank: false
        },{
            fieldLabel: 'Kode',
            name: 'kode',
            dataIndex: 'kode',
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
                Ext.data.StoreManager.lookup('mtahunpelajaranStore').save();
            }
        }
        ]
            
    });
        
    var MTahunPelajaranAdministration = Ext.create('Ext.panel.Panel', {
        id:'mtahunpelajaran-administration',
        border:0,
        layout: {
            type: 'border',
            padding: '0 0 0 0'
        },
        items:[
        GridPanelMTahunPelajaran,
        MTahunPelajaranPanelForm //GridPanelRole
        ]
    });
        
    //Tambahkan elemen ke tab
    Ext.getCmp('docs-icon-user-config').add(MTahunPelajaranAdministration);
    Ext.getCmp('docs-icon-user-config').doLayout();
});