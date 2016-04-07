Ext.define('Esmk.model.TBantuanPersyaratanPenerima', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'keterangan', type: 'string'},
            {name: 'jenis_jawaban', type: 'string'},
            {name: 'skor', type: 'string'},
            {name: 'urutan', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});