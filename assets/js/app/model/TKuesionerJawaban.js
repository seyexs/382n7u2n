Ext.define('Esmk.model.TKuesionerJawaban', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'user_id', type: 'string'},
            {name: 't_kuesioner_pilihan_jawaban_id', type: 'string'},
            {name: 'catatan_jawaban', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});