Ext.define('Esmk.view.TBantuanProgram._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuanprogramGrid',
	id:'tbantuanprogramgridid',
	isProp:null,
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
		'Ext.ux.grid.FiltersFeature',
    ],
    iconCls: 'icon-grid',
    title: 'Master Bantuan',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanProgram');
        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },{
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'kode',
                    text: 'Kode',
                    flex:false,
					width: 80,
					hidden:true,
                },{
                    dataIndex: 'tahun',
                    text: 'Tahun',
                    flex:false,
                    width: 50, 
                    
                },{
                    dataIndex: 'nama',
                    text: 'Nama',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'r_bantuan_name',
                    text: 'Jenis Bantuan',
                    flex:true,
                  
                    
                },{
					dataIndex:'m_pegawai_nama',
					text:'PPK',
					flex:true,
					hidden:true,
				},{
                    dataIndex: 'bentuk_bantuan',
                    text: 'Bentuk Bantuan',
                    flex:false,
					width: 100,
					renderer:function(value, p, record){
						return Ext.String.format('{0}',value=='1'?'Barang':'Uang');
					}    
                },{
                    dataIndex: 'r_bantuan_penerima_nama',
                    text: 'Penerima Bantuan',
                    flex:false,
					width: 100,
					   
                },{
                    dataIndex: 'nilai_bantuan',
                    text: 'Nilai Bantuan',
                    flex:true,
					renderer: function(val) {
						// '&#8364;' is the euro symbol
						return Ext.util.Format.number(val,'0,000');
					}
                     
                    
                },{
                    dataIndex: 'keterangan_nilai_bantuan',
                    text: 'Ket. Nilai Bantuan',
                    flex:true,
                     
                    
                }
                 
                
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>No data found</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				itemdblclick: function(dataview, index, item, e) {
					me.actionDbClick(dataview, index, item, e);
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
                        },
                        {
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
								me.actionCreate(this);
							}
                        },{
							xtype:'button',
							iconCls:'icon-pencil',
							text: 'Ubah',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionUpdate(this,records);
								
							}
						},{
							xtype:'button',
							iconCls:'icon-base',
							text:'Detail',
							handler:function(){
								me.actionDetail(this);
							}
						}
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	getOptionJenisBantuan:function(){
		return ['Reguler','Rujukan','Pesantren','Aliansi','USB'];
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
	actionShowDetail:function(record){
		var panel=Ext.getCmp('tbantuandocpanel');
		var bantuanId=record.get('id');
		var r_bantuan_id=record.get('r_bantuan_id');
		var kid=record.get('t_kuesioner_id');
		Ext.suspendLayouts();
		Ext.getCmp('dokterkaittabitem').remove('bantuanprogramfilebrowserviewid',true);
		Ext.getCmp('dokterkaittabitem').add(Ext.create('Esmk.view.TBantuanProgram.fileBrowser',{
			bantuanId:bantuanId,
			parentId:0
		}));
		/* tab persyaratan penerima */
		Ext.getCmp('persyaratanpenerimaitemid').remove('tbantuanpersyaratanpenerimagridid',true);
		Ext.getCmp('persyaratanpenerimaitemid').add(Ext.create('Esmk.view.TBantuanPersyaratanPenerima._grid',{
			t_bantuan_program_id:bantuanId,
			iconCls:null
		}));
		/* tab daftar pelaporan yang harus dilengkapi*/
		Ext.getCmp('pelaporanpertanggungjawabanitemid').remove('daftarlaporangridid',true)
		Ext.getCmp('pelaporanpertanggungjawabanitemid').add(Ext.create('Esmk.view.TBantuanProgram._daftar_laporan_grid',{
			r_bantuan_id:r_bantuan_id,
			iconCls:null,
		}));
		
		/* tab instrumen verifikasi persyaratan*/
		
		if(this.isProp==1){
			Ext.getCmp('tabpanelbantuanproperties').remove('instrumenverifikasipenerimaid',true);
		}else{
			Ext.getCmp('instrumenverifikasipenerimaid').remove('formpengisianpanelid',true)
			Ext.getCmp('instrumenverifikasipenerimaid').add(Ext.create('Esmk.view.TKuesionerJawaban.form_pengisian',{
				kid:kid,
				modeView:true,
				iconCls:null
			}));
		}
		/* tab jadwal kegiatan*/
		
		Ext.getCmp('agendaitemid').remove('tbantuanjadwalkegiatangridid',true)
		Ext.getCmp('agendaitemid').add(Ext.create('Esmk.view.TBantuanJadwalKegiatan._grid',{
			t_bantuan_program_id:bantuanId,
			iconCls:null,
			title:null,
			border:0
		}));
		/* tab daftar penerima*/
		Ext.getCmp('daftarpesertatabitemid').update('Status : Memproses data....');
		Ext.Ajax.request({
			url:'Laporan/GetViewSKPenerimaBantuan',
			method:'POST',
			params:{tbid:bantuanId},
			success: function(response){
				//me.update('');
				Ext.getCmp('daftarpesertatabitemid').update(response.responseText);
				
			}
		});
		Ext.getCmp('dokterkaittabitem').doLayout();
		panel.setTitle('Detail Terkait '+record.get('nama'));
		panel.expand();
		panel.enable();
		Ext.resumeLayouts(true); 
	},
	actionDbClick: function(dataview, record, item, index, e, options){
		this.actionShowDetail(record);
           
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
		var me=this;
        var formTBantuanProgram = Ext.create('Esmk.view.TBantuanProgram._form',{
			isProp:me.isProp
		});
		
        if (record) {

            formTBantuanProgram.down('form').loadRecord(record);

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
					store.remove(item);
				});

				store.reload();
			}
			
		});
        
    },
    
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='TBantuanProgram/read/?q='+values;
    },
});


