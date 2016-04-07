<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	//Ext.onReady(function() {
		var userViewer = Ext.create('Esmk.view.User._grid');
		Ext.getCmp('docs-icon-user-config-99.1-User').add(userViewer);
	//});
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/User/_grid.js"></script>
