<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
//$label=$this->pluralize($this->class2name($this->modelClass));
$label=$this->class2name($this->modelClass);
echo "\$this->breadcrumbs=array(
	'$label'
);\n";
?>


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; 
?>

<?php echo "<?php"; ?> $this->widget('ext.widgets.grid.AxGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
        'title' => 'Grid Title',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
    if(!empty($_POST['gridcolmn'])){
        if(in_array($column->name, $_POST['gridcolmn'])){
            if(++$count==7)
                echo "\t\t/*\n";
            echo "\t\tarray(\n";
            echo "\t\t\t'name' => '".$column->name."',\n";
            echo "\t\t\t'htmlOptions' => array('class' => 'left'),\n";
            echo "\t\t),\n";
        }
    }
}
if($count>=7)
	echo "\t\t*/\n";
?>
        array(
            'header' => 'Action',
            'class' => 'ext.widgets.grid.AxActionColumn',
            'buttons' => array(
                array('label' => 'View', 'url' => array('view')),
                array('label' => 'Edit', 'url' => array('update')),
                array('label' => 'Delete', 'url' => array('delete'), "ajax" => true,'confirm' => 'Are you sure want to delete?'),
             )), 
	),
)); ?>