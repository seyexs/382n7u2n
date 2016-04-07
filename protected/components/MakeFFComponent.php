<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MakeFFComponent
 *
 * @author ardha
 * @created on Nov 5, 2012
 */
class MakeFFComponent extends CComponent {

    public static function create($className=__CLASS__) {
        return new $className();
    }

    public static function createDropdownList($modelClass, $column, $type='textfield', $addParam='', $activeForm=true) {
        $strOpt = "array()";
        if (!empty($addParam)) {
            $IdModel = '';
            $DefModel = '';
            $model = new $addParam;
            $columns = $model->attributeLabels();
            foreach ($columns as $name => $label) {
                if (strtolower($name) == 'id')
                    $IdModel = $name;
                elseif (strtolower($name) == 'nama')
                    $DefModel = $name;
            }
            $c = 0;
            foreach ($columns as $name => $label) {
                if (empty($IdModel) && $c == 0)
                    $IdModel = $name;
                elseif (empty($DefModel) && $c == 1) {
                    $DefModel = $name;
                    break;
                }
                $c++;
            }
            if ($activeForm)
                $strOpt = "CHtml::listData({$addParam}::model()->findAll(), '{$IdModel}', '{$DefModel}')";
        }
        if ($activeForm)
            return "\$form->dropDownList(\$model,'{$column->name}', {$strOpt})";
        else
            return "CHtml::activeDropDownList(\$model,'{$column->name}', {$strOpt})";
    }

    public static function createRadioButton($modelClass, $column, $type='textfield', $addParam='', $activeForm=true) {
        $strData = "array(";
        if (!empty($addParam)) {
            $arrVars = explode(';', $addParam);
            if (is_array($arrVars)) {
                foreach ($arrVars as $var) {
                    list($key, $val) = explode(':', $var);
                    $strData .= "'{$key}'=>'{$val}',";
                }
            }
        }
        $strData .= ")";
        if ($activeForm)
            return "\$form->radioButtonList(\$model,'{$column->name}', {$strData}, array('class'=>'x-form-field x-form-radio', 'container'=>'ul'))";
        else
            return "CHtml::activeRadioButtonList(\$model,'{$column->name}', {$strData}, array('class'=>'x-form-field x-form-radio', 'container'=>'ul'))";
    }

    public static function createCheckBox($modelClass, $column, $type='textfield', $addParam='', $activeForm=true) {
        $strData = "array(";
        if (!empty($addParam)) {
            $arrVars = explode(';', $addParam);
            if (is_array($arrVars)) {
                foreach ($arrVars as $var) {
                    list($key, $val) = explode(':', $var);
                    $strData .= "'{$key}'=>'{$val}',";
                }
            }
        }
        $strData .= ")";
        if ($activeForm)
            return "\$form->checkBoxList(\$model,'{$column->name}', {$strData}, array('class'=>'x-form-field x-form-checkbox', 'container'=>'ul'))";
        else
            return "CHtml::activeCheckBoxList(\$model,'{$column->name}', {$strData}, array('class'=>'x-form-field x-form-checkbox', 'container'=>'ul'))";
    }
    public static function createDateField($modelClass, $column, $type='textfield', $addParam='', $activeForm=true){
        return "if (isset(\$model->{$column->name}) && !empty(\$model->{$column->name})) {
                        \$model->{$column->name} =
                                Yii::app()->format->date(strtotime(\$model->{$column->name}));
                    }
                \$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => \$model,
                        'attribute' => '{$column->name}',
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                            'changeMonth' => true,
                            'changeYear' => true,
                            'yearRange' => 'c-50:c',
                            'showOn' => 'button',
                            'buttonImage' => Yii::app()->baseUrl . '/images/calendar.gif',
                            'buttonImageOnly' => true
                        ),
                        'htmlOptions' => array(
                            'class' => 'x-form-field x-form-text',
                            'style' => 'width:75px;',
                        ),
                    ))";
    }

}

?>
