<?php
/* @var $this PengaturanGroupController */
/* @var $model AuthItem */

$this->breadcrumbs=array(
	'Pendaftaran Pemakai'=>array('index'),
	'Pengaturan Hak Akses',
);


?>
<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Pengaturan Hak Akses Pengguna', 'width' => 900)); ?>
<?php 
//echo '<div class="grid-view" style="margin:4px;padding:10px 10px 5px 10px;">';
echo '<div style="padding:5px;">';
echo CHtml::label('Nama Pengguna: ', false, array('style' => 'margin-right:57px;'));
echo CHtml::textField('username', $model->username, array('class' => 'x-form-field x-form-text', 'style' =>'width:300px;font-weight: bold;', 'disabled'=>true));
echo '</div>';
echo '<div style="padding:5px;">';
echo CHtml::label('Nama Lengkap Pengguna: ', false, array('style' => 'margin-right:2px;'));
echo CHtml::textField('displayname', $model->displayname, array('class' => 'x-form-field x-form-text', 'style' =>'width:300px;font-weight: bold;', 'disabled'=>true));
echo '</div>';

?>
<?php $this->beginWidget('ext.widgets.spry.SpryAccordionPanel', array('itemView' => '_setakses', 'model'=>$modelsAsigns, 'defaultPanel' => $panelDefault, 'params' => array('modelRTO' => $modelRTO))); ?>

<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>