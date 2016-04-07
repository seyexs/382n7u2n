<style>

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

</style>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/animationSVG/normalize.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/animationSVG/demo.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/animationSVG/component.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    var BASE_URL = '<?php echo Yii::app()->request->baseUrl; ?>';
	var items=[];
	<?php
		$i=1;
		foreach($dashboard as $d){
			if($d->kode_module=="")
				continue;
	?>
		try{
			var item=Ext.create('<?=$d->kode_module?>',{
				<?=$d->properties?>
			});
		}catch(err){
			alert('<?=$d->kode_module?>');
		}
		items.push({
			title:'Dashboard <?=$i?>',
			autoHeight:true,
			layout:'fit',
			iconCls:'icon-app-block',
			frame:false,
			border:0,
			flex:true,
			items:[item]
		});
	<?php 
		$i++;
	}
	?>
	var dashboardViewer = Ext.create('Esmk.view.Dashboard.index',{
		dashboardItems:items
	});
	Ext.getCmp('docs-icon-dashboard-1.1-Dashboard').add(dashboardViewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/Dashboard/index.js"></script>
