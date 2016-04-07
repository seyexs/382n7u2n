Ext.define('Esmk.store.Sekolah', {
    extend: 'Ext.data.Store',
    model: 'Esmk.model.Sekolah',
    autoLoad: false,
    remoteFilter: true,
    autoSync: true,
    proxy: {
        type: 'rest',
        
        api: {
            create: 'Sekolah/create', 
            read: 'Sekolah/read',
            update: 'Sekolah/update',
            destroy: 'Sekolah/delete',
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