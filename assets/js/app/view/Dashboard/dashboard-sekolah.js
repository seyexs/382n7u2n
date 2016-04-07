Ext.define('Esmk.view.Dashboard.dashboard-sekolah', {
    extend: 'Ext.Container',
    xtype: 'framed-panels',
    width: 660,
	DataBantuan:null,
	requires: [
		'Ext.layout.container.Column',
		'Ext.layout.container.Anchor',
		'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
	],
    layout:'column',
    defaults: {
        bodyPadding: 10,
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
		

		//alert(items.length);
        this.items = [
			{
				xtype:'container',
				columnWidth:0.70,
				flex:true,
				id:'containerDaftarBantuanId',
				defaults: {
					xtype: 'panel',
					width: 235,
					height: 235,
					bodyPadding: 10,
					style:'background:#ffffff;text-align:center;',
					frame: true
				},
				layout:{
					type:'table',
					columns:3,
					tdAttrs: { style: 'padding: 10px;' }
				},
				listeners:{
					'afterrender':function(){
						me.actionGetBantuan(me);
					}
				},
				items:me.DataBantuan
			},{
				xtype:'container',
				columnWidth:0.40,
				flex:true,
				//title:'Keterangan',
				style:'margin: 10px 5px 0 0;',
				items:[
					//Ext.create('Esmk.view.Dashboard.info_bantuan')
				]
				
			}
			
            
        ];

        this.callParent();
    },
	actionGetBantuan:function(me){
		var container=[];
		var msg=Ext.MessageBox.show({
               msg: 'Mohon Tunggu...',
               progressText: 'Tunggu...',
               width:300,
               wait:true,
               waitConfig: {interval:200},
            });
		Ext.Ajax.request({
			url: 'TBantuanPenerima/GetDaftarBantuanSekolah',
			method:'POST',
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				json=json.data;
				for(var i=0;i<json.length;i++){
					//alert(i);
					var data=json[i];
					//alert(data.nama);
					container.push({
						
						bodyCls:'grid item-bantuan',
						//id:'bantuansaspras2015',
						listeners:{
							click: {
								element: 'body', //bind to the underlying body property on the panel
								fn: function(){ 
									me.actionDetailBantuan(data.id,data.nama,data.tbid);
								}
							},
							single:true
						},
						html:'<div class="box"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">'+
								'<line class="top" x1="0" y1="0" x2="900" y2="0"/>'+
								'<line class="left" x1="0" y1="460" x2="0" y2="-920"/>'+
								'<line class="bottom" x1="300" y1="460" x2="-600" y2="460"/>'+
								'<line class="right" x1="300" y1="0" x2="300" y2="1380"/>'+
							'</svg><h3>'+data.nama+'</h3><div class="positionbottom"><span>'+data.tahun+'</span><span>'+data.jenis+'</span></div></div>'
					});
				};
				Ext.getCmp('containerDaftarBantuanId').add(container);
				msg.close();
				//return container;
			}
		});
		
	},
	actionDetailBantuan:function(id,nama,bantuanId){
		//overwrite animation :p
				Ext.window.Window.override({
					animateTarget: Ext.getDoc(),
					modal:true,
					maximize: function(){
					  this.callParent([true]);
					},
					restore:function(){
					  this.callParent([true]);
					}
				});
		
		
		
		var msg=Ext.MessageBox.show({
               msg: 'Mohon Tunggu...',
               progressText: 'Tunggu...',
               width:300,
               wait:true,
               waitConfig: {interval:200},
            });
		Ext.Ajax.request({
			url: 'TBantuanPenerima/GetDaftarLaporanBantuan',
			method:'POST',
			params:{tbpid:id},
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				json=json.data;
				var tabitems=[];
				/*add tab file browser*/
				tabitems.push({
					title:'Penerimaan & Pengembalian Bantuan',
					iconCls:'icon-uang',
					items:[Ext.create('Esmk.view.TBantuanPenerimaanPengembalian.index',{
						tbpid:id
					})]
				},{
					title:'Dokumen Terkait Bantuan',
					iconCls:'icon-blogs-stack',
					items:[Ext.create('Esmk.view.TBantuanProgram.fileBrowser',{
						bantuanId:bantuanId,
						modeShare:1,
						parentId:0
					})]
				});
				for(var i=0;i<json.length;i++){
					var data=json[i];
					if(data.kode_module!=''){
						var strprop=(data.properties!='' && data.properties!=null)?'{tbpid:'+id+',namaBantuan:\''+data.nama+'\','+data.properties+'};':'{tbpid:'+id+',namaBantuan:\''+data.nama+'\'};';
						eval('prop='+strprop);
						tabitems.push({
							title:data.nama,
							autoScroll:true,
							//layout:'fit',
							iconCls:'icon-invoice',
							items:[Ext.create(data.kode_module,prop)]
						});
					}
				}
				var tab={
					xtype:'tabpanel',
					frame:false,
					border:0,
					layout:'fit',
					autoScroll:true,
					items:tabitems
				}
				
				if(tab.items.length>0){
					var myWindow = Ext.create('Ext.window.Window', {
						title:nama,
						width:1250,
						height:600,
						maximized:true,
						modal:true,
						id:'detailBantuan'+id,
						requires: ['Ext.form.Panel',
							'Ext.form.field.Text',
							'Ext.ux.DataTip',
							'Ext.data.*'
						],
						layout:'fit',
						items:tab,
						buttons: [{ 
							text: 'Close',
							iconCls: 'icon-reset',
							handler:function(){
								myWindow.close();
							}
						}]
					});
					//myWindow.add(tab);
					myWindow.show();
				}
				msg.close();
			}
		});
	}
});

