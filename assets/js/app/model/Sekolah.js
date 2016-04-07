Ext.define('Esmk.model.Sekolah', {
extend: 'Ext.data.Model',
fields: [
			{name: 'sekolah_id', type: 'string'},
            {name: 'nama', type: 'string'},
			{name: 'npsn', type: 'string'},
			{name: 'propinsi', type: 'string'},
			{name: 'kabupaten', type: 'string'},
			{name: 'nomor_telepon', type: 'string'},
			{name: 'nomor_fax', type: 'string'},
			{name: 'email', type: 'string'},
			{name: 'alamat_jalan', type: 'string'},
            {name: 'jumlah_siswa', type: 'string'},
    ]
});