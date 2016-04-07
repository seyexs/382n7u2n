<?php

$form = $this->beginWidget('ext.CAxActiveForm', array(
    'id' => 'mpegawai-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php

echo CHtml::activeFileField($person, 'uploadedFoto', array('onchange' => 'alert("Me");'));
?>
<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'ax-btn')); ?>
<?php $this->endWidget(); ?>
<?php

if (!empty($uploadedFile)) {
    $uploadImg = Yii::app()->uploadImage;
    $destPath = $uploadImg->setDestination(Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoPegawai']);
    $desthUrl = $uploadImg->setDestination(Yii::app()->params['dirFotoPegawai'], false);
    $tm = time();
    $filename = "{$destPath}/{$tm}_{$uploadedFile->name}";
    $sourceFile = Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoTemp']."/".$uploadedFile->name;
    //$destPath . '/' . $fileName;
    $uploadedFile->saveAs($sourceFile);
    //echo $uploadedFile->tempName;
    
    $uploadImg->saveOriginal($sourceFile, $filename);
    echo "{$desthUrl}/{$tm}_{$uploadedFile->name}";
}
?>
<?php
//$t = new AxXML('bla');
//$t->createXMLFromData();
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
?>