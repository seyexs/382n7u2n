
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tbantuanpenerimabosViewer = Ext.create('Esmk.view.TBantuanPenerima.Bos.index',{
		rbid:'<?=$rbid?>',
		kodeWilayahProp:'<?=$kode_prop?>'
	});
	Ext.getCmp('docs-icon-app-4.4.3-Penentuan-Penerima-BOS').add(tbantuanpenerimabosViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanPenerima/Bos/index.js"></script>
