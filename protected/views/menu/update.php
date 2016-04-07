<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menu'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'title' => 'Update Menu')); ?>