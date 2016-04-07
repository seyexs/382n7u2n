Ext.define('Esmk.view.TBantuanPenerimaSiswa.index', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.tbantuanpenerimasiswaForm',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Data Siswa Penerima Bantuan',
    layout: 'fit',
	tbpid:null,
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'bogus',
	initComponent: function() {
		var me=this;
		this.items=[Ext.create('Esmk.view.TBantuanPenerimaSiswa._grid',{
			tbpid:me.tbpid,
			title:null,
			iconCls:null
		})];
		this.callParent(arguments);
	},
});