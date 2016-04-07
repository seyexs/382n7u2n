Ext.define('Esmk.model.PesertaDidik', {
extend: 'Ext.data.Model',
fields: [
			{name: 'propinsi', type: 'string'},
            {name: 'kabupaten', type: 'string'},
			{name: 'sekolah_id', type: 'string'},
			{name: 'nama_sekolah', type: 'string'},
			{name: 'npsn', type: 'string'},
			{name: 'nomor_telepon', type: 'string'},
			{name: 'alamat_jalan', type: 'string'},
			{name: 'peserta_didik_id', type: 'string'},
			{name: 'nisn', type: 'string'},
			{name: 'nik', type: 'string'},
			{name: 'nama', type: 'string'},
			{name: 'tempat_lahir', type: 'string'},
			{name: 'tanggal_lahir', type: 'string'},
            {name: 'jenis_kelamin', type: 'string'},
            {name: 'nomor_telepon_rumah', type: 'string'},
            {name: 'nomor_telepon_seluler', type: 'string'},
            {name: 'tinggi_badan', type: 'string'},
            {name: 'berat_badan', type: 'string'},
    ]
});