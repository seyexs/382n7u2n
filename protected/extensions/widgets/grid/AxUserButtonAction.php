<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxUserButtonAction
 *
 * @author Ardha
 */
class AxUserButtonAction extends CWidget {

    //put your code here
    public $actions = array();
    public $htmlOptions = array();
    public $selectOptions = array();
    public $buttonOptions = array();
    public $buttonName = 'Go';
    public $header = 'With Selected:';
    public $list;
    private $_data = array();

    public function init() {
        if (!isset($this->selectOptions['id'])) {
            $this->selectOptions['id'] = $this->getId() . '_select';
        }
        if (!isset($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = $this->getId() . '_button';
        }
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        if (!isset($this->buttonOptions['class'])) {
            $this->buttonOptions['class'] = 'f-btn';
        }
        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = 'form-action';
        }
        $this->registerScript();
    }

    public function run() {
        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::label($this->header, null);
        echo CHtml::dropDownList('select_button', null, $this->_data, $this->selectOptions);
        echo CHtml::button($this->buttonName, $this->buttonOptions);
        echo CHtml::tag('div', array('class' => 'clearboth'), '');
        echo CHtml::closeTag('div');
    }

    protected function registerScriptActions() {

        $js = 'var value = $("#' . $this->selectOptions['id'] . '").val();
               var action = function(){};
               switch (value) {
';
        foreach ($this->actions as $val => $action) {
            $js .= 'case "' . $val . '":';
            $js .= 'action = ' . $action['callback'] . ';';
            $js .= 'break;';
            $this->_data[$val] = $action['label'];
        }
        $js .= '} action(this);';
        return $js;
    }

    protected function registerScript() {
        $cs = Yii::app()->clientScript;
        $js = '
        $("#' . $this->buttonOptions['id'] . '").live("click",function(){
            ' . $this->registerScriptActions() . '
        });';
        $cs->registerScript(__CLASS__ . '#' . $this->getId(), $js);
    }

}

?>
