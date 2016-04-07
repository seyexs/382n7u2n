Ext.define('Esmk.view.Dashboard.provinsi.bansos_sekolah',{
    extend: 'Ext.Container',
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
	//autoScroll: true,
	initComponent: function () {
		var me=this;
		

		//alert(items.length);
        this.items = [
			{
				xtype:'panel',
				columnWidth:0.22,
				//layout:'fit',
				flex:true,
				id:'containerDaftarBantuanSosialId',
				height:540,
				autoScroll:true,
				defaults: {
					xtype: 'panel',
					width: 235,
					height: 235,
					bodyPadding: 10,
					style:'background:#ffffff;text-align:center;',
					frame: true
				},
				
				listeners:{
					'afterrender':function(){
						me.actionGetBantuan(me);
					}
				},
				//items:me.DataBantuan
			},{
				xtype:'container',
				columnWidth:0.78,
				flex:true,
				height:540,
				autoScroll:true,
				//style:'margin: 10px 5px 0 0;',
				items:[{
					xtype:'tabpanel',
					id:'tabpaneldetailbantuanid',
					items:[]
				}]
				
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
			url: 'TBantuanPenerima/GetDaftarBantuanBySekolah',
			method:'POST',
			params:{sid:me.sid},
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				json=json.data;
				//for(var j=1;j<=4;j++)
				for(var i=0;i<json.length;i++){
					//alert(i);
					var data=json[i];
					//alert(data.nama);
					var obj={
						
						bodyCls:'grid item-bantuan',
						id:'id_'+data.tbid+'_'+data.id+'_'+data.isBOS+'_'+data.nama,
						listeners:{
							click: {
								element: 'body', //bind to the underlying body property on the panel
								fn: function(){
									var l=this.parent();
									me.actionDetailBantuan(l);
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
					};
					container.push(obj);
				};
				Ext.getCmp('containerDaftarBantuanSosialId').add(container);
				msg.close();
				//return container;
			}
		});
		
	},
	//actionDetailBantuan:function(tbpid,nama,tbid,isBOS){
	actionDetailBantuan:function(obj){
		var id=obj.id;
		var data=id.split('_');
		var tbpid=data[2],
			nama=data[4],
			tbid=data[1],
			isBOS=data[3];
			
		var me=this;
		var tab=Ext.getCmp('tabpaneldetailbantuanid');
		
		var msg=Ext.MessageBox.show({
               msg: 'Mohon Tunggu...',
               progressText: 'Tunggu...',
               width:300,
               wait:true,
               waitConfig: {interval:200},
            });
		tab.removeAll();
		//tab.items.each(function(c){tab.remove(c,true);});
		tab.add({
				title:'Home',
				iconCls:'icon-bank',
				loader: {
					url: 'TBantuanProgram/GetDetailBantuan/?tbid='+tbid,
					renderer: 'html',
					autoLoad: true,
					scripts: true
				},
			});
		if(isBOS){
			/* jika ada tab tambahan khusus*/
		}
		me.actionGetDaftarLaporan(tbpid,msg);
	},
	/*
	Ext.Ajax.request({
			url: base_url + 'TBantuanProgram/GetDetailBantuan',
			method:'POST',
			params:{tbid:tbid},
			success: function(response){
				var pnl=Ext.getCmp('detailbantuantabitemid');
				pnl.update(response.responseText);
			}
		});
	*/
	actionGetDaftarLaporan:function(id,msg){
		var me=this;
		var tab=Ext.getCmp('tabpaneldetailbantuanid');
		Ext.Ajax.request({
			url:'TBantuanPenerima/GetDaftarLaporanBantuan',
			method:'POST',
			params:{tbpid:id},
			success: function(response){
				var json = Ext.JSON.decode(response.responseText);
				json=json.data;
				var tabitems=[];
				
				for(var i=0;i<json.length;i++){
					var data=json[i];
					var strprop=(data.properties!='' && data.properties!=null)?'{tbpid:'+id+',namaBantuan:\''+data.nama+'\','+data.properties+'};':'{tbpid:'+id+',namaBantuan:\''+data.nama+'\'};';
					eval('prop='+strprop);
					if(data.kode_module=='')
						continue;
					
					tabitems.push({
						title:data.nama,
						autoScroll:true,
						//layout:'fit',
						iconCls:'icon-invoice',
						items:[Ext.create(data.kode_module,prop)]
					});
				}
				tab.add(tabitems);
				msg.close();
				
			}
		});
	},
});