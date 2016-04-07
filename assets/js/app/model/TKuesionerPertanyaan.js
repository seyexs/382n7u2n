Ext.define('Esmk.model.TKuesionerPertanyaan', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_kuesioner_id', type: 'string'},
            {name: 'parent_id', type: 'string'},
            {name: 'pertanyaan', type: 'string'},
            {name: 'penjelasan', type: 'string'},
            {name: 'jenis_jawaban', type: 'string'},
            {name: 'allow_multi_answer', type: 'integer'},
            {name: 'urutan', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name:'options'},
			{name:'style'}
    ]
});