<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	
	var messageViewer = Ext.create('Esmk.view.TMessage.MyMessageViewer');
	Ext.getCmp('docs-icon-mail-1.2-Pesan').add(messageViewer);//docs-icon-mail-1.2-My Message
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TMessage/MyMessageViewer.js"></script>


