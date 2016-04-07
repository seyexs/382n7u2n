Ext.define('Esmk.component.DipaPicker', {
    extend: 'Ext.form.field.ComboBox',

    mustChoose: true,

    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            fieldLabel: 'Revisi DIPA',
            name: 'dipa_revision',
            store: me.createStore(),
            displayField: 'dipa_revision_name',
            valueField: 'dipa_revision',
            editable: false,
            queryMode: 'local',
            listConfig: {
                getInnerTpl: function() {
                    return '{dipa_revision_name} <br> {dipa_number}';
                }
            }
        });
        this.callParent(arguments);
    },

    createStore: function (){
        var me = this;
        
        Ext.define('DIPA_Model',{
            extend: 'Ext.data.Model',
            fields: [{
                name: 'fiscal_year_id',
                type: 'string'
            },{
                name: 'satker_id',
                type: 'string'
            },{
                name: 'dipa_revision',
                type: 'integer'
            },{
                name: 'dipa_revision_name',
                type: 'string'
            },{
                name: 'dipa_number',
                type: 'string'
            },{
                name: 'dipa_date',
                type: 'string'
            },{
                name: 'dipa_file_upload',
                type: 'int'
            }]
        });
        
        this.dipaStore = Ext.create('Ext.data.Store',{
            model: 'DIPA_Model',
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/spp/alldipa'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                },
                extraParams:{
                    fiscal_year_id: this.fiscal_year_id,
                    satker_id: this.satker_id
                }
            }
        });
        
        this.dipaStore.on('load',function(store) {
            if (store.getAt(0) != null && me.mustChoose == true){
                var value = store.getAt(0).get('dipa_revision');
                me.setValue(value);
            }
        });
        
        if (this.fiscal_year_id != null && this.satker_id != null )
            this.dipaStore.load();
            
        return this.dipaStore;
    },

    loadDipa: function(){
        if (this.fiscal_year_id != null && this.satker_id == null ){
            this.dipaStore.proxy.extraParams.fiscal_year_id = this.fiscal_year_id;
            this.dipaStore.proxy.extraParams.satker_id = this.satker_id;
            this.dipaStore.load();
        }
    }
});


