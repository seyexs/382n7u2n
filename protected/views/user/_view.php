<?php
/* @var $this UserController */
/* @var $data User */
?>
<?php
$this->breadcrumbs=array(
	'User'=>array('index'),
	'View',
);
?>
<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Data Users', 'width' => 900,'height' => 500)); 
?>
<div class="form">
    <table class="ma-table">
        <tbody>
            <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('username')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'username', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                 <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('displayname')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'displayname', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                 <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('password')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'password', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                 <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'email', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                 <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('avatar_file')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'avatar_file', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                 <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo CHtml::encode($model->getAttributeLabel('state_online')); ?>:</label></td>
                <td><?php echo CHtml::activeTextField($model,'state_online', array('class'=>'readonly', 'readonly'=>true)); ?></td>
             </tr>    
                  <tr>
                 <td></td>
                 <td><?php echo CHtml::Button('Kembali', array('class' => 'ax-btn', 'type' => 'button', 'onclick' => 'window.location=\'' . Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction . '\'')); ?></td>
             </tr>
        </tbody>
    </table>
</div>
<?php $this->endWidget(); ?>