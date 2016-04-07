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

.thumb {
    padding: 3px;
}

.thumb-wrap {
    float: left;
    margin: 4px;
    margin-right: 0;
    padding: 5px;
}

.thumb-wrap span {
    display: block;
    overflow: hidden;
    text-align: center;
}

.x-view-over {
    border:1px solid #dddddd;
    background-color: #efefef;
    padding: 4px;
}

.x-item-selected {
    background: #DFEDFF;
    border: 1px solid #6593cf;
    padding: 4px;
}

.x-item-selected .thumb {
    background:transparent;
}

.x-item-selected span {
    color:#1A4D8F;
}
.share_file1{
	color:green;
	font-style: italic;
}
</style>
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var TBantuanProgramViewer = Ext.create('Esmk.view.TBantuanProgram.index',{
		isProp:<?=$isProp?>
	});
	Ext.getCmp('docs-icon-app-4.1.1-Master-Bantuan').add(TBantuanProgramViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/TBantuanProgram/index.js"></script>


