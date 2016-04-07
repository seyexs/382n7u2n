<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo '
<script type="text/javascript">
    var BASE_URL = \'<?php echo Yii::app()->request->baseUrl; ?>\';
	var '.strtolower($this->modelClass).'Viewer = Ext.create(\'Esmk.view.'.$this->modelClass.'._grid\');
	Ext.getCmp(\'docs-\').add('.strtolower($this->modelClass).'Viewer);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/app/view/'.$this->modelClass.'/_grid.js"></script>
';?>
