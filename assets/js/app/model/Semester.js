Ext.define('Esmk.model.Semester', {
extend: 'Ext.data.Model',
fields: [
			{name: 'semester_id', type: 'string'},
            {name: 'tahun_ajaran_id', type: 'string'},
			{name: 'nama', type: 'string'},
			{name: 'semester', type: 'string'},
			{name: 'periode_aktif', type: 'string'},
			{name: 'tanggal_mulai', type: 'string'},
			{name: 'tanggal_selesai', type: 'string'}
    ]
});