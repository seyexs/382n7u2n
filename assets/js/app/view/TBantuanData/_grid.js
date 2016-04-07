Ext.define('Esmk.view.TBantuanData._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuandataGrid',
	id:'tbantuandatagridid',
	t_bantuan_program_id:null,
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Penerima Bantuan',
    loadMask: true,
    selType : 'checkboxmodel',
	selModel : 
	{
		mode : 'MULTI'
	},
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanData');
		var storeTBantuanProgram=Ext.create("Esmk.store.TBantuanProgram");
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
                    dataIndex: 'nama_bantuan',
                    text: 'Program Bantuan',
                    flex:true, 
                },{
                    dataIndex: 'm_sekolah_text',
                    text: 'Nama Sekolah',
                    flex:true, 
                },{
                    dataIndex: 'jumlah_paket',
                    text: 'Jumlah Paket',
                    flex:true,
                },{
                    dataIndex: 'tgl_cetak_sk',
                    text: 'Tanggal Cetak SK',
                    flex:true,
                },
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>No data found</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				itemdblclick: function(dataview, index, item, e) {
					//me.actionDbClick(dataview, index, item, e);
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
							xtype:'combobox',
							name: 't_bantuan_program_id',
							id:'t_bantuan_program_id',
							width:200,
							store:storeTBantuanProgram,
							mode: 'remote',
							valueField: 'id',
							displayField: 'nama',
							typeAhead: true,
							forceSelection: true,
							pageSize: 30,
							minChars:2,
							matchFieldWidth: false,
							emptyText:'Program Bantuan',
							listConfig: {
								loadingText: 'Proses Pencarian...',
								emptyText: 'Program Bantuan Tidak Ditemukan',
								//width: '71%',
								//height:300,
								autoHeight:true,
								getInnerTpl: function() {
									return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
								}
							},
							listeners: {
								select: function(combo, records, index) {
									
									var value = records[0].get(combo.valueField);
									me.t_bantuan_program_id = value;
																		
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
                        /*{
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            text: 'Hapus',
							handler:function(){
								me.actionDelete(this);
							}
                        },*/
                        {
                            xtype: 'button',
                            iconCls: 'icon-add',
                            text: 'Buat Baru',
							handler:function(){
								me.actionCreate();
							}
                        }/*,{
							xtype:'button',
							iconCls:'icon-pencil',
							text: 'Ubah',
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
                    emptyMsg: 'No data to display',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	actionDbClick: function(dataview, record, item, index, e, options){
        var formTBantuanData = Ext.create('Esmk.view.TBantuanData._form');

        if (record) {

            formTBantuanData.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
		var grid = Ext.getCmp('tbantuandatagridid');
		var records = grid.getSelectionModel().getSelection();
        var formTBantuanData = Ext.create('Esmk.view.TBantuanData._form');

        if (record) {

            formTBantuanData.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },
	actionDelete: function(button) {
		var grid = button.up('grid');
		var records = grid.getSelectionModel().getSelection();
		var store = grid.getStore();
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					store.remove(item);
				});

				store.load();
			}
		});
        
    },
    
    actionSearch: function(button) {

        var grid =button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values,t_bantuan_program_id:this.t_bantuan_program_id}});
		grid.getStore().getProxy().api.read='TBantuanData/read/?q='+values+'&t_bantuan_program_id='+this.t_bantuan_program_id;
    },
});


