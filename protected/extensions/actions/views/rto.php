<?php
if(!empty($model)){
    if(count($model) > 1){
        echo CHtml::openTag('div', array('class' => 'row', 'style'=>'margin-bottom:7px;'));
        echo CHtml::checkBox('checkall', false, array('value' => '', 'class' => 'selectall')).CHtml::label('Pilih Semua', false, array('style'=>'margin-left:4px;text-align:left;'));
        echo CHtml::closeTag('div');
    }
    foreach($model as $m){
        if(!isset($ExistingAuthChild[$m->name])){
            echo CHtml::openTag('div', array('class' => 'row', 'style'=>'margin-bottom:7px;'));
            echo CHtml::checkBox('AssignRTOForm[rto][]', false, array('value' => $m->name, 'class' => 'selectitem')).CHtml::label(!empty($m->description)?$m->description:str_replace('.', ' ', $m->name), false, array('style'=>'margin-left:4px;text-align:left;'));
            echo CHtml::closeTag('div');
        }
    }
}
?>
