
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var rbantuanViewer = Ext.create('Esmk.view.RBantuan._grid');
	Ext.getCmp('docs-icon-app-4.99.1-Jenis-Bantuan').add(rbantuanViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/RBantuan/_grid.js"></script>
