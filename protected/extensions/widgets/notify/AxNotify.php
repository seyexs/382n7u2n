<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxNotify
 *
 * @author ardha
 * @created on Dec 6, 2012
 */
class AxNotify  extends CWidget {

    //private $cssFile = 'colorbox.css';
    private $jsFile = array(
        'jquery.noty.js',
        'layouts/top.js',
        'layouts/topCenter.js',
        /*'layouts/topLeft.js',
        'layouts/topRight.js',*/
        'themes/default.js',
    );

    public function init() {
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);

        Yii::app()->clientScript->registerCoreScript('jquery');
        foreach($this->jsFile as $file)
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $file, CClientScript::POS_HEAD);
        //Yii::app()->clientScript->registerCssFile($baseUrl . '/' . $this->cssFile);
        $this->registerClientScript();
    }

    public function registerClientScript() {
        $js = <<<EOS
        function showOnNotify(type, text, timeout){
            /* type can be 'alert','information','error','warning','notification','success'   */
            var n = noty({
                    text: text,
                    type: type,
                    dismissQueue: true,
                    layout: 'topCenter',
                    theme: 'defaultTheme'
            });
            if(timeout != 0){
                setTimeout(function() {
                    $.noty.close(n.options.id);
                }, timeout);
            }
       }

EOS;
 
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_END);
    }

}

?>
