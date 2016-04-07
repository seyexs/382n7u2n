<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tbantuanpersyaratanpenerimainqueryViewer = Ext.create('Esmk.view.TBantuanPersyaratanPenerimaInquery._display_container',{
		dataItems:[<?=$items?>]
	});
	Ext.getCmp('docs-icon-app-4.4.4-Data-Penerima-BOS').add(tbantuanpersyaratanpenerimainqueryViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanPersyaratanPenerimaInquery/_display_container.js"></script>
