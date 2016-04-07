Ext.define('Esmk.controller.MTahunPelajaranController', {
    extend: 'Ext.app.Controller',
    stores: [
        'MTahunPelajaran',
    ],
    models: ['Esmk.model.MTahunPelajaran'],
    
    views: ['Esmk.view.MTahunPelajaran._grid'],
    
    refs: [
        {
        ref: 'mtahunpelajaranForm',
            selector: 'panel'
        },
        {
        ref: 'mtahunpelajaranGrid',
            selector: 'grid',
        }
    ],
    
    init: function() {

        this.control({
            'mtahunpelajaranGrid dataview': {
                itemdblclick: this.actionDbClick
            },
            'mtahunpelajaranGrid button[action=delete]': {
                click: this.actionDelete
            },
            'mtahunpelajaranForm button[action=save]': {
                click: this.actionSave
            },
            'mtahunpelajaranForm button[action=cancel]': {
                click: this.actionCancel
            },
            'mtahunpelajaranGrid button[action=search]': {
                click: this.actionSearch
            },
            'mtahunpelajaranForm button[action=reset]': {
                click: this.actionReset
            }
        });

    },

    actionDbClick: function(dataview, record, item, index, e, options){
        var formMTahunPelajaran = Ext.create('Esmk.view.MTahunPelajaran._form');

        if (record) {

            formMTahunPelajaran.down('form').loadRecord(record);

        }    
    },

    actionUpdate: function(dataview, record) { //function(grid, record) {
        var formMTahunPelajaran = Ext.create('Esmk.view.MTahunPelajaran._form');

        if (record) {

            formMTahunPelajaran.down('form').loadRecord(record);

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
            record = Ext.create('Esmk.model.MTahunPelajaran');
            record.set(values);
            this.getMTahunPelajaranStore().add(record);
            isNewRecord = true;
			this.getMTahunPelajaranStore().reload();
        }
		
        win.close();
        //this.getMTahunPelajaranStore().sync(); use this code for autoSync : false

    },
    actionDelete: function(button) {

        var grid = this.getMTahunPelajaranGrid();
        var record = grid.getSelectionModel().getSelection();
        var store = this.getMTahunPelajaranStore();

        store.remove(record);
        //this.getMTahunPelajaranStore().sync();

        this.getMTahunPelajaranStore().load();
    },
    actionReset: function(button, e, options) {
        var win = button.up('window'),
        form = win.down('form');
        form.getForm().reset();
    },

    actionCancel: function(button, e, options) {
		alert(1);
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