Ext.define('PHPExtJS.view.TBantuanDoc._grid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.tbantuandocGrid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    iconCls: 'icon-grid',
    title: 'PHPExtJS - TBANTUANDOC',
    store: 'TBantuanDoc',
    loadMask: true,
    
    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
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
                    dataIndex: 't_program_bantuan_id',
                    text: 'T_PROGRAM_BANTUAN_ID',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'path_file',
                    text: 'PATH_FILE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'file_type',
                    text: 'FILE_TYPE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'created_date',
                    text: 'CREATED_DATE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'created_by',
                    text: 'CREATED_BY',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'modified_date',
                    text: 'MODIFIED_DATE',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'modified_by',
                    text: 'MODIFIED_BY',
                    flex:true,
                     
                    
                },
                 
                {
                    dataIndex: 'deleted',
                    text: 'DELETED',
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
                            action: 'search',
                            iconCls: 'icon-save',
                            text: 'Cari'
                        },
                        {
                            xtype: 'button',
                            action: 'delete',
                            iconCls: 'icon-add',
                            text: 'Hapus'
                        },
                        {
                            xtype: 'button',
                            action: 'create',
                            iconCls: 'icon-add',
                            text: 'Buat Baru'
                        }
                    ]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: this.store,
                }
            ]

        });

        me.callParent(arguments);
    }
});


