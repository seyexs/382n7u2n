<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getModelClass(); ?>Controller */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>
<?php 
$label=$this->pluralize($this->class2name($this->modelClass));
echo "<?php"; ?> $this->beginWidget('ext.widgets.XPanel', array('title' => '<?php echo $label; ?>', 'width' => 900,'height' => 500)); 
?>
<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('ext.CAxActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
    <table class="ma-table">
        <tbody>
<?php

foreach($this->getModelSchema() as $column)
{
	if($column->autoIncrement)
            continue;
        if($column->name == 'created_date' || $column->name == 'created_by' || $column->name == 'modified_date' || $column->name == 'modified_by')
            continue;
 
        $fieldType = 'textfield';
        if(isset($_POST['fields'][$column->name]))
             $fieldType = $_POST['fields'][$column->name];
        $addParam = '';
        if(isset($_POST['addparam-'.$column->name]))
                $addParam = $_POST['addparam-'.$column->name];
        
?>
	<tr>
		<td class="labelfield" style="width:200px"><?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?></td>
		<td><?php 
                if($fieldType == 'datefield')
                    echo "<?php ".$this->generateActiveField($this->modelClass,$column,$fieldType,$addParam)."; ?>\n"; 
                else
                    echo "<?php echo ".$this->generateActiveField($this->modelClass,$column,$fieldType,$addParam)."; ?>\n"; 
                
                ?></td>
		<td><?php echo ""; ?></td>
	</tr>

<?php
}
?>
        <tr>
            <td height="10px"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
		<td></td>
                <td>
                <?php 
                    echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? 'Simpan' : 'Simpan', array('class'=>'ax-btn')); ?>\n"; 
                    echo "<?php echo CHtml::Button('Batal', array('class' => 'ax-btn', 'type' => 'button', 'onclick' => 'window.location=\'' . Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction . '\'')); ?>";
                ?>
                </td>
                <td></td>
	</tr>
        <tr>
            <td height="4px"></td>
            <td></td>
            <td></td>
        </tr>

        </tbody>
    </table>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->
<?php echo "<?php \$this->endWidget(); ?>"; ?>