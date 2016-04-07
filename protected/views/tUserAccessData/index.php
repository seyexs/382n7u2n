
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tuseraccessdataViewer = Ext.create('Esmk.view.TUserAccessData._grid');
	Ext.getCmp('docs-icon-app-99.6-User-Mapping').add(tuseraccessdataViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TUserAccessData/_grid.js"></script>
