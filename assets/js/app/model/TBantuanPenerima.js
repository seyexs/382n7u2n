Ext.define('Esmk.model.TBantuanPenerima', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'sekolah_id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'jumlah_bantuan', type: 'string'},
            {name: 'tanggal_cetak_sk', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'tanggal_diterima_bantuan', type: 'string'},
            {name: 'bukti_penerimaan_bantuan', type: 'string'},
            {name: 'nama_sekolah', type: 'string'},
            {name: 'nama_bantuan', type: 'string'},
            {name: 'jumlah_dana', type: 'string'},
            {name: 'prop', type: 'string'},
            {name: 'kab', type: 'string'}
			
    ]
});