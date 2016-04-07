Ext.define('Esmk.view.TKuesionerPertanyaan.index', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: '',
    layout: 'fit',
    autoShow: true,
    width: 1200,
	minHeight:300,
    autoHeight:true,
	autoWidth:true,
    iconCls: 'icon-new-data',
	initComponent: function() {
		var me=this;
        this.items = [Ext.create('Esmk.view.TKuesionerPertanyaan._grid',{kid:me.kid})];
		this.callParent(arguments);
	}
});