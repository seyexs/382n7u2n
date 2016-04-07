Ext.define('Esmk.model.MPegawai', {
extend: 'Ext.data.Model',
fields: [
            {name: 'nip', type: 'string'},
            {name: 'nama', type: 'string'},
            {name: 'gelar_depan', type: 'string'},
            {name: 'gelar_belakang', type: 'string'},
            {name: 'jenis_kelamin', type: 'string'},
            {name: 'foto', type: 'string'},
            {name: 'tanggal_lahir', type: 'string'},
            {name: 'userid', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'last_sync', type: 'string'},
            {name: 'deleted', type: 'string'},
    ]
});