Ext.define('Esmk.model.AuthItem', {
extend: 'Ext.data.Model',
fields: [
        {name: 'name', type: 'string'},
            {name: 'type', type: 'string'},
            {name: 'description', type: 'string'},
			{name: 'bizrule', type: 'string'},
			{name: 'data', type: 'string'},
    ]
});