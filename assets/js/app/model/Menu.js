Ext.define('Esmk.model.Menu', {
extend: 'Ext.data.Model',
fields: [
        {name: 'id', type: 'string'},
            {name: 'sort', type: 'string'},
            {name: 'parent_id', type: 'string'},
            {name: 'title', type: 'string'},
            {name: 'url', type: 'string'},
            {name: 'bizrule', type: 'string'},
            {name: 'cssclass', type: 'string'},
            {name: 'last_sync', type: 'string'},
            {name: 'created_date', type: 'string'},
            {name: 'created_by', type: 'string'},
            {name: 'modified_date', type: 'string'},
            {name: 'modified_by', type: 'string'},
            {name: 'deleted', type: 'string'},
			{name: 'petunjuk_penggunaan', type: 'string'},
    ]
});