
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tbantuanproposalViewer = Ext.create('Esmk.view.TBantuanProposal._grid');
	Ext.getCmp('docs-').add(tbantuanproposalViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanProposal/_grid.js"></script>
