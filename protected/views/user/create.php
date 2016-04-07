<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Pendaftaran Pemakai'=>array('index'),
	'Buat Pemakai Baru',
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model,'title' => 'Buat Pemakai Baru')); ?>