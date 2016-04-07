Ext.define('Esmk.view.TMessage.MyMessageCompose', {
    extend: 'Ext.panel.Panel',
    initComponent: function(){
        var me = this;

        Ext.apply(this, {
            bodyCls: 'infowindow-base',
            border: 0,
            layout:'fit',
            tbar: [
            {
                xtype: 'button',
                text: 'Kirim Pesan',
                iconCls: 'icon-drive-arrow',
                handler: me.sendMessage,
                scope:me
            }],
            items:[me.createForm()]
        });
        
        this.addEvents(
            'completeSend'
            );
        
        this.callParent(arguments);
    },
    
    createForm: function(){
        
        var me = this;
        Ext.define('Users', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'id',
                type: 'string'
            },
            {
                name: 'username',
                type: 'string'
            },
            {
                name: 'email',
                type: 'string'
            },
            {
                name: 'displayname',
                type: 'string'
            }
            ]
        });
        
        var store = Ext.create('Ext.data.Store', {
            model: 'Users',
            pageSize: 5,
            proxy: {
                type: 'ajax',
                api : {
                    read :'user/GetUserList'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                }
            }, 
            autoLoad: true
        });

        var comboUser = Ext.create('Ext.form.field.ComboBox', {
            name: 'message_to',
            fieldLabel: "Kepada",
            store:store,
            mode: 'remote',
            valueField: 'id',
            displayField: 'displayname',
            typeAhead: true,
            forceSelection: true,
            pageSize: 30,
            minChars:2,
            matchFieldWidth: false,

            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Pengguna Tidak Ditemukan',
                width: '71%',
                height:300,
                autoHeight:true,

                getInnerTpl: function() {
                    return '<div style="height:70px;"><img class="img-left framed" style="cursor:pointer;width:50px;" src="assets/icons/avatar_small.png"><h2>{displayname} ( {username} )</h2><table style="margin-top:4px;"><tr><td>{email}</td></tr></table></div>';
                }
            },
            listeners: {
                select: function(combo, records, index) {
                    var value = records[0].get(combo.valueField);
                    me.messageTo = value;
                }
            }
        });
        
        me.composeForm = Ext.create('Ext.form.Panel', {
            plain: true,
            border: 0,
            bodyPadding: 5,
            
            fieldDefaults: {
                labelWidth: 55,
                anchor: '100%'
            },

            layout: {
                type: 'vbox',
                align: 'stretch'  
            },

            items: [comboUser, 
            {
                xtype: 'textfield',
                fieldLabel: 'Judul',
                name: 'message_title'
            },new Ext.form.HtmlEditor({
				  name : 'message_content',
				  //the HTML Element id you want to render the HTMLEditor to
				  //renderTo : 'myContainer',
				  flex: 1 
				  //more options see the documentation
			}),/*{
                xtype: 'textarea',
                fieldLabel: 'Message',
                hideLabel: true,
                name: 'message_content',
                style: 'margin:0',
                flex: 1 
            }*/]
        });
        
        return me.composeForm;
    },
    
    sendMessage: function(){
        var me = this;
        
        var form = me.composeForm.getForm();
        
        if(form.isValid()){
            form.submit({
                url: 'tmessage/sendmessage',
                waitMsg: 'Mengririm pesan...',
                success: function(form, action) {
                    me.removeAll();
                    me.add(
                    {
                        html:'<h2>Pesan anda telah terkirim<h2>',
                        border: 0
                    });
                    me.fireEvent('completeSend', me);
                    me.doLayout();
                },
                failure: function(form, action) {
                    
                }
            });
        }
    }
});