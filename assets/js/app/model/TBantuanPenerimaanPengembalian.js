Ext.define('Esmk.model.TBantuanPenerimaanPengembalian', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_penerima_id', type: 'string'},
            {name: 'jumlah_bantuan', type: 'string'},
            {name: 'tanggal_diterima_dikembalikan', type: 'string'},
            {name: 'bukti_diterima_dikembalikan', type: 'string'},
            {name: 'status', type: 'integer'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});