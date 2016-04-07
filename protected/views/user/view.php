<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Pendaftaran Pemakai'=>array('index'),
	'Detail',
);
?>

<?php echo $this->renderPartial('_view', array('model'=>$model, 'title' => 'View User')); ?>