Ext.define('Esmk.model.User', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'username', type: 'string'},
            {name: 'displayname', type: 'string'},
            {name: 'password', type: 'string'},
            {name: 'email', type: 'string'},
            {name: 'avatar_file', type: 'string'},
            {name: 'state_online', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
			{name: 'kode_kepemilikan', type: 'string'},
			{name: 'pemilik_id', type: 'string'},
    ]
});