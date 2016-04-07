Ext.define('Esmk.view.TBantuanProgram.fileInfo', {
    extend: 'Ext.panel.Panel',
    alias : 'widget.tprogrambantuanfileinfo',
    id: 'img-detail-panel',
	region: 'east',
    split: true,
    width: 150,
    minWidth: 150,

    tpl: [
        '<div class="details">',
            '<tpl for=".">',
                    (!Ext.isIE6? '<img src="icons/{file_type}.png" />' : 
                    '<div style="width:74px;height:74px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'icons/{file_type}.png\')"></div>'),
                '<div class="details-info">',
                    '<b>Example Name:</b>',
                    '<span>{name}</span>',
                    '<b>Example URL:</b>',
                    '<span><a href="http://esmk-yii/media/myDocuments/program-bantuan/p1/{path_file}" target="_blank">{path_file}</a></span>',
                    '<b>Type:</b>',
                    '<span>{type}</span>',
                '</div>',
            '</tpl>',
        '</div>'
    ],
    
    afterRender: function(){
        this.callParent();
        if (!Ext.isWebKit) {
            this.el.on('click', function(){
                alert('The Sencha Touch examples are intended to work on WebKit browsers. They may not display correctly in other browsers.');
            }, this, {delegate: 'a'});
        }    
    },

    /**
     * Loads a given image record into the panel. Animates the newly-updated panel in from the left over 250ms.
     */
    loadRecord: function(image) {
        this.body.hide();
        this.tpl.overwrite(this.body, image.data);
        this.body.slideIn('l', {
            duration: 250
        });
    },
    
    clear: function(){
        this.body.update('');
    }
})