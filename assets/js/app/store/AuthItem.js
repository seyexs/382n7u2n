Ext.define('Esmk.store.AuthItem', {
    extend: 'Ext.data.Store',
	storeId:'AuthItemStoreID',
    model: 'Esmk.model.AuthItem',
    autoLoad: false,
    remoteFilter: true,
    autoSync: true,
	
    proxy: {
        type: 'rest',
        
        api: {
            create: 'Groups/create', 
            read: 'Groups/read',
            update: 'Groups/update',
            destroy: 'Groups/delete',
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