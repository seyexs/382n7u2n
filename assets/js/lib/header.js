var HeaderPanel = Ext.create('Ext.panel.Panel', {
    ui: 'webtitle',
    xtype: 'panel',
    region: 'north',
	id:'tpheaderid',
    height: 43,
    layout: {
        type: 'border',
        padding: '0 5 5 5'
    },
	listeners:{
		'afterrender':function(){
			//showNotif();
		}
	},
    items:[{
        id:'west-content-title',
        ui:'content-title',
        xtype:'panel',
        region:'west',
        width: 280,
		//html:'<div style="padding:5px;color:eaeaea;font-family:monospace;font-family:-moz-fixed;font-size:20px;"><h1>E-Bantuan SMK<h1></div>'
		html:'<img src="assets/images/ebantuan-logo.png" style="width:227px;height:31px;"/>'
        //bodyCls:'icon-image'
    },{
        id:'right-top-menu',
        ui:'content-title',
        xtype:'panel',
        region: 'center',
        buttons:[{
			text:'Petunjuk',
			ui:'blue-button',
			iconCls:'icon-information',
			handler:function(){
				var headerPanel=this.up('#tpheaderid');
				var appContent = Ext.getCmp('app-content');
				var activetab=appContent.getActiveTab();

				
				Ext.Ajax.request({
                    url: base_url + 'Menu/GetPetunjukPenggunaan',
					params:{menu:activetab.menuId},
					method:'POST',
                    success: function(response){
						Ext.MessageBox.show({
							msg: response.responseText,
							progressText: 'Meminta Petunjuk..',
							title:'Petunjuk Penggunaan',
							autoWidth:true,
							autoHeight:true,
							closeable:true,
							//wait:true,
							//waitConfig: {interval:200},
						});
						//msg.update(response.responseText);
					}
				});
			}
		},{
            ui:'blue-button',
            id:'btn-setting',
            text:'Profil Saya',
			iconCls:'icon-users',
            handler: function() {
                    
                Ext.Ajax.request({
                    url: base_url + 'user/GetUserLoggedIn',
                    success: function(response){
                        var json = Ext.JSON.decode(response.responseText);
                        var user_info = json[0];
                            
                        var form = Ext.widget('form',{
                            bodyPadding:10,
                            layout: 'anchor',
                            defaultType: 'textfield',
                            items: [{
                                xtype: 'hidden',
                                name: 'user_id',
                                allowBlank: false,
                                value: user_info.id
                            },{
                                name: 'username',
                                fieldLabel: 'Username',
                                allowBlank: false,
                                width: 330,
                                labelWidth: 125,
                                readOnly: true,
                                value: user_info.username
                            },{
                                name: 'displayname',
                                fieldLabel: 'Nama Lengkap',
                                allowBlank: false,
                                width: 330,
                                labelWidth: 125,
                                value: user_info.displayname
                            },{
                                name: 'email',
                                fieldLabel: 'Email',
                                width: 330,
                                labelWidth: 125,
                                value: user_info.email
                            },{
                                inputType: 'password',
                                name: 'password',
                                fieldLabel: 'Password',
                                width: 330,
                                //value: '**&&**&&',
                                labelWidth: 125
                            },{
                                xtype: 'fileuploadfield',
                                width: 330,
                                labelWidth: 125,
                                id: 'avatar_file',
                                emptyText: 'Pilih sebuah foto',
                                fieldLabel: 'Foto',
                                name: 'avatar_file',
                                buttonText: '',
                                buttonConfig: {
                                    iconCls: 'upload-icon'
                                }
                            }],
                            buttons: [{
                                text: 'Batal',
                                margins: '2 2 2 2',
                                handler: function (){
                                    this.up('form').getForm().reset();
                                    this.up('window').close();
                                }
                            },{
                                text: 'Simpan',
                                iconCls: 'icon-disk-black',
                                margins: '2 2 2 2',
                                handler: function(form,action){
                                    var form = this.up('form').getForm();
                                    var self = this;
                                    if(form.isValid()){
                                        form.submit({
                                            url: base_url + 'user/UpdateProfile',
                                            waitMsg: 'Mohon tunggu hingga proses selesai...',
                                            success: function(form, action) {
                                                self.up('form').getForm().reset();
                                                self.up('window').close();

                                                Ext.create('widget.uxNotification', {
                                                    title: 'Notifikasi',
                                                    position: 'br',
                                                    manager: 'demo1',
                                                    iconCls: 'ux-notification-icon-information',
                                                    autoHideDelay: 5000,
                                                    autoHide: true,
                                                    spacing: 20,
                                                    html: 'Profil berhasil dirubah'
                                                }).show();
                                                   
                                                var now = new Date();
                                                var src = document.getElementById('profile-image-crop').src;
                                                document.getElementById('profile-image-crop').src = src + '?' + now.getTime(); 
                                                    
                                            },
                                            failure: function(form, action) {
                                                self.up('form').getForm().reset();
                                                Ext.Msg.show({
                                                    title: 'Gagal',
                                                    msg:  action.result.message,
                                                    minWidth: 200,
                                                    modal: true,
                                                    icon: Ext.Msg.ERROR,
                                                    buttons: Ext.Msg.OK
                                                });
                                            }
                                        });
                                    }else{
                                        Ext.create('widget.uxNotification', {
                                            title: 'Notifikasi',
                                            position: 'br',
                                            manager: 'demo1',
                                            iconCls: 'ux-notification-icon-information',
                                            autoHideDelay: 2000,
                                            autoHide: true,
                                            spacing: 20,
                                            html: 'Form tidak valid'
                                        }).show();
                                    }
                                }
                            }]
                        });
                        var window = Ext.widget('window',{
                            items: form,
                            modal: true,
                            title: 'Ubah Profil'
                        });
                        window.show();

                    },
                    scope: this
                });
            }
        },{
            ui:'red-button',
            id:'btn-logout',
            text:'Keluar',
			iconCls:'door-open-out',
            handler: function() {
                window.location.href = site_url + '/logout/';
            }
        }
        ]
    }
    ],
    renderTo: Ext.getBody()
});
    
