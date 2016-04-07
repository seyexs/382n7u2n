<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Pendaftaran Pemakai'=>array('index'),
        'Buat Pemakai Baru'=>array('create'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

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
			<tr>
				<td class="labelfield" >Nama Pengguna</td>
				<td><?php echo $model->displayname;?></td>
			</tr>
			<?php if (Yii::app()->user->id <> 1) : ?>
			<tr>
				<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'old_password'); ?>
				</td>
				<td><?php echo CHtml::activePasswordField($model, 'oldpassword', array('class' => 'x-form-field x-form-text')); ?>
				</td>
				<td></td>
			</tr>
			<?php endif; ?>			
			<tr>
				<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'password'); ?>
				</td>
				<td><?php echo CHtml::activePasswordField($model, 'password', array('value'=>'','class' => 'x-form-field x-form-text')); ?>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model, 'confirmpassword'); ?>
				</td>
				<td><?php echo CHtml::activePasswordField($model, 'confirmpassword', array('class' => 'x-form-field x-form-text')); ?>
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
