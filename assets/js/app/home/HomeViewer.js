Ext.define('Esmk.home.HomeViewer', {
    extend: 'Ext.panel.Panel',
    border:0,
    initComponent: function(){
        var me = this;
        
        Ext.apply(this, {
            padding: 5,
            layout: 'column',
            title: 'Home',
            items:[
			{
				columnWidth:0.75,
				xtype:'panel',
				id:'columnforumpanelid',
				frame:false,
				//padding: 5,
				layout: 'anchor',
				border:0,
				anchor: '100%',
				height:'100%',
				items:[me.createForumDisplay(),me.createInputForm()]
			},{
				columnWidth:0.25,
				xtype:'panel',
				frame:false,
				layout:'anchor',
				padding: 6,
				bodyCls:'clamp',
				id:'todocomppanelid',
				title:'-',
				anchor: '100%',
				html:'<div id="todopanelid"></div>',
				listeners:{
					afterrender:function(){
						me.createTodoPanel();
					}
				}
			}
            //me.createForumDisplay(),
            //me.createInputForm()
            ]
        });

        this.callParent(arguments);
    },
    createTodoPanel:function(){
		var strTodo='';
		var me=this;
		Ext.Ajax.request({
            url: site_url + '/TKuesionerRespondent/GetTodo',
            params: {
            },
            success: function(response){
				var todos=JSON.parse(response.responseText);
                for (var r in todos.data) {
					strTodo+='<li onclick="GetRemoteForm(\'Pengisian Kuesioner\', \'icon-app\',\'TKuesionerJawaban/FormPengisian/?tkid='+todos.data[r].tkid+'\',\'GET\')"><a>'+todos.data[r].tkjudul+'</a></li>';
				}
				if(!strTodo){
					Ext.getCmp('columnforumpanelid').columnWidth=1;
					Ext.getCmp('todocomppanelid').setVisible(false);
				}
				
				strTodo='<div id="todopanelid">'+strTodo+'</div>';
				Ext.getCmp('todocomppanelid').add({html:strTodo,frame:false});
				
				//return Ext.String.format('<div id="todopanelid"><ul>{0}</ul></div>',strTodo);
            },
        });
		/*
		for (var key in todo.todos) {
			if (!todo.todos.hasOwnProperty(key)) {
				//The current property is not a direct property of p
				continue;
			}
			//Do your logic with the property here
			
			strTodo+='<li onclick="GetRemoteForm(\'Pengisian Kuesioner\', \'icon-app\',\'TKuesionerJawaban/FormPengisian\')"><a>'+todo.todos[key].name+'</a></li>';
		}*/
		
	},
	addTodo:function(strLi){
		return Ext.String.format('<div id="todopanelid"><ul>{0}</ul></div>',strLi);
	},
    createForumDisplay: function(){
        Ext.define('Forum', {
            extend: 'Ext.data.Model',
            fields: [
            {
                name: 'forum_id',   
                type: 'int'
            },
            {
                name: 'forum_from',
                type: 'string'
            },
            {
                name: 'forum_content',
                type: 'string'
            },
            {
                name: 'forum_date',
                type: 'string',//'date',
                //dateFormat: 'Y-m-d H:i:s'
            },
            {
                name: 'user_realname',
                type: 'string'
            }
            ]
        });
        
        var store = Ext.create('Ext.data.Store', {
            storeId:'forumStore',
            model: 'Forum',
			autoSync: true,
            pageSize: 20,
            proxy: {
                type: 'ajax',
                api : {
                    read : site_url + '/tforum/messagelist/'
                },
                reader: {
                    //Kita gunakan tipe data json
                    type: 'json',
                    //Parent element untuk data
                    root: 'rows',
                    //Jumlah total record
                    totalProperty: 'total'
                }
            },
            //Untuk sorting
            remoteSort:true
        });
        //Inisialisasi pertama bila pagging
        //belum dilakukan
        store.load({
            params:{
                start:0,
                limit: 20
            }
        });
        
        //Membuat grid user
        var GridPanel = Ext.create('Ext.grid.Panel', {
            id:'home-forum-display',
            padding: 5,
            autoScroll: true,
            region: 'center',
            title:'Pengumuman oleh Direktorat Pembinaan SMK',
            layout:'fit',
            autoScroll:true,
			minHeight:440,
			maxHeight:440,
            store: Ext.data.StoreManager.lookup('forumStore'),
            columns: [{
                text: 'Isi',
                dataIndex: 'forum_content',
                flex: 1,
                renderer: this.formatTitle
            },{
                text: 'Tanggal',
                dataIndex: 'forum_date',
                renderer: this.formatDate,
                width: 140,
                align: 'right'
            }],
            selType: 'rowmodel',
			listeners:{
				itemdblclick: function(dataview, index, item, e) {
					this.actionDbClick(dataview, index, item, e);
				}
			},
            //Pagging control
            bbar: new Ext.PagingToolbar({
                store:  Ext.data.StoreManager.lookup('forumStore'),
                displayInfo: true
            }),
            tbar: [
            {
                xtype: 'button',
                text: 'Refresh',
                iconCls: 'icon-refresh',
                handler: function(){
                    Ext.data.StoreManager.lookup('forumStore').load({});
                }
            },
            '->',
            {
                xtype: 'label',
                text: 'Cari',
                margins: '0 5 0 10'
            },
            new Ext.app.SearchField({
                width: 200,
                store: Ext.data.StoreManager.lookup('forumStore')
            }),
            ],
			actionDbClick:function(dataview, record, item, index, e, options){
				var detailForum=Ext.create('Esmk.home._forum_detail',{fid:1});
				if(record)
					detailForum.down('form').loadRecord(record);
				
			}
        });

        return GridPanel;
    },
    
    createInputForm: function(){
        var me = this;
        var panel =  Ext.create('Ext.form.Panel',{
            id:'home-input-form',
            region: 'south',
			hidden:(!me.forumInsert),
            padding: 5,
            height: 100,
            border:0,
            items:[
            {
                xtype: 'fieldcontainer',
                hideLabel: true,
                layout: 'hbox',
                defaultType: 'textfield',

                items: [new Ext.form.HtmlEditor({
					name : 'TForum[forum_content]',
					flex:1,
					height:85
				}),
				/*{
                    xtype: 'textarea',
                    layout: 'fit',
                    flex: 1,
                    name: 'TForum[forum_content]',
                    fieldLabel: 'Pesan',
                    hideLabel: true,                 
                    margins: '0 5 0 0' 
                },*/ {
                    xtype: 'button',
                    layout: 'fit',
                    text: 'Kirim',                     
                    margins: '0 0 0 5',
                    ui:'blue-button',
                    width: 100,
                    height: 85,
                    handler: me.sendMessageForum,
                    scope:me
                }]
            }
            ]
        });
        return panel;
    },
    
    /**
     * Title renderer
     * @private
     */
    formatTitle: function(value, p, record){
        return Ext.String.format('<div class="topic"><b>{0}</b><div class="author">{1}</div></div>', record.get('forum_from') || "Tidak Dikenal", value);
    },

    /**
     * Date renderer
     * @private
     */
    formatDate: function(date){
        if (!date) {
            return '';
        }
		var dta=date.split('.');
		date=dta[0];
		tgl=dta[0].split(' ');
		var ftime=new Date(tgl[0]);
		ftime=ftime.getFullYear()+"-"+ftime.getMonth()+"-"+ftime.getDate();
		var now=new Date();
		now=now.getFullYear()+"-"+now.getMonth()+"-"+now.getDate();
		if(now==ftime){
			return "Today at "+tgl[1];
		}
		return date;
        /*var now = new Date(), d = Ext.Date.clearTime(now, true), notime = Ext.Date.clearTime(date, true).getTime();
		
        if (notime === d.getTime()) {
            return "Hari Ini, " + Ext.Date.format(date, 'H:i:s');
        }
		alert(notime+"==="+d.getTime());
        d = Ext.Date.add(d, 'd', -6);
		
        if (d.getTime() <= notime) {
            return Ext.Date.format(date, 'd/m/Y H:i:s');
        }
		
        return Ext.Date.format(date, 'd/m/Y H:i:s');
		*/
		
    },
    
    sendMessageForum: function(){
        var me = this;
        var form = Ext.getCmp('home-input-form').getForm();
        var forum_content = form.findField('TForum[forum_content]').getValue();
        form.findField('TForum[forum_content]').setValue('');
        
        Ext.Ajax.request({
            url: site_url + '/tforum/insert',
            params: {
                forum_content: forum_content
            },
            success: function(response){
                Ext.data.StoreManager.lookup('forumStore').load({});
            },
            scope: me
        });
        
    }
});