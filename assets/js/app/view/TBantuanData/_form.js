Ext.define('Esmk.view.TBantuanData._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuandataForm',
	id:'tbantuandataformid',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*',
		'Ext.layout.container.HBox',
    ],
    title: 'Penerima Bantuan',
    layout: 'fit',
    autoShow: true,
    width: 900,
	
    height:600,
    iconCls: 'icon-new-data',
    initComponent: function() {
		var me=this;
		var group1 = this.id + 'group1',
            group2 = this.id + 'group2',
            columns1 = [{xtype: 'rownumberer',width: 50,sortable: false,flex: false},
				{dataIndex:'id',hidden:true},
				{text:'Nama Sekolah',name:'t_data_rekap_id',dataIndex:'t_data_rekap_id',type:'string',flex:true}],
			columns2 = [{dataIndex:'id',hidden:true},
				{text:'Propinsi',name:'propinsi_text',dataIndex:'propinsi_text',type:'string',flex:true},
				{text:'Kab/Kota',name:'kabupaten_text',dataIndex:'kabupaten_text',type:'string',flex:true},
				{text:'Nama Sekolah',name:'m_sekolah_text',dataIndex:'m_sekolah_text',type:'string',flex:true}];
        var storeTBantuanDataIn=Ext.create('Esmk.store.TBantuanData');
		var storeTBantuanDataOut=Ext.create('Esmk.store.TDataRekap');
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
                        title: '<b>Data</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [{
									xtype:'combobox',
									fieldLabel:'Bantuan',
									name: 't_bantuan_program_id',
									id:'t_bantuan_program_id1',
									width:200,
									store:Ext.create('Esmk.store.TBantuanProgram'),
									mode: 'remote',
									valueField: 'id',
									displayField: 'nama',
									typeAhead: true,
									forceSelection: true,
									pageSize: 30,
									minChars:2,
									//matchFieldWidth: false,
									editable: false,
									emptyText:'Bantuan',
									listConfig: {
										loadingText: 'Proses Pencarian...',
										emptyText: 'Bantuan Tidak Ditemukan',
										//width: '71%',
										//height:300,
										autoHeight:true,
										getInnerTpl: function() {
											return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
										}
									},
									listeners:{
										'select':function( combo, records, eOpts){
											var tbpid=records[0].get(combo.valueField);
											Ext.getCmp('gridsekolahin').getStore().getProxy().api.read='TBantuanData/GetDaftarPenerimaBantuan/?tbpid='+tbpid;
											Ext.getCmp('gridsekolahin').getStore().getProxy().api.destroy='TBantuanData/GetDaftarPenerimaBantuan/?tbpid='+tbpid;
											Ext.getCmp('gridsekolahin').getStore().pageSize=20;
											Ext.getCmp('gridsekolahin').getStore().load();
											Ext.getCmp('gridsekolahout').getStore().getProxy().api.read='TBantuanData/GetDaftarBukanPenerimaBantuan/?tbpid='+tbpid;
											Ext.getCmp('gridsekolahout').getStore().getProxy().api.destroy='TBantuanData/GetDaftarBukanPenerimaBantuan/?tbpid='+tbpid;
											Ext.getCmp('gridsekolahout').getStore().pageSize=20;
											Ext.getCmp('gridsekolahout').getStore().load();
										}
									}
									
								}],
                    },{
						xtype:'container',
						layout: {
							type: 'hbox',
							align: 'stretch',
							padding: 5
						},
						autoWidth:true,
						items:[{
								itemId: 'gridsekolahin',
								id:'gridsekolahin',
								flex: 1,
								xtype: 'grid',
								multiSelect: true,
								viewConfig: {
									plugins: {
										ptype: 'gridviewdragdrop',
										dragText: 'Keluarkan Dari Daftar Penerima Bantuan',
										dropText:'Masukkan Dalam daftar Penerima Bantuan',
										dragGroup: group1,
										dropGroup: group2
									},
									listeners: {
										drop: function(node, data, dropRec, dropPosition) {
											//alert('di grid in '+data.records[0].get('displayname'));
											me.actionAddUserToGroup(data.records);
												//var dropOn = dropRec ? ' ' + dropPosition + ' ' + dropRec.get('name') : ' on empty view';
												//alert('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
												//Ext.example.msg('Drag from right to left', 'Dropped ' + data.records[0].get('name') + dropOn);
										},
										viewready: function() {
											/*this.getStore().getProxy().api.read='TBantuanData/GetDaftarPenerimaBantuan/?tbpid='+me.tbpid;
											this.getStore().getProxy().api.destroy='Groups/GetDaftarPenerimaBantuan/?tbpid='+me.tbpid;
											this.getStore().pageSize=20;
											this.getStore().load();*/
										},
									}
								},
								store:storeTBantuanDataIn,
								columns: columns1,
								stripeRows: true,
								title: 'Daftar Penerima Bantuan',
								tools: [{
									type: 'refresh',
									tooltip: 'Reset both grids',
									scope: this,
									handler: this.onResetClick
								}],
								margins: '0 5 0 0',
								dockedItems:[{
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
							},
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
												allowBlank:true
											},
											{
												xtype: 'button',
												iconCls: 'icon-search',
												text: 'Cari',
												handler:this.actionSearchUserIn
											}
										]
									},{
										xtype: 'pagingtoolbar',
										dock: 'bottom',
										displayInfo: true,
										emptyMsg: 'No data to display',
										store: storeTBantuanDataIn,
								}]
							},{
								itemId: 'gridsekolahout',
								id:'gridsekolahout',
								flex: 1,
								xtype: 'grid',
								multiSelect: true,
								viewConfig: {
									plugins: {
										ptype: 'gridviewdragdrop',
										dragText: 'Masukkan Dalam daftar Penerima Bantuan',
										dropText:'Keluarkan Dari Daftar Penerima Bantuan',
										dragGroup: group2,
										dropGroup: group1
									},
									listeners: {
										drop: function(node, data, dropRec, dropPosition) {
											me.actionRemoveUserFromGroup(data.records);
											//alert( data.records[0].get('name'));
										},
										viewready: function() {
											/*this.getStore().getProxy().api.read='Groups/GetDaftarBukanPenerimaBantuan/?tbpid='+me.tbpid;
											this.getStore().getProxy().api.destroy='Groups/GetDaftarBukanPenerimaBantuan/?tbpid='+me.tbpid;
											this.getStore().pageSize=20;
											this.getStore().load();*/
										},
									}
								},
								store:storeTBantuanDataOut,
								columns: columns2,
								stripeRows: true,
								title: 'Daftar Sekolah',
								dockedItems:[{
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
							},
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
												allowBlank:true
											},
											{
												xtype: 'button',
												iconCls: 'icon-search',
												text: 'Cari',
												handler:function(){
													me.actionSearchUserOut();
												}
											}
										]
									},{
										xtype: 'pagingtoolbar',
										dock: 'bottom',
										displayInfo: true,
										emptyMsg: 'No data to display',
										store: storeTBantuanDataOut,
								}]
							}]
					}]
            }];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', /*{
                        iconCls: 'icon-save',
                        text: 'Simpan',
                        action: 'save',
						handler:function(){
							me.actionSave(this);
						}
                    },*/ {
                        iconCls: 'icon-reset',
                        text: 'Tutup',
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
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
        if (values.id !='') {
            record.set(values); //saving line
        } else {
            record = Ext.create('Esmk.model.TBantuanData');
            record.set(values);
            Ext.getCmp('tbantuandatagridid').getStore().add(record);
            isNewRecord = true;
        }
		Ext.getCmp('tbantuandatagridid').getStore().load();
        win.close();
    },
});


