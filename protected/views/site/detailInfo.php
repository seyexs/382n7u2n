<?php
/* @var $this DetailInfoController */
/* @var $model DetailInfo */
/* @var $form CActiveForm */
?>
<?php $this->beginWidget('ext.widgets.XPanel', array('title' => 'Detail Infos', 'width' => 900,'height' => 500)); 
?>
<div class="form">

<?php $form=$this->beginWidget('ext.CAxActiveForm', array(
	'id'=>'detail-info-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
    <table class="ma-table">
        <tbody>
	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'nama'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'nama'); ?>
</td>
		<td><?php echo $form->error($model,'nama'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'jenis_kelamin'); ?>
</td>
		<td><?php echo $form->radioButtonList($model,'jenis_kelamin', array('1'=>'Pria','2'=>'Wanita',), array('container'=>'ul')); ?>
</td>
		<td><?php echo $form->error($model,'jenis_kelamin'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'tempat_lahir'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'tempat_lahir'); ?>
</td>
		<td><?php echo $form->error($model,'tempat_lahir'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'tanggal_lahir'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'tanggal_lahir'); ?>
</td>
		<td><?php echo $form->error($model,'tanggal_lahir'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'agama'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'agama'); ?>
</td>
		<td><?php echo $form->error($model,'agama'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'kewarganegaraan'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'kewarganegaraan'); ?>
</td>
		<td><?php echo $form->error($model,'kewarganegaraan'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'alamat'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'alamat'); ?>
</td>
		<td><?php echo $form->error($model,'alamat'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'telp'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'telp'); ?>
</td>
		<td><?php echo $form->error($model,'telp'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'jarak_dari_rumah'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'jarak_dari_rumah'); ?>
</td>
		<td><?php echo $form->error($model,'jarak_dari_rumah'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'tanggal_masuk'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'tanggal_masuk'); ?>
</td>
		<td><?php echo $form->error($model,'tanggal_masuk'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'t_tahun_pelajaran'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'t_tahun_pelajaran'); ?>
</td>
		<td><?php echo $form->error($model,'t_tahun_pelajaran'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'golongan_darah'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'golongan_darah'); ?>
</td>
		<td><?php echo $form->error($model,'golongan_darah'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'tinggi_badan'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'tinggi_badan'); ?>
</td>
		<td><?php echo $form->error($model,'tinggi_badan'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'berat_badan'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'berat_badan'); ?>
</td>
		<td><?php echo $form->error($model,'berat_badan'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'foto'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'foto'); ?>
</td>
		<td><?php echo $form->error($model,'foto'); ?>
</td>
	</tr>

	<tr>
		<td class="labelfield" style="width:200px"><?php echo $form->labelEx($model,'keterangan_tinggal'); ?>
</td>
		<td><?php echo CHtml::activeTextField($model,'keterangan_tinggal'); ?>
</td>
		<td><?php echo $form->error($model,'keterangan_tinggal'); ?>
</td>
	</tr>

	<tr>
		<td></td>
                <td><?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'ax-btn')); ?>
</td>
                <td></td>
	</tr>
        </tbody>
    </table>
<?php $this->endWidget(); ?>

</div><!-- form -->
<?php $this->endWidget(); ?>