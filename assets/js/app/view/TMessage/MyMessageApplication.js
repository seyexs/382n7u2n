Ext.onReady(function() {
    var appViewer = Ext.create('Esmk.view.TMessage.MyMessageViewer');
    Ext.getCmp('docs-icon-mail').add(appViewer);
});