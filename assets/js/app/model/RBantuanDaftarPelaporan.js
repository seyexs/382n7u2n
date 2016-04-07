Ext.define('Esmk.model.RBantuanDaftarPelaporan', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'r_bantuan_id', type: 'string'},
            {name: 'nama', type: 'string'},
            {name: 'kode_module', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 'r_bantuan_name', type: 'string'},
			{name: 'properties', type: 'string'},
    ]
});