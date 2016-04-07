Ext.define('Esmk.administration.user.UserMainPanel', {
    extend: 'Ext.panel.Panel',
    animCollapse: true,
    data: null,
    initComponent: function(){
        var me = this;

        Ext.apply(this, {
            layout : 'border',
            items:[
            this.createInfoPanel(),
            this.createTabPanel()
            ]
        });

        this.callParent(arguments);
    },
    
    createInfoPanel: function(){
        this.infoPanel = Ext.create('Ext.panel.Panel',
        {
            id: 'user-role-info',
            border:0,
            region: 'north',
            height: 56,
            html:'<div id="user-role-info-panel"><h2 id="user-role-info-title"></h2></div>'
        });
        return this.infoPanel;
    },
    
    createTabPanel: function(){
        
        this.userPanel = Ext.create('Esmk.administration.user.UserMainUserPanel',{
            title: 'Pengguna',
            iconCls:'icon-users'
        });
        
        this.profilePanel = Ext.create('Esmk.administration.user.UserProfilePanel',{
            title: 'Hak Akses',
            iconCls:'icon-key'
        });

        var TabPanel = Ext.create('Ext.tab.Panel',
        {
            id: 'user-role-tab',
            region: 'center',
            border:0,
            items: [
                this.userPanel,
                this.profilePanel
                
            ]
        });
        return TabPanel;
    },

    changeOrganization: function(data){
        this.data = data;
        if(data == null)
            document.getElementById('user-role-info-title').innerHTML = "";
        else
            document.getElementById('user-role-info-title').innerHTML = data.text;
            
        this.userPanel.setRole(data);
        this.profilePanel.setRole(data);
    }


});