Ext.define('Esmk.model.TBantuanPersyaratanPenerimaInquery', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'query', type: 'string'},
            {name: 'keterangan', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 't_bantuan_program_nama', type: 'string'},
    ]
});