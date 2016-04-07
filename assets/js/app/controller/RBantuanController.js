Ext.define('PHPExtJS.controller.RBantuanController', {
    extend: 'Ext.app.Controller',
    stores: [
        'RBantuan',
    ],
    models: ['RBantuan'],
    
    views: ['RBantuan._grid'],
    
    refs: [
        {
        ref: 'rbantuanForm',
            selector: 'panel'
        },
        {
        ref: 'rbantuanGrid',
            selector: 'grid',
        }
    ],
    
    init: function() {

        this.control({
            'rbantuanGrid dataview': {
                itemdblclick: this.actionDbClick
            },
            'rbantuanGrid button[action=delete]': {
                click: this.actionDelete
            },
            'rbantuanForm button[action=save]': {
                click: this.actionSave
            },
            'rbantuanForm button[action=cancel]': {
                click: this.actionCancel
            },
            'rbantuanGrid button[action=search]': {
                click: this.actionSearch
            },
            'rbantuanForm button[action=reset]': {
                click: this.actionReset
            }
        });

    },

    actionDbClick: function(dataview, record, item, index, e, options){
        var formRBantuan = Ext.create('PHPExtJS.view.RBantuan._form');

        if (record) {

            formRBantuan.down('form').loadRecord(record);

        }    
    },

    actionUpdate: function(dataview, record) { //function(grid, record) {
        var formRBantuan = Ext.create('PHPExtJS.view.RBantuan._form');

        if (record) {

            formRBantuan.down('form').loadRecord(record);

        }
    },

    actionCreate: function(button, e, options) {
        this.actionUpdate();
    },

    actionSave: function(button) {

        var win = button.up('window'),
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
                if (values.id !='') {
            record.set(values); //saving line
        } else {
            record = Ext.create('PHPExtJS.model.RBantuan');
            record.set(values);
            this.getRBantuanStore().add(record);
            isNewRecord = true;
        }

        win.close();
        //this.getRBantuanStore().sync(); use this code for autoSync : false

    },
    actionDelete: function(button) {

        var grid = this.getRBantuanGrid();
        var record = grid.getSelectionModel().getSelection();
        var store = this.getRBantuanStore();

        store.remove(record);
        //this.getRBantuanStore().sync();

        this.getRBantuanStore().load();
    },
    actionReset: function(button, e, options) {
        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
    },

    actionCancel: function(button, e, options) {

        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
        win.close();

    },
    actionSearch: function(button) {

        var win = button.up('window'),
        form = win.down('textfield'),
        grid = win.down('grid'),
        values = form.getSubmitValue();

        grid.getStore().load({params: {q: values}});

    },
});