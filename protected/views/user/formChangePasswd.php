<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Change Password', 'width' => 900)); ?>
<div class="form">
    <?php
    $typeFID = CHtml::activeId($model, 'type');
    $personFID = CHtml::activeId($model, 'person');
    $personIdFID = CHtml::activeId($model, 'personId');
    $displaynameFID = CHtml::activeId($model, 'displayname');

    $form = $this->beginWidget('ext.CAxActiveForm', array(
        'id' => 'change-password-form',
        'enableAjaxValidation' => false,
'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <table class="ma-table">
        <tbody>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'oldpassword'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'oldpassword', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'newpassword'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'newpassword', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'confirmpassword'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'confirmpassword', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
	<!--<tr>
                <td class="labelfield" style="width:200px;vertical-align: top;"><?php echo $form->labelEx($model, 'filefoto'); ?>
                </td>
                <td><div id='file_browse_wrapper'>
                        <?php
                        echo CHtml::activeHiddenField($model, 'filefoto');
                        echo CHtml::activeFileField($model, 'uploadedFoto', array('class' => 'realupload', 'id' => 'file_browse'));
                        ?>
                    </div>
                </td>
                <td></td>
            </tr>-->
            <tr>
                <td></td>
                <td>
                    <?php echo CHtml::submitButton('Simpan', array('class' => 'ax-btn')); ?>
                    <?php echo CHtml::Button('Batal', array('class' => 'ax-btn-delete', 'type' => 'button', 'onclick' => 'window.location=\'' . Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction . '\'')); ?>                </td>
                <td></td>
            </tr>
            <tr>
                <td height="4px"></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php $this->endWidget(); ?>

</div><!-- form -->
<?php $this->endWidget(); ?>
