<?php
/* @var $this MenuController */
/* @var $model Menu */
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
                        <?php echo $form->label($model,'title').':'; ?>
                </td>
                <td>
                        <?php echo CHtml::activeTextField($model,'title', array('class'=>'x-form-field x-form-text')); ?>
                </td>
                <td class="labelfield">
                        <?php echo $form->label($model,'url').':'; ?>
                </td>
                <td>
                        <?php echo CHtml::activeTextField($model,'url', array('class'=>'x-form-field x-form-text')); ?>
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

