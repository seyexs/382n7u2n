<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CAxJQForm
 *
 * @author ardha
 * @created on Feb 11, 2013
 */
class CAxJQForm extends CActiveForm {
    public $waitMsg = 'Sedang Proses, silahkan menunggu';
    public $parentContainerId = '';
    public $progressbarClass = 'sync-progress shadow';
    public $progressbarId = 'progressbar';
    public $notifyTime = 3000;
    public $jsCurrencyFile = 'jquery.form.js';
    public function init() {
        parent::init();
        if(!empty($this->id)){
            $this->controller->widget('ext.widgets.notify.AxNotify');
            $this->regScriptJQScript();
        }
    }

    private function regScriptJQScript() {
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'js';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $this->jsCurrencyFile, CClientScript::POS_HEAD);
        $js = <<<EOS
   $('#{$this->id}').ajaxForm({
       dataType:'json',
       beforeSubmit: function(){
            $('#{$this->parentContainerId}').hide();
            $('#{$this->progressbarId}').addClass('{$this->progressbarClass}');
            $('#{$this->progressbarId}').html('{$this->waitMsg}');
       },
       success:function(data){
            $('#{$this->parentContainerId}').show();  
            $('#{$this->progressbarId}').removeClass('{$this->progressbarClass}');
            $('#{$this->progressbarId}').html('');
            showOnNotify(data.type, data.msg, {$this->notifyTime});
            $('#{$this->id}').resetForm();
        },
        error: function(data) { // if error occured
             showOnNotify(data.type,XHR, {$this->notifyTime});
        },
});
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);
    }

}

?>
