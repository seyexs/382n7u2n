<style>
.biodata input{
	border:none !important;
	background:none !important;
}
.x-form-cb{
	margin-right:5px;
}
.offered_answer span{
	margin-top:5px;
}
.pertanyaan td{
	padding:5px;
}

</style>
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var proposalViewer = Ext.create('Esmk.view.Proposal.index',{
		biodataSekolah:<?=$biodata?>
	});
	Ext.getCmp('docs-icon-app-4.2.1-Proposal-Pengajuan').add(proposalViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/Proposal/index.js"></script>
