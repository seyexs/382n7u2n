<style>
.pertanyaan {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 100%;
    border-collapse: collapse;
}

.pertanyaan td, .pertanyaan th {
    font-size: 1em;
    /* border: 1px solid #98bf21; */
    padding: 3px 7px 2px 7px;
}

.x-form-radio{
	margin-right:10px;
}
</style>
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var tkuesionerViewer = Ext.create('Esmk.view.TKuesioner.index');
	Ext.getCmp('docs-icon-app-5.1-Master-Kuesioner').add(tkuesionerViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TKuesioner/index.js"></script>
