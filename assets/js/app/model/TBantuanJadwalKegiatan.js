Ext.define('Esmk.model.TBantuanJadwalKegiatan', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'kegiatan', type: 'string'},
            {name: 'waktu_pelaksanaan', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});