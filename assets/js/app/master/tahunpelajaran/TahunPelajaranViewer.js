Ext.define('Esmk.master.tahunpelajaran.TahunPelajaranViewer', {
    extend: 'Ext.panel.Panel',
    
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            padding: 5,
            layout: 'border',
            border:0,
            items: [
                this.createTahunPelajaranMainPanel()
            ]
        });
        
        this.callParent(arguments);
    },
	createTahunPelajaranMainPanel: function(){
        this.tahunPelajaranMainPanel = Ext.create('Esmk.master.tahunpelajaran.TahunPelajaranMainPanel', {
            region: 'center'
        });
		this.tapelMainPanel=Ext.create('Ext.panel.Panel',{
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
						type: 'string'
					}
					]
				});

				//Store memuat data melalui proxy, dan juga menyediakan
				//fungsi untuk sorting, filtering dan query.
				var store = Ext.create('Ext.data.Store', {
					storeId:'mtahunpelajaranStore',
					model: "MTahunPelajaran",
					//Jumlah per halaman
					pageSize: 5,
					proxy: {
						type: 'ajax',
						api : {
							read : BASE_URL + '/mtahunpelajaran/',
							create : BASE_URL + '/mtahunpelajaran/' + 'insert/',
							update : BASE_URL + '/mtahunpelajaran/' + 'update/',
							destroy : BASE_URL + '/mtahunpelajaran/' + 'delete/'
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

				//Inisialisasi pertama bila pagging belum dilakukan
				store.load({
					params:{
						start:0,
						limit: 5
					}
				});

				//Membuat grid user
				var GridPanelMTahunPelajaran = Ext.create('Ext.grid.Panel', {
					id:'panelMTahunPelajaran',
					title: 'Tahun Pelajaran Manager',
					layout:'fit',
					scroll:true,
					border:0,
					
					//Me-link kan dengan Store
					store: Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
					
					//Kolom yang ditampilkan
					columns: [{
						xtype: 'column',
						text: 'ID',
						flex: 1,
						dataIndex: 'id',
						sortable: true,
					},{
						xtype: 'column',
						text: 'Kode',
						flex: 1,
						dataIndex: 'kode',
						sortable: true,
					},{
						xtype: 'column',
						text: 'Status Aktif',
						flex: 1,
						dataIndex: 'status',
						sortable: false,
					}
					],
					selType: 'rowmodel',

					//Pagging control
					bbar: new Ext.PagingToolbar({
						store:  Ext.data.StoreManager.lookup('mtahunpelajaranStore'),
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
							//me.addTahunPelajaran(me.selectedRole);
							
						}
					},{
						xtype: 'button',
						text: 'Ubah',
						id: 'icon-edit-user',
						hidden: true,
						iconCls:'icon-user-pencil',
						handler: function(){
							//me.editUser(me.selectedUser,me.selectedRole);
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
						store: Ext.data.StoreManager.lookup('mtahunpelajaranStore')
					}),
					],
					
					listeners: {
						selectionchange: function(model, records) {
							
						},
						itemdblclick :function(view, record, item, index, e, eOpts ){
							me.selectedMTahunPelajaran = record.data;
							//alert("User ID : " + me.selectedUser.user_id);
						},
						itemclick :function(view, record, item, index, e, eOpts ){
							var store = Ext.data.StoreManager.lookup('mtahunpelajaranStore');
							me.selectedMTahunPelajaran = record.data;
							Ext.getCmp('icon-edit-user').show();
						//me.selectedUser.role_id = store.proxy.extraParams['role_id'];
						}
					}
				});

				return GridPanelMTahunPelajaran;
			},
		});
        return this.tapelMainPanel;
    },
});