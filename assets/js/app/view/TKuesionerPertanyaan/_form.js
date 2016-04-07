Ext.define('Esmk.view.TKuesionerPertanyaan._form', {
    //extend: 'Ext.panel.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tkuesionerpertanyaanForm',
	id:'tkuesionerpertanyaanFormid',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
	kid:0,
    title: 'Form Pertanyaan & Pilihan Jawaban',
    layout: 'fit',
    autoShow: true,
    width: 800,
    autoHeight:true,
    iconCls: 'icon-app',
    initComponent: function() {
		var me=this;
        this.items = [{
			xtype:'tabpanel',
			autoScroll:true,
			items:[{
				title:'Form Pertanyaan',
				items:[{
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
							title: '<b>Form Isian</b>',
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
									fieldLabel: 'T_KUESIONER_ID',
									name: 't_kuesioner_id',
									hidden:true,
																	
								},
								{
									xtype:'combobox',
									fieldLabel:'Parent',
									name: 'parent_id',
									id:'parent_id',
									width:200,
									allowBlank:true,
									store:Ext.create('Esmk.store.TKuesionerPertanyaan'),
									mode: 'remote',
									valueField: 'id',
									displayField: 'pertanyaan',
									typeAhead: true,
									forceSelection: true,
									pageSize: 30,
									minChars:2,
									//matchFieldWidth: false,
									editable: false,
									emptyText:'Parent',
									listConfig: {
										loadingText: 'Proses Pencarian...',
										emptyText: 'Pertanyaan Tidak Ditemukan',
										//width: '71%',
										//height:300,
										autoHeight:true,
										getInnerTpl: function() {
											return '<span style="margin-top:2px;margin-left:2px;"><b>{pertanyaan}</span>';
										}
									},
									listeners:{
										'afterrender' :function(){
											var kid=Ext.getCmp('tkuesionerpertanyaanFormid').kid;
											this.getStore().getProxy().api.read='TKuesionerPertanyaan/read/?kid='+kid;
										},
										'select':function( combo, records, eOpts){
											
										}
									}
									
								},                               
								{
									xtype: 'textareafield',
									fieldLabel: 'Pertanyaan/Judul',
									grow : true,
									name: 'pertanyaan',
																	
								},
								 
								{
									xtype: 'textareafield',
									fieldLabel: 'Penjelasan',
									grow : true,
									allowBlank:true,
									name: 'penjelasan',
																	
								},{
									xtype:'radiogroup',
									fieldLabel:'Jenis Jawaban',
									allowBlank:false,
									defaults: {
										name: 'jenis_jawaban',
										//margin: '0 15 0 0'
									},
									items:[{
										inputValue:'0',
										boxLabel:'Kosong',
									},{
										inputValue:'1',
										boxLabel:'Pilihan Ganda',
										//checked:true
									},{
										inputValue:'2',
										boxLabel:'Teks Bebas',
										
									}],
									listeners:{
										change: function (field, newValue, oldValue) {
											if(Ext.getCmp('tkuesionerpertanyaanFormid').pid){
												if(newValue['jenis_jawaban']!='1')
													Ext.getCmp('tabpilihanjawabanid').disable();
												else
													Ext.getCmp('tabpilihanjawabanid').enable();
											}
										}
									}
									
									
								},{
									xtype: 'checkboxfield',
									fieldLabel: 'Allow Multi Answer',
									name: 'allow_multi_answer',
									inputValue:'0',
									hidden:true
								},{
									xtype: 'numberfield',
									fieldLabel: 'Urutan',
									name: 'urutan',
									allowBlank:false								
								}
														 
							]
						}]
						
				}]
			},{
				title:'Form Pilihan Jawaban',
				id:'tabpilihanjawabanid',
				items:[Ext.create('Esmk.view.TKuesionerPilihanJawaban._grid',{
					pid:me.pid
				})]
			}],
			listeners:{
				'afterrender':function(){
					if(!me.pid)
						Ext.getCmp('tabpilihanjawabanid').disable();
				}
			}
		}];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
                        iconCls: 'icon-save',
                        text: 'Simpan',
                        action: 'save',
						handler:function(){
							me.actionSave(this);
						}
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Batal',
                        action: 'cancel',
						handler:function(button, e, options){
							me.actionCancel(button, e, options);
						}
                    }]
            }];
		
        this.callParent(arguments);


    },
	actionReset: function(button, e, options) {
        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
    },

    actionCancel: function(button, e, options) {

        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
        win.close();

    },
	actionSave: function(button) {

        var win = button.up('window'),
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
        if (values.id !='') {
            record.set(values); //saving line
        } else {
            record = Ext.create('Esmk.model.TKuesionerPertanyaan');
			values.t_kuesioner_id=(values.t_kuesioner_id=='')?this.kid:values.t_kuesioner_id;
            record.set(values);
            Ext.getCmp('tkuesionerpertanyaangridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('tkuesionerpertanyaangridid').getStore().reload();
        win.close();
    },
});


