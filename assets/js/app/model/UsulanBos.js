Ext.define('Esmk.model.UsulanBos', {
extend: 'Ext.data.Model',
fields: [
            {name: 'propinsi', type: 'string'},
            {name: 'kabupaten', type: 'string'},
			{name: 'nama', type: 'string'},
			{name: 'sekolah_id', type: 'string'},
			{name: 'kode_wilayah', type: 'string'},
            {name: 'jumlah_sekolah', type: 'string'},
            {name: 'jumlah_siswa', type: 'string'},
			{name: 'jumlah_siswa_tdk_bernisn', type: 'string'},
    ]
});