Ext.define('Esmk.model.TDashboard', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'authitem_name', type: 'string'},
            {name: 'kode_module', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'properties', type: 'string'},
    ]
});