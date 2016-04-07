Ext.define('PHPExtJS.view.TBantuanDoc._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuandocForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'PHPExtJS - Form TBantuanDoc',
    layout: 'fit',
    autoShow: true,
    width: 600,
    height: 400,
    iconCls: 'icon-new-data',
    initComponent: function() {

        this.items = [
            {
                xtype: 'form',
                bodyPadding: '10 10 0 10',
                border: false,
                style: 'background-color: #fff;',
                autoScroll: true,
                fieldDefaults: {
                    anchor: '100%',
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: true,
                    msgTarget: 'side',
                    labelWidth: 200,
                },
                items: [
                    {
                        xtype: 'fieldset',
                        title: '<b>TBANTUANDOC</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'ID',
                                name: 'id',
                                 
                                hidden:true,
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'T_PROGRAM_BANTUAN_ID',
                                name: 't_program_bantuan_id',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'PATH_FILE',
                                name: 'path_file',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'FILE_TYPE',
                                name: 'file_type',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'CREATED_DATE',
                                name: 'created_date',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'CREATED_BY',
                                name: 'created_by',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'MODIFIED_DATE',
                                name: 'modified_date',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'MODIFIED_BY',
                                name: 'modified_by',
                                                                
                            },
                             
                            {
                                xtype: 'textfield',
                                fieldLabel: 'DELETED',
                                name: 'deleted',
                                                                
                            }
                                                     
                        ]
                    }]
            }];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
                        iconCls: 'icon-save',
                        text: 'Simpan',
                        action: 'save'
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Batal',
                        action: 'cancel'
                    },]
            }];

        this.callParent(arguments);


    }
});


