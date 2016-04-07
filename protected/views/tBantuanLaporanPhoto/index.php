
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tbantuanlaporanphotoViewer = Ext.create('Esmk.view.TBantuanLaporanPhoto._grid');
	Ext.getCmp('docs-').add(tbantuanlaporanphotoViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanLaporanPhoto/_grid.js"></script>
