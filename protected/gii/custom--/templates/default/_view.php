<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $data <?php echo $this->getModelClass(); ?> */
?>
<?php
$label=$this->class2name($this->modelClass);
echo "<?php\n\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'View',
);\n";
?>
?>
<?php 
$label=$this->pluralize($this->class2name($this->modelClass));
echo "<?php"; ?> $this->beginWidget('ext.widgets.XPanel', array('title' => 'Data <?php echo $label; ?>', 'width' => 900)); 
?>
<div class="form">
    <table class="ma-table">
        <tbody>
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
    if($column->isPrimaryKey)
		continue;
    if($column->name == 'created_date' || $column->name == 'created_by' || $column->name == 'modified_date' || $column->name == 'modified_by')
            continue;
    ?>
            <tr> 
                <td class="labelfield" style="width:200px"><label><?php echo "<?php echo CHtml::encode(\$model->getAttributeLabel('{$column->name}')); ?>"; ?>:</label></td>
                <td><?php echo "<?php echo CHtml::activeTextField(\$model,'{$column->name}', array('class'=>'readonly', 'readonly'=>true)); ?>"; ?></td>
             </tr>    
     <?php
}
?>
             <tr>
                 <td></td>
                 <td><?php echo "<?php echo CHtml::Button('Kembali', array('class' => 'ax-btn', 'type' => 'button', 'onclick' => 'window.location=\'' . Yii::app()->baseUrl . '/' . Yii::app()->controller->uniqueId . '/' . Yii::app()->controller->defaultAction . '\'')); ?>"; ?></td>
             </tr>
        </tbody>
    </table>
</div>
<?php echo "<?php \$this->endWidget(); ?>"; ?>