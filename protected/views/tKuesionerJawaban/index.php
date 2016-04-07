
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tkuesionerjawabanViewer = Ext.create('Esmk.view.TKuesionerJawaban._grid');
	Ext.getCmp('docs-').add(tkuesionerjawabanViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TKuesionerJawaban/_grid.js"></script>
