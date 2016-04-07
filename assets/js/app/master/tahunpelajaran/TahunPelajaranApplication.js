Ext.onReady(function() {
    var tahunPelajaranViewer = Ext.create('Esmk.master.tahunpelajaran.TahunPelajaranViewer');
    Ext.getCmp('docs-icon-user-config').add(tahunPelajaranViewer);
});