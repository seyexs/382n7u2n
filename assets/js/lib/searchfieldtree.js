Ext.app.SearchFieldTree = Ext.extend(Ext.form.TriggerField, {
    initComponent : function(){
        Ext.app.SearchFieldTree.superclass.initComponent.call(this);
        this.on('specialkey', function(f, e){
            if(e.getKey() == e.ENTER){
                this.onTrigger2Click();
            }
        }, this);
        var me = this;

        this.tree.store.on('load', function(){
            var removeArray = [];
            var i=0;
            me.tree.getRootNode().cascadeBy(function() {
                if(!(this.data.text.toLowerCase().indexOf(me.filter)>-1)) {
                    removeArray[i] = this;
                    i++;
                }
            })
            for(var j=0;j<i;j++) {
                if (removeArray[j].data.leaf == true)
                    removeArray[j].remove();
            }
        }, this);
    },

    validationEvent:false,
    validateOnBlur:false,
    trigger1Cls:'x-form-clear-trigger',
    trigger2Cls:'x-form-search-trigger',
    width:250,
    hasSearch : false,
    paramName : 'filter',
    filter: '',

    onTrigger1Click : function(){

        var me = this;
        if(this.hasSearch){
            this.setRawValue('');
            this.filter = '';
            this.store.load();
            this.hasSearch = false;
        }
    },

    onTrigger2Click : function(){
        
        var v = this.getRawValue().toLowerCase();
        if(v.length < 1){
            this.onTrigger1Click();
            return;
        }
        this.filter = v;
        
        this.store.load();

        this.hasSearch = true;
    }
});