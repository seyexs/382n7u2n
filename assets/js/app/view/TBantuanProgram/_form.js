Ext.define('Esmk.view.TBantuanProgram._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanprogramForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Master Bantuan',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var me=this;
		//var storeRBantuan=Ext.create('Esmk.store.RBantuan');
		var storeTim=Ext.create('Esmk.store.TBantuanTimPengelola');
		storeTim.getProxy().api.read="RBantuan/RawRead";
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
                                xtype: 'numberfield',
                                fieldLabel: 'Tahun',
                                name: 'tahun',
                                                                
                            },
                            {
								xtype:'combobox',
								fieldLabel:'Jenis Bantuan',
								name: 'r_bantuan_id',
								id:'combo_id_kab',
								width:200,
								store:Ext.create('Esmk.store.RBantuan'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'name',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Jenis Bantuan',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Jenis Bantuan Tidak Ditemukan',
									width: '21%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{name}</span>';
									}
								},
								listeners:{
									'beforerender':function(){
										this.getStore().getProxy().api.read="RBantuan/RawRead";
									}
								}
								
							}, 
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Program',
                                name: 'kode',
                                                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Nama Program',
                                name: 'nama',
                                                                
                            },{
                                xtype: 'numberfield',
                                fieldLabel: 'Nilai Bantuan',
                                name: 'nilai_bantuan',
                                allowBlank:true,                                
                            },{
                                xtype: 'textareafield',
                                fieldLabel: 'Keterangan Nilai Bantuan',
                                name: 'keterangan_nilai_bantuan',
								height:50,
                                allowBlank:true,                                
                            },{
									xtype:'radiogroup',
									fieldLabel:'Bentuk Bantuan',
									allowBlank:false,
									defaults: {
										name: 'bentuk_bantuan',
										//margin: '0 15 0 0'
									},
									items:[{
										inputValue:'0',
										boxLabel:'Uang',
									},{
										inputValue:'1',
										boxLabel:'Barang',
										//checked:true
									}]
							},{
								xtype:'combobox',
								fieldLabel:'Penerima Bantuan',
								name: 'r_bantuan_penerima_id',
								id:'combo_r_bantuan_penerima_id',
								width:200,
								store:Ext.create('Esmk.store.RBantuanPenerima'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'nama',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Jenis Penerima Bantuan',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Jenis Penerima Bantuan Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
									}
								},
								listeners:{
									'beforerender':function(){
										this.getStore().getProxy().api.read="RBantuanPenerima/RawRead";
									}
								}
								
							},{
								xtype:'combobox',
								fieldLabel:'PPK',
								name: 'm_pegawai_nip',
								allowBlank:true,
								id:'combo_m_pegawai_nip',
								width:200,
								store:Ext.create('Esmk.store.MPegawai'),
								mode: 'remote',
								valueField: 'nip',
								displayField: 'nama',
								typeAhead: true,
								hidden:(me.isProp==1),
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'PPK',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Data Tidak Ditemukan',
									width: '21%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{gelar_depan} {nama} {gelar_belakang}</span>';
									}
								},
								listeners:{
									'beforerender':function(){
										//this.getStore().getProxy().api.read="RBantuan/RawRead";
									}
								}
								
							},{
								xtype:'combobox',
								fieldLabel:'Instrumen Verifikasi Persyaratan',
								name: 't_kuesioner_id',
								id:'combo_t_kuesioner_id',
								allowBlank:true,
								width:200,
								hidden:(me.isProp==1),
								store:Ext.create('Esmk.store.TKuesioner'),
								mode: 'remote',
								valueField: 'id',
								displayField: 'judul',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								emptyText:'Instrumen Verifikasi',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Instrumen Tidak Ditemukan',
									//width: '71%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{judul}</br>({nomor})</span>';
									}
								},
								
								
							},{
								flex:1,
								xtype:'combobox',
								fieldLabel:'Nama Tim Pengelola',
								name: 't_bantuan_tim_pengelola_nama',
								id:'combo_nama_tim',
								hidden:(me.isProp==1),
								width:200,
								store:Ext.create('Esmk.store.TBantuanTimPengelola'),
								mode: 'remote',
								valueField: 'nama',
								displayField: 'nama',
								typeAhead: true,
								forceSelection: true,
								pageSize: 30,
								minChars:2,
								matchFieldWidth: false,
								editable: true,
								allowBlank:true,
								emptyText:'Pilih Tim Pengelola',
								listConfig: {
									loadingText: 'Proses Pencarian...',
									emptyText: 'Data Tidak Ditemukan',
									width: '23%',
									//height:300,
									autoHeight:true,
									getInnerTpl: function() {
										return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
									}
								},
								listeners:{
									'afterrender':function(){
										this.getStore().getProxy().api.read='TBantuanTimPengelola/GetNamaTimPengelola';
									}
								}
									
							},/*{
								xtype:'datefield',
								name:'tgl_cutoff_data_dapodikmen',
								allowBlank:true,
								format: 'Y-m-d',
								submitFormat:'Y-m-d',
								hidden:(me.isProp==1),
								fieldLabel:'Tanggal Cutoff Data Dapodikmen',
							},*/{
								xtype:'tabpanel',
								layout:'fit',
								items:[{
									title:'Pengertian',
									iconCls:'report-paper',
									layout:'fit',
									items:[new Ext.form.HtmlEditor({
											name : 'pengertian',
											emptyText:'Uraian',
											flex:1,
											autoWidth:true
										})]
								},{
									title:'Tujuan',
									iconCls:'report-paper',
									layout:'fit',
									items:[new Ext.form.HtmlEditor({
											name : 'tujuan',
											emptyText:'Uraian',
											flex:1,
											autoWidth:true
										})]
								},{
									title:'Sasaran',
									iconCls:'report-paper',
									layout:'fit',
									items:[new Ext.form.HtmlEditor({
											name : 'sasaran',
											emptyText:'Uraian',
											flex:1,
											autoWidth:true
										})]
								},{
									title:'Pemanfaatan Dana',
									iconCls:'report-paper',
									layout:'fit',
									items:[new Ext.form.HtmlEditor({
											name : 'pemanfaatan_dana',
											emptyText:'Uraian',
											flex:1,
											autoWidth:true
										})]
								}]
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
                    },]
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
        values = form.getValues(false, false,false,true);

        var isNewRecord = false;
        
        if (values.id !='') {
			//var dt=new Date(values.tgl_cutoff_data_dapodikmen);
			//values.tgl_cutoff_data_dapodikmen=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
			//alert(values.tgl_cutoff_data_dapodikmen);
			//alert(dt.getFullYear()+'-'+dt.getMonth()+'-'+dt.getDate());
            record.set(values); //saving line
			Ext.getCmp('tbantuanprogramgridid').getStore().load();
        } else {
			//var dt=new Date(values.tgl_cutoff_data_dapodikmen);
			//values.tgl_cutoff_data_dapodikmen=dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate();
            record = Ext.create('Esmk.model.TBantuanProgram');
            record.set(values);
            Ext.getCmp('tbantuanprogramgridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanprogramgridid').getStore().load();
        }
        win.close();
    },
});


