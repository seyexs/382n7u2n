<?php
/* @var $this PengaturanAplikasiController */
/* @var $model AuthItem */
/* @var $form CActiveForm */
?>
<div class="form">

    <?php
    $typeFID = CHtml::activeId($model, 'type');
    $taskFID = CHtml::activeId($model, 'task');
    $form = $this->beginWidget('ext.CAxActiveForm', array(
        'id' => 'registerrto-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
    <table class="ma-table">
        <tbody>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'type'); ?>
                </td>
                <td>
                    <?php
                    echo $form->hiddenField($model, 'parent');
                    echo $form->dropDownList($model, 'type', $model->getTypeOptions(), array('class' => 'x-form-field', 'empty' => '- Pilih -'));
                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'task'); ?>
                </td>
                <td>
                    <?php
                    $mtasks = AuthItem::model()->task()->findAll();
                    $options = array();
                    if (!empty($mtasks)) {
                        foreach ($mtasks as $mtask) {
                            $options[$mtask->name] = !empty($mtask->description) ? $mtask->description : $mtask->name;
                        }
                    }
                    if(isset($model->type)){
                        if($model->type == CAuthItem::TYPE_ROLE || $model->type == CAuthItem::TYPE_TASK)
                            echo $form->dropDownList($model, 'task', $options, array('empty' => '- Pilih -', 'disabled'=>true));
                        else
                            echo $form->dropDownList($model, 'task', $options, array('empty' => '- Pilih -'));
                    }
                    else
                        echo $form->dropDownList($model, 'task', $options, array('empty' => '- Pilih -', 'disabled'=>true));
                    
                    ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    <div id="registerrto-content">
                        <?php
                        if (isset($model->type)) {
                            if ($model->type == CAuthItem::TYPE_ROLE || $model->type == CAuthItem::TYPE_TASK) {
                                $mAvailabelRTO = AuthItem::model()->findAll('type=:type', array(':type' => $model->type));
                            } else {
                                if (isset($model->task)) {
                                    $mAvailabelRTO = AuthItem::model()->operation()->findAll("name LIKE '{$model->task}%'");
                                }
                            }
                        }
                        if (isset($mAvailabelRTO)) {
                            $options = array();
                            foreach ($mAvailabelRTO as $rto) {
                                $options[$rto->name] = !empty($rto->description) ? $rto->description : $rto->name;
                            }

                            echo $form->checkBoxList($model, 'rto', $options);
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="10px"></td>
                <td></td>
                <td></td>
            </tr>
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

<?php
if (Yii::app()->user->hasFlash('success') || Yii::app()->user->hasFlash('error')) {
    foreach (Yii::app()->user->getFlashes() as $key => $msg) {
        $this->widget('ext.widgets.notify.AxNotify');
        $js = <<<EOS
    showOnNotify('{$key}', '{$msg}', 2000); 
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#registerrto-form-1', $js, CClientScript::POS_READY);
        break;
    }
}
?>
<?php
$role = CAuthItem::TYPE_ROLE;
$task = CAuthItem::TYPE_TASK;
$operation = CAuthItem::TYPE_OPERATION;
$params = "{}";
if(isset($_GET['group']))
    $params = "{group: '{$_GET['group']}'}";
elseif(isset($_GET['userid']))
    $params = "{userid: '{$_GET['userid']}'}";
$sourceUrl = $this->createUrl('getRto');
$js = <<<EOS
    $('#{$typeFID}').live('change', function(){
        if($(this).val()=='{$role}' || $(this).val()=='{$task}'){
            var params = {$params};
            params.type = $(this).val();
            $('#{$taskFID}').val('');
            $('#{$taskFID}').attr('disabled','disabled');
            getRTO(params);
        }
        else{
            $('#{$taskFID}').removeAttr('disabled');
        }
           
    });
    $('#{$taskFID}').live('change', function(){
        if($(this).val() != ''){
            var params = {$params};
            params.task = $(this).val();
            params.type = $('#{$typeFID}').val();
            getRTO(params);
        }
    });
            
    $('.selectall').live('click', function(){
        $('#registerrto-content .selectitem').attr('checked', this.checked);
    });
    
    function getRTO(params){
        $.ajax({
            type: 'GET',
            url:'{$sourceUrl}',
            data:params,
            dataType: 'html',
            success: function(data){
                $('#registerrto-content').html(data);
            },
            error: function(data) { 
                alert("Error occured");
            }
        });
    }
    
EOS;
    $cs = Yii::app()->getClientScript();
    $cs->registerScript(__CLASS__ . '#registerrto-form-2', $js, CClientScript::POS_READY);
?>