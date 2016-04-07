<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CActTabPanel
 *
 * @author ACTIN02
 */
class SpryTabPanel extends CWidget {
    public $containerWidgetId;
    public $itemView;
    public $data;
    public $model;
    public $model2;
    public $defaultIndexTab=0;
    
    public $dataUsed;
    
    protected $jsFile = "js/SpryTabbedPanels.js";
    public function init(){
        if($this->itemView===null)
            throw new CException(Yii::t('Tab Panel','The property "itemView" cannot be empty.'));           
        $assets = dirname(__FILE__).'/'.'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerScriptFile($baseUrl.'/'.$this->jsFile,CClientScript::POS_HEAD);
        parent::init();
    }
    public function run(){
            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';
            $owner->$render($this->itemView, array('data' => $this->data,'model' => $this->model,'model2' => $this->model2));
            $this->registerClientScript();
    }
    public function registerClientScript() {
        $js = <<<EOS
	var formTabbedPanels = new Spry.Widget.TabbedPanels("{$this->containerWidgetId}", {defaultTab:{$this->defaultIndexTab}, tabHoverClass: "TabbedPanelsTabHover", panelVisibleClass:"TabbedPanelsContentVisible", tabSelectedClass: "TabbedPanelsTabSelected"});
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
        //parent::registerClientScript();
    }
}
?>