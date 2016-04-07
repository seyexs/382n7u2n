Ext.define('Esmk.model.TBantuanData', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 't_data_rekap_id', type: 'string'},
            {name: 'jumlah_paket', type: 'string'},
            {name: 'tgl_cetak_sk', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 'm_sekolah_text',type:'string'},
			{name: 'nama_bantuan',type:'string'}
    ]
});