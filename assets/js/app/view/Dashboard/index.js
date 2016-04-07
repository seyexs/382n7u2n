Ext.define('Esmk.view.Dashboard.index', {
    extend: 'Ext.Container',
    xtype: 'framed-panels',
    width: 660,
	requires: ['Ext.layout.container.Column','Ext.layout.container.Anchor'],
    layout:'fit',
	mask: true,
	items:[{
		xtype:'tabpanel',
		id:'maindashboardtabpanelid',
		layout:'fit',
		border:0,
		frame:false,
		items:[]	
	}],
    defaults: {
        bodyPadding: 2,
		style:'background:#ffffff;',
        frame: true
    },
	listeners:{
		'beforerender':function(){
			
		},
		'afterrender':function(){
			Ext.getCmp('maindashboardtabpanelid').add(this.dashboardItems);
			
		},
		'afterlayout':function(){
		}
	},
	autoScroll: true,
    initComponent: function () {
		/*this.msg=Ext.MessageBox.show({
               msg: 'Menyiapkan Dashboard Anda!',
               progressText: 'Loading...',
               width:300,
               wait:true,
               waitConfig: {interval:200},
            });*/
		this.callParent();
	}
});