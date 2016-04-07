
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tbantuandataViewer = Ext.create('Esmk.view.TBantuanData._grid');
	Ext.getCmp('docs-icon-app-4.1.2-Penerima-Bantuan').add(tbantuandataViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanData/_grid.js"></script>
