<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Users', 'width' => 900)); ?>
<div class="form">

    <?php
    $typeFID = CHtml::activeId($model, 'type');
    $personFID = CHtml::activeId($model, 'person');
    $personIdFID = CHtml::activeId($model, 'personId');
    $displaynameFID = CHtml::activeId($model, 'displayname');

    $form = $this->beginWidget('ext.CAxActiveForm', array(
        'id' => 'user-form',
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
            <?php if ($model->isNewRecord): ?>
                <tr>
                    <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'type'); ?>
                    </td>
                    <td><?php echo $form->dropDownList($model, 'type', $model->getTypeUserOptions(), array('class' => 'x-form-field')); ?>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'person'); ?>
                    </td>
                    <td><?php
            echo $form->hiddenField($model, 'personId');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'person',
                'source' => "js:function(request, response){
                                    $.getJSON('{$this->createUrl("getPerson")}',
                                        {
                                            maxRows: 20,
                                            name: request.term,
                                            type:$('#{$typeFID}').val()
                                        },function(data){
                                            response( $.map( data, function( item ) {
                                                return {
                                                    label: item.nama,
                                                    value: item.id,
                                                    displayname: item.displayname
                                                }
                                            }));
                                        });
                                    }",
                'options' => array(
                    'minLength' => '4',
                    'focus' => new CJavaScriptExpression('function(event,ui) {
					$("#' . $personFID . '").val(ui.item.displayname);
					$("#' . $displaynameFID . '").val(ui.item.displayname);
					return false;
				}'),
                    'select' => new CJavaScriptExpression("function( event, ui ){                                    
                            $('#{$personFID}').val(ui.item.displayname);
                            $('#{$displaynameFID}').val(ui.item.displayname);
                            $('#{$personIdFID}').val(ui.item.value);
                            return false;
                        }"),
                ),
                'htmlOptions' => array(
                    'class' => 'x-form-field x-form-text'
                ),
            ));
                ?>
                    </td>
                    <td></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'username'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'username', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>

            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'displayname'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'displayname', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <?php if ($model->isNewRecord): ?>
                <tr>
                    <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'password'); ?>
                    </td>
                    <td><?php echo CHtml::activeTextField($model, 'password', array('class' => 'x-form-field x-form-text')); ?>
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
            <?php endif; ?>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'email'); ?>
                </td>
                <td><?php echo CHtml::activeTextField($model, 'email', array('class' => 'x-form-field x-form-text')); ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px;vertical-align: top;"><?php echo $form->labelEx($model, 'avatar_file'); ?>
                </td>
                <td><div id='file_browse_wrapper'>
                        <?php
                        echo CHtml::activeHiddenField($model, 'avatar_file');
                        echo CHtml::activeFileField($model, 'uploadedFoto', array('class' => 'realupload', 'id' => 'file_browse'));
                        ?>
                    </div>
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
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan', array('class' => 'ax-btn')); ?>
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
<script>
    $(document).ready(function(){
        $('#TUserPegawaiSiswa_is_pegawai_siswa').change(function(){
            var v=$(this).val();
            renderCombo(v);
        });
    });
    function renderCombo(v){
        $.ajax({
            type: "POST",
            url: "RenderCombo",
            data: { "is_pegawai_siswa":v}
        }).done(function( resHtml ) {
            $("#view-combo").find('select').remove();
            $("#view-combo").html($("#view-combo").html()+resHtml);
        });
    }
</script>
<?php
$js = <<<EOS
    $('#{$typeFID}').live('change', function(){
        $('#{$personFID}').val('');
        $('#{$personIdFID}').val('');
        $('#{$displaynameFID}').val('');
    });
EOS;
$cs = Yii::app()->getClientScript();
$cs->registerScript(__CLASS__ . '#user-form-2', $js, CClientScript::POS_END);
?>    
<?php $this->widget('ext.widgets.notify.AxShowNotify'); ?>
