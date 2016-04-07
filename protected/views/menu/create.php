<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menu'=>array('index'),
	'Create',
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title' => 'Create Menu')); ?>