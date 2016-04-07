Ext.define('Esmk.view.TKuesionerJawaban.form_pengisian', {
    extend:'Ext.form.Panel',
	kid:0,
	layout:'fit',
	initComponent: function() {
        var me = this;
        Ext.applyIf(me, {
			this.items:[Ext.create('Esmk.view.TKuesionerJawaban._form_pengisian',{kid:})]
		});
		me.callParent(arguments);
	}
	
});