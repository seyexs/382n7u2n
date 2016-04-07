Ext.define('Esmk.view.TBantuanPenggunaanDana._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpenggunaandanaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Form Perencanaan Penggunaan Dana',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'bogus',
    initComponent: function() {
		var me=this;
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
                        items:[{
							xtype:'textfield',
							hidden:true,
							name:'id'
						},{
							xtype: 'textfield',
							name:'t_bantuan_penerima_id',
							hidden:true,
							value:me.tbpid
						},{
							xtype: 'textfield',
							name:'status_data',
							hidden:true,
							value:me.statusData
						},{
							xtype:'datefield',
							name: 'tanggal_transaksi',
							fieldLabel:'Tanggal Transaksi',
							maxValue: new Date(),
							format:'d-m-Y',
							sumbitFormat:'Y-m-d',
							hidden:(me.statusData=='P')
							
						},{
							xtype:'textfield',
							name: 'uraian',
							fieldLabel:'Uraian Transaksi',
							width:400,
							hidden:(me.statusData=='P')
						},{
							xtype:'numberfield',
							name: 'qty',
							width:80,
							fieldLabel:'Jumlah',
							hidden:(me.statusData=='P')
						},{
							xtype:'textfield',
							name: 'satuan_id',
							width:80,
							fieldLabel:'satuan',
							hidden:(me.statusData=='P')
						},{
							xtype:'numberfield',
							minValue:1,
							name: 'harga_total',
							fieldLabel:'Total Biaya'
						},{
							xtype:'combobox',
							store:Ext.create('Esmk.store.RPeruntukanDanaBos'),
							mode: 'remote',
							name:'r_peruntukan_dana_bos_id',
							valueField: 'id',
							displayField: 'peruntukan_dana',
							typeAhead: true,
							forceSelection: true,
							pageSize: 30,
							minChars:2,
							matchFieldWidth: false,
							editable: true,
							emptyText:'Peruntukan Dana',
							fieldLabel:'Peruntukan Dana',
							listConfig: {
								loadingText: 'Proses Pencarian...',
								emptyText: 'Data Tidak Ditemukan',
								//width: '71%',
								//height:300,
								autoHeight:true,
								getInnerTpl: function() {
									return '<span style="margin-top:2px;margin-left:2px;">{peruntukan_dana}</span>';
								}
							},
						},{
							xtype:'textfield',
							name:'no_bukti',
							fieldLabel:'No.Bukti',
							emptyText:'Nomor',
							hidden:(me.statusData=='P')
						},{
							xtype: 'fileuploadfield',
							//width:110,
							id: 'bukti_kwitansi',
							emptyText: 'Pilih dokumen',
							fieldLabel: 'Upload Nota',
							name: 'bukti_kwitansi',
							buttonText: '',
							buttonConfig: {
								iconCls: 'blueprint'
							},
							hidden:true
						}]
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

        var win =this,
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        if (values.id !='') {
			alert('update');
            record.set(values); //saving line
			Ext.getCmp('tbantuanpenggunaandanagridrencanaid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.TBantuanPenggunaanDana');
            record.set(values);
            Ext.getCmp('tbantuanpenggunaandanagridrencanaid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('tbantuanpenggunaandanagridrencanaid').getStore().load();
        }
		
        win.close();
    },
});


