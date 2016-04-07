Ext.define('Esmk.model.TUserAccessData', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'userid', type: 'string'},
            {name: 'level', type: 'string'},
            {name: 'wilayah', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name:'user_displayname',type:'string'}
    ]
});