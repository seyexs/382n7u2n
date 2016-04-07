Ext.app.SearchField = Ext.extend(Ext.form.TriggerField, {
    initComponent : function(){
        Ext.app.SearchField.superclass.initComponent.call(this);
        this.on('specialkey', function(f, e){
            if(e.getKey() == e.ENTER){
                this.onTrigger2Click();
            }
        }, this);
    },

    validationEvent:false,
    validateOnBlur:false,
    trigger1Cls:'x-form-clear-trigger',
    trigger2Cls:'x-form-search-trigger',
    width:250,
    hasSearch : true,
    paramName : 'filter',

    onTrigger1Click : function(){
        if(this.hasSearch){
            this.setRawValue('');
            var o = {start: 0};
            this.store.proxy.extraParams = this.store.proxy.extraParams || {};
            this.store.proxy.extraParams[this.paramName] = '';
            this.store.load({params:o});
            this.hasSearch = false;
        }
    },

    onTrigger2Click : function(){
        var v = this.getRawValue();
        if(v.length < 1){
            this.onTrigger1Click();
            return;
        }
        var o = {start: 0};
        this.store.proxy.extraParams = this.store.proxy.extraParams || {};
        this.store.proxy.extraParams[this.paramName] = v;
        this.store.load({params:o});
        this.store.loadPage(1);
        this.hasSearch = true;
    }
});