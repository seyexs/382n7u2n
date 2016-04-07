Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', './assets/js/ext.4.2.1/src/ux/');
Ext.define('Ext.ux.form.MultiSelect', {
    extend: 'Ext.form.field.Base',
    alternateClassName: 'Ext.ux.Multiselect',
    alias: ['widget.multiselect', 'widget.multiselectfield'],
    uses: [
        'Ext.view.BoundList',
        'Ext.form.FieldSet',
        'Ext.ux.layout.component.form.MultiSelect',
        'Ext.view.DragZone',
        'Ext.view.DropZone'
    ],

   ddReorder: false,

    appendOnly: false,

    displayField: 'text',

    allowBlank: true,

    minSelections: 0,

    maxSelections: Number.MAX_VALUE,

    blankText: 'This field is required',

    minSelectionsText: 'Minimum {0} item(s) required',
    maxSelectionsText: 'Maximum {0} item(s) allowed',

    delimiter: ',',

    componentLayout: 'multiselectfield',

    fieldBodyCls: Ext.baseCSSPrefix + 'form-multiselect-body',


    // private
    initComponent: function(){
        var me = this;

        me.bindStore(me.store, true);
        if (me.store.autoCreated) {
            me.valueField = me.displayField = 'field1';
            if (!me.store.expanded) {
                me.displayField = 'field2';
            }
        }

        if (!Ext.isDefined(me.valueField)) {
            me.valueField = me.displayField;
        }

        me.callParent();
    },

    bindStore: function(store, initial) {
        var me = this,
            oldStore = me.store,
            boundList = me.boundList;

        if (oldStore && !initial && oldStore !== store && oldStore.autoDestroy) {
            oldStore.destroyStore();
        }

        me.store = store ? Ext.data.StoreManager.lookup(store) : null;
        if (boundList) {
            boundList.bindStore(store || null);
        }
    },


    // private
    onRender: function(ct, position) {
        var me = this,
            panel, boundList, selModel;

        me.callParent(arguments);

        boundList = me.boundList = Ext.create('Ext.view.BoundList', {
            deferInitialRefresh: false,
            multiSelect: true,
            store: me.store,
            displayField: me.displayField,
            border: false,
            disabled: me.disabled
        });

        selModel = boundList.getSelectionModel();
        me.mon(selModel, {
            selectionChange: me.onSelectionChange,
            scope: me
        });

        panel = me.panel = Ext.create('Ext.panel.Panel', {
            title: me.listTitle,
            tbar: me.tbar,
            items: [boundList],
            renderTo: me.bodyEl,
            layout: 'fit'
        });

        // Must set upward link after first render
        panel.ownerCt = me;

        // Set selection to current value
        me.setRawValue(me.rawValue);
    },

    // No content generated via template, it's all added components
    getSubTplMarkup: function() {
        return '';
    },

    // private
    afterRender: function() {
        var me = this;
        me.callParent();

        if (me.ddReorder && !me.dragGroup && !me.dropGroup){
            me.dragGroup = me.dropGroup = 'MultiselectDD-' + Ext.id();
        }

        if (me.draggable || me.dragGroup){
            me.dragZone = Ext.create('Ext.view.DragZone', {
                view: me.boundList,
                ddGroup: me.dragGroup,
                dragText: '{0} Item{1}'
            });
        }
        if (me.droppable || me.dropGroup){
            me.dropZone = Ext.create('Ext.view.DropZone', {
                view: me.boundList,
                ddGroup: me.dropGroup,
                handleNodeDrop: function(data, dropRecord, position) {
                    var view = this.view,
                        store = view.getStore(),
                        records = data.records,
                        index;

                    // remove the Models from the source Store
                    data.view.store.remove(records);

                    index = store.indexOf(dropRecord);
                    if (position === 'after') {
                        index++;
                    }
                    store.insert(index, records);
                    view.getSelectionModel().select(records);
                }
            });
        }
    },

    onSelectionChange: function() {
        this.checkChange();
    },

    /**
     * Clears any values currently selected.
     */
    clearValue: function() {
        this.setValue([]);
    },

    /**
     * Return the value(s) to be submitted for this field. The returned value depends on the {@link #delimiter}
     * config: If it is set to a String value (like the default ',') then this will return the selected values
     * joined by the delimiter. If it is set to <tt>null</tt> then the values will be returned as an Array.
     */
    getSubmitValue: function() {
        var me = this,
            delimiter = me.delimiter,
            val = me.getValue();
        return Ext.isString(delimiter) ? val.join(delimiter) : val;
    },

    // inherit docs
    getRawValue: function() {
        var me = this,
            boundList = me.boundList;
        if (boundList) {
            me.rawValue = Ext.Array.map(boundList.getSelectionModel().getSelection(), function(model) {
                return model.get(me.valueField);
            });
        }
        return me.rawValue;
    },

    // inherit docs
    setRawValue: function(value) {
        var me = this,
            boundList = me.boundList,
            models;

        value = Ext.Array.from(value);
        me.rawValue = value;

        if (boundList) {
            models = [];
            Ext.Array.forEach(value, function(val) {
                var undef,
                    model = me.store.findRecord(me.valueField, val, undef, undef, true, true);
                if (model) {
                    models.push(model);
                }
            });
            boundList.getSelectionModel().select(models, false, true);
        }

        return value;
    },

    // no conversion
    valueToRaw: function(value) {
        return value;
    },

    // compare array values
    isEqual: function(v1, v2) {
        var fromArray = Ext.Array.from,
            i, len;

        v1 = fromArray(v1);
        v2 = fromArray(v2);
        len = v1.length;

        if (len !== v2.length) {
            return false;
        }

        for(i = 0; i < len; i++) {
            if (v2[i] !== v1[i]) {
                return false;
            }
        }

        return true;
    },

    getErrors : function(value) {
        var me = this,
            format = Ext.String.format,
            errors = me.callParent(arguments),
            numSelected;

        value = Ext.Array.from(value || me.getValue());
        numSelected = value.length;

        if (!me.allowBlank && numSelected < 1) {
            errors.push(me.blankText);
        }
        if (numSelected < this.minSelections) {
            errors.push(format(me.minSelectionsText, me.minSelections));
        }
        if (numSelected > this.maxSelections) {
            errors.push(format(me.maxSelectionsText, me.maxSelections));
        }

        return errors;
    },

    onDisable: function() {
        var me = this;
        
        me.callParent();
        me.updateReadOnly();
        if (me.boundList) {
            me.boundList.disable();
        }
    },

    onEnable: function() {
        var me = this;
        
        me.callParent();
        me.updateReadOnly();
        if (me.boundList) {
            me.boundList.enable();
        }
    },

    setReadOnly: function(readOnly) {
        this.readOnly = readOnly;
        this.updateReadOnly();
    },

    /**
     * @private Lock or unlock the BoundList's selection model to match the current disabled/readonly state
     */
    updateReadOnly: function() {
        var me = this,
            boundList = me.boundList,
            readOnly = me.readOnly || me.disabled;
        if (boundList) {
            boundList.getSelectionModel().setLocked(readOnly);
        }
    },

    onDestroy: function(){
        Ext.destroyMembers(this, 'panel', 'boundList', 'dragZone', 'dropZone');
        this.callParent();
    }
});



