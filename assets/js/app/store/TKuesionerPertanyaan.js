Ext.define('Esmk.store.TKuesionerPertanyaan', {
    extend: 'Ext.data.Store',
    model: 'Esmk.model.TKuesionerPertanyaan',
    autoLoad: false,
    remoteFilter: true,
    autoSync: true,
    proxy: {
        type: 'rest',
        
        api: {
            create: 'TKuesionerPertanyaan/create', 
            read: 'TKuesionerPertanyaan/read',
            update: 'TKuesionerPertanyaan/update',
            destroy: 'TKuesionerPertanyaan/delete',
        },
        
        listeners: {
            exception: function(proxy, response, options) {
                Ext.MessageBox.alert('Warning!', response.status + ": " + response.statusText + " " + response.responseText + "!");
            }
        },
        
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
        
        writer: {
            type: 'json',
            writeAllFields: true,
            encode: true,
            root: 'data'
        },
        
        // sends single sort as multi parameter
        simpleSortMode: true,

        // Parameter name to send filtering information in
        //filterParam: 'query',

        // The PHP script just use query=<whatever>
        
        encodeFilters: function(filters) {
            return filters[0].value;
        }
        
        
    }
});