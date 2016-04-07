Ext.define('Esmk.component.RemoteCombo', {
    extend: 'Ext.form.field.ComboBox',
    
    initComponent: function(){
        var me = this;
        Ext.apply(this, {
            mode: 'remote',
            typeAhead: true,
            forceSelection: true,
            pageSize: 5,
            minChars:0,
            triggerAction: 'query',
            matchFieldWidth: true
        });
        this.callParent(arguments);
    }
});



