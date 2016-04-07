Ext.define('Esmk.model.TKuesionerPilihanJawaban', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_pertanyaan_id', type: 'string'},
            {name: 'pilihan_jawaban', type: 'string'},
            {name: 'keterangan_tambahan', type: 'string'},
            {name: 'urutan', type: 'string'},
			{name: 'skor', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});