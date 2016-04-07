Ext.define('Esmk.view.TKuesionerPertanyaan._grid', {
    extend:'Ext.grid.Panel',
	kid:0,
    alias: 'widget.tkuesionerpertanyaanGrid',
	id:'tkuesionerpertanyaangridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    //iconCls: 'icon-grid',
    //title: 'Esmk - TKUESIONERPERTANYAAN',
	frameHeader:false,
    loadMask: true,
    selType: 'rowmodel',
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TKuesionerPertanyaan');
		this.store.getProxy().api.read='TKuesionerPertanyaan/read/?kid='+this.kid;
		this.store.pageSize=500;
        Ext.applyIf(me, {
            columns: [                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'pertanyaan',
                    text: 'Pertanyaan',
                    flex:true,
					renderer: this.formatPertanyaan 
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
								me.actionCreate();
							}
                        },{
							xtype:'button',
							iconCls:'icon-pencil',
							text: 'Ubah',
							handler:function(){
								var records = this.up('grid').getSelectionModel().getSelection()[0];
								me.actionUpdate(this,records);
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
	formatPertanyaan:function(value, p, record){
		var opt=record.get('options');
		var strOpt='';
		var dot='............................................';
		if(opt && record.get('jenis_jawaban')=='1'){
			Ext.each(opt,function(o){
				strOpt+='<tr><td class="offered_answer" style="'+record.get('style')+'"><input type="radio" name=r'+record.get('id')+' class="x-form-field x-form-radio x-form-cb"/><span>'+o.pilihan_jawaban+'</span></td></tr>';
			});
			strOpt+='<tr><td style="'+record.get('style')+'padding-top:18px;">'+dot+dot+dot+dot+dot+'</td></tr>';
		}else if(record.get('jenis_jawaban')=='2'){
			strOpt+='<tr><td style="'+record.get('style')+'">'+dot+dot+dot+dot+dot+'</td></tr>';
		}
		var p=(record.get('jenis_jawaban')=='0')?'<h2>'+record.get('pertanyaan')+'</h2>':record.get('pertanyaan');
		var pertanyaan='<table class="pertanyaan"><tr><td>'+p+'</td></tr>'+strOpt+'</table>';
		return Ext.String.format('{0}',pertanyaan);
	},
	actionDbClick: function(dataview, record, item, index, e, options){
        var me=this;
		var box = Ext.MessageBox.wait('Please wait ...', 'Performing Actions');
        var formTKuesionerPertanyaan = Ext.create('Esmk.view.TKuesionerPertanyaan._form',{
			kid:me.kid,
			pid:record.get('id')
		});
        if (record) {

            formTKuesionerPertanyaan.down('form').loadRecord(record);
			
        }
		box.hide();
    },
	actionUpdate: function(dataview, record) { //function(grid, record) {
		var me=this;
        var formTKuesionerPertanyaan = Ext.create('Esmk.view.TKuesionerPertanyaan._form',{
			kid:me.kid,
			pid:record.get('id')
		});

        if (record) {

            formTKuesionerPertanyaan.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
		var me=this;
        var formTKuesionerPertanyaan = Ext.create('Esmk.view.TKuesionerPertanyaan._form',{
			kid:me.kid,
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
		grid.getStore().getProxy().api.read='TKuesionerPertanyaan/read/?kid='+this.kid+'&q='+values;
    },
});


