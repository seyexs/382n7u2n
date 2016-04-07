
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tkuesionerpilihanjawabanViewer = Ext.create('Esmk.view.TKuesionerPilihanJawaban._grid');
	Ext.getCmp('docs-').add(tkuesionerpilihanjawabanViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TKuesionerPilihanJawaban/_grid.js"></script>
