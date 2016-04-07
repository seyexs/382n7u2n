Ext.define('Esmk.component.AkunPicker', {
    extend: 'Ext.form.field.ComboBox',

    mustChoose: true,

    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            fieldLabel: 'Akun Nomor',
            name: 'akun_id',
            id: 'akun_id',
            store: me.createStore(),
            displayField: 'akun_id',
            valueField: 'akun_id',
            editable: false,
            queryMode: 'local',
            listConfig: {
                getInnerTpl: function() {
                    return '{akun_id}. {akun_name}';
                }
            }
        });
        this.callParent(arguments);
    },

    createStore: function (){
        var me = this;
        
        Ext.define('Akun_Model',{
            extend: 'Ext.data.Model',
            fields: [{
                name: 'bkpk_id',
                type: 'string'
            },{
                name: 'akun_id',
                type: 'string'
            },{
                name: 'akun_name',
                type: 'string'
            }]
        });
        
        this.akunStore = Ext.create('Ext.data.Store',{
            model: 'Akun_Model',
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/akun/get_all_akun'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                }
            }
        });
        
        this.akunStore.on('load',function(store) {
            if (store.getAt(0) != null && me.mustChoose == true){
                me.setValue(this.akun_id);
            }
        });
        
        this.akunStore.load();
            
        return this.akunStore;
    },

    loadAkun: function(){
        this.akunStore.load();
    }
});


