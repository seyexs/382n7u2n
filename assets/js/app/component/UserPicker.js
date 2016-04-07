Ext.define('Esmk.component.UserPicker', {
    extend: 'Esmk.component.RemoteCombo',

    defaultValue: '',
    
    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            width: 530,
            labelWidth: 200,
            store: this.createStore(),
            valueField: 'user_id',
            displayField: 'user_realname',
            value: this.defaultValue,
            listClass: 'x-combo-list-small',
            listConfig: {
                loadingText: 'Proses Pencarian...',
                emptyText: 'Pengguna Tidak Ditemukan',
                width: 700,
                height:300,
                autoHeight:true,

                getInnerTpl: function() {
                    return '<div style="height:70px;"><img class="img-left framed" style="cursor:pointer;" src="{user_image}"><h2>{user_realname}<br>( {user_name} )</h2><table style="margin-top:4px;"><tr><td>{user_email}</td></tr></table></div>';
                }
            }
        });
        
        this.callParent(arguments);
    },

    createStore: function (){

        Ext.define('Users', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'user_id',
                type: 'string'
            },
            {
                name: 'user_name',
                type: 'string'
            },
            {
                name: 'user_email',
                type: 'string'
            },
            {
                name: 'user_realname',
                type: 'string'
            },
            {
                name: 'user_image',
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
                    read : BASE_URL + '/pic/userlist'
                },
                reader: {
                    type: 'json',
                    root: 'rows'
                },
                extraParams:{
                    'query' : this.defaultValue
                }
            },
            autoLoad: false
        });
        return store;
    }
});
