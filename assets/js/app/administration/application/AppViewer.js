Ext.define('Esmk.administration.application.AppViewer', {
    extend: 'Ext.panel.Panel',

    selectedSatkerId : null,
    selectedYear : null,
    
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            padding: 5,
            region:'center',
            layout: 'border',
            title: 'Administrasi Aplikasi',
            items:[
            this.createTopPanel(),
            this.createForm()
            ],
            tbar: [
            {
                xtype: 'button',
                text: 'Simpan',
                iconCls:'icon-disk-black',
                handler: me.saveValue,
                scope: me
            }],
            scope:me
        });
        
        this.callParent(arguments);
    },

    createSatker : function(){
        var me = this;
        Ext.define('Satker', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'satker_id',
                type: 'string'
            },
            {
                name: 'satker_name',
                type: 'string'
            }
            ]
        });

        var store = Ext.create('Ext.data.Store', {
            storeId:'satkerStore',
            model: "Satker",
            pageSize: 30,
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/arrange/satkerlist'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                }
            }
        });

        store.load({
            params:{
                start:0,
                limit: 30
            }
        });


        var comboSatker = Ext.create('Ext.form.field.ComboBox', {
            name: 'satker_id',
            fieldLabel: 'Satker',
            width: 275,
            labelWidth: 125,
            store:store,
            mode: 'remote',
            valueField: 'satker_id',
            displayField: 'satker_id',
            typeAhead: true,
            forceSelection: false,
            pageSize: 30,
            minChars:2,
            matchFieldWidth: false,

            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Satuan Kerja Tidak Ditemukan',
                width: 700,
                height:300,
                autoHeight:true,

                getInnerTpl: function() {
                    return '{satker_id}. {satker_name}';
                }
            },
            listeners: {
                select: function(combo, records, index) {
                    Ext.getCmp('tab-form-panel').setDisabled( false );
                    var value = records[0].get(combo.valueField);
                    me.selectedSatkerId = value;
                    me.loadData();
                }
            }
        });

        return comboSatker;
    },

    createTopPanel: function(){

        var me = this;

        var panel = Ext.create('Ext.form.Panel', {
            id:'top-form-panel',
            region:'north',
            bodyStyle:'padding:10px',
            border:0,
            fieldDefaults: {
                msgTarget: 'side',
                labelWidth: 75
            },
            defaultType: 'textfield',

            items: [me.createYear(), me.createSatker()]
        });

        return panel;
    },

    createYear: function(){

        var me = this;
        var now = new Date();
        var fullYear = now.getFullYear();
        var comboYear = Ext.create('Esmk.component.YearCombo', {
            name: 'fiscal_year_id',
            width: 275,
            labelWidth: 125,
            listeners: {
                select: function(combo, records, index) {
                    var value = records[0].get(combo.valueField);
                    me.selectedYear = value;
                    me.loadData();
                }
            }
        });
        comboYear.setValue(fullYear);
        me.selectedYear = fullYear;

        return comboYear;
    },
    
    loadData: function(){
        var me = this;
        var satker_id = me.selectedSatkerId;
        var fiscal_year_id = me.selectedYear;

        if (satker_id == '' || fiscal_year_id == ''){
            return;
        }

        Ext.Ajax.request({
            url: BASE_URL + '/administration/settinglist',
            params: {
                satker_id: satker_id,
                fiscal_year_id: fiscal_year_id
            },
            success: function(response){
                var data = eval(response.responseText);
                me.settingData = data[0];
                me.setValue();
            },
            scope: me
        });

        //Load dipa data
        this.appDipa.satker_id =  satker_id;
        this.appDipa.fiscal_year_id =  fiscal_year_id;
        this.appDipa.loadForm();
    },

    setValue: function(){
        var me = this;
        var form = Ext.getCmp('tab-form-panel').getForm();

        form.findField('tahun_aktif').setValue(this.settingData.tahun_aktif);
        // form.findField('dipa_no').setValue(this.settingData.dipa_no);
        //form.findField('dipa_tanggal').setValue(this.settingData.dipa_tanggal);
        //form.findField('dipa_revisi').setValue(this.settingData.dipa_revisi);

        form.findField('satker_kementrian').setValue(this.settingData.satker_kementrian);
        form.findField('satker_unit').setValue(this.settingData.satker_unit);
        form.findField('satker_lokasi').setValue(this.settingData.satker_lokasi);
        form.findField('satker_kantor').setValue(this.settingData.satker_kantor);
        form.findField('satker_tempat').setValue(this.settingData.satker_tempat);
        form.findField('satker_alamat').setValue(this.settingData.satker_alamat);

        form.findField('bp_nama').setValue(this.settingData.bp_nama);
        form.findField('bp_alamat').setValue(this.settingData.bp_alamat);
        form.findField('bp_bank').setValue(this.settingData.bp_bank);
        form.findField('bp_npwp').setValue(this.settingData.bp_npwp);
        form.findField('bp_no_rekening').setValue(this.settingData.bp_no_rekening);

        form.findField('kpa_nama').setValue(this.settingData.kpa_nama);
        form.findField('kpa_diskripsi').setValue(this.settingData.kpa_diskripsi);

        me.storeKpa.proxy.extraParams['satker_id'] = this.selectedSatkerId;
        me.store.proxy.extraParams['satker_id'] = this.selectedSatkerId;
        
        me.storeKpa.load({
            params:{
                start:0,
                limit: 5,
                query: this.settingData.kpa_id
            }
        });

        me.comboKPA.setValue(this.settingData.kpa_id);

        me.store.load({
            params:{
                start:0,
                limit: 5,
                query: this.settingData.bp_id
            }
        });

        me.comboUser.setValue(this.settingData.bp_id);
    },

    saveValue: function(){
        var me = this;
        
        var topForm = Ext.getCmp('top-form-panel').getForm();

        var form = Ext.getCmp('tab-form-panel').getForm();

        var satker_id = topForm.findField('satker_id').getValue();
        var fiscal_year_id = topForm.findField('fiscal_year_id').getValue();
        
        var tahun_aktif = form.findField('tahun_aktif').getValue();
        //var dipa_no = form.findField('dipa_no').getValue();
        //var dipa_tanggal = form.findField('dipa_tanggal').getRawValue();
        //var dipa_revisi = form.findField('dipa_revisi').getValue();

        var satker_kementrian = form.findField('satker_kementrian').getValue();
        var satker_unit = form.findField('satker_unit').getValue();
        var satker_lokasi = form.findField('satker_lokasi').getValue();
        var satker_kantor = form.findField('satker_kantor').getValue();
        var satker_tempat = form.findField('satker_tempat').getValue();
        var satker_alamat = form.findField('satker_alamat').getValue();

        var bp_nama = form.findField('bp_nama').getValue();
        var bp_alamat = form.findField('bp_alamat').getValue();
        var bp_bank = form.findField('bp_bank').getValue();
        var bp_npwp = form.findField('bp_npwp').getValue();
        var bp_no_rekening = form.findField('bp_no_rekening').getValue();

        var kpa_nama = form.findField('kpa_nama').getValue();
        var kpa_diskripsi = form.findField('kpa_diskripsi').getValue();

        var bp_id = form.findField('bp_id').getValue();
        var kpa_id = form.findField('kpa_id').getValue();
        var dipaForm = me.appDipa.getForm();
        me.getEl().mask('Mohon tunggu hingga proses upload selesai');
        dipaForm.submit({
            params: {
                total_revision: me.appDipa.total_revision,
                fiscal_year_id: me.selectedYear,
                satker_id: me.selectedSatkerId
            },
            url: BASE_URL + '/administration/uploaddipa',
            success: function(form, action) {
                me.getEl().unmask();
                me.appDipa.loadForm();
            },
            failure: function(form, action) {
                Ext.create('widget.uxNotification', {
                    title: 'Notifikasi',
                    position: 'br',
                    manager: 'demo1',
                    iconCls: 'ux-notification-icon-error',
                    autoHideDelay: 5000,
                    autoHide: true,
                    spacing: 20,
                    html: "Data Gagal di Upload<br>" + action.result.message
                }).show();               
                
                me.getEl().unmask();
            }
        });

        Ext.Ajax.request({
            url: BASE_URL + '/administration/insertsetting',
            params: {
                fiscal_year_id: fiscal_year_id,
                satker_id: satker_id,
                
                tahun_aktif: tahun_aktif,
                //dipa_no: dipa_no,
                //dipa_tanggal: dipa_tanggal,
                //dipa_revisi: dipa_revisi,

                satker_kementrian: satker_kementrian,
                satker_unit: satker_unit,
                satker_lokasi: satker_lokasi,
                satker_kantor: satker_kantor,
                satker_tempat: satker_tempat,
                satker_alamat: satker_alamat,

                bp_nama: bp_nama,
                bp_alamat: bp_alamat,
                bp_bank: bp_bank,
                bp_npwp: bp_npwp,
                bp_no_rekening: bp_no_rekening,

                kpa_nama: kpa_nama,
                kpa_diskripsi: kpa_diskripsi,

                kpa_id: kpa_id,
                bp_id: bp_id

            },
            success: function(response){
                Ext.create('widget.uxNotification', {
                    title: 'Notifikasi',
                    position: 'br',
                    manager: 'demo1',
                    iconCls: 'ux-notification-icon-information',
                    autoHideDelay: 2000,
                    autoHide: true,
                    spacing: 20,
                    html: 'Data berhasil disimpan'
                }).show();
            },
            failure: function(form, action) {
            }
        });
    },

    createForm : function(){

        var me = this;
        Ext.define('Users', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'user_id',
                type: 'string'
            },
            {
                name: 'user_name',
                type: 'string'
            },
            {
                name: 'user_email',
                type: 'string'
            },
            {
                name: 'user_realname',
                type: 'string'
            },
            {
                name: 'user_image',
                type: 'string'
            }
            ]
        });

        me.store = Ext.create('Ext.data.Store', {
            model: 'Users',
            pageSize: 5,
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/pic/userlist'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                }
            }
        });

        me.storeKpa = Ext.create('Ext.data.Store', {
            model: 'Users',
            pageSize: 5,
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/pic/userlist'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                }
            }
        });

        me.store.load({
            params:{
                start:0,
                limit: 5
            }
        });

        me.storeKpa.load({
            params:{
                start:0,
                limit: 5
            }
        });

        me.comboUser = Ext.create('Ext.form.field.ComboBox', {
            name: 'bp_id',
            width: 500,
            labelWidth: 150,
            fieldLabel: 'User BP',
            store:me.store,
            mode: 'remote',
            valueField: 'user_id',
            displayField: 'user_realname',
            typeAhead: true,
            forceSelection: false,
            pageSize: 30,
            minChars:2,
            matchFieldWidth: false,

            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Pengguna Tidak Ditemukan',
                width: 700,
                height:300,
                autoHeight:true,

                getInnerTpl: function() {
                    return '<div style="height:70px;"><img class="img-left framed" style="cursor:pointer;" src="{user_image}"><h2>{user_realname}<br>( {user_name} )</h2><table style="margin-top:4px;"><tr><td>{user_email}</td></tr></table></div>';
                }
            },
            listeners: {
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    var column = this.columns[colIndex],
                    editor = column.editor || column.field,
                    comboStore = editor.store,
                    displayField = editor.displayField;
                    return comboStore.getById(value) ? comboStore.getById(value).get(displayField) : ['(', value, ')'].join('');
                }
            }
        });

        me.comboKPA = Ext.create('Ext.form.field.ComboBox', {
            name: 'kpa_id',
            width: 500,
            labelWidth: 150,
            fieldLabel: 'User KPA',
            store: me.storeKpa,
            mode: 'remote',
            valueField: 'user_id',
            displayField: 'user_realname',
            typeAhead: true,
            forceSelection: false,
            pageSize: 30,
            minChars:2,
            matchFieldWidth: false,

            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Pengguna Tidak Ditemukan',
                width: 700,
                height:300,
                autoHeight:true,

                getInnerTpl: function() {
                    return '<div style="height:70px;"><img class="img-left framed" style="cursor:pointer;" src="{user_image}"><h2>{user_realname}<br>( {user_name} )</h2><table style="margin-top:4px;"><tr><td>{user_email}</td></tr></table></div>';
                }
            },
            listeners: {
                renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                    var column = this.columns[colIndex],
                    editor = column.editor || column.field,
                    comboStore = editor.store,
                    displayField = editor.displayField;
                    return comboStore.getById(value) ? comboStore.getById(value).get(displayField) : ['(', value, ')'].join('');
                }
            }
        });

        var tabs = Ext.create('Ext.form.Panel', {
            id:'tab-form-panel',
            border: 0,
            bodyBorder: false,
            region:'center',
            layout: 'fit',
            disabled: true,
            fieldDefaults: {
                labelWidth: 100,
                width : 300
            },
            defaults: {
                anchor: '100%'
            },

            items: {
                xtype:'tabpanel',
                activeTab: 0,
                defaults:{
                    bodyStyle:'padding:10px',
                    border: 0
                },

                items:[
                {
                    title:'Umum',
                    defaultType: 'textfield',
                    items: [{
                        fieldLabel: 'Tahun Aktif',
                        name: 'tahun_aktif',
                        allowBlank:false
                    }]
                },
                me.createDipaView(),
                {
                    title:'Informasi Satker',
                    defaultType: 'textfield',
                    items: [{
                        fieldLabel: 'Kementrian / Lembaga',
                        name: 'satker_kementrian',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Unit Organisasi',
                        name: 'satker_unit',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Lokasi',
                        name: 'satker_lokasi',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Kantor / Satker',
                        name: 'satker_kantor',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Tempat',
                        name: 'satker_tempat',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Alamat',
                        name: 'satker_alamat',
                        xtype: 'textareafield',
                        labelWidth: 150,
                        width : 500
                    }
                    ]
                },
                {
                    title:'Bendahara Pengeluaran',
                    defaultType: 'textfield',

                    items: [
                    me.comboUser,
                    {
                        fieldLabel: 'Atas Nama BP',
                        name: 'bp_nama',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Alamat BP',
                        name: 'bp_alamat',
                        xtype: 'textareafield',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Bank',
                        name: 'bp_bank',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'NPWP',
                        name: 'bp_npwp',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Nomor Rekening',
                        name: 'bp_no_rekening',
                        labelWidth: 150,
                        width : 500
                    },
                    ]
                },
                {
                    title:'Kuasa Pengguna Anggaran',
                    defaultType: 'textfield',

                    items: [
                    me.comboKPA,
                    {
                        fieldLabel: 'Nama KPA',
                        name: 'kpa_nama',
                        labelWidth: 150,
                        width : 500
                    },{
                        fieldLabel: 'Deskripsi',
                        name: 'kpa_diskripsi',
                        labelWidth: 150,
                        width : 500
                    }
                    ]
                }]
            }
        });

        return tabs;
    },
    
    showUploadDialog: function(){
        var form = Ext.widget('form', {
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            border: false,
            bodyPadding: 10,

            fieldDefaults: {
                labelAlign: 'top',
                labelWidth: 100,
                labelStyle: 'font-weight:bold'
            },
            defaults: {
                margins: '0 0 5 0'
            },
            items:[{
                xtype: 'filefield',
                name: 'dbf-file',
                fieldLabel: 'File RKAKL'
            }],
            buttons: [{
                text: 'Cancel',
                margins: '2 2 2 2',
                handler: function() {
                    this.up('form').getForm().reset();
                    this.up('window').hide();
                }
            }, {
                text: 'Upload',
                iconCls: 'upload-icon',
                handler: function() {
                    var self = this;
                    var form = this.up('form').getForm();

                    if(form.isValid()){
                        form.submit({
                            url: BASE_URL + '/arrange/uploadhandler',
                            waitMsg: 'Mohon tunggu hingga proses selesai...',
                            success: function(form, action) {

                                self.up('form').getForm().reset();
                                self.up('window').close();

                                Ext.Msg.show({
                                    title: 'Berhasil',
                                    msg:  action.result.message,
                                    minWidth: 200,
                                    modal: true,
                                    icon: Ext.Msg.INFO,
                                    buttons: Ext.Msg.OK
                                });
                            },
                            failure: function(form, action) {
                                self.up('form').getForm().reset();
                                Ext.Msg.show({
                                    title: 'Gagal',
                                    msg:  action.result.message,
                                    minWidth: 200,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }
                        });
                    }


                }
            }]
        });

        var win = Ext.widget('window', {
            title: 'Upload RKAKL',
            width: 400,
            height: 150,
            minHeight: 150,
            layout: 'fit',
            resizable: true,
            modal: true,
            items: form
        });
        win.show();
    },

    createDipaView: function(){
        var me = this;
        me.appDipa = Ext.create('Esmk.administration.application.AppDipa', {
            id: 'app-admin-dipa-form'
        });
        return me.appDipa;
    }
});