var menu_store = Ext.create('Ext.data.TreeStore', {
    storeId:'menu_store',
    proxy: {
        type: 'ajax',
        url : site_url + '/menu/data'
    }
});

var SidebarPanel = Ext.create('Ext.panel.Panel', 
{
    id: 'app-menu',
    region: 'west',
    width: 280,  
    xtype: 'panel',
    //collapsed:true,
    collapsible: true,
    floatable: true,
    animCollapse: true,
    layout: {
        type: 'border',
        padding: '0 0 0 0'
    },
    items:[{
        id:'login-details',
        xtype:'panel',
        region: 'north',
        height:105,
        border:0,
        html:'<img alt="Hello Admin" id="profile-image-crop" src="' + image_path + '" class="img-left framed"><h2>' + user_real_name + '</h2><hr><h4>' + role_name + '</h4>'
    },{
        id:'outer-panel',
        xtype:'panel',
        region: 'center',
        border:0,
        layout: {
            type: 'border',
            //padding: '2 2 2 2'
        },
        items:[
        {
            id:'tree-panel',
            //title:'Menu',
            xtype:'treepanel',
            border:0,
            region: 'center',
            expand:true,
            store: menu_store,
            rootVisible: false,
            useArrows: true,
			style:'background-color:#d5d5d5;',
            listeners: {
                itemclick: function(node, event){
                    GetRemoteForm(event.data.id,event.data.text, event.data.iconCls, event.data.hrefTarget);
                },
				viewready: function() {
					this.expandAll();
                },
            }
        }]
    }],
    renderTo: Ext.getBody()
});
function showNotif(){
	
	Ext.create('widget.uxNotification', {
        title: 'Perhitungan Mundur Peluncuran Aplikasi :',
        position: 't',
		id:'lapp',
		width:500,
		height:80,
		cls: 'ux-notification-light',
		slideInAnimation: 'elasticIn',
		slideBackAnimation: 'elasticIn',
		useXAxis: false,
		closable:false,
        iconCls: 'ux-notification-icon-information',
        slideInDuration: 800,
		slideBackDuration: 1500,
        autoHide: true,
		autoHideDelay: 10000,
		listeners:{
			'hide':function(){
				//todoWindow.show();
				
			}
		},
        html: '<div style="background:#4581ab;color:white;font-family:Oswald, Arial, Sans-serif;font-size:20px;text-transform:uppercase;text-align:center;padding:10px 0;font-weight:normal;"><span id="countdown">--------------</span></div>'
    }).show();
	
	
}

function GetRemoteForm(menuId,text, cls, url, method, params){
    //console.log(text, cls, url);
    //Not for non menu
    if (url == '#')
        return;
    //Add Base url
    url = site_url + '/' +  url;

    //Mengambil content
    var appContent = Ext.getCmp('app-content');
	var codeName=text.replace(/ /g,'-');
    var id = 'docs-' + cls+'-'+codeName;
    var tab = appContent.getComponent(id);
    //Jika tab sudah ada
    if(tab){
        //Mengaktifkan tab
		//appContent.setActiveTab(tab);
		appContent.remove(tab);
    }
    //Jika tab belum ada
    //else{
        var autoLoad = null;
        //Menambahkan autoload
        if (method == null){
            autoLoad = {
                url: url,
                scripts: true,
                method:'GET'
            };
        }
        else{
            autoLoad = {
                url: url,
                scripts: true,
                method:method,
                params:params
            };
        }
        //Membuat panel baru
        var p = appContent.add({
            id: id,
			menuId:menuId,
            title : text,
            closable:true,
            autoWidth:true,
            autoLoad:autoLoad,
            iconCls : cls,
            bodyBorder: false,
            layout:'fit',
            padding: 5
        });
        //Mengaktifkan tab
        appContent.setActiveTab(p);
    //}
}