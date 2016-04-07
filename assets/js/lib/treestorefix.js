Ext.override(Ext.data.TreeStore, {
    rejectChanges: function() {
        var me = this;
        // re-add removed records
        Ext.each(me.removed, function(rec) {
            rec.join(me);
            me.data.add(rec);
        });
        me.removed = [];

        // revert dirty records and trash newly added records ('phantoms')
        me.each(function(rec) {
            if (rec.dirty) {
                rec.reject();
            }
            if (rec.phantom) {
                record.unjoin(me); // probably not really necessary
                me.data.remove(rec);
            }
        });

        me.fireEvent('datachanged', me);
    }
});