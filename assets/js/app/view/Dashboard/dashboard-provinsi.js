Ext.define('Esmk.view.Dashboard.dashboard-provinsi', {
    extend: 'Ext.Container',
    xtype: 'framed-panels',
    width: 660,
	requires: ['Ext.layout.container.Column','Ext.layout.container.Anchor'],
    layout:'column',
    defaults: {
        //bodyPadding: 10,
		style:'background:#ffffff;',
        frame: true
    },
	listeners:{
		'afterrender':function(){
			
		}
	},
	autoScroll: true,
    initComponent: function () {
		var me=this;
        this.items = [
		
			{
				xtype:'container',
				columnWidth:1,
				flex:true,
				layout:'fit',
				items:[Ext.create('Esmk.view.Dashboard.provinsi._grid',{
					maxHeight:500
				})]
			}
			
            
        ];

        this.callParent();
    },
	actionDetailBantuan:function(){
		//overwrite animation :p
				Ext.window.Window.override({
					animateTarget: Ext.getDoc(),
					maximize: function(){
					  this.callParent([true]);
					},
					restore:function(){
					  this.callParent([true]);
					}
				});
		var myWindow = Ext.create('Ext.window.Window', {
			title:nama,
			width:1250,
			height:600,
			maximized:true,
			modal:true,
			layout:'fit',
			items:[{
				xtype:'tabpanel',
				id:'tabpaneldetailsekolah',
				layout:'fit',
				items:[{
					title:'Bantuan Sosial',
					iconCls:'icon-uang',
					items:[Ext.create('Esmk.view.Dashboard.provinsi.bansos_sekolah')]
				}]
			}],
			buttons: [{ 
				text: 'Close',
				iconCls: 'icon-reset',
				handler:function(){
					myWindow.close();
				}
			}]
		});

		
		myWindow.show();
	}
});

