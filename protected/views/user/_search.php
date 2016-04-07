<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php $this->beginWidget('ext.widgets.XPanel', array('width'=>900, 'height'=>130, 'title'=> 'Pencarian')); ?>

<?php $form=$this->beginWidget('ext.CAxActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route), 
	'method'=>'get',
)); ?>
    
    <table class="ma-table">
        <tbody>
<tr>                <td class="labelfield">
                        <?php echo $form->label($model,'username').':'; ?>
                </td>
                <td>
                        <?php echo CHtml::activeTextField($model,'username', array('class'=>'x-form-field x-form-text')); ?>
                </td>
                <td class="labelfield">
                        <?php echo $form->label($model,'email').':'; ?>
                </td>
                <td>
                        <?php echo CHtml::activeTextField($model,'email', array('class'=>'x-form-field x-form-text')); ?>
                </td>
</tr>
	<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
		<?php echo CHtml::submitButton('Search', array('class'=>'ax-btn')); ?>
            </td>
	</tr>
        </tbody></table>

<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>

