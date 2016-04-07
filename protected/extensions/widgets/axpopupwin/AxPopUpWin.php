<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CActPopUpWin
 *
 * @author Ardha
 */
class AxPopUpWin extends CWidget {

    public $name;
    public $elm;
    public $view;
    public $width = 800;
    public $height = 600;
    public $htmlOptions=array();
    public $vendorOptions=array();
    public $isCallDirect = false;
    public $isRemoveCloseBtn = false;
    private $cssFile = 'css/colorbox.css';
    private $jsFile = 'jquery.colorbox-min.js';
    public function init() {
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerCssFile($baseUrl . '/' . $this->cssFile);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $this->jsFile, CClientScript::POS_HEAD);
        if(!$this->isCallDirect)
            $this->registerClientScript();
    }

    public function registerClientScript() {
        //parent::registerClientScript();
        //$url = $this->controller->id.'/'.$this->actionUrl;
        //$delete_url = $this->controller->id.'/deletenote';
        $js = <<<EOS
        $("#{$this->elm}").colorbox({
            iframe:true, 
            width:"{$this->width}", 
            height:"{$this->height}", 
            overlayClose:false
EOS;
        if($this->isRemoveCloseBtn)
            $js .= ", 
                onLoad: function() {
                    $('#cboxClose').remove();
                }
            });";
        else
            $js .= "});";   
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }
}

?>
