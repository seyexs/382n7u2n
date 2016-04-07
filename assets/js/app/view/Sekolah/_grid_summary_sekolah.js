Ext.define('Esmk.view.Sekolah._grid_summary_sekolah', {
    extend: 'Ext.panel.Panel',
    //alias: 'widget.mpegawaiGrid',
	id:'gridsummarysekolahgridid',
	namasekolah:null,
	kodewilayah:null,
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    layout:'fit',
	autoScroll:true,
    initComponent: function() {
        var me = this;
		//this.store=Ext.create('Esmk.store.MPegawai');
		Ext.applyIf(me, {
			viewConfig: {
				emptyText: '<h3><b>Data Kosong</b></h3>'
			},
			listeners: {
				viewready: function() {
					
					//this.store.load();
				},
				itemdblclick: function(dataview, index, item, e) {
					//me.actionDbClick(dataview, index, item, e);
				}
			},
							
		});
		this.listeners={
			'afterrender':function(){
				//if(me.queryId)
				 me.actionBuildView(me);
			}
        }

        me.callParent(arguments);
    },
	actionBuildView:function(me){
		var msg=Ext.MessageBox.show({
               msg: 'Melakukan Query Data....',
               progressText: 'Tunggu...',
               width:300,
               wait:true,
               waitConfig: {interval:200},
            });
		//msg.show();
		//var me=Ext.getCmp('tbantuanpersyaratanpenerimainqueryprocessgridid');
		Ext.Ajax.request({
			url: 'Sekolah/GetDataSummarySekolah',
			waitMessage:'Please Wait..',
			params:{q:me.namasekolah,k:me.kodewilayah},
			success: function(response){
				var storeMstWilayah=Ext.create('Esmk.store.MstWilayah');
				//storeMstWilayah.getProxy().api.read='MstWilayah/read/?='+values;
				var json = Ext.JSON.decode(response.responseText);
				if(!json.modelColumn){
					msg.close();
					Ext.MessageBox.show({
						msg: 'Terjadi Kesalahan pada Script Query',
						closeAble:true,
					});
					return;
				}
				var result = json;
				var modelColumn=result.modelColumn;
				var paket=[{
						xtype: 'rownumberer',
						width: 50,
						sortable: false,
						flex: false,
					}/*,{
						xtype: 'actioncolumn',width: 55,
						header:'Paket',
						sortable: false,
						flex: false,
						renderer:function(){
							return '<button style="width:50px" type="button" onclick="this.innerHTML=(parseInt(this.innerHTML)<5)?(parseInt(this.innerHTML)+1):1;">1</button>';
						}
					}*/];
				var gridColumn=paket.concat(result.gridColumn);
				Ext.define('QueryDataSummaerySekolah', {
					extend: 'Ext.data.Model',
					fields:modelColumn
				});
				//var model=Ext.create('Ext.data.Model',{
					//fields:modelColumn
				//});
						
					var store = Ext.create('Ext.data.Store', {
						autoLoad: false,
						data : result,
						totalProperty: result.total,
						model:'QueryDataSummaerySekolah',
						pageSize:500,
						//url:'TBantuanPersyaratanPenerimaInquery/execute',
						proxy: {
							type: 'memory',
							reader: {
								type: 'json',
								root: 'data'
							}
						}
					});
					var grid={
						xtype:'grid',
						loadMask: true,
						selType : 'checkboxmodel',
						selModel : 
						{
							mode : 'MULTI'
						},
						/*initComponent: function() {
							var me=this;
							Ext.applyIf(me, {
								
							});
							me.callParent(arguments);
						},*/
						features: [/*{
							ftype:'groupingsummary',
							//tpl: Ext.create('Ext.XTemplate','{kode} ({children.length})'),
							//groupHeaderTpl:'{kode}{id}{status}',
							//enableNoGroups:true,
							//hideGroupedHeader: false
						},{
							ftype: 'summary',
							dock: 'bottom',
							//remoteRoot: 'summaryData'
						}*/],
						columns:gridColumn,
						/*viewConfig: {
							listeners: {
								refresh: function(view) {      
									// get all grid view nodes
									var nodes = view.getNodes();
									for (var i = 0; i < nodes.length; i++) {
										var node = nodes[i];
										// get node record
										var record = view.getRecord(node);
										// get color from record data
										var color =((record.get('status_data')).indexOf('Ok')>=0)?'green':'';
										// get all td elements
										if(color!=''){
											var cells = Ext.get(node).query('td');  
											// set bacground color to all row td elements
											//Ext.fly(cells[2]).setStyle('background-color', color);
											for(var j = 2; j < cells.length; j++) {
												//console.log(cells[j]);
												Ext.fly(cells[j]).setStyle('background-color', color);
												Ext.fly(cells[j]).setStyle('color','white');
											}
										}										
									}
								}      
							}
						},*/
						store:store,
						autoScroll:true,
						maxHeight:540
					};
					//grid.store=store;
					//grid.columns=gridColumn;
					grid.dockedItems=[
						{
							xtype: 'toolbar',
							dock: 'top',
							items: [
								'Pencarian', 
								{
									xtype: 'textfield',
									emptyText:'Nama Sekolah',
									value:me.namasekolah,
									name: 'searchfield',
											listeners:{
												keyup: {
													element: 'el',
													fn: function(event, target){ 
															if(event.keyCode=='13'){
																me.actionSearch(me.down('textfield'));
															}
													}
												}
											}
								},{
									xtype:'combobox',
									//fieldLabel:'Kab/Kota',
									name: 'kode_wilayah',
									id:'combo_kode_wilayah',
									allowBlank:true,
									width:300,
									store:storeMstWilayah,
									mode: 'remote',
									valueField: 'kode_wilayah',
									displayField: 'nama',
									typeAhead: true,
									forceSelection: true,
									pageSize: 30,
									minChars:2,
									matchFieldWidth: false,
									editable: true,
									emptyText:'Kab/Kota',
									//values:me.kodewilayah,
									listConfig: {
										loadingText: 'Proses Pencarian...',
										emptyText: 'Data Tidak Ditemukan',
										//width: '71%',
										//height:300,
										autoHeight:true,
										getInnerTpl: function() {
											return '<span style="margin-top:2px;margin-left:2px;">{nama}</span>';
										}
									},
									listeners:{
										select: function(combo, records, index) {
											var v = records[0].get('kode_wilayah');
											me.kodewilayah=v;
										},
										
									}
									
								},{
									xtype: 'button',
									iconCls: 'icon-search',
									text: 'Cari',
									handler:function(){
											me.actionSearch(this);
									}
								},'-',{
									xtype: 'button',
									iconCls: 'icon-xls',
									text: 'Export Excel',
									hidden:true,
									handler:function(){
										me.actionExportExcel();
									}
								}
							]
						}/*,{
							xtype: 'pagingtoolbar',
							dock: 'bottom',
							displayInfo: true,
							emptyMsg: 'Data Kosong',
							store:store,
							plugins: new Ext.ux.ProgressBarPager()
						}*/];
				me.removeAll();
				me.namasekolah=null;
				me.kodewilayah=null;
				me.add(grid);
				msg.close();
				
			}
							
		});
	},
	actionExportExcel:function(){
		this.downloadFile({
			url:'Sekolah/GetDataSummarySekolah',
			method:'POST',
			params:{xls:1}
		});	
	},
    downloadFile: function(config){
		config = config || {};
		var url = config.url,
			method = config.method || 'POST',// Either GET or POST. Default is POST.
			params = config.params || {};
		
		// Create form panel. It contains a basic form that we need for the file download.
		var form = Ext.create('Ext.form.Panel', {
			standardSubmit: true,
			url: url,
			method: method
		});

		// Call the submit to begin the file download.
		form.submit({
			target: '_blank', // Avoids leaving the page. 
			//params: params
		});

		// Clean-up the form after 100 milliseconds.
		// Once the submit is called, the browser does not care anymore with the form object.
		Ext.defer(function(){
			form.close();
		}, 100);

	},
    actionSearch: function(button) {

        var grid = button.up('grid'),
        form = grid.down('textfield'),
		combo = grid.down('combobox'),
		combovalues=combo.getValue(),
        values = form.getSubmitValue();
		var me=this;
		me.namasekolah=values;
		this.actionBuildView(me);
        //grid.getStore().load({params: {q: values}});
		//grid.getStore().getProxy().api.read='MPegawai/read/?q='+values;
    },
	actionSum:function(records,dataIndex,v){
		
			var i = 0,
				length = records.length,
				total = 0,
				record;

			for (; i < length; ++i) {
				record = records[i];
				if(this.isNumeric(record.get(dataIndex))){
					total += parseInt(record.get(dataIndex));
				}
			}
			return (total==0)?'-':Ext.util.Format.number(total,'0,000');
		
		//return records[1].get(dataIndex);
	},
	actionGroupSum:function(value,summaryData,dataIndex){
		return value;
	},
	isNumeric:function(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
});


