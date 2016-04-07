Ext.define('Esmk.view.TBantuanProgram.fileBrowser', {
    extend: 'Ext.view.View',
    alias: 'widget.bantuanprogramfilebrowser',
	id:'bantuanprogramfilebrowserviewid',
    uses: 'Ext.data.Store',
	singleSelect: false,
	region: 'center',
    overItemCls: 'x-view-over',
    itemSelector: 'div.thumb-wrap',
	loadingText:'collecting your documents..',
    tpl: [
        // '<div class="details">',
            '<tpl for=".">',
                '<div class="thumb-wrap" style="width:77px">',
                    '<div class="thumb" style="text-align:center">',
                    (!Ext.isIE6? '<img src="assets/icons/file/{file_type}.png" style="width:48px;"/>' : 
                    '<div style="width:74px;height:74px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'assets/icons/file/{file_type}.png\')"></div>'),
                    '</div>',
                    '<span style="word-wrap:break-word;font-size:10px" class="share_file{share_file}">{filename}</span>',
                '</div>',
            '</tpl>'
        // '</div>'
    ],
    listeners: {
        click: {
            element: 'el', //bind to the underlying el property on the panel
            fn: function(dataview, index, item, e){
				var selectedFile = Ext.getCmp('bantuanprogramfilebrowserviewid').selModel.getSelection()[0];
				if(selectedFile){
					var path='Location: Home/'+selectedFile.get('path_file');//+selectedFile.get('filename');
					Ext.getCmp('lblfileinfo').setText(path);
				}
			}
        },
		
        dblclick: {
            element: 'el', //bind to the underlying body property on the panel
            fn: function(){
				Ext.getCmp('bantuanprogramfilebrowserviewid').actionDblClick();
			}
        },
		keyup: {
                element: 'el',
                fn: function(event, target){ 
                    //var record = Ext.getCmp(this.id).ownerCt.context.record;
                    Ext.getCmp('bantuanprogramfilebrowserviewid').actionKeyUp(event.keyCode);
                }
        },
    },
    initComponent: function() {
        this.store = Ext.create('Esmk.store.TBantuanDoc');
		
		/*diakses melalui menu master bantuan*/
		if(this.bantuanId)
			this.store.getProxy().api.read='TBantuanDoc/read/?id='+this.bantuanId+'&pid='+this.parentId;
        
		/*diakses oleh sekolah pada tab detail bantuan mereka*/
		if(this.modeShare && this.bantuanId)
			this.store.getProxy().api.read='TBantuanDoc/GetShareFile/?id='+this.bantuanId;
		this.store.load();
        this.callParent(arguments);
        this.store.sort();
		var me=this;
		
    },
	actionDblClick:function(){
		var selectedFile = Ext.getCmp('bantuanprogramfilebrowserviewid').selModel.getSelection()[0];
		if(!selectedFile)
			return;
		
		if(selectedFile.get('is_dir')=='1'){
			this.actionGoTo(selectedFile.get('t_bantuan_program_id'),selectedFile.get('id'));
		}else{
			var url=site_url+'media/mydocuments/program-bantuan/p'+this.bantuanId+'/'+selectedFile.get('filename');
			this.downloadFile({url:url});
		}
	},
	actionUp:function(){
		var s=Ext.create('Esmk.store.TBantuanDoc');
		var me=this;
		s.load({
			params:{
				id:me.bantuanId
			},
			callback:function(){
				var p=this.getById(me.parentId);
				if(p){
					me.actionGoTo(me.bantuanId,p.get('parent_id'));
					var path='Location: Home/'+p.get('path_file');
					Ext.getCmp('lblfileinfo').setText(path);
				}
			}
		});
	},
	actionGoTo:function(id,pid){
		var tabDok=Ext.getCmp('dokterkaittabitem');
		tabDok.removeAll();
		
		var newDok=Ext.create('Esmk.view.TBantuanProgram.fileBrowser',{
			bantuanId:id,
			parentId:pid
		});
		tabDok.add(newDok);
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
	actionKeyUp:function(keyCode){
		switch(keyCode){
			case 13:
				this.actionDblClick();
				break;
			case 46:
				this.actionDelete();
				break;
			default:
				return;
		}
	},
	actionDelete:function(){
		var view = Ext.getCmp('bantuanprogramfilebrowserviewid');
		var records = view.getSelectionModel().getSelection();
		var store = view.getStore();
		Ext.Msg.confirm('Delete Requirment','Are you sure?',function(id,value){
			if(id==='yes'){
				
				Ext.each(records, function(item) {
					//name=item.get('name');
					//store.getProxy().api.destroy='TDataRekap/delete/?id='+item.get('id')
					store.remove(item);
				});
				//store.remove(record);
				//this.getTBantuanProgramStore().sync();
				
			}
		});
	},
	
});