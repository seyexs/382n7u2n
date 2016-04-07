Ext.define('Esmk.view.Dashboard.info_bantuan', {
    extend: 'Ext.grid.Panel',
	id:'dashboardinfobantuangridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
		'Ext.ux.grid.FiltersFeature',
    ],
	frame:false,
    loadMask: true,
	headerFrame:false,
	frame:false,
	header:false,
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TBantuanProgram');
        Ext.applyIf(me, {
            columns: [
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'tahun',
                    //text: 'Tahun',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'r_bantuan_name',
                    //text: 'Jenis Bantuan',
                    flex:true,
                    
                },{
                    dataIndex: 'nama',
                    //text: 'Nama',
                    flex:true,
                     
                    
                },{
                    dataIndex: 'bentuk_bantuan',
                    //text: 'Bentuk Bantuan',
                    flex:true,
					renderer:function(value, p, record){
						return Ext.String.format('{0}',value=='1'?'Barang':'Uang');
					}    
                },{
                    dataIndex: 'r_bantuan_penerima_nama',
                    //text: 'Penerima Bantuan',
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
                            xtype: 'button',
                            iconCls: 'icon-search',
                            text: 'Cari',
							handler:function(){
								me.actionSearch(this);
							}
                        },
                        
                    ]
                },
                /*{
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }*/
            ]

        });

        me.callParent(arguments);
    },
	
    
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});
		grid.getStore().getProxy().api.read='TBantuanProgram/read/?q='+values;
    },
});


