Ext.define('Esmk.administration.system.SystemViewer', {
    extend: 'Ext.panel.Panel',
    
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            padding: 5,
            title: 'System Administration'
        });
        
        this.callParent(arguments);
    }
});