Ext.define('Esmk.administration.application.AppDipa', {
    extend: 'Ext.form.Panel',

    total_revision : 0,
    initComponent: function(){
        var me = this;

        Ext.apply(this, {
            title: 'DIPA',
            border: 0,
            autoScroll:true,
            tbar: [
            {
                xtype: 'button',
                text: 'Tambah Revisi',
                iconCls: 'icon-add',
                scope:me,
                handler: function(){
                    me.addForm();
                }
            },
            {
                xtype: 'button',
                text: 'Reset',
                iconCls: 'icon-refresh',
                scope:me,
                handler: function(){
                    me.loadForm();
                }
            }],
            fieldDefaults: {
                labelWidth: 100,
                width: 500
            }
        });

        this.callParent(arguments);
    },

    addForm: function(){
        this.total_revision = this.total_revision + 1;
        var dummyObject = new Object();
        dummyObject.dipa_revision = this.total_revision;
        dummyObject.dipa_number = '';
        dummyObject.dipa_date = '';
        dummyObject.dipa_file_uploaded = 0;

        var panel = this.createMainDIPA(dummyObject);
        this.add(panel);
    },

    createMainDIPA: function(obj){
        var me = this;
        var cheked = '<span class="circle_green" style="width:16px;height:16px;display:block;">&nbsp<span>';
        if (obj.dipa_file_uploaded == '0'){
            cheked = '<span class="circle_red" style="width:16px;height:16px;display:block;">&nbsp<span>';
        }
        
        var fieldset = Ext.create('Ext.form.FieldSet',{
            defaultType: 'textfield',
            padding: 4,
            title:'Revisi ' + obj.dipa_revision,
            
            items:[
            {
                fieldLabel: 'Nomor DIPA',
                name: 'dipa_number_' + obj.dipa_revision,
                value: obj.dipa_number,
                allowBlank:false
            },
            {
                fieldLabel: 'Tanggal DIPA',
                name: 'dipa_date_' + obj.dipa_revision,
                xtype: 'datefield',
                format: 'd/m/Y',
                altFormats: 'Y-m-d',
                value: obj.dipa_date
            },{
                xtype: 'fileuploadfield',
                fieldLabel: 'File DIPA',
                name: 'dipa_file_' + obj.dipa_revision
            },{
                xtype: 'displayfield',
                fieldLabel: 'Data DIPA',
                value :cheked,
                name: 'dipa_file_' + obj.dipa_revision
            }
            ]
        });

        return fieldset;
    },

    loadForm: function(){
        var me = this;
        me.removeAll();
        Ext.Ajax.request({
            url: BASE_URL + '/administration/dipainfo',
            params: {
                satker_id: me.satker_id,
                fiscal_year_id: me.fiscal_year_id
            },
            success: function(response){
                var json = Ext.JSON.decode(response.responseText);
                me.total_revision = json.length - 1;
                if (json.length == 0){
                    me.total_revision = 0;
                    
                    var dummyObject = new Object();
                    dummyObject.dipa_revision = 0;
                    dummyObject.dipa_number = '';
                    dummyObject.dipa_date = '';
                    dummyObject.dipa_file_uploaded = '0';

                    me.add(me.createMainDIPA(dummyObject));
                }
                else{
                    for(var i = 0; i < json.length; i++){
                        me.add(me.createMainDIPA(json[i]));
                    }
                }
            },
            scope: me
        });
    }
});