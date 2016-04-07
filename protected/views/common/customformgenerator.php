<?php
$selectedModel = "";
$allModels = array();
?>
<div class="row template sticky">
	<?php echo $this->labelEx($model,'template'); ?>
	<?php echo $this->dropDownList($model,'template',$templates); ?>
	<div class="tooltip">
		Please select which set of the templates should be used to generated the code.
	</div>
	<?php echo $this->error($model,'template'); ?>
</div>
<div>
	<?php if($model->status===CCodeModel::STATUS_PREVIEW && !$model->hasErrors()): ?>
                <?php
                    if(isset($_POST[$this->controller->modelClass])){
                        $allModels = $this->controller->getListModel();
                        echo "<h4>Fields Model :</h4>";
                ?>
            <table>
                <thead>
                    <tr>
                        <th width="150">Table Field</th>
                        <th width="150">Ext Component</th>
                        <th>Add Ext Option</th>
                        <th width="150">Label</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $modelCls = new $_POST[$this->controller->modelClass]['model']; 
                        $selectedModel = $_POST[$this->controller->modelClass]['model'];
                        $columns = $modelCls->attributeLabels();
                            foreach ($columns as $name => $label) {
                                echo "<tr>";
                                $default = 'textfield';
                                if (isset($_POST[$name]))
                                    $default = $_POST[$name];
                                echo '<td>' . $name . '</td>';
                                echo '<td>' . CHtml::dropDownList('fields[' . $name . ']', $default, $this->controller->typeFields, array('fieldname' => $name, 'class' => 'type-ext-field', 'empty' => ' Select ')) . '</td>';
                                echo '<td>' . '</td>';
                                echo '<td>' . $label . '</td>';
                                echo "</tr>";
                            }
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>                        
                        
                        
                <?php
                    
                    }
                ?>
	<?php endif; ?>
</div>
<div class="buttons">
	<?php echo CHtml::submitButton('Preview',array('name'=>'preview')); ?>

	<?php if($model->status===CCodeModel::STATUS_PREVIEW && !$model->hasErrors()): ?>
		<?php echo CHtml::submitButton('Generate',array('name'=>'generate')); ?>
	<?php endif; ?>
</div>

<?php if(!$model->hasErrors()): ?>
	<div class="feedback">
	<?php if($model->status===CCodeModel::STATUS_SUCCESS): ?>
		<div class="success">
			<?php echo $model->successMessage(); ?>
		</div>
	<?php elseif($model->status===CCodeModel::STATUS_ERROR): ?>
		<div class="error">
			<?php echo $model->errorMessage(); ?>
		</div>
	<?php endif; ?>

	<?php if(isset($_POST['generate'])): ?>
		<pre class="results"><?php echo $model->renderResults(); ?></pre>
	<?php elseif(isset($_POST['preview'])): ?>
		<?php echo CHtml::hiddenField("answers"); ?>
		<table class="preview">
			<tr>
				<th class="file">Code File</th>
				<th class="confirm">
					<label for="check-all">Generate</label>
					<?php
						$count=0;
						foreach($model->files as $file)
						{
							if($file->operation!==CCodeFile::OP_SKIP)
								$count++;
						}
						if($count>1)
							echo '<input type="checkbox" name="checkAll" id="check-all" />';
					?>
				</th>
			</tr>
			<?php foreach($model->files as $i=>$file): ?>
			<tr class="<?php echo $file->operation; ?>">
				<td class="file">
					<?php echo CHtml::link(CHtml::encode($file->relativePath), array('code','id'=>$i), array('class'=>'view-code','rel'=>$file->path)); ?>
					<?php if($file->operation===CCodeFile::OP_OVERWRITE): ?>
						(<?php echo CHtml::link('diff', array('diff','id'=>$i), array('class'=>'view-code','rel'=>$file->path)); ?>)
					<?php endif; ?>
				</td>
				<td class="confirm">
					<?php
					if($file->operation===CCodeFile::OP_SKIP)
						echo 'unchanged';
					else
					{
						$key=md5($file->path);
						echo CHtml::label($file->operation, "answers_{$key}")
							. ' ' . CHtml::checkBox("answers[$key]", $model->confirmed($file));
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php
$arrOptions = '';
$arrRelModels = "var models = new Array();\n";
$c=0;
foreach($allModels as $opt){
	$arrOptions .= '<option value="'.$opt.'">'.$opt.'</option>';
	$arrRelModels .= "models[{$c}] = '".$opt."';\n";
	$c++;
}
$js = <<<EOS
$arrRelModels
var curModel = '{$selectedModel}';
function createComboboxField(name){
    var strFrm = '<select name="'+name+'">';
    strFrm += '<option value=""> Select Relation Model </option>';
    for(var i=0; i < models.length; i++){
    	if(models[i] != curModel)
    		strFrm += '<option value="'+models[i]+'">'+models[i]+'</option>';
    }  
        /*strFrm += '{$arrOptions}';*/
   	strFrm += '</select>';
    return strFrm;
}

$("#modelLst").change(function(){
    $("#a-form").submit();
});
$('.type-ext-field').live('change', function(){
    switch($(this).val()){
        case 'combobox':
            $(this).parent().next().html(createComboboxField('addparam-'+$(this).attr('fieldname')));
            break;
        case 'checkbox':
        case 'radio':
            $(this).parent().next().html('<input type="text" name="addparam-'+$(this).attr('fieldname')+'">');
            break;
        default:
            $(this).parent().next().html('');
            break;
    }

});
EOS;
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScript('ChangeTableValue',$js, CClientScript::POS_END);
?>
