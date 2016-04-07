Ext.define('Esmk.model.TBantuanUsulanPenerima', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'sekolah_id', type: 'string'},
            {name: 'catatan', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 't_bantuan_program_name', type: 'string'},
			{name: 'sekolah_nama', type: 'string'},
    ]
});