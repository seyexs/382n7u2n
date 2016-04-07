<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Mpsbps', 'width' => 900, 'height' => 500));
?>
<div class="form">

    <?php
    $form = $this->beginWidget('ext.CAxActiveForm', array(
        'id' => 'mpsbp-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php echo $form->errorSummary($model); ?>
    <table class="ma-table">
        <tbody>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'start_date'); ?>
                </td>
                <td><?php echo $form->textField($model, 'start_date', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'month'); ?>
                </td>
                <td><?php echo $form->dropDownList($model, 'month', $model->getYearOptionsList(), array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'year'); ?>
                </td>
                <td><?php echo $form->dropDownList($model, 'year', $model->getMonthOptionsList(), array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td height="10px"></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php echo CHtml::submitButton('Cari', array('class' => 'ax-btn')); ?>
                    <?php echo CHtml::Button('Reset', array('class' => 'ax-btn-delete', 'type' => 'button', 'onclick' => 'window.location=\'' . Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction . '/' . $cat . '\'')); ?>                
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>


    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php $this->endWidget(); ?>