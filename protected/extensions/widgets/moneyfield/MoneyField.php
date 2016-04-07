<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoneyInput
 *
 * @author ardha
 * @created on Feb 2, 2013
 */
class MoneyField extends CWidget {

    private $jsFile = 'jquery.formatCurrency-1.4.0.min.js';
    public $fieldId = '';
    public $model;
    public $attribute;
    public $htmlOptions;
    public $config = array();

    public function init() {
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $this->jsFile, CClientScript::POS_HEAD);
        $this->registerClientScript();
    }

    public function run() {
        $htmlOptions['name'] = $name;
        if (!isset($htmlOptions['id']))
            $htmlOptions['id'] = self::getIdByName($name);
        else if ($htmlOptions['id'] === false)
            unset($htmlOptions['id']);
        echo CHtml::activeTextField($model, $attribute, $htmlOptions);
    }

    public function registerClientScript() {
        $js = <<<EOS
$('#{$this->fieldId}').blur(function() {
    $('#{$this->fieldId}').html(null);
    $(this).formatCurrency({
        colorize: true, 
        negativeFormat: '-%s%n', 
        roundToDecimalPlace: 2
    });
})
.keyup(function(e) {
    var e = window.event || e;
    var keyUnicode = e.charCode || e.keyCode;
    if (e !== undefined) {
        switch (keyUnicode) {
            case 16:
                break; // Shift
            case 17:
                break; // Ctrl
            case 18:
                break; // Alt
            case 27:
                this.value = '';
                break; // Esc: clear entry
            case 35:
                break; // End
            case 36:
                break; // Home
            case 37:
                break; // cursor left
            case 38:
                break; // cursor up
            case 39:
                break; // cursor right
            case 40:
                break; // cursor down
            //case 78:
            //    break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
            //case 110:
            //    break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
            case 190:
                break; // .
            default:
                $(this).formatCurrency({
                colorize: true, 
                negativeFormat: '-%s%n', 
                roundToDecimalPlace: -1, 
                eventOnDecimalsEntered: true
            });
        }
    }
})
.bind('decimalsEntered', function(e, cents) {
    if (String(cents).length > 2) {
        var errorMsg = 'Please do not enter any cents (0.' + cents + ')';
        $('#{$this->fieldId}').html(errorMsg);
        log('Event on decimals entered: ' + errorMsg);
    }
});       
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);
    }

}

?>
