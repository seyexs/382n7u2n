Ext.onReady(function() {
    var systemViewer = Ext.create('Esmk.administration.system.SystemViewer');
    Ext.getCmp('docs-icon-system-config').add(systemViewer);
});