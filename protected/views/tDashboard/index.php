
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tdashboardViewer = Ext.create('Esmk.view.TDashboard._grid');
	Ext.getCmp('docs-icon-app-block-99.5-Pengelolaan-Dashboard').add(tdashboardViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TDashboard/_grid.js"></script>
