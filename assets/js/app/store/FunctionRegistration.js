Ext.define('Esmk.store.FunctionRegistration', {
    extend: 'Ext.data.Store',
	storeId:'FunctionRegistrationStoreID',
    model: 'Esmk.model.FunctionRegistration',
    autoLoad: false,
    remoteFilter: true,
    autoSync: true,
    proxy: {
        type: 'rest',
        
        api: {
            create: 'FunctionRegistration/QuickRegister', 
            read: 'FunctionRegistration/GetUnRegisteredFunction',
            update: 'FunctionRegistration/update',
            destroy: 'FunctionRegistration/delete',
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