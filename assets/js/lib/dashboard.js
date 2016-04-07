Ext.define('Ext.app.Dashboard', {
    extend: 'Ext.container.Viewport',

    initComponent: function(){
		var me=this;
        Ext.apply(this, {
            id: 'app-viewport',
            layout: {
                type: 'border',
                padding: '0 0 0 0'
            },
            items: [
            HeaderPanel,
            {
                xtype: 'container',
                region: 'center',
                layout: 'border',
                border:0,
                items: [SidebarPanel,{
                    //ui:'main-panel',
                    xtype: 'tabpanel',
                    id: 'app-content',
                    layoutOnTabChange: true,
                    layout: {
                        align: 'stretch'
                    },
                    defaults: {
                        layout: 'fit'
                    },
                    
                    region: 'center', 
                    border:0,
					plain:true,
                    items: [
                    this.createHomePanel()
                    ]
                }]
            }]
        });
        this.callParent(arguments);
    },
    
    createHomePanel: function(){
		var me=this;
        var panel =  Ext.create('Esmk.home.HomeViewer',
        {
            id:'welcome-panel',
            bodyCls:'welcome-panel',
            autoScroll: true,
            iconCls:'icon-home',
            closable: false,
			forumInsert:me.forumInsert
        });
        return panel;
    }
});
