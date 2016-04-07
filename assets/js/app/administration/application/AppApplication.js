Ext.onReady(function() {
    var appViewer = Ext.create('Esmk.administration.application.AppViewer');
    Ext.getCmp('docs-icon-app-config').add(appViewer);
});