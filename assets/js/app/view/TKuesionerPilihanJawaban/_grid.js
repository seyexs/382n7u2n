Ext.define('Esmk.view.TKuesionerPilihanJawaban._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tkuesionerpilihanjawabanGrid',
	id:'tkuesionerpilihanjawabangridid',
    requires: [
        'Ext.toolbar.Paging',
		'Ext.selection.CellModel',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'Pilihan Jawaban',
    loadMask: true,
    selType : 'cellmodel',
	
    initComponent: function() {
        var me = this;
		this.cellEditing = new Ext.grid.plugin.CellEditing({
            clicksToEdit: 1
        });
		this.store=Ext.create('Esmk.store.TKuesionerPilihanJawaban');
		this.store.getProxy().api.read="TKuesionerPilihanJawaban/read?pid="+this.pid;
        Ext.applyIf(me, {
			plugins: [this.cellEditing],
            columns: [
                {
                    xtype: 'rownumberer',
                    width: 50,
                    sortable: false,
                    flex: false,
                },
                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                    hidden:true,
                     
                    
                },
                 
                {
                    dataIndex: 't_pertanyaan_id',
                    text: 'T_PERTANYAAN_ID',
                    flex:true,
                    hidden:true,
                    
                },
                 
                {
                    dataIndex: 'pilihan_jawaban',
                    text: 'Pilihan Jawaban',
                    flex:true,
                    editor: {
						allowBlank: false
					} 
                    
                },{
                    dataIndex: 'skor',
                    text: 'Skor',
                    flex:true,
                    editor: {
						allowBlank: false
					} 
                    
                },{
                    dataIndex: 'urutan',
                    text: 'Urutan',
                    flex:true,
                    editor: {
						xtype:'numberfield',
						allowBlank: false
					} 
                    
                },{
                    dataIndex: 'keterangan_tambahan',
                    text: 'Keterangan Tambahan',
                    flex:true,
                    editor: {
						allowBlank: false
					} 
                    
                },
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>No data found</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				/*itemdblclick: function(dataview, index, item, e) {
					me.actionDbClick(dataview, index, item, e);
				}*/
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
	actionDbClick: function(dataview, record, item, index, e, options){
        var formTKuesionerPilihanJawaban = Ext.create('Esmk.view.TKuesionerPilihanJawaban._form');

        if (record) {

            formTKuesionerPilihanJawaban.down('form').loadRecord(record);

        }    
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
        var formTKuesionerPilihanJawaban = Ext.create('Esmk.view.TKuesionerPilihanJawaban._form');

        if (record) {

            formTKuesionerPilihanJawaban.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
		var me=this;
		var grid=Ext.getCmp('tkuesionerpilihanjawabangridid');
        var rec = new Esmk.model.TKuesionerPilihanJawaban({
			t_pertanyaan_id:me.pid,
			deleted:0
		});
		this.getStore().insert(0, rec);
        this.cellEditing.startEditByPosition({
            row: 0, 
            column: 0
        });
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

				store.load();
			}
			
		});
        
    },
    
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='TKuesionerPilihanJawaban/read/?q='+values+'&pid='+this.pid;
    },
});


