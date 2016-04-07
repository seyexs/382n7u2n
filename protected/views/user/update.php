<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Pendaftaran Pemakai'=>array('index'),
        'Buat Pemakai Baru'=>array('create'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'title' => 'Update User')); ?>