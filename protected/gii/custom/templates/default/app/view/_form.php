<?php

?>
Ext.define('Esmk.view.<?php echo $this->modelClass; ?>._form', {
    //extend: 'Ext.form.Panel', //use this code for panel form
    extend: 'Ext.window.Window',
    alias: 'widget.<?php echo strtolower($this->modelClass); ?>Form',
    requires: ['Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.ux.DataTip',
        'Ext.data.*'
    ],
    title: 'Siban - Form <?php echo $this->modelClass; ?>',
    layout: 'fit',
    autoShow: true,
    width: 600,
    autoHeight:true,
    iconCls: 'bogus',
    initComponent: function() {
		var me=this;
        this.items = [
            {
                xtype: 'form',
                bodyPadding: '10 10 0 10',
                border: false,
                style: 'background-color: #fff;',
                autoScroll: true,
                fieldDefaults: {
                    anchor: '100%',
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: true,
                    msgTarget: 'side',
                    labelWidth: 200,
                },
                items: [
                    {
                        xtype: 'fieldset',
                        title: '<b>Form Isian</b>',
                        collapsible: false,
                        layout: 'anchor',
                        items: [
                            <?php foreach($this->tableSchema->columns as $column){ if($column->name=='created_date'||$column->name=='created_by'||$column->name=='modified_date'||$column->name=='modified_by'||$column->name=='deleted') continue;echo " \n"; ?>
                            {
                                xtype: 'textfield',
                                fieldLabel: '<?php echo strtoupper($column->name); ?>',
                                name: '<?php echo $column->name;  ?>',
                                <?php if($column->isPrimaryKey){ echo " \n"; ?>
                                hidden:true,
                                <?php } ?>                                
                            },
                            <?php } ?>
                         
                        ],
                    }],
            }];

        this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'bottom',
                id: 'buttons',
                ui: 'footer',
                items: ['->', {
                        iconCls: 'icon-save',
                        text: 'Simpan',
                        action: 'save',
						handler:function(){
							me.actionSave(this);
						}
                    }, {
                        iconCls: 'icon-reset',
                        text: 'Tutup',
                        action: 'cancel',
						handler:function(button, e, options){
							me.actionCancel(button, e, options);
						}
                    },]
            }];

        this.callParent(arguments);


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
	actionSave: function(button) {

        var win = button.up('window'),
        form = win.down('form'),
        record = form.getRecord(),
        values = form.getValues(false, false, false, true);

        var isNewRecord = false;
        
        if (values.id !='') {
            record.set(values); //saving line
			Ext.getCmp('<?=strtolower($this->modelClass)?>gridid').getStore().load();
        } else {
            record = Ext.create('Esmk.model.<?=$this->modelClass?>');
            record.set(values);
            Ext.getCmp('<?=strtolower($this->modelClass)?>gridid').getStore().add(record);
            isNewRecord = true;
			Ext.getCmp('<?=strtolower($this->modelClass)?>gridid').getStore().load();
        }
		
        win.close();
    },
});


