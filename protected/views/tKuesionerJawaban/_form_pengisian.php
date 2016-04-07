<style>
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
	
	var tkuesionerjawabanformpengisianViewer = Ext.create('Esmk.view.TKuesionerJawaban.form_pengisian',{
		kid:<?=$kid?>
	});
	Ext.getCmp('docs-icon-app-Pengisian-Kuesioner').add(tkuesionerjawabanformpengisianViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TKuesionerJawaban/form_pengisian.js"></script>
