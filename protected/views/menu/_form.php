<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
?>
<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Menus', 'width' => 900, 'height' => 500)); ?>
<div class="form">

    <?php
    $form = $this->beginWidget('ext.CAxActiveForm', array(
        'id' => 'menu-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>
    <table class="ma-table">
        <tbody>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'sort'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'sort', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td><?php echo $form->error($model, 'sort'); ?>
                </td>
            </tr>

            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'parent_id'); ?>
                </td>
                <td>
                    <?php
                    $parents = Menu::model()->findAll('parent_id = 0');
                    $cm = new CommonMethods();
                    $data = $cm->makeDropDown($parents);
                    //echo $form->labelEx($model,'parent_id');
                    echo $form->dropDownList($model, 'parent_id', $data);
                    ?>

<?php //echo CHtml::activeTextField($model,'parent_id', array('class'=>'x-form-field x-form-text'));  ?>
                </td>
                <td><?php echo $form->error($model, 'parent_id'); ?>
                </td>
            </tr>

            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'title'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'title', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td><?php echo $form->error($model, 'title'); ?>
                </td>
            </tr>

            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'url'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'url', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td><?php echo $form->error($model, 'url'); ?>
                </td>
            </tr>

            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'bizrule'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'bizrule', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td><?php echo $form->error($model, 'bizrule'); ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td><?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'ax-btn')); ?>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
<?php $this->endWidget(); ?>

</div><!-- form -->
<?php $this->endWidget(); ?>
