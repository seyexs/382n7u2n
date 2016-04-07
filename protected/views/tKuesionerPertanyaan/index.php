
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tkuesionerpertanyaanViewer = Ext.create('Esmk.view.TKuesionerPertanyaan._grid');
	Ext.getCmp('docs-').add(tkuesionerpertanyaanViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TKuesionerPertanyaan/_grid.js"></script>
