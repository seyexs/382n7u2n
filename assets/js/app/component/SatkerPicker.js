Ext.define('Esmk.component.SatkerPicker', {
    extend: 'Esmk.component.RemoteCombo',

    defaultValue: '',

    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            store: this.createStore(),
            valueField: 'satker_id',
            displayField: 'satker_name',
            value: this.defaultValue,
            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Satuan Kerja Tidak Ditemukan',
                autoHeight:true,

                getInnerTpl: function() {
                    return '{satker_id}. {satker_name}';
                }
            }
        });

        this.callParent(arguments);
    },

    createStore: function (){

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
                },
                extraParams:{
                    'query' : this.defaultValue
                },
                autoLoad: false
            }
        });

        return store;
    }
});
