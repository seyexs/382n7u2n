<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SpryPanel
 *
 * @author ardha
 * @created on Feb 23, 2013
 */
class SpryAccordionPanel extends CWidget {

    //put your code here
    private $jsFile = 'js/SpryAccordion.js';
    private $cssFile = 'css/SpryAccordion.css';
    public $htmlOptions = array('class' => 'Accordion', 'tabindex' => '0');
    public $htmlOptionsContainer = array();
    public $cssPanel = 'AccordionPanel';
    public $cssPanelTab = 'AccordionPanelTab';
    public $cssPanelContent = 'AccordionPanelContent';
    public $id;
    public $itemView;
    public $data;
    public $model;
    public $defaultPanel=0;
    public $params = array();
    public function init() {
        if($this->itemView===null)
            throw new CException(Yii::t('Accordion Panel','The property "itemView" cannot be empty.'));
        parent::init();
        if (isset($this->htmlOptions['id']))
            $this->id = $this->htmlOptions['id'];
        else {
            $this->id = $this->getId() . time();
            $this->htmlOptions['id'] = $this->id;
        }
        $this->registerClientScript();
    }

    public function run() {
        echo CHtml::openTag('div', $this->htmlOptionsContainer); //Container
        echo CHtml::openTag('div', $this->htmlOptions);
        $owner = $this->getOwner();
        $render = $owner instanceof CController ? 'renderPartial' : 'render';
        $owner->$render($this->itemView, array('data' => $this->data, 'model' => $this->model, 'params' => $this->params));
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
    }

    public function registerClientScript() {
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        $cs = Yii::app()->getClientScript();
        $cs->registerCSSFile($baseUrl . '/' . $this->cssFile);
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . '/' . $this->jsFile, CClientScript::POS_HEAD);
        $js = <<<EOS
   var a1 = new Spry.Widget.Accordion('{$this->id}',{defaultPanel:'{$this->defaultPanel}'});
EOS;

        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }

}

?>
