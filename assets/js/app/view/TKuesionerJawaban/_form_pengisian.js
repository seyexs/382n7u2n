Ext.define('Esmk.view.TKuesionerJawaban._form_pengisian', {
    extend:'Ext.grid.Panel',
	kid:0,
	modeView:false,
    alias: 'widget.tkuesionerjawabanformpengisianGrid',
	id:'tkuesionerjawabanformpengisiangridid',
    requires: [
        'Ext.toolbar.Paging',
        'Ext.grid.RowNumberer',
		'Ext.ux.ProgressBarPager',
        'Ext.grid.*',
        'Ext.data.*',
    ],
    //iconCls: 'icon-grid',
    //title: 'Esmk - TKUESIONERPERTANYAAN',
	frameHeader:false,
    loadMask: true,
    selType: 'rowmodel',
    initComponent: function() {
        var me = this;
		this.store=Ext.create('Esmk.store.TKuesionerPertanyaan');
		this.store.getProxy().api.read='TKuesionerPertanyaan/read/?kid='+this.kid;
		this.store.pageSize=50;
        Ext.applyIf(me, {
            columns: [                 
                {
                    dataIndex: 'id',
                    text: 'ID',
                    flex:true,
                     
                    hidden:true,
                     
                    
                },{
                    dataIndex: 'pertanyaan',
                    text: 'Pertanyaan',
                    flex:true,
					renderer: this.formatPertanyaan 
                }
                                
            ],
            viewConfig: {
                emptyText: '<h3><b>No data found</b></h3>'
            },
            listeners: {
                viewready: function() {
                    this.store.load();
                },
				itemdblclick: function(dataview, index, item, e) {
					//me.actionDbClick(dataview, index, item, e);
				}
            },
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [{
                        iconCls: 'icon-save',
                        text: 'Kirim',
                        action: 'save',
						hidden:me.modeView,
						handler:function(){
							me.actionSave(this);
						}
                    }]
                },
                {
                    xtype: 'pagingtoolbar',
                    dock: 'bottom',
                    displayInfo: true,
                    emptyMsg: 'No data to display',
                    store: this.store,
					plugins: new Ext.ux.ProgressBarPager(),
                }
            ]

        });

        me.callParent(arguments);
    },
	formatPertanyaan:function(value, p, record){
		var opt=record.get('options');
		var strOpt='';
		var id=record.get('id');
		var dot='<div class="inputtext"><textarea name="tkuesionerjawaban[k'+id+'][catatan_jawaban]"></textarea></div>';
		
		if(opt && record.get('jenis_jawaban')=='1'){
			Ext.each(opt,function(o){
				strOpt+='<tr><td class="offered_answer" style="'+record.get('style')+'"><input type="radio" name="tkuesionerjawaban[k'+id+'][t_kuesioner_pilihan_jawaban_id]" class="x-form-field x-form-radio x-form-cb"/><span>'+o.pilihan_jawaban+'</span></td></tr>';
			});
			strOpt+='<tr><td style="'+record.get('style')+'padding-top:10px;">'+dot+'</td></tr>';
		}else if(record.get('jenis_jawaban')=='2'){
			strOpt+='<tr><td style="'+record.get('style')+'padding-top:10px;">'+dot+'</td></tr>';
		}
		var p=(record.get('jenis_jawaban')=='0')?'<h2>'+record.get('pertanyaan')+'</h2>':record.get('pertanyaan');
		var pertanyaan='<table class="pertanyaan"><tr><td>'+p+'</td></tr>'+strOpt+'</table>';
		return Ext.String.format('{0}',pertanyaan);
	}
	
});


