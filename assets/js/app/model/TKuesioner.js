Ext.define('Esmk.model.TKuesioner', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'nomor', type: 'string'},
            {name: 'judul', type: 'string'},
            {name: 'keterangan', type: 'string'},
            {name: 'is_open', type: 'integer'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});