Ext.define('Esmk.model.TBantuanDoc', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 't_bantuan_program_id', type: 'string'},
            {name: 'parent_id', type: 'string'},
			{name: 'path_file', type: 'string'},
			{name: 'filename', type: 'string'},
            {name: 'file_type', type: 'string'},
			{name: 'is_dir', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
            {name: 'share_file', type: 'string'},
    ]
});