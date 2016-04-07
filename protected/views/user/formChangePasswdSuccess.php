<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
    'Change Password'=>array('updatePassword'),
    'Change Password Successfully',
);
?>

<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Change Password', 'height' => 150, 'width' => 900)); ?>
Password telah diupdate, sekarang password baru anda adalah <?php echo $model->newpassword; ?>   
<?php $this->endWidget(); ?>