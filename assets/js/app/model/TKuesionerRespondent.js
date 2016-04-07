Ext.define('Esmk.model.TKuesionerRespondent', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_kuesioner_id', type: 'string'},
            {name: 'authitem_name', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 'kuesioner_judul', type: 'string'},
			{name: 'keterangan', type: 'string'},
    ]
});