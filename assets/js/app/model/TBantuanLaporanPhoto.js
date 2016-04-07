Ext.define('Esmk.model.TBantuanLaporanPhoto', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_penerima_id', type: 'string'},
            {name: 'path_photo', type: 'string'},
            {name: 'keterangan_photo', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'kategori_progres', type: 'string'}
    ]
});