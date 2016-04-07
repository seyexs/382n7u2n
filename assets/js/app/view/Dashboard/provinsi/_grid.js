Ext.define('Esmk.view.Dashboard.provinsi._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.msekolahGrid',
	id:'sekolahprovinsigridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
		'Ext.grid.property.*',
    ],
    iconCls: 'icon-grid',
    title: 'Daftar Sekolah Menengah Kejuruan (SMK)',
    loadMask: true,
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.Sekolah');
		this.store.pageSize=200;
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },{
                    dataIndex: 'sekolah_id',
                    text: 'Sekolah ID',
                    flex:true,
                    hidden:true 
                    
                },{
					dataIndex:'propinsi',
					text:'Provinsi',
					flex:true,
					hidden:true,
				},{
					dataIndex:'kabupaten',
					text:'Kabupaten/Kota',
					flex:true
				},{
                    dataIndex: 'nama',
                    text: 'Nama SMK',
                    flex:true,
                    renderer:function(value,p,record){
						return value;
						//return record.get('gelar_depan')+value+record.get('gelar_belakang');
					} 
                    
                },{
					dataIndex:'npsn',
					text:'NPSN',
					flex:true,
				},{
					dataIndex:'alamat_jalan',
					text:'Alamat',
					flex:true
				},{
					dataIndex:'nomor_telepon',
					text:'No.Telp',
					flex:true
				},{
					dataIndex:'nomor_fax',
					text:'No.Fax',
					flex:true
				},{
					dataIndex:'email',
					text:'Email',
					flex:true
				}               
            ],
            viewConfig: {
                emptyText: '<h3><b>Data Kosong</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				itemdblclick: function(dataview, index, item, e) {
					me.actionDetailSekolah(dataview, index, item, e);
				},
				//itemclick:function(dataview, record, item, e){
					//me.actionGetInfoSummarySekolah(record);
				//}
            },
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        'Pencarian', {
                            xtype: 'textfield',
                            name: 'searchfield',
							listeners:{
								keyup: {
									element: 'el',
									fn: function(event, target){ 
											if(event.keyCode=='13'){
												me.actionSearch(me.down('textfield'));
											}
									}
								}
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch(this);
							}
                        },{
                            xtype: 'button',
                            iconCls: 'icon-base',
                            text: 'Detail',
							handler:function(){
								me.actionDetail(this);
							}
                        }
                        /*{
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete(this);
							}
                        },
                        {
                            xtype: 'button',
                            iconCls: 'icon-add',
                            text: 'Buat Baru',
							handler:function(){
								me.actionCreate();
							}
                        },{
							xtype:'button',
							iconCls:'icon-pencil',
							text:'Ubah',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionUpdate(this,records);
							}
						}*/
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'Data Kosong',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	actionDetail:function(button){
		var me=this;
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		if(records)
			Ext.each(records, function(item) {
					me.actionShowDetail(item);
			});		
	},
	actionDetailSekolah:function(dataview, record, item, index, e, options){
		this.actionShowDetail(record);
	},
	actionShowDetail:function(record){
		var myWindow = Ext.create('Ext.window.Window', {
			title:record.get('nama'),
			maximized:true,
			width:1200,
			height:400,
			layout:'fit',
			items:[{
				xtype:'tabpanel',
				items:[{
					title:'Bantuan Sosial',
					layout:'fit',
					items:[Ext.create('Esmk.view.Dashboard.provinsi.bansos_sekolah',{
						sid:record.get('sekolah_id')
					})]
				}]
			}],
			buttons: [{ 
				text: 'Close',
				handler:function(){
					myWindow.close();
				}
			}]
		});

		
		myWindow.show();
	},
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formSekolah = Ext.create('Esmk.view.Sekolah._form');

        if (record) {

            formSekolah.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },
	actionDelete: function(button) {
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		if(!records)
			alert('Silahkan pilih data yang akan dihapus!');
		var store = grid.getStore();
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					store.getProxy().api.destroy="Sekolah/delete/?nip="+item.get('nip');
					store.remove(item);
				});

				store.load();
			}
			
		});
        
    },
    
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='Sekolah/read/?q='+values;
    },
	actionGetInfoSummarySekolah:function(record){
		var sid=record.get('sekolah_id');
		Ext.Ajax.request({
			url: 'Sekolah/GetDetailInfoSekolah',
			method:'POST',
			params:{sid:sid},
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				json=json.data[0];
				var pnl=Ext.getCmp('infosummarysekolahcontainerid');
				if(pnl.items.length)
					pnl.remove('detailsekolahpropertygridid',true);
				var biodata = new Ext.grid.PropertyGrid({
					id:'detailsekolahpropertygridid',
					listeners:{
						'beforeedit': function (e) {
							return false; 
						}
					}
				});
				biodata.getStore().sorters.items = []; // Remove default sorting
				var source={
						'Nama':json.nama,
						'Alamat':json.alamat_jalan,
						'Kabupaten/Kota':json.kabupaten,
						'Desa':json.desa_kelurahan,
						'Dusun':json.nama_dusun,
						'Nomor Telepon': json.nomor_telepon,
						'Nomor Fax': json.nomor_fax,
						'Email': json.email,
						'Tgl Sk Pendirian': json.tanggal_sk_pendirian,
						'Tgl SK Izin Operasional':json.tanggal_sk_izin_operasional
						
				};
				biodata.setSource(source);	
					
				
				pnl.add(biodata)
			}
		});
	}
});


