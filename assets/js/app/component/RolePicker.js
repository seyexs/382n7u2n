Ext.define('Esmk.component.RolePicker', {
    extend: 'Esmk.component.RemoteCombo',

    defaultValue: '',
    
    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            store: this.createStore(),
            width: 330,
            labelWidth: 125,
            valueField: 'role_id',
            displayField: 'role_name',
            value: this.defaultValue
        });
        
        me.callParent(arguments);
    },

    createStore: function (){

        Ext.define('Roles', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'role_id',
                type: 'string'
            },
            {
                name: 'role_name',
                type: 'string'
            },
            {
                name: 'role_short',
                type: 'string'
            },
            {
                name: 'role_description',
                type: 'string'
            },
            {
                name: 'role_structural',
                type: 'string'
            },
            {
                name: 'role_active',
                type: 'string'
            }]
        });

        var store = Ext.create('Ext.data.Store', {
            model: 'Roles',
            pageSize: 5,
            proxy: {
                type: 'ajax',
                api : {
                    read : BASE_URL + '/role/jabatan/?structural=1'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                },
                extraParams:{
                    'query' : this.defaultValue
                }
            },
            autoLoad: false
        });
        return store;
    }
});
