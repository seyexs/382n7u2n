Ext.form.field.ComboBox.override( {
    setValue: function(v) {
        if(!this.store.isLoaded && typeof(v) != "undefined") {
            //v = (v && v.toString) ? v.toString() : v;
            this.store.addListener("load", function() {
                this.store.isLoaded = true;
                if (!this.store.firstLoad) {
                    this.setValue(v);
                    this.store.firstLoad = true;
                }
            }, this);
            this.store.load();
        } else {
            this.callOverridden(arguments);
        }
    }
});