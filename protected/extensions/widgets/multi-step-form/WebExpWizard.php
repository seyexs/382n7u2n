<?php

class WebExpWizard extends CWidget{

    public $elId;
    protected $jsFile = "formToWizard.js";
    protected $cssFile = "main_style.css";
    
    public function init() {
        $this->registerAsset();
        $this->setInitScript();
    }
    public function run() {
        //$this->render('index', array('webexpwizard'=> $webexpwizard));
    }
    public function registerAsset(){
        $assets = dirname(__FILE__) . '/' . 'resource';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/'. $this->cssFile);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' .'jquery.min.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/'. $this->jsFile, CClientScript::POS_HEAD);
        
    }
    public function setInitScript(){
        $js = <<<EOS
	$(document).ready(function(){
            $("#{$this->elId}").formToWizard({ submitButton: 'SaveAccount' })
        });
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
        return 'Please wait..';
    }

}
?>
