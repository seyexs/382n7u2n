<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ColumnMixLine
 *
 * @author obi
 */
class AmCharts extends CWidget  {
    public $elId;
    public $chartData;
    public $categoryField;
    public $columnTitle;
    public $lineTitle;
    public $lineValueField;
    public $columnValueField;
    private $settings=array();
    
    public function init() {
        $this->registerSetting(); 
        $this->registerAsset();
        //$this->setInitScript();
    }

    public function run() {
        //$this->render('index', array('settings'=> $this->settings),false,true);
        
    }
    public function registerSetting(){
        /* repackage all properties of this class for sending data usage */
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            $this->settings[$name]=$this->$name;
        }
    }
    public function registerAsset() {
        $assets = dirname(__FILE__) . '/' . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/style.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/amcharts/amcharts.js', CClientScript::POS_HEAD);        
    }
    
}

?>
