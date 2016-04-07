Ext.define('Esmk.component.YearCombo', {
    extend: 'Ext.form.field.ComboBox',

    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            fieldLabel: 'Tahun Anggaran',
            store: me.createStore(),
            displayField: 'fiscal_year_id',
            valueField: 'fiscal_year_id',
            editable: false,
            queryMode: 'local'
        });
        this.callParent(arguments);
    },

    createStore: function (){
        
        Ext.define('Year', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'fiscal_year_id',
                type: 'integer'
            }
            ]
        });

        var yearData = new Array();
        var now = new Date();
        
       for(var i = now.getFullYear() + 1; i >= 2011 ; i--){
            yearData.push({
                "fiscal_year_id":i
            });
        }

        var store = Ext.create('Ext.data.Store', {
            fields: ['fiscal_year_id'],
            data: yearData
        });

        return store;
    }
});


