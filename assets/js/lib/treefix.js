Ext.override(Ext.data.TreeStore, {
    load: function(options) {
        options = options || {};
        options.params = options.params || {};


        var me = this,
        node = options.node || me.tree.getRootNode(),root;

        if (!node) {
            node = me.setRootNode({
                expanded: true
            });
        }

        if (me.clearOnLoad) {
            node.removeAll(false);
        }

        Ext.applyIf(options, {
            node: node
        });
        options.params[me.nodeParam] = node ? node.getId() : 'root';

        if (node) {
            node.set('loading', true);
        }

        return me.callParent([options]);
    }
});
