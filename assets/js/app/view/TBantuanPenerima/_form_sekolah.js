Ext.define('Esmk.view.TBantuanPenerima._form_sekolah', {
    extend: 'Ext.grid.Panel',
	id:'formsekolahgridid',
	kodewilayah:null,
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
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.Sekolah');
		this.store.getProxy().api.read='TBantuanPenerima/GetDaftarSekolahCalonPenerima/?tbid='+me.tbid;
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
					dataIndex:'nomor_telepon',
					text:'No.Telp',
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
					//me.actionDbClick(dataview, index, item, e);
				},
				itemclick:function(dataview, record, item, e){
					//me.actionGetInfoSummarySekolah(record);
				}
            },
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        'Pencarian', {
                            xtype: 'textfield',
                            name: 'searchfield',
							emptyText:'Nama Sekolah',
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
                        },{
							xtype:'combobox',
							//fieldLabel:'Kab/Kota',
							id:'combo_kode_wilayah',
							allowBlank:true,
							width:300,
							store:Ext.create('Esmk.store.MstWilayah'),
							mode: 'remote',
							valueField: 'kode_wilayah',
							displayField: 'nama',
							typeAhead: true,
							forceSelection: true,
							pageSize: 30,
							minChars:2,
							matchFieldWidth: false,
							editable: true,
							emptyText:'Kab/Kota',
							
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
								select: function(combo, records, index) {
									var v = records[0].get('kode_wilayah');
									me.kodewilayah=v;
								},
								
							}
								
						},{
                            xtype: 'button',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch(this);
							}
                        },'-',{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            text: 'Simpan',
							handler:function(){
								me.actionSimpan(this);
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
	actionDbClick: function(dataview, record, item, index, e, options){
        var formSekolah = Ext.create('Esmk.view.Sekolah._form');

        if (record) {

            formSekolah.down('form').loadRecord(record);

        }    
    },
	actionSimpan: function(button) {
		var me=this;
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		if(!records)
			alert('Silahkan pilih data yang akan ditambahkan ke dalam daftar Penerima Bantuan!');
		
		var store = grid.getStore();
		Ext.Msg.confirm('Konfirmasi','Apakah anda yakin ingin menambahkan data ini ke dalam daftar Penerima Bantuan?',function(id,value){
			if(id==='yes'){
				var skid={};
				var i=0;
				Ext.each(records, function(item) {
					skid[i]=item.get('sekolah_id');
					i++;
				});
				Ext.Ajax.request({
					url: 'TBantuanPenerima/TambahDaftarPenerimaBantuan',
					waitMessage:'Please Wait..',
					params:{skid:JSON.stringify(skid),tbid:me.tbid},
					success: function(response){
						
					}
				});
				store.load();
			}
			
		});
        
    },
    
    actionSearch: function(button) {
        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();
		var kw=this.kodewilayah;
        grid.getStore().load({params: {q: values,kw:kw}});
		//grid.getStore().getProxy().api.read='TBantuanPenerima/GetDaftarSekolahCalonPenerima/?q='+values+'&kw='+this.kodewilayah;
		//grid.getStore().load();
    }
	
});


