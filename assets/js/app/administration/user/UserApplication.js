Ext.onReady(function() {
    var userViewer = Ext.create('Esmk.administration.user.UserViewer');
    Ext.getCmp('docs-icon-user-config').add(userViewer);
});