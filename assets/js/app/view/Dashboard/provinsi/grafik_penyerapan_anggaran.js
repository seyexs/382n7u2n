Ext.define('Esmk.view.Dashboard.provinsi.grafik_penyerapan_anggaran',{
	extend:'Ext.Panel',
	id:'grafikpenyerapananggaranid',
	layout:'fit',
	width: 600,
    height: 600,
	requires: [
		'Ext.chart.*',
		'Ext.layout.container.Fit', 
		'Ext.fx.target.Sprite'
	],
	initComponent: function () {
		var me=this;
		Ext.applyIf(me, {
			items:[{
				xtype:'panel',
				layout:'fit',
				id:'grafikpenyerapananggaranpanelid',
				height:500,
				width:500,
				tbar: [{
					text: 'Save Chart',
					handler: function() {
						Ext.MessageBox.confirm('KOnfirmasi', 'Grafik akan disimpan dalam bentuk gambar,lanjutkan?', function(choice){
							if(choice == 'yes' && me.chart){
								me.chart.save({
									type: 'image/png'
								});
							}
						});
					}
				},'|',{
					xtype:'combobox',
					flex:true,
					//fieldLabel:'Program Bantuan',
					//name: 't_program_bantuan_id',
					allowBlank:false,
					//width:500,
					store:Ext.create('Esmk.store.TBantuanProgram'),
					mode: 'remote',
					valueField: 'id',
					displayField: 'nama',
					typeAhead: true,
					forceSelection: true,
					pageSize: 30,
					minChars:2,
					matchFieldWidth: false,
					editable: true,
					emptyText:'Nama Program Bantuan',
						listConfig: {
							loadingText: 'Proses Pencarian...',
							emptyText: 'Data Tidak Ditemukan',
							//width: '71%',
							//height:300,
							autoHeight:true,
							getInnerTpl: function() {
								return '<span style="margin-top:2px;margin-left:2px;">{nama}</br>Tahun: {tahun}</span>';
							}
						},
					listeners:{
						select: function(combo, records, index) {
							var v = records[0].get('id');
							me.buildGrafik(v);
								//me.actionBuildGridBOS(v);
								//me.actionBuildPaktaIntegritas(v);
								
						},
						'afterrender':function(){
							if(me.rbid)
								this.getStore().getProxy().api.read='TBantuanProgram/GetBantuanBOS';
						}
					}				
				}]
			}]
		});
		me.callParent(arguments);
	},
	buildGrafik:function(tbid){
		var me=this;
		var store=me.getGrafikStore();
		var chart=Ext.create('Ext.chart.Chart',{
					style: 'background:#fff',
					animate: true,
					shadow: true,
					store: store,
					axes: [{
						type: 'Numeric',
						position: 'left',
						fields: ['values'],
						label: {
							renderer: Ext.util.Format.numberRenderer('0,0')
						},
						title: 'DANA',
						grid: true,
						minimum: 0
					}, {
						type: 'Category',
						position: 'bottom',
						fields: ['name'],
						title: 'JENIS PERUNTUKAN BOS'
					}],
					series: [{
						type: 'column',
						axis: 'left',
						highlight: true,
						tips: {
						  trackMouse: true,
						  width: 250,
						  height: 28,
						  renderer: function(storeItem, item) {
							this.setTitle(storeItem.get('name')+':'+storeItem.get('values'));
						  }
						},
						label: {
							display: 'insideEnd',
							'text-anchor': 'middle',
							field: 'values',
							renderer: Ext.util.Format.numberRenderer('0'),
							orientation: 'vertical',
							color: '#333'
						},
						xField: 'name',
						yField: 'values'
					}]
		});
		this.chart=chart;
		//Ext.getCmp('grafikpenyerapananggaranpanelid').removeAll();
		//Ext.getCmp('grafikpenyerapananggaranpanelid').add(chart);
		me.showGraph();
	},
	getGrafikStore:function(){
		Ext.define('GrafikPenyerapanAnggaranModel', {
			extend: 'Ext.data.Model',
			fields:[{name: 'name', type: 'string'},{name: 'values', type: 'string'}]
		});
		var result={
				total:4,
				data:[{
					name:'Perbaikan Ringan',
					values:'5000000'
				},{
					name:'Pembelian Buku1',
					values:'10000000'
				},{
					name:'Pembelian Buku2',
					values:'20000000'
				},{
					name:'Pembelian Buku3',
					values:'30000000'
				}]
		}
		var store=Ext.create('Ext.data.Store', {
				autoLoad: false,
				data : result,
				totalProperty: result.total,
				model:'GrafikPenyerapanAnggaranModel',
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
		return store;
		
	},
	showGraph:function(){
		var me=this;
		var win = Ext.create('Ext.window.Window', {
			width: 800,
			height: 600,
			minHeight: 400,
			minWidth: 550,
			hidden: false,
			maximizable: true,
			title: 'Column Chart',
			autoShow: true,
			layout: 'fit',
			tbar: [{
				text: 'Save Chart',
				handler: function() {
					Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
						if(choice == 'yes'){
							chart.save({
								type: 'image/png'
							});
						}
					});
				}
			}, {
				text: 'Reload Data',
				handler: function() {
					// Add a short delay to prevent fast sequential clicks
					window.loadTask.delay(100, function() {
						store1.loadData(generateData());
					});
				}
			}],
			items: me.chart    
		});
	}
});