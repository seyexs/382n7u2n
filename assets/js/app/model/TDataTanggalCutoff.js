Ext.define('Esmk.model.TDataTanggalCutoff', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'tanggal', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'keterangan_periode', type: 'string'},
            {name: 'status', type: 'string'},
    ]
});