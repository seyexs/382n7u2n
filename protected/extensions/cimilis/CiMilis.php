<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CiMilis
 *
 * @author obi
 */
class CiMilis extends CWidget {

    public $model;
    public $dataUri="";
    public $avatarUrl="";
    private $settings=array();
    

    public function init() {
        $this->registerSetting(); 
        $this->registerAsset();
    }

    public function run() {
        $this->render('index', array('settings'=> $this->settings));
    }
    public function registerSetting(){
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            $this->settings[$name]=$this->$name;
        }
    }
    public function registerAsset() {
        $assets = dirname(__FILE__) . '/' . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/prettyPhoto.css');
        Yii::app()->clientScript->registerCssFile($baseUrl . '/style.css');
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/ajaxupload.3.5.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.prettyPhoto.js', CClientScript::POS_HEAD);
        
    }

}

?>

