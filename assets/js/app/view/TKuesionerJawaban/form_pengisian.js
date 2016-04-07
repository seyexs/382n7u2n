Ext.define('Esmk.view.TKuesionerJawaban.form_pengisian', {
    extend:'Ext.form.Panel',
	kid:0,
	modeView:false,
	id:'formpengisianpanelid',
	layout:'fit',
	initComponent: function() {
        var me = this;
        this.items=[Ext.create('Esmk.view.TKuesionerJawaban._form_pengisian',{kid:me.kid,modeView:me.modeView})];
		this.callParent(arguments);
	}
	
});