Ext.define('Esmk.model.RSatuan', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'nama', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});