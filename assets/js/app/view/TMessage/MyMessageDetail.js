Ext.define('Esmk.view.TMessage.MyMessageDetail', {
    extend: 'Ext.panel.Panel',
    mode : 'send',
    message_id: null,
    
    initComponent: function(){
        var me = this;

        Ext.apply(this, {
            padding: 5,
            bodyCls: 'infowindow-base',
            layout:'border',
            items:[
            this.createInfo(),
            this.createPanel()
            ]
        });
        
        this.callParent(arguments);
    },
    
    createInfo: function(){
        var me = this;
        if (me.mode != 'send'){
            this.infoPanel =  Ext.create('Ext.panel.Panel', {
                border: 0,
                region: 'north',
                height: 60,
                html: '<table><tr><td style="width:100px;">Dari</td><td style="width:20px;"> : </td><td><b>' + me.from + '</b></td></tr><tr><td>Tanggal</td><td> : </td><td><b>'  + Ext.Date.format(me.date, 'd/m/Y H:i:s') +'</b></td></tr></table>'
            });
        }
        else{
            this.infoPanel =  Ext.create('Ext.panel.Panel', {
                border: 0,
                region: 'north',
                height: 60,
                html: '<table><tr><td style="width:100px;">Kepada</td><td style="width:20px;"> : </td><td><b>' + me.from + '</b></td></tr><tr><td>Tanggal</td><td> : </td><td><b>'  + Ext.Date.format(me.date, 'd/m/Y H:i:s') +'</b></td></tr></table>'
            });
        }
        return this.infoPanel;
    },

    createPanel : function(){
        var me = this;
        var params = {message_id: me.message_id}
        if (me.mode != 'send'){
            params = {message_id: me.message_id, message_mode: 'receive'}
        }
        this.innerPanel =  Ext.create('Ext.panel.Panel', {
            border: 1,
            region : 'center',
            autoScroll: true,
            bodyPadding:5,
            loader: {
                url: 'tmessage/messagedetail',
                params: params,
                autoLoad: true
            }
        });
		
        return this.innerPanel;
    }
});