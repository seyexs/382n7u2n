Ext.define('Esmk.administration.user.UserViewer', {
    extend: 'Ext.panel.Panel',
    
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            padding: 5,
            layout: 'border',
            border:0,
            items: [
                this.createUserRolePanel(),
                this.createUserMainPanel()
            ]
        });
        
        this.callParent(arguments);
    },

    createUserRolePanel: function(){
        this.userRolePanel = Ext.create('Esmk.administration.user.UserRolePanel', {
            region: 'west',
            width: 290,
            collapsible: true,
            title: 'Kelompok Pengguna',
            split:true,
            listeners: {
                scope: this,
                organizationChange: this.onOrganizationChange
            }
        });
        return this.userRolePanel;
    },

    createUserMainPanel: function(){
        this.userMainPanel = Ext.create('Esmk.administration.user.UserMainPanel', {
            region: 'center'
        });
        return this.userMainPanel;
    },

    onOrganizationChange : function(sender, val){
        this.userMainPanel.changeOrganization(val);
    }
});