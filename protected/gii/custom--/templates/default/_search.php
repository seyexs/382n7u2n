<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<?php echo "<?php"; ?> $this->beginWidget('ext.widgets.XPanel', array('width'=>900, 'height'=>130, 'title'=> 'Pencarian')); ?>

<?php echo "<?php \$form=\$this->beginWidget('ext.CAxActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route), 
	'method'=>'get',
)); ?>\n"; ?>
    
    <table class="ma-table">
        <tbody>
<?php 
    $c = 0;
    foreach($this->tableSchema->columns as $column){
        if(!empty($_POST['insearch'])){
            if(in_array($column->name, $_POST['insearch'])){
                $fieldType = 'textfield';
                if(isset($_POST['fields'][$column->name]))
                    $fieldType = $_POST['fields'][$column->name];
                $addParam = '';
                if(isset($_POST['addparam-'.$column->name]))
                    $addParam = $_POST['addparam-'.$column->name];
                if($c==0)
                    echo "<tr>";
                elseif(($c%2) == 0){
                    echo "</tr>\n";
                    echo "<tr>\n";
                }
?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
?>
                <td class="labelfield">
                        <?php echo "<?php echo \$form->label(\$model,'{$column->name}').':'; ?>\n"; ?>
                </td>
                <td>
                        <?php 
                        if($fieldType == 'datefield')
                            echo "<?php ".$this->generateActiveField($this->modelClass,$column,$fieldType, $addParam)."; ?>\n";
                        else
                            echo "<?php echo ".$this->generateActiveField($this->modelClass,$column,$fieldType, $addParam)."; ?>\n"; 
                        
                        ?>
                </td>
<?php
                
                $c++;
                
            }
        }
    } 
    if($c > 1 )
        echo "</tr>\n";
    
?>
	<tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
		<?php echo "<?php echo CHtml::submitButton('Search', array('class'=>'ax-btn')); ?>\n"; ?>
            </td>
	</tr>
        </tbody></table>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

