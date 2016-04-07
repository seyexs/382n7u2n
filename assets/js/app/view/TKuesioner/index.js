Ext.define('Esmk.view.TKuesioner.index', {
    extend: 'Ext.panel.Panel',
	layout:'accordion',
	requires: [

	],
	title: 'Detail Items',
	//bodyStyle: "padding: 5px;",
	
	items:[Ext.create('Esmk.view.TKuesioner._grid'),
	{
		xtype: 'panel',
        layout: 'fit',
		iconCls: 'icon-grid',
		id:'tkuesionerpertanyaanpanelid',
		collapsible: false,
		border:false,
		collapsed:true,
		disabled:true,
		items:[Ext.create('Esmk.view.TKuesionerPertanyaan._grid')],
		listeners: {
			collapse: function(){
				this.setDisabled(true);
			},
		},
	}]
	
